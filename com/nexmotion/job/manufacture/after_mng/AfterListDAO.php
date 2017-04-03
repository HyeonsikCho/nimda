<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/after_mng/AfterMngHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/after_mng/AfterMngDOC.php');

/**
 * @file BasicAfterListDAO.php
 *
 * @brief 생산 - 후공정관리 - 조판별-후공정 DAO
 */
class AfterListDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 생산공정관리 - 주문별 - 후공정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterList($conn, $param) {

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
            $query .= "\n       ,D.order_num ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,A.specialty_items ";
        }
        $query .= "\n  FROM  after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,order_common AS D ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  A.order_common_seqno = D.order_common_seqno ";

        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $val = substr($param["cate_sortcode"], 1, -1);
            $query .= "\n   AND  A.cate_sortcode LIKE '" . $val . "%' ";
        }
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $val = substr($param["state"], 1, -1);
            $query .= "\n   AND  A.state =" . $val;
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
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
   
    /**
     * @brief 주문별 - 후공정공정 작업일지 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterProcessView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT A.cate_sortcode";
        $query .= "\n      ,A.after_name";
        $query .= "\n          ,A.depth1";
        $query .= "\n          ,A.depth2";
        $query .= "\n          ,A.depth3";
        $query .= "\n          ,A.amt";
        $query .= "\n          ,A.amt_unit";
        $query .= "\n          ,A.memo";
        $query .= "\n          ,A.specialty_items";
        $query .= "\n          ,A.dlvrboard";
        $query .= "\n          ,A.state";
        $query .= "\n          ,A.orderer";
        $query .= "\n          ,A.order_common_seqno";
        $query .= "\n          ,A.order_detail_dvs_num";
        $query .= "\n          ,A.specialty_items";
        $query .= "\n          ,B.order_num";
        $query .= "\n          ,B.oper_sys";
        $query .= "\n          ,B.order_detail";
        $query .= "\n          ,C.extnl_brand_seqno";
        $query .= "\n          ,C.extnl_etprs_seqno"; 
        $query .= "\n  FROM after_op AS A";
        $query .= "\n      ,order_common AS B";
        $query .= "\n      ,extnl_brand AS C";
        $query .= "\n WHERE A.after_op_seqno = " . $param["seqno"];
        $query .= "\n   AND A.order_common_seqno = B.order_common_seqno";
        $query .= "\n   AND A.extnl_brand_seqno = C.extnl_brand_seqno";

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
}
?>
