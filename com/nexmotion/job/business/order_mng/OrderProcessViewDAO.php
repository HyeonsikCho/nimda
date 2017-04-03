<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BusinessCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/business/order_mng/OrderProcessViewHTML.php');

/**
 * @file OrderProcessViewDAO.php
 *
 * @brief 생산 - 주문관리 - 공정확인리스트 DAO
 */
class OrderProcessViewDAO extends BusinessCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 공정확인 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderProcessViewList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query .= "\nSELECT COUNT(A.order_common_seqno) AS cnt";

        } else if ($dvs == "SEQ") {
            $query .= "\nSELECT A.order_num";
            $query .= "\n      ,A.order_state"; 
            $query .= "\n      ,A.title"; 
            $query .= "\n      ,B.member_name"; 
            $query .= "\n      ,B.office_nick"; 
        }
        $query .= "\n  FROM order_common AS A";
        $query .= "\n      ,member AS B";
        $query .= "\n WHERE order_state > 1300";
        $query .= "\n   AND A.member_seqno = B.member_seqno";
        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $query .= "\n   AND A.cate_sortcode = " . $param["cate_sortcode"];
        }
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND A.cpn_admin_seqno = " . $param["sell_site"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND A.order_state = " . $param["state"];
        }
        if ($this->blankParameterCheck($param ,"search_cnd") && $this->blankParameterCheck($param ,"search_txt")) {
            $search_cnd = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND " . $search_cnd . " = " . $param["search_txt"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND A." . $val. " <= " . $param["to"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nORDER BY A.order_regi_date DESC ";
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
}
?>
