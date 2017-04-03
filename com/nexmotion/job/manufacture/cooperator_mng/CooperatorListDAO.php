<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/cooperator_mng/CooperatorMngHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/cooperator_mng/CooperatorMngDOC.php');

/**
 * @file CooperatorListDAO.php
 *
 * @brief 생산 - 협력업체품목관리 - 협력품목생산리스트 DAO
 */
class CooperatorListDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 협력품목생산리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCooperatorList($conn, $param) {

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
            $query .= "\nSELECT A.oper_sys";
            $query .= "\n      ,B.order_detail_dvs_num";
            $query .= "\n      ,A.title";
            $query .= "\n      ,A.order_detail";
            $query .= "\n      ,B.receipt_finish_date";
            $query .= "\n      ,B.amt";
            $query .= "\n      ,B.amt_unit_dvs";
            $query .= "\n      ,B.count";
            $query .= "\n      ,B.state";
            $query .= "\n      ,B.order_detail_seqno";
            $query .= "\n      ,A.order_common_seqno";
        }
        $query .= "\n  FROM order_common AS A";
        $query .= "\n      ,order_detail AS B";
        $query .= "\n      ,cate AS C";
        $query .= "\n WHERE A.order_common_seqno = B.order_common_seqno";
        $query .= "\n   AND A.cate_sortcode = C.sortcode";
        $query .= "\n   AND C.typset_way = 'OUTSOURCE'";
        $query .= "\n   AND A.order_state > '2100'";

        if ($this->blankParameterCheck($param ,"outsource_etprs_cate_name")) {
            $query .= "\n   AND  C.outsource_etprs_cate_name = " . $param["outsource_etprs_cate_name"];
        }
        /*
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        */
        if ($this->blankParameterCheck($param ,"oper_sys")) {
            $query .= "\n   AND  A.oper_sys = " . $param["oper_sys"];
        }
        if ($this->blankParameterCheck($param ,"order_state")) {
            $query .= "\n   AND  A.order_state = " . $param["order_state"];
        }
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $cnd = substr($param["search_cnd"], 1, -1);
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A." . $cnd ." Like '%" . $val . "%'";
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  B." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  B." . $val. " <= " . $param["to"];
        }

        $query .= "\nORDER BY A.order_common_seqno DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
}
