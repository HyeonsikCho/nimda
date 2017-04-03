<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BusinessCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/business/order_mng/OrderCommonMngHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/business/order_mng/OrderTreeHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/business/order_mng/OrderCommonMngDOC.php');

/**
 * @file MemberCommonListDAO.php
 *
 * @brief 영업 - 주문관리 - 주문통합관리 DAO
 */
class OrderCommonMngDAO extends BusinessCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 주문 리스트 검색조건 없이 검색
     *
     * @detail 대용량 게시판 쿼리용
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderListHtml($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.order_common_seqno AS seqno";
        $query .= "\n        ,C.sell_site";
        $query .= "\n        ,A.order_num";
        $query .= "\n        ,A.order_regi_date";
        $query .= "\n        ,B.office_nick";
        $query .= "\n        ,B.member_name";
        $query .= "\n        ,A.title";
        $query .= "\n        ,A.order_detail";
        $query .= "\n        ,A.order_state";
        $query .= "\n        ,A.sell_price";
        $query .= "\n        ,A.add_after_price";
        $query .= "\n        ,A.add_opt_price";
        $query .= "\n        ,B.member_seqno";

        $query .= "\n   FROM  order_common AS A";
        $query .= "\n        ,member       AS B";
        $query .= "\n        ,cpn_admin    AS C";

        $query .= "\n  WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n    AND  A.cpn_admin_seqno = C.cpn_admin_seqno";
        $query .= "\n    AND  A.cpn_admin_seqno =  %s";
        $query .= "\n    AND  A.order_common_seqno BETWEEN %s AND %s";
        $query .= "\n    AND  A.order_regi_date > %s ";
        $query .= "\n    AND  A.order_regi_date < SYSDATE() ";

        $query .= "\n  ORDER BY A.order_common_seqno DESC";
        $query .= "\n  LIMIT %s";

        $query  = sprintf($query, $param["sell_site"]
                                , $param["start_seqno"]
                                , $param["end_seqno"]
                                , $param["start_date"]
                                , substr($param["list_size"], 1, -1));

        $rs = $conn->Execute($query);

        return makeOrderListHtml($conn, $this, $rs);
    }

    /**
     * @brief 주문 리스트 조건검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderListCondHtml($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $state = $param["state"];
        unset($param["state"]);

        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        $param["state"] = $state;

        $query  = "\n SELECT  A.order_common_seqno AS seqno";
        $query .= "\n        ,C.sell_site";
        $query .= "\n        ,A.order_num";
        $query .= "\n        ,A.order_regi_date";
        $query .= "\n        ,B.office_nick";
        $query .= "\n        ,B.member_name";
        $query .= "\n        ,A.title";
        $query .= "\n        ,A.order_detail";
        $query .= "\n        ,A.order_state";
        $query .= "\n        ,A.sell_price";
        $query .= "\n        ,A.add_after_price";
        $query .= "\n        ,A.add_opt_price"; 
        $query .= "\n        ,B.member_seqno";
        $query .= "\n   FROM  order_common AS A";
        $query .= "\n        ,member       AS B";
        $query .= "\n        ,cpn_admin    AS C";

        $query .= "\n  WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n    AND  A.cpn_admin_seqno = C.cpn_admin_seqno";

        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n    AND  A.cpn_admin_seqno = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n    AND  B.member_seqno     = ";
            $query .= $param["member_seqno"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n    AND  A.order_state     in (";
            $query .= $param["state"] . ")";
        }
        if ($this->blankParameterCheck($param ,"depar_code")) {
            $query .= "\n    AND  B.biz_resp = ";
            $query .= $param["depar_code"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $query .= "\n    AND  ";
            $query .= $param["from"];
            $query .= " <= A.order_regi_date";
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $query .= "\n    AND  A.order_regi_date <= ";
            $query .= $param["to"];
        }
        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $query .= "\n    AND  A.cate_sortcode = ";
            $query .= $param["cate_sortcode"];
        }

        $query .= "\n  ORDER BY A.order_common_seqno DESC";
        $query .= "\n  LIMIT %s, %s";

        $query  = sprintf($query, substr($param["limit_block"], 1, -1)
                                , substr($param["list_size"], 1, -1));

        $rs = $conn->Execute($query);

        if ($rs->EOF == 1) {
            return false;
        }

        return makeOrderListHtml($conn, $this, $rs);
    }

    /**
     * @brief 주문 리스트 조건검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderListCond($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $state = $param["state"];
        unset($param["state"]);

        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        $param["state"] = $state;

        if ($dvs == "COUNT") {
            $query  = "\n SELECT  COUNT(A.order_common_seqno) AS cnt";
        } else if ($dvs == "TOTAL") {
            $query  = "\n SELECT  SUM(A.sell_price) AS sell_sum";
            $query .= "\n        ,SUM(A.add_after_price) AS after_sum";
            $query .= "\n        ,SUM(A.add_opt_price) AS opt_sum";
        }
        $query .= "\n   FROM  order_common AS A";
        $query .= "\n        ,member       AS B";
        $query .= "\n        ,cpn_admin    AS C";

        $query .= "\n  WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n    AND  A.cpn_admin_seqno = C.cpn_admin_seqno";
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n    AND  A.cpn_admin_seqno = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param ,"office_nick")) {
            $query .= "\n    AND  B.office_nick     = ";
            $query .= $param["office_nick"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n    AND  A.order_state     in (";
            $query .= $param["state"] . ")";
        }
        if ($this->blankParameterCheck($param ,"depar_code")) {
            $query .= "\n    AND  B.biz_resp = ";
            $query .= $param["depar_code"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $query .= "\n    AND  ";
            $query .= $param["from"];
            $query .= " <= A.order_regi_date";
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $query .= "\n    AND  A.order_regi_date <= ";
            $query .= $param["to"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 주문별 후공정 가격 검색
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 공통 일련번호
     *
     * @return 검색결과
     */
    /*************************************************
    function selectOrderAfterPrice($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();
        $temp["col"]   = "IFNULL(sum(price), 0) AS price";
        $temp["table"] = "order_after_history";
        $temp["where"]["order_common_seqno"] = $seqno;

        $rs = $this->selectData($conn, $temp);

        return $rs->fields["price"];
    }
    */

    /**
     * @brief 주문별 옵션 가격 검색
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 공통 일련번호
     *
     * @return 검색결과
     */
    /*************************************************
    function selectOrderOptPrice($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();
        $temp["col"]   = "IFNULL(sum(price), 0) AS price";
        $temp["table"] = "order_opt_history";
        $temp["where"]["order_common_seqno"] = $seqno;

        $rs = $this->selectData($conn, $temp);

        return $rs->fields["price"];
    }
    */

    /*
     * 주문 정보 조회
     * $conn : DB Connection
     * $param["seqno"] : 주문공통 일련번호
     * return : resultSet 
     */ 
    function selectOrderInfo($conn, $param) {

        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  B.flattyp_yn ";
        $query .= "\n       ,A.order_state ";
        $query .= "\n       ,B.cate_name ";
        $query .= "\n       ,C.dlvr_way ";
        $query .= "\n       ,C.dlvr_sum_way ";
        $query .= "\n  FROM  order_common AS A ";
        $query .= "\n       ,cate AS B ";
        $query .= "\n       ,order_dlvr AS C ";
        $query .= "\n WHERE  A.cate_sortcode = B.sortcode ";
        $query .= "\n   AND  A.order_common_seqno = C.order_common_seqno ";
        if ($this->blankParameterCheck($param, "seqno")) {
            $query .= "\n   AND  A.order_common_seqno = " . $param["seqno"];
        }

        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 주문 배송 정보 조회
     * $conn : DB Connection
     * $param["seqno"] : 주문공통 일련번호
     * return : resultSet 
     */ 
    function selectOrderDlvrInfo($conn, $param) {

        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  tsrs_dvs ";
        $query .= "\n       ,name ";
        $query .= "\n       ,tel_num ";
        $query .= "\n       ,cell_num ";
        $query .= "\n       ,zipcode ";
        $query .= "\n       ,addr ";
        $query .= "\n  FROM  order_dlvr ";
        if ($this->blankParameterCheck($param, "seqno")) {
            $query .= "\n WHERE  order_common_seqno = " . $param["seqno"];
        }

        $result = $conn->Execute($query);

        return $result;
    }
}
?>
