<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/produce/typset_mng/TypsetStandbyListDOC.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/typset_mng/TypsetStandbyListHTML.php');

/**
 * @file ReceiptListDAO.php
 *
 * @brief 생산 - 조판관리 - 조판대기리스트 DAO
 */
class TypsetStandbyListDAO extends ProduceCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 조판대기리스트 - 낱장형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFlattYTypsetStandbyList($conn, $param) {

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
            $query  = "\nSELECT  T1.*";
            $query .= "\n       ,T3.member_name";
            $query .= "\n       ,T3.office_nick";
            $query .= "\n       ,T2.title";
            $query .= "\n       ,T2.oper_sys";
            $query .= "\n       ,T4.cate_name";
            $query .= "\n       ,T2.order_detail";
            $query .= "\n       ,T5.sell_site";
        }
        $query .= "\n  FROM (";
        $query .= "\n        SELECT  A.order_common_seqno";
        $query .= "\n               ,A.order_detail_seqno";
        $query .= "\n               ,A.stan_name";
        $query .= "\n               ,A.print_tmpt_name";
        $query .= "\n               ,A.amt AS tot_amt";
        $query .= "\n               ,A.amt_unit_dvs";
        $query .= "\n               ,B.amt";
        $query .= "\n               ,B.amt_order_detail_sheet_seqno";
        $query .= "\n               ,B.sheet_typset_seqno";
        $query .= "\n               ,B.state";
        $query .= "\n               ,C.order_detail_count_file_seqno";
        $query .= "\n               ,C.seq";
        $query .= "\n               ,C.order_detail_file_num";
        $query .= "\n          FROM  order_detail AS A";
        $query .= "\n               ,amt_order_detail_sheet AS B";
        $query .= "\n               ,order_detail_count_file AS C";
        $query .= "\n         WHERE  A.order_detail_seqno = C.order_detail_seqno";
        $query .= "\n           AND  B.order_detail_count_file_seqno = C.order_detail_count_file_seqno";
        $query .= "\n       ) AS T1";
        $query .= "\n       ,order_common AS T2";
        $query .= "\n       ,member AS T3";
        $query .= "\n       ,cate AS T4";
        $query .= "\n       ,cpn_admin AS T5";
        $query .= "\n WHERE  T1.order_common_seqno = T2.order_common_seqno";
        $query .= "\n   AND  T2.member_seqno = T3.member_seqno";
        $query .= "\n   AND  T2.cate_sortcode = T4.sortcode";
        $query .= "\n   AND  T2.cpn_admin_seqno = T5.cpn_admin_seqno";
        $query .= "\n   AND  T1.state = 2120";
        $query .= "\n   AND  T2.order_state > 2000";
        $query .= "\n   AND  T2.del_yn = 'N'";

        //카테고리 검색
        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $query .= "\n   AND  T2.cate_sortcode = " . $param["cate_sortcode"];
        }
        //판매채널 검색
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  T2.cpn_admin_seqno = " . $param["sell_site"];
        }
        //상태 검색
        if ($this->blankParameterCheck($param ,"order_state")) {
            $query .= "\n   AND  T2.order_state = " . $param["order_state"];
        }
        //주문일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  T2.order_common_seqno = " . $param["order_common_seqno"];
        }
        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  T3.member_seqno = " . $param["member_seqno"];
        }
        //인쇄물제목
        if ($this->blankParameterCheck($param ,"title")) {
            $val = substr($param["title"], 1, -1);
            $query .= "\n   AND  T2.title LIKE '%$val%' ";
        }
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd2"], 1, -1);
            $query .= "\n   AND  T2.$val >= $param[from] ";
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd2"], 1, -1);
            $query .= "\n   AND  T2.$val <= $param[to] ";
        }

        //임시테이블 생성 검색 조건
        if ($this->blankParameterCheck($param ,"amt_order_detail_sheet_seqno")) {
            $val = substr($param["amt_order_detail_sheet_seqno"], 1, -1);
            $query .= "\n   AND  T1.amt_order_detail_sheet_seqno IN ($val) ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY  T2.order_num ASC, T1.seq";

        if ($dvs == "SEQ" && $this->blankParameterCheck($param ,"s_num") && $this->blankParameterCheck($param ,"list_num")) {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 조판대기리스트 - 책자형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFlattNTypsetStandbyList($conn, $param) {

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
            $query .= "\nSELECT  T1.*";
            $query .= "\n       ,T3.member_name";
            $query .= "\n       ,T3.office_nick";
            $query .= "\n       ,T2.title";
            $query .= "\n       ,T2.oper_sys";
            $query .= "\n       ,T4.cate_name";
            $query .= "\n       ,T2.order_detail";
            $query .= "\n       ,T5.sell_site";
        }
        $query .= "\n  FROM (";
        $query .= "\n         SELECT  A.order_common_seqno";
        $query .= "\n                ,A.order_detail_brochure_seqno";
        $query .= "\n                ,A.order_detail_num";
        $query .= "\n                ,A.page_amt";
        $query .= "\n                ,A.stan_name";
        $query .= "\n                ,A.print_tmpt_name";
        $query .= "\n                ,A.amt AS tot_amt";
        $query .= "\n                ,A.amt_unit_dvs";
        $query .= "\n                ,B.page";
        $query .= "\n                ,B.page_order_detail_brochure_seqno";
        $query .= "\n                ,B.brochure_typset_seqno";
        $query .= "\n                ,B.state";
        $query .= "\n                ,B.order_detail_dvs_num";
        $query .= "\n           FROM  order_detail_brochure AS A";
        $query .= "\n      LEFT JOIN  page_order_detail_brochure AS B";
        $query .= "\n             ON  A.order_detail_dvs_num = B.order_detail_dvs_num) AS T1";
        $query .= "\n        ,order_common AS T2";
        $query .= "\n        ,member AS T3";
        $query .= "\n        ,cate AS T4";
        $query .= "\n        ,cpn_admin AS T5";
        $query .= "\n WHERE  T1.order_common_seqno = T2.order_common_seqno";
        $query .= "\n   AND  T2.member_seqno = T3.member_seqno";
        $query .= "\n   AND  T2.cate_sortcode = T4.sortcode";
        $query .= "\n   AND  T2.cpn_admin_seqno = T5.cpn_admin_seqno";
        $query .= "\n   AND  T1.state = 410";
        $query .= "\n   AND  T2.order_state > 400";
        $query .= "\n   AND  T2.del_yn = 'N'";

        //카테고리 검색
        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $query .= "\n   AND  T2.cate_sortcode = " . $param["cate_sortcode"];
        }
        //판매채널 검색
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  T2.cpn_admin_seqno = " . $param["sell_site"];
        }
        //상태 검색
        if ($this->blankParameterCheck($param ,"order_state")) {
            $query .= "\n   AND  T2.order_state = " . $param["order_state"];
        }
        //주문일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  T2.order_common_seqno = " . $param["order_common_seqno"];
        }
        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  T3.member_seqno = " . $param["member_seqno"];
        }
        //인쇄물제목
        if ($this->blankParameterCheck($param ,"title")) {
            $val = substr($param["title"], 1, -1);
            $query .= "\n   AND  T2.title LIKE '%$val%' ";
        }
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd2"], 1, -1);
            $query .= "\n   AND  T2.$val >= $param[from] ";
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd2"], 1, -1);
            $query .= "\n   AND  T2.$val <= $param[to] ";
        }

        //임시테이블 생성 검색 조건
        if ($this->blankParameterCheck($param ,"page_order_detail_brochure_seqno")) {
            $val = substr($param["page_order_detail_brochure_seqno"], 1, -1);
            $query .= "\n   AND  T1.page_order_detail_brochure_seqno IN ($val) ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY  T2.order_num ASC ";

        if ($dvs == "SEQ" && $this->blankParameterCheck($param ,"s_num") && $this->blankParameterCheck($param ,"list_num")) {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 분류코드 가져옴
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateSortcode($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT cate_sortcode ";
        $query .= "\n  FROM order_common ";
        $query .= "\n WHERE order_common_seqno = " . $param["seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 운영체제 가져옴
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOperSys($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT oper_sys ";
        $query .= "\n  FROM order_common ";
        $query .= "\n WHERE order_common_seqno = " . $param["seqno"];

        return $conn->Execute($query);
    }
}
?>
