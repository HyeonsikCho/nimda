<?
/***********************************************************************************
 *** 프로 젝트 : CJ 송장 출력
 *** 개발 영역 : 퀄리 모듈
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.07.25
 ***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/typset_mng/ProcessMngHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/produce/typset_mng/ProcessPopupDOC.php');

class InvoiceSocketDAO extends ProduceCommonDAO
{

    function __construct ()
    {
    }

    /***********************************************************************************
    *** 송정번호 가져오기
    ***********************************************************************************/

    function getInvoiceNumberDataValue($conn)
    {
        if ($this->connectionCheck($conn) === false) {
            $nRes = "LOST";
        } else {
            $query = "SELECT ship_div, in_number FROM invoice_number WHERE status = 'Y' ORDER BY invoice_no ASC LIMIT 1";
            $ret = $conn->Execute($query);

            if ($ret->RecordCount() > 0) {
                $nRes['ship_div'] = $ret->fields['ship_div'];
                $nRes['in_number'] = $ret->fields['in_number'];
            } else {
                $nRes = "FAILED";
            }
        }

        return $nRes;
    }


    /***********************************************************************************
     *** 송신 배송정보 가져오기
     ***********************************************************************************/

    function getShipInfoTransDataList($conn, $order_detail_num)
    {
        if ($this->connectionCheck($conn) === false) {
            $nRes = "LOST";
        } else {
            $query = "SELECT oc.order_num AS oc_num, oc.title AS title, oc.order_detail AS detail, oc.pay_price AS price, ";
            $query .= "od.name AS cp_name, od.tel_num AS phone, od.cell_num AS mobile, od.addr AS addr, od.zipcode AS zipcode, ";
            $query .= "mb.member_name AS mb_name, mb.tel_num AS mb_phone, mb.cell_num AS mb_mobile ";
            //$query .= "mb.zipcode AS mb_zipcode, mb.addr AS mb_addr1, mb.addr_detail AS mb_addr2 ";
            $query .= "FROM order_common AS oc, order_dlvr AS od, member AS mb ";
            $query .= "WHERE oc.order_common_seqno = od.order_common_seqno ";
            $query .= "AND oc.member_seqno = mb.member_seqno ";
            $query .= "AND oc.order_state = '3220' AND od.tsrs_dvs = '송신' ";
            $query .= "AND oc.order_common_seqno in ( ". $order_detail_num .") ";
            $query .= "ORDER BY oc.order_common_seqno ASC LIMIT 1";
            $ret = $conn->Execute($query);

            $i = 0;
            while ($ret && !$ret->EOF) {
                $nRes[$i]['tr_oc_num'] = $ret->fields['oc_num'];
                $nRes[$i]['tr_title'] = $ret->fields['title'];
                $nRes[$i]['tr_detail'] = $ret->fields['detail'];
                $nRes[$i]['tr_price'] = $ret->fields['price'];
                $nRes[$i]['tr_cp_name'] = $ret->fields['cp_name'];
                $nRes[$i]['tr_phone'] = $ret->fields['phone'];
                $nRes[$i]['tr_mobile'] = $ret->fields['mobile'];
                $nRes[$i]['tr_addr'] = $ret->fields['addr'];
                $nRes[$i]['tr_zipcode'] = $ret->fields['zipcode'];
                $nRes[$i]['tr_mb_name'] = $ret->fields['mb_name'];
                $nRes[$i]['tr_mb_phone'] = $ret->fields['mb_phone'];
                $nRes[$i]['tr_mb_mobile'] = $ret->fields['mb_mobile'];
                //$nRes[$i]['tr_mb_zipcode'] = $ret->fields['mb_zipcode'];
                //$nRes[$i]['tr_mb_addr1'] = $ret->fields['mb_addr1'];
                //$nRes[$i]['tr_mb_addr2'] = $ret->fields['mb_addr2'];

                $i++;
                $ret->moveNext();
            }
        }

        return $nRes;
    }

    /***********************************************************************************
     *** 한 상품에 대해 관련된 묶음 정보를 가져온다
     ***********************************************************************************/
    function selectBunGroup($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  B.bun_dlvr_order_num ";
        $query .= "\n       ,B.bun_group ";
        $query .= "\n       ,A.state ";

        $query .= "\n  FROM  order_detail as A ";
        $query .= "\n  LEFT JOIN order_dlvr as B on A.order_common_seqno = B.order_common_seqno and B.tsrs_dvs = '수신' ";
        $query .= "\n  where A.order_detail_dvs_num = ". $param['order_detail_num'];

        return $conn->Execute($query);

    }

    function selectInvoiceRecord($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $query  = "\nSELECT  COUNT(*) as cnt";
        $query .= "\n  FROM  CJparcel_record ";
        $query .= "\n  WHERE order_num = '". $param['order_num'] . "'";

        return $conn->Execute($query);
    }

    function selectDeliveryWaitinList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        //$param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.order_num ";
        $query .= "\n       ,C.addr ";
        $query .= "\n       ,A.title ";
        $query .= "\n       ,C.dlvr_way ";
        $query .= "\n       ,B.amt ";
        $query .= "\n       ,B.count ";
        $query .= "\n       ,B.state ";
        $query .= "\n       ,A.order_common_seqno ";
        $query .= "\n       ,B.order_detail_dvs_num ";
        $query .= "\n       ,A.member_seqno ";
        $query .= "\n       ,A.expec_weight ";

        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  INNER JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on A.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  where B.order_common_seqno in ( ". $param['seqs'] .")";

        return $conn->Execute($query);
    }


    /***********************************************************************************
     *** 묶음정보로 관련 정보들을 모두 불러옴
     ***********************************************************************************/
    function selectBunGroupSeq($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  order_common_seqno ";

        $query .= "\n  FROM  order_dlvr ";
        $query .= "\n  where bun_dlvr_order_num =  " . $param['bun_dlvr_order_num'];
        $query .= "\n  and bun_group = ". $param['bun_group'] ."";

        return $conn->Execute($query);
    }


    /***********************************************************************************
     *** 수신 배송정보 가져오기
     ***********************************************************************************/

    function getShipInfoResvDataList($conn, $order_detail_num)
    {
        if ($this->connectionCheck($conn) === false) {
            $nRes = "LOST";
        } else {
            $query = "SELECT oc.order_num AS oc_num, od.name AS cp_name, od.tel_num AS phone, od.cell_num AS mobile, od.addr AS addr, ";
            $query .= "od.zipcode AS zipcode, od.dlvr_way AS ship_div, od.dlvr_sum_way AS ship_way_div, od.dlvr_price AS ship_price, odt.amt AS amt, odt.count AS count ";
            $query .= "FROM order_common AS oc, order_dlvr AS od, order_detail AS odt ";
            $query .= "WHERE oc.order_common_seqno = od.order_common_seqno ";
            $query .= "AND oc.order_common_seqno = odt.order_common_seqno ";
            $query .= "AND oc.order_state = '3220' AND od.tsrs_dvs = '수신' ";
            $query .= "AND oc.order_common_seqno in ( ". $order_detail_num .") ";
            $query .= "ORDER BY ship_price DESC LIMIT 1";
            $ret = $conn->Execute($query);

            $i = 0;
            while ($ret && !$ret->EOF) {
                $nRes[$i]['rv_oc_num'] = $ret->fields['oc_num'];
                $nRes[$i]['rv_cp_name'] = $ret->fields['cp_name'];
                $nRes[$i]['rv_phone'] = $ret->fields['phone'];
                $nRes[$i]['rv_mobile'] = $ret->fields['mobile'];
                $nRes[$i]['rv_addr'] = $ret->fields['addr'];
                $nRes[$i]['rv_zipcode'] = $ret->fields['zipcode'];
                $nRes[$i]['rv_ship_div'] = $ret->fields['ship_div'];
                $nRes[$i]['rv_ship_way_div'] = $ret->fields['ship_way_div'];
                $nRes[$i]['rv_ship_price'] = $ret->fields['ship_price'];
                $nRes[$i]['rv_amt'] = $ret->fields['amt'];
                $nRes[$i]['rv_count'] = $ret->fields['count'];
                $i++;
                $ret->moveNext();
            }
        }

        return $nRes;
    }


    /***********************************************************************************
     *** 송정번호 업데이트
     ***********************************************************************************/

    function setInvoiceNumberDataUpdateComplete($conn, $in_num)
    {
        if ($this->connectionCheck($conn) === false) {
            $nRes = "LOST";
        } else {
            $updt_date = date("Y-m-d H:i:s");

            $query = "UPDATE invoice_number SET status = 'N', updt_date = '".$updt_date."' ";
            $query .= "WHERE in_number = '".$in_num."' LIMIT 1";
            $ret = $conn->Execute($query);

            if (!$ret) {
                $nRes = "FAILED";
            } else {
                $nRes = "SUCCESS";
            }
        }

        return $nRes;
    }

    /***********************************************************************************
     *** 송정출력 기록 삽입
     ***********************************************************************************/

    function insertInvoiceRecord($conn, $param)
    {
        if ($this->connectionCheck($conn) === false) {
            return null;
        } else {
            $query = "\n INSERT INTO CJparcel_record (amt "; //1
            $query .= "\n                           ,boxCount "; //2
            $query .= "\n                           ,branshortnm "; //3
            $query .= "\n                           ,calgubun "; //4
            $query .= "\n                           ,clsfaddr "; //5
            $query .= "\n                           ,clsfcd "; //6
            $query .= "\n                           ,cost "; //7
            $query .= "\n                           ,count "; //8
            $query .= "\n                           ,from_address "; //9
            $query .= "\n                           ,from_cellNum "; //10
            $query .= "\n                           ,from_name "; //11
            $query .= "\n                           ,from_tellNum "; //12
            $query .= "\n                           ,invoiceNumber "; //13
            $query .= "\n                           ,receiptDate "; //14
            $query .= "\n                           ,subclsfcd "; //15
            $query .= "\n                           ,title "; //16
            $query .= "\n                           ,to_address "; //17
            $query .= "\n                           ,to_cellNum "; //18
            $query .= "\n                           ,to_name "; //19
            $query .= "\n                           ,to_tellNum "; //20
            $query .= "\n                           ,vempnicknm "; //21
            $query .= "\n                           ,vempnm "; //22
            $query .= "\n                           ,order_num) VALUES "; //23
            $query .= "\n                           (%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s, %s) ";


            $query = sprintf($query
                , "'". $param["amt"] . "'"//1
                , "'". $param["boxcount"] . "'" //2
                , "'".$param["branshortnm"] . "'"//3
                , "'". $param["calgubun"] . "'"//4
                , "'". $param["clsfaddr"] . "'"//5
                , "'". $param["clsfcd"] . "'"//6
                , "'". $param["cost"] . "'"//7
                , "'". $param["count"] . "'"//8
                , "'". $param["from_address"] . "'"//9
                , "'". $param["from_cellnum"] . "'"//10
                , "'". $param["from_name"] . "'"//11
                , "'". $param["from_tellnum"] . "'"//12
                , "'". $param["invoiceNumber"] . "'"//13
                , "'". $param["receiptDate"] . "'"//14
                , "'". $param["subclsfcd"] . "'"//15
                , "'". $param["title"] . "'"//16
                , "'". $param["to_address"] . "'"//17
                , "'". $param["to_cellnum"] . "'"//18
                , "'". $param["to_name"] . "'"//19
                , "'". $param["to_tellnum"] . "'"//20
                , "'". $param["vempnicknm"] . "'"//21
                , "'". $param["vempnm"] . "'"//22
                , "'". $param["order_num"] . "'"); //23


            return $conn->Execute($query);

        }
    }

    /***********************************************************************************
     *** 송정출력 기록 select
     ***********************************************************************************/

    function findInvoiceRecord($conn, $param)
    {
        if ($this->connectionCheck($conn) === false) {
            return null;
        } else {
            $query = "\n SELECT * FROM CJparcel_record WHERE order_num = '" . $param['order_num'] . "'"; //1
            return $conn->Execute($query);
        }
    }

}
?>
