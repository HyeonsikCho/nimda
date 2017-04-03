<?php
/***********************************************************************************
 *** 프로 젝트 : 3.0
 *** 개발 영역 : 송장 클래스
 *** 개  발  자 : 조현식
 *** 개발 날짜 : 2016.09.27
 ***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . "/define/cj_invoice_define.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/dprinting/InvoiceSocketDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/dprinting/CJAddressPackageDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/dprinting/file.php');

class CJparcel
{
    var $dao;
    var $connectionPool;
    var $util;
    var $conn;
    var $inSock;
    var $CJAddr;
    var $CFile;
    var $json;
    var $strRes;
    var $srvRes;
    var $strCount;
    var $srvCount;
    var $today;
    var $boxcount;
    var $fileName;
    var $mode;
    var $json_array;
    var $seqs;
    var $weight;
    var $_CJ_OPENDBTEST;
    var $_CJ_CGISDEV;

    function __construct() {
        $this->connectionPool = new ConnectionPool();
        $this->conn = $this->connectionPool->getPooledConnection();
        $this->inSock = new InvoiceSocketDAO();
        $this->CJAddr = new CJAddressPackageDAO();
        $this->CFile = new CLS_File();

        $this->today = date("Y.m.d");
        $this->boxcount = "극소D";
        $this->fileName = "/home/dprinting/nimda/logs/invoice_socket_".date("Y-m-d").".log";
        $this->mode = "a+";
        $this->json_array = array();
        $this->weight = 0;
        $this->strCount = 0;
        $this->srvCount = 0;

        $this->_CJ_OPENDBTEST = _CJ_OPEN_DB_SERVER_IP.':'._CJ_OPEN_DB_SERVER_PORT."/OPENDBT";
        $this->_CJ_CGISDEV = _CJ_ADDR_REFINE_SERVER_IP.':'._CJ_ADDR_REFINE_SERVER_PORT."/CGISDEV";
    }

    function doWork($order_detail_num) {
        // 기초 데이터 설정
        $this->processDeliveryInfo($order_detail_num);

        // CJ측에 송장번호 외 기초 데이터 전송
        $this->receiptInvoiceToCJ();

        // 전송받은 값에 대해 Agent에 출력요청
        $this->sendSocketToAgent();
        $this->conn->close();
    }

    /***********************************************************************************
     *** 송장출력 해야 할 시기를 체크
     ***********************************************************************************/
    function checkPrintPoint($order_detail_num) {
        $param = array();
        $param['order_detail_num'] = $order_detail_num;
        $tmp = substr($order_detail_num, 1);
        $param['order_num'] = substr($tmp, 0, -2);

        $rs = $this->inSock->selectBunGroup($this->conn, $param);
        $param['bun_dlvr_order_num'] = $rs->fields['bun_dlvr_order_num'];
        $param['bun_group'] = $rs->fields['bun_group'];

        $rs1 = $this->inSock->selectBunGroupSeq($this->conn, $param);

        $seq = "";
        while ($rs1 && !$rs1->EOF) {
            $seq .= "'".$rs1->fields["order_common_seqno"]."',";
            $rs1->MoveNext();
        }

        $param["seqs"] = substr($seq, 0, -1);
        $this->seqs = $param["seqs"];
        $rs2 = $this->inSock->selectDeliveryWaitinList($this->conn, $param);

        while ($rs2 && !$rs2->EOF) {
            if($rs2->fields['state'] != '3220' || $rs2->fields['dlvr_way'] != '01') {
                return false;
            }
            $this->weight += $rs2->fields['expec_weight'];
            $rs2->MoveNext();
        }

        // 이미 출력한적이 있는지 확인
        $rs3 = $this->inSock->selectInvoiceRecord($this->conn, $param);

        if($rs3->fields['cnt'] > 0) {
            return false;
        }

        return true;
    }

    /***********************************************************************************
     *** 배송정보 가공
     ***********************************************************************************/
    private function processDeliveryInfo($order_detail_num) {
        $this->strRes = $this->inSock->getShipInfoTransDataList($this->conn, $this->seqs);
        $this->srvRes = $this->inSock->getShipInfoResvDataList($this->conn, $this->seqs);

        // 이건 명함인 경우
        $boxCnt = ceil($this->weight / 15) - 1;

        for($i = 0; $i < $boxCnt; $i++) {
            $tmp = $i + 1;
            $this->strRes[$tmp] = $this->strRes[$i];
            $this->srvRes[$tmp] = $this->srvRes[$i];

            $this->strRes[$tmp]['tr_oc_num'] .= '_'.$tmp;
            $this->srvRes[$tmp]['rv_oc_num'] .= '_'.$tmp;
        }
        $this->strRes[0]['tr_oc_num'] .= '_0';
        $this->srvRes[0]['rv_oc_num'] .= '_0';

        $this->strCount = count($this->strRes);
        $this->srvCount = count($this->srvRes);

        if ($this->strCount == $this->srvCount && $this->strCount > 0 &&  $this->srvCount > 0) {
            for ($i = 0; $i < $this->strCount; $i++) {
                $invRes = $this->inSock->getInvoiceNumberDataValue($this->conn);

                if (is_array($invRes)) {
                    //*** 주소 정체
                    $addrRes = $this->CJAddr->getCJAddrPackageTransForm($this->_CJ_CGISDEV, $this->srvRes[$i]['rv_addr']);

                    if (!is_array($addrRes)) {
                        echo $addrRes;
                        exit;
                    }

                    if ($addrRes['P_NEWADDRYN'] == "Y") {
                        $gtAddr = "[".$addrRes['P_ETCADDR']."]";
                    } else {
                        $gtAddr = "";
                    }

                    // 택배, 선불이면
                    if ($this->srvRes[$i]['rv_ship_div'] == "01" && $this->srvRes[$i]['rv_ship_way_div'] == "01") {
                        $this->srvRes[$i]['credit'] = "신용";
                    } else {
                        $this->srvRes[$i]['credit'] = "";
                    }

                    $this->json_array["invoice"][$i] = array(
                        "invoiceNumber" =>  $invRes['in_number'],
                        "receiptDate"   =>  $this->today,
                        "title"         =>  $this->strRes[$i]['tr_title']."-".$this->strRes[$i]['tr_detail'],
                        "to_name"       =>  $this->strRes[$i]['tr_cp_name'],
                        "to_tellnum"    =>  $this->strRes[$i]['tr_phone'],
                        "to_cellnum"    =>  $this->strRes[$i]['tr_mobile'],
                        "to_address"    =>  $this->strRes[$i]['tr_addr'],
                        "amt"           =>  $this->srvRes[$i]['rv_amt'],
                        "count"         =>  $this->srvRes[$i]['rv_count'],
                        "from_name"     =>  $this->srvRes[$i]['rv_cp_name'],
                        "from_tellnum"  =>  $this->srvRes[$i]['rv_phone'],
                        "from_cellnum"  =>  $this->srvRes[$i]['rv_mobile'],
                        "from_address"  =>  $addrRes['P_NEWADDRESS']." ".$addrRes['P_NESADDRESSDTL']." ".$gtAddr,
                        "cost"          =>  $this->srvRes[$i]['rv_ship_price'],
                        "boxcount"      =>  $this->boxcount,
                        "calgubun"      =>  $this->srvRes[$i]['credit'],
                        "clsfcd"        =>  $addrRes['P_CLSFCD'],
                        "subclsfcd"     =>  $addrRes['P_SUBCLSFCD'],
                        "clsfaddr"      =>  $addrRes['P_CLSFADDR'],
                        "branshortnm"   =>  $addrRes['P_CLLDLCBRANSHORTNM'],
                        "vempnm"        =>  $addrRes['P_CLLDLVEMPNM'],
                        "vempnicknm"    =>  $addrRes['P_CLLDLVEMPNICKNM'],
                        "order_num"     =>  $this->srvRes[$i]['rv_oc_num']
                    );

                    // 송장번호 사용현황 업뎃
                    $updRes = $this->inSock->setInvoiceNumberDataUpdateComplete($this->conn, $invRes['in_number']);

                    if ($invRes == "LOST") {
                        echo "DBCON_LOST";
                        exit;
                    } else if ($invRes == "FAILED") {
                        echo "UPD_FAILED";
                        exit;
                    }

                } else if ($invRes == "LOST") {
                    echo "DBCON_LOST";
                    exit;
                } else {
                    echo "INV_FAILED";
                    exit;
                }

                unset($invRes);
                unset($addrRes);
            }
        } else {
            echo "SHIP_FAILED";
            exit;
        }
    }


    /***********************************************************************************
     *** CJ 배송 접수
     ************************************************************************************/
    private function receiptInvoiceToCJ() {
        $openSucessCount = 0;
        $isDataCount = 0;

        if ($this->strCount == $this->srvCount && $this->strCount > 0 &&  $this->srvCount > 0) {
            //db 연결
            $oc_conn = oci_connect(_CJ_OPEN_DB_ID, _CJ_OPEN_DB_PW, $this->_CJ_OPENDBTEST, 'UTF8');

            if (!$oc_conn) {
                exit;
            }

            for ($i = 0; $i < $this->strCount; $i++) {
                $chkRes = $this->CJAddr->getCJShipReceiveRequestValueCheck($oc_conn, $this->strRes[$i]);

                if ($chkRes == "SUCCESS") {
                    $opeRes = $this->CJAddr->setCJShipReceiveRequestInsertComplete($oc_conn, $this->strRes[$i], $this->srvRes[$i], $this->json_array["invoice"][$i]);
                    if ($opeRes == "SUCCESS") {
                        $openSucessCount++;
                    } else {
                        echo $opeRes;
                        exit;
                    }
                } else {
                    $isDataCount++;
                }
            }

            if ($this->strCount == $openSucessCount) {
                // 트랜젝션 커밋
                oci_commit($oc_conn);
            } else if ($this->strCount == $isDataCount) {
                oci_close($oc_conn);

                echo "OC_NONE_PRINT";
                exit;
            } else {
                // 트랜젝션 롤백
                oci_rollback($oc_conn);
                oci_close($oc_conn);

                echo "OC_TRAC_FAILED";
                exit;
            }

            // db 종료
            oci_close($oc_conn);
        }

        // 재출력해야할 경우를 대비해 데이터를 저장해야함
        $cnt = count($this->json_array["invoice"]);
        for($i = 0; $i < $cnt; $i++) {
            $this->inSock->insertInvoiceRecord($this->conn, $this->json_array["invoice"][$i]);
        }
        $this->json = json_encode($this->json_array)."@";
    }

    /***********************************************************************************
     *** 소켓통신 데이터 전송
     ***********************************************************************************/
    private function sendSocketToAgent() {
        $sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($sock == false) {
            echo "SK_CRT_FAILED";
            exit;
        }

        $result = @socket_connect($sock, _CSHAP_ENZIN_IP, _CSHAP_ENZIN_PORT);

        if ($result == false) {
            @socket_close($sock);
            echo "SK_CON_FAILED";
            exit;
        }

        @socket_write($sock, $this->json);
        $sMsg  = @socket_read($sock, 4096);

        @socket_close($sock);
    }

    /***********************************************************************************
     *** 송장 재출력
     ***********************************************************************************/
    function printAgain($order_num) {
        //1. CJparcel_record 검색
        $param = array();
        $param['order_num'] = $order_num;
        $rs = $this->inSock->findInvoiceRecord($this->conn, $param);

        if(!$rs) {
            return false;
        }

        $i = 0;
        while($rs && !$rs->EOF) {
            $this->json_array["invoice"][$i] = array(
                "invoiceNumber" =>  $rs->fields['invoiceNumber'],
                "receiptDate"   =>  $rs->fields['receiptData'],
                "title"         =>  $rs->fields['title'],
                "to_name"       =>  $rs->fields['to_name'],
                "to_tellnum"    =>  $rs->fields['to_tellNum'],
                "to_cellnum"    =>  $rs->fields['to_cellNum'],
                "to_address"    =>  $rs->fields['to_address'],
                "amt"           =>  $rs->fields['amt'],
                "count"         =>  $rs->fields['count'],
                "from_name"     =>  $rs->fields['from_name'],
                "from_tellnum"  =>  $rs->fields['from_tellNum'],
                "from_cellnum"  =>  $rs->fields['from_cellNum'],
                "from_address"  =>  $rs->fields['from_address'],
                "cost"          =>  $rs->fields['cost'],
                "boxcount"      =>  $rs->fields['boxcount'],
                "calgubun"      =>  $rs->fields['calgubun'],
                "clsfcd"        =>  $rs->fields['clsfcd'],
                "subclsfcd"     =>  $rs->fields['subclsfcd'],
                "clsfaddr"      =>  $rs->fields['clsfaddr'],
                "branshortnm"   =>  $rs->fields['branshortnm'],
                "vempnm"        =>  $rs->fields['vempnm'],
                "vempnicknm"    =>  $rs->fields['vempnicknm'],
                "order_num"     =>  $rs->fields['order_num']
            );
            $rs->moveNext();
            $i++;
        }
        $this->json = json_encode($this->json_array)."@";
        $this->sendSocketToAgent();
        $this->conn->close();
        return true;
    }
}

?>