<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/typset_mng/ProcessMngHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/produce/typset_mng/ProcessPopupDOC.php');

/**
 * @file ReceiptListDAO.php
 *
 * @brief 생산 - 조판관리 - 생산공정관리 DAO
 */
class ProcessMngDAO extends ProduceCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 생산공정관리 - 출력
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputOpProcessList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.output_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,A.name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.size ";
            $query .= "\n       ,A.board ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,A.state ";
        }
        $query .= "\n  FROM  output_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //출력명
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.name LIKE '% " . $val . "%' ";
        }
        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //상태
        if ($this->blankParameterCheck($param ,"state")) {
            $val = substr($param["state"], 1, -1);
            $query .= "\n   AND  A.state " . $val;
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        $query .= "\nORDER BY regi_date DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 생산공정관리 - 인쇄
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintOpProcessList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.print_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,A.name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.size ";
            $query .= "\n       ,A.beforeside_tmpt ";
            $query .= "\n       ,A.beforeside_spc_tmpt ";
            $query .= "\n       ,A.aftside_tmpt ";
            $query .= "\n       ,A.aftside_spc_tmpt ";
            $query .= "\n       ,A.tot_tmpt ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,A.state ";
        }
        $query .= "\n  FROM  print_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //인쇄명
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.name LIKE '% " . $val . "%' ";
        }
        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //상태
        if ($this->blankParameterCheck($param ,"state")) {
            $val = substr($param["state"], 1, -1);
            $query .= "\n   AND  A.state " . $val;
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }
        $query .= "\nORDER BY regi_date DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 생산공정관리 - 조판별 - 후공정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBasicAfterOpProcessList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.basic_after_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,A.after_name ";
            $query .= "\n       ,A.depth1 ";
            $query .= "\n       ,A.depth2 ";
            $query .= "\n       ,A.depth3 ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.orderer ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,C.manu_name ";
        }
        $query .= "\n  FROM  basic_after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //후공정명
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.after_name LIKE '" . $val . "%' ";
        }
        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //상태
        if ($this->blankParameterCheck($param ,"state")) {
            $val = substr($param["state"], 1, -1);
            $query .= "\n   AND  A.state =" . $val;
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }
        $query .= "\nORDER BY regi_date DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 생산공정관리 - 주문별 - 후공정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterOpProcessList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.after_op_seqno ";
            $query .= "\n       ,A.after_name ";
            $query .= "\n       ,A.depth1 ";
            $query .= "\n       ,A.depth2 ";
            $query .= "\n       ,A.depth3 ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.orderer ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,D.order_num ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,E.cate_name ";
        }
        $query .= "\n  FROM  after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n       ,order_common AS D ";
        $query .= "\n       ,cate AS E ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.order_common_seqno = D.order_common_seqno ";
        $query .= "\n   AND  D.cate_sortcode = E.sortcode ";

        //후공정명
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.after_name LIKE '" . $val . "%' ";
        }
        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //상태
        if ($this->blankParameterCheck($param ,"state")) {
            $val = substr($param["state"], 1, -1);
            $query .= "\n   AND  A.state =" . $val;
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }
        $query .= "\nORDER BY A.regi_date DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

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
    $query .= "\n       ,B.dlvr_way ";

    $query .= "\n  FROM  order_detail as A ";
    $query .= "\n  LEFT JOIN order_dlvr as B on A.order_common_seqno = B.order_common_seqno and B.tsrs_dvs = '수신' ";
    $query .= "\n  where A.order_detail_dvs_num = ". $param['order_detail_num'];

    return $conn->Execute($query);

    }

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
        $query .= "\n       ,B.order_detail ";

        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  INNER JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on A.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
		$query .= "\n  where B.order_common_seqno in ( ". $param['seqs'] .")";

        return $conn->Execute($query);
    }


    function selectAfterProcess($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\nSELECT  after_name ";
        $query .= "\n  FROM  order_after_history ";
        $query .= "\n  where order_common_seqno = " . $param["order_common_seqno"];

        return $conn->Execute($query);
    }

    function selectOption($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\nSELECT  opt_name ";
        $query .= "\n  FROM  order_opt_history ";
        $query .= "\n  where order_common_seqno = " . $param["order_common_seqno"];

        return $conn->Execute($query);
    }


    function selectDirectDlvrInfo($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\nSELECT  dlvr_code ";
        $query .= "\n  FROM  member ";
        $query .= "\n  where member_seqno = " . $param["member_seqno"];

        return $conn->Execute($query);
    }


	/****************************************************************************
	*****order_detail테이블의 state필드를 변경
	*****state필드 변경 시 order_common테이블의 order_state필드 또한 고려해줘야한다.
	*****************************************************************************/

	function updateOrderState($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $arr_order_detail_num = $param["order_detail_num"];

        $tmp_order_num = "";
        $tmp_order_detail_num = "";
        foreach($arr_order_detail_num as $order_detail_num) {
            $tmp_order_detail_num .= "'" . $order_detail_num . "',";
            $order_num = substr($order_detail_num, 1);
            $order_num = substr($order_num, 0, -2);
            $tmp_order_num .= "'" . $order_num . "',";
        }

        $tmp_order_detail_num = substr($tmp_order_detail_num, 0, -1);
        $tmp_order_num = substr($tmp_order_num, 0, -1);

        $param['order_detail_num'] = $tmp_order_detail_num;
        $param['order_num'] = $tmp_order_num;

        $query  = "\n UPDATE order_detail set state = " . $param['state'];

        if ($param['order_detail_num']) {
		    $query .= "\n  WHERE order_detail_dvs_num in (". $param['order_detail_num'] . ")";
        } else if ($param['order_common_seqno']) {
		    $query .= "\n  WHERE order_common_seqno = ". $param['order_common_seqno'];
        } else if ($param['order_detail_count_file_seqno']) {
		    $query .= "\n  WHERE order_detail_count_file_seqno = ". $param['order_detail_count_file_seqno'];
        }

        $rs = $conn->Execute($query);

        if($rs) {
            $this->updateOrderCommonStatetmp($conn, $param);
        }

        return $rs;
	}

    function updateBunOrderCommonState($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $seqs = $param['seqs'];
        $param = $this->parameterArrayEscape($conn, $param);
        //인젝션 어택 방지
        //$param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n  UPDATE order_common SET order_state = " . $param['state'] . " WHERE order_common_seqno IN (" . $seqs . ")";

        return $conn->Execute($query);
    }

    function updateBunOrderDetailState($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $seqs = $param['seqs'];
        $param = $this->parameterArrayEscape($conn, $param);
        //인젝션 어택 방지
        //$param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n  UPDATE order_detail SET state = " . $param['state'] . " WHERE order_common_seqno IN (" . $seqs . ")";

        return $conn->Execute($query);
    }

    function updateOrderCommonStatetmp($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //$param = $this->parameterArrayEscape($conn, $param);

        $order_num = $param['order_num'];
        //인젝션 어택 방지
        //$param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE order_common set order_state = " . $param['state'];
        $query .= "\n  WHERE order_num in (". $order_num . ")";

        return $conn->Execute($query);
    }

    //책자용 state 변경
	function updateOrderBrochureState($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE order_detail_brochure set state = " . $param['state'];
		$query .= "\n  WHERE order_common_seqno = ". $param['order_common_seqno'];

        return $conn->Execute($query);
	}

    function selectWaitInList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '3120' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
            $val = substr($param["theday_yn"], 1, -1);
            if($val == "Y") {
                $query .= "\n   AND E.opt_name = \"당일판\" ";
            } else {
                $query .= "\n   AND E.opt_name != \"당일판\" ";
            }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }


    function selectWaitOutList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.depo_finish_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '3220' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
            $val = substr($param["theday_yn"], 1, -1);
            if($val == "Y") {
                $query .= "\n   AND E.opt_name = \"당일판\" ";
            } else {
                $query .= "\n   AND E.opt_name != \"당일판\" ";
            }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    // 배송중 리스트 출력
    function selectDeliveryIngList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.depo_finish_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '3320' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
            $val = substr($param["theday_yn"], 1, -1);
            if($val == "Y") {
                $query .= "\n   AND E.opt_name = \"당일판\" ";
            } else {
                $query .= "\n   AND E.opt_name != \"당일판\" ";
            }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    // 배송중 리스트 출력
    function selectDeliveryCompleteList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.depo_finish_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '9120' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
            $val = substr($param["theday_yn"], 1, -1);
            if($val == "Y") {
                $query .= "\n   AND E.opt_name = \"당일판\" ";
            } else {
                $query .= "\n   AND E.opt_name != \"당일판\" ";
            }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    //order_common order_state 변경
    function selectDlvrListByCar($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

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
        $query .= "\n       ,B.order_detail ";

        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  INNER JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on A.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  where B.state = '3320' ";
        $query .= "\n  AND C.invo_cpn = %s ";

        $query = sprintf($query
        , $param['carname']);

        return $conn->Execute($query);
    }

    //order_common order_state 변경
	function updateOrderCommonState($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE order_common set order_state = " . $param['state'];
		$query .= "\n  WHERE order_common_seqno = ". $param['order_common_seqno'];

        return $conn->Execute($query);
	}

    //기존 작업일지 작업완료 시간 입력
	function updateWorkReport($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query = "\n UPDATE " . $param["table"]  . " SET";

        $i = 0;
        $col = "";
        $value = "";

        foreach ($param["col"] as $key => $val) {

         //   $inchr = $val;
            $inchr = $conn->qstr($val,get_magic_quotes_gpc());

            if ($i == 0) {
                $value  .= "\n " . $key . "=" . $inchr;
            } else {
                $value  .= "\n ," . $key . "=" . $inchr;
            }

            $i++;
        }

        $query .= $value;
        $query .= "\n WHERE " . $param["prk"] . "=" . $conn->qstr($param["prkVal"], get_magic_quotes_gpc());
		$query .= "\n    AND valid_yn = 'Y'";

        return $conn->Execute($query);
	}

    // 생산 지시서 구분
    function selectProduceOrdDvs($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT count(produce_ord_seqno) AS cnt, sum(amt) AS tot_amt";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";
        $query .= "\n   AND ord_dvs = " .$param["ord_dvs"];
        $query .= "\n   AND dvs = " .$param["dvs"];

        return $conn->Execute($query);
    }

    // 생산 지시 구분
    function selectOrdDvs($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT DISTINCT(ord_dvs)";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";

        if ($this->blankParameterCheck($param ,"ord_dvs")) {
            $query .= "\n  AND ord_dvs = " .$param["ord_dvs"];
        }

        return $conn->Execute($query);
    }

    // 생산 구분
    function selectDvs($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT DISTINCT(dvs)";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";

        if ($this->blankParameterCheck($param ,"ord_dvs")) {
            $query .= "\n  AND ord_dvs = " .$param["ord_dvs"];
        }

        return $conn->Execute($query);
    }

    // 생산 지시서 리스트
    function selectProduceOrdList($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT dvs, typset_num, paper, size, print_tmpt, amt, amt_unit, specialty_items";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";
        $query .= "\n   AND dvs = " .$param["dvs"];

        if ($this->blankParameterCheck($param ,"ord_dvs")) {
            $query .= "\n   AND ord_dvs = " .$param["ord_dvs"];
        }

        return $conn->Execute($query);
    }
}
?>
