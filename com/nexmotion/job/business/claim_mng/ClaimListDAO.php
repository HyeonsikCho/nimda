<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BusinessCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/business/claim_mng/ClaimListHTML.php');

/**
 * @file MemberCommonListDAO.php
 *
 * @brief 영업 - 클레임관리 - 클레임리스트 DAO
 */

class ClaimListDAO extends BusinessCommonDAO {
    function __construct() {
    }

    /**
     * @brief 클레임리스트 조건검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectClaimListCond($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\n SELECT  COUNT(A.order_claim_seqno) AS cnt";
        } else {
            $query  =   "\nSELECT  A.regi_date ";
            $query .=   "\n       ,C.member_name ";
            $query .=   "\n       ,C.office_nick ";
            //$query .=   "\n       ,C.biz_resp ";
            $query .=   "\n       ,B.order_num ";
            $query .=   "\n       ,A.title ";
            $query .=   "\n       ,A.dvs ";
            $query .=   "\n       ,A.state ";
            $query .=   "\n       ,A.order_claim_seqno ";
            $query .=   "\n       ,A.empl_seqno ";
            $query .=   "\n       ,B.member_seqno ";
            $query .=   "\n       ,B.cpn_admin_seqno ";
        }
        $query .=   "\n  FROM  order_claim AS A ";
        $query .=   "\n       ,order_common AS B ";
        $query .=   "\n       ,member AS C ";
        $query .=   "\n WHERE  A.order_common_seqno = B.order_common_seqno ";
        $query .=   "\n   AND  B.member_seqno = C.member_seqno";
        if ($this->blankParameterCheck($param ,"claim_dvs")) {
            $query .= "\n   AND  A.dvs = ";
            $query .= $param["claim_dvs"];
        }
        /*
        if ($this->blankParameterCheck($param ,"depar_code")) {
            $query .= "\n   AND  C.biz_resp = ";
            $query .= $param["depar_code"];
        }
        */
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  B.cpn_admin_seqno = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  C.member_seqno = ";
            $query .= $param["member_seqno"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND  A.state = ";
            $query .= $param["state"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.$val >= $param[from] ";
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.$val <= $param[to] ";
        }
        $query .= "\nORDER BY A.order_claim_seqno DESC ";
        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
        if (!$dvs) { 
            $query .= "\n   LIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 클레임 상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectClaimView($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);
 
        if ($dvs == "COUNT") {
            $query  = "\n SELECT  COUNT(T1.order_claim_seqno) AS cnt";
        } else {
            $query  = "\n   SELECT T1.* ";
            $query .= "\n         ,T2.depar_code ";
            $query .= "\n         ,T2.name AS empl_name ";
        }
        $query .= "\n     FROM (";
        $query .= "\n               SELECT  A.regi_date ";
        $query .= "\n                      ,C.member_name ";
        $query .= "\n                      ,C.office_nick ";
        $query .= "\n                      ,B.order_num ";
        $query .= "\n                      ,A.title ";
        $query .= "\n                      ,A.dvs ";
        $query .= "\n                      ,A.state ";
        $query .= "\n                      ,A.order_claim_seqno ";
        $query .= "\n                      ,A.empl_seqno ";
        $query .= "\n                      ,A.cust_cont ";
        $query .= "\n                      ,A.dvs_detail ";
        $query .= "\n                      ,A.mng_cont ";
        $query .= "\n                      ,A.extnl_etprs_seqno ";
        $query .= "\n                      ,A.agree_yn ";
        $query .= "\n                      ,A.order_yn ";
        $query .= "\n                      ,A.occur_price ";
        $query .= "\n                      ,A.refund_prepay ";
        $query .= "\n                      ,A.refund_money ";
        $query .= "\n                      ,A.cust_burden_price ";
        $query .= "\n                      ,A.outsource_burden_price ";
        $query .= "\n                      ,A.count AS reorder_count ";
        $query .= "\n                      ,B.member_seqno ";
        $query .= "\n                      ,B.cpn_admin_seqno ";
        $query .= "\n                      ,B.order_common_seqno ";
        $query .= "\n                      ,B.count ";
        $query .= "\n                 FROM  order_claim AS A ";
        $query .= "\n                      ,order_common AS B ";
        $query .= "\n                      ,member AS C ";
        $query .= "\n                WHERE  A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n                  AND  B.member_seqno = C.member_seqno) AS T1 ";
        $query .= "\nLEFT JOIN  empl AS T2 ";
        $query .= "\n       ON  T1.empl_seqno = T2.empl_seqno ";
        if ($this->blankParameterCheck($param ,"order_claim_seqno")) {
            $query .= "\n  WHERE  T1.order_claim_seqno = ";
            $query .= $param["order_claim_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 주문 일련번호로 주문 내용 팝업 html 생성
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 일련번호
     *
     * @return 주문정보팝업 html
     */
    function selectOrderInfoNonePop($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $seqno = $this->parameterEscape($conn, $seqno);

        $query  = "\n SELECT  prdt_basic_info";
        $query .= "\n        ,prdt_add_info";
        $query .= "\n        ,prdt_price_info";
        $query .= "\n        ,prdt_pay_info";
        $query .= "\n   FROM  order_common";
        $query .= "\n  WHERE  order_common_seqno = %s";

        $query  = sprintf($query, $seqno);
        $rs = $conn->Execute($query);

        return makeOrderInfoNonePopHtml($rs->fields);
    }

    /**
     * @brief 주문 일련번호로 주문 내용 팝업 html 생성
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 일련번호
     *
     * @return 주문정보팝업 html
     */
    function selectFlattypYn($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $seqno = $this->parameterEscape($conn, $seqno);

        $query  = "\n SELECT  B.flattyp_yn";
        $query .= "\n   FROM  order_common AS A";
        $query .= "\n        ,cate AS B";
        $query .= "\n  WHERE  A.cate_sortcode = B.sortcode";
        $query .= "\n    AND  A.order_common_seqno = %s";

        $query  = sprintf($query, $seqno);
        return $conn->Execute($query);
    }

    /**
     * @brief 주문 공통 재주문
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 일련번호
     *
     */
    function selectReOrder($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $seqno = $this->parameterEscape($conn, $seqno);
 
        $query  = "\nINSERT INTO order_common (";
        $query .= "\n       order_num";
        $query .= "\n      ,order_state";
        $query .= "\n      ,oper_sys";
        $query .= "\n      ,pro";
        $query .= "\n      ,pro_ver";
        $query .= "\n      ,req_cont";
        $query .= "\n      ,basic_price";
        $query .= "\n      ,grade_sale_price";
        $query .= "\n      ,event_price";
        $query .= "\n      ,use_point_price";
        $query .= "\n      ,sell_price";
        $query .= "\n      ,cp_price";
        $query .= "\n      ,pay_price";
        $query .= "\n      ,order_regi_date";
        $query .= "\n      ,stan_name";
        $query .= "\n      ,amt";
        $query .= "\n      ,amt_unit_dvs";
        $query .= "\n      ,member_seqno";
        $query .= "\n      ,mono_yn";
        $query .= "\n      ,claim_yn";
        $query .= "\n      ,order_detail";
        $query .= "\n      ,title";
        $query .= "\n      ,expec_weight";
        $query .= "\n      ,count";
        $query .= "\n      ,bun_group";
        $query .= "\n      ,receipt_regi_date";
        $query .= "\n      ,memo";
        $query .= "\n      ,cpn_admin_seqno";
        $query .= "\n      ,del_yn";
        $query .= "\n      ,eraser";
        $query .= "\n      ,point_use_yn";
        $query .= "\n      ,owncompany_img_use_yn";
        $query .= "\n      ,pay_way";
        $query .= "\n      ,cate_sortcode";
        $query .= "\n      ,after_use_yn";
        $query .= "\n      ,opt_use_yn";
        $query .= "\n      ,print_tmpt_name";
        $query .= "\n      ,tot_tmpt";
        $query .= "\n      ,prdt_basic_info";
        $query .= "\n      ,prdt_add_info";
        $query .= "\n      ,prdt_price_info";
        $query .= "\n      ,bun_yn";
        $query .= "\n      ,prdt_pay_info";
        $query .= "\n      ,add_after_price";
        $query .= "\n      ,add_opt_price";
        $query .= "\n      ,expenevid_req_yn";
        $query .= "\n      ,expenevid_dvs";
        $query .= "\n      ,expenevid_num";
        $query .= "\n      ,event_yn";
        $query .= "\n      ,receipt_dvs";
        $query .= "\n      ,stor_release_yn";
        $query .= "\n      ,receipt_mng";
        $query .= "\n      ,order_mng";
        $query .= "\n      ,dlvr_finish_date)";
        $query .= "\nSELECT order_num";
        $query .= "\n      ,order_state";
        $query .= "\n      ,oper_sys";
        $query .= "\n      ,pro";
        $query .= "\n      ,pro_ver";
        $query .= "\n      ,req_cont";
        $query .= "\n      ,basic_price";
        $query .= "\n      ,grade_sale_price";
        $query .= "\n      ,event_price";
        $query .= "\n      ,use_point_price";
        $query .= "\n      ,sell_price";
        $query .= "\n      ,cp_price";
        $query .= "\n      ,pay_price";
        $query .= "\n      ,order_regi_date";
        $query .= "\n      ,stan_name";
        $query .= "\n      ,amt";
        $query .= "\n      ,amt_unit_dvs";
        $query .= "\n      ,member_seqno";
        $query .= "\n      ,mono_yn";
        $query .= "\n      ,claim_yn";
        $query .= "\n      ,order_detail";
        $query .= "\n      ,title";
        $query .= "\n      ,expec_weight";
        $query .= "\n      ,count";
        $query .= "\n      ,bun_group";
        $query .= "\n      ,receipt_regi_date";
        $query .= "\n      ,memo";
        $query .= "\n      ,cpn_admin_seqno";
        $query .= "\n      ,del_yn";
        $query .= "\n      ,eraser";
        $query .= "\n      ,point_use_yn";
        $query .= "\n      ,owncompany_img_use_yn";
        $query .= "\n      ,pay_way";
        $query .= "\n      ,cate_sortcode";
        $query .= "\n      ,after_use_yn";
        $query .= "\n      ,opt_use_yn";
        $query .= "\n      ,print_tmpt_name";
        $query .= "\n      ,tot_tmpt";
        $query .= "\n      ,prdt_basic_info";
        $query .= "\n      ,prdt_add_info";
        $query .= "\n      ,prdt_price_info";
        $query .= "\n      ,bun_yn";
        $query .= "\n      ,prdt_pay_info";
        $query .= "\n      ,add_after_price";
        $query .= "\n      ,add_opt_price";
        $query .= "\n      ,expenevid_req_yn";
        $query .= "\n      ,expenevid_dvs";
        $query .= "\n      ,expenevid_num";
        $query .= "\n      ,event_yn";
        $query .= "\n      ,receipt_dvs";
        $query .= "\n      ,stor_release_yn";
        $query .= "\n      ,receipt_mng";
        $query .= "\n      ,order_mng";
        $query .= "\n      ,dlvr_finish_date";
        $query .= "\n FROM  order_common";
        $query .= "\nWHERE  order_common_seqno=" . $seqno;

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else { 
            return true;
        }
    }

    /**
     * @brief 주문 상세 재주문 - 책자형
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 일련번호
     *
     */
    function selectReOrderDetail($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $seqno = $this->parameterEscape($conn, $seqno);
 
        $query  = "\nINSERT INTO order_detail";
        $query .= "\n      (order_common_seqno";
        $query .= "\n      ,typ";
        $query .= "\n      ,page_amt";
        $query .= "\n      ,cate_paper_mpcode";
        $query .= "\n      ,spc_dscr";
        $query .= "\n      ,detail_num";
        $query .= "\n      ,order_detail_num";
        $query .= "\n      ,work_size_wid";
        $query .= "\n      ,work_size_vert";
        $query .= "\n      ,cut_size_wid";
        $query .= "\n      ,cut_size_vert";
        $query .= "\n      ,tomson_size_wid";
        $query .= "\n      ,tomson_size_vert";
        $query .= "\n      ,cate_beforeside_print_mpcode";
        $query .= "\n      ,cate_beforeside_add_print_mpcode";
        $query .= "\n      ,cate_aftside_print_mpcode";
        $query .= "\n      ,cate_aftside_add_print_mpcode";
        $query .= "\n      ,cut_front_wing_size_wid";
        $query .= "\n      ,cut_front_wing_size_vert";
        $query .= "\n      ,work_front_wing_size_wid";
        $query .= "\n      ,work_front_wing_size_vert";
        $query .= "\n      ,cut_rear_wing_size_wid";
        $query .= "\n      ,cut_rear_wing_size_vert";
        $query .= "\n      ,work_rear_wing_size_wid";
        $query .= "\n      ,work_rear_wing_size_vert";
        $query .= "\n      ,seneca_size )";
        $query .= "\nSELECT order_common_seqno";
        $query .= "\n      ,typ";
        $query .= "\n      ,page_amt";
        $query .= "\n      ,cate_paper_mpcode";
        $query .= "\n      ,spc_dscr";
        $query .= "\n      ,detail_num";
        $query .= "\n      ,order_detail_num";
        $query .= "\n      ,work_size_wid";
        $query .= "\n      ,work_size_vert";
        $query .= "\n      ,cut_size_wid";
        $query .= "\n      ,cut_size_vert";
        $query .= "\n      ,tomson_size_wid";
        $query .= "\n      ,tomson_size_vert";
        $query .= "\n      ,cate_beforeside_print_mpcode";
        $query .= "\n      ,cate_beforeside_add_print_mpcode";
        $query .= "\n      ,cate_aftside_print_mpcode";
        $query .= "\n      ,cate_aftside_add_print_mpcode";
        $query .= "\n      ,cut_front_wing_size_wid";
        $query .= "\n      ,cut_front_wing_size_vert";
        $query .= "\n      ,work_front_wing_size_wid";
        $query .= "\n      ,work_front_wing_size_vert";
        $query .= "\n      ,cut_rear_wing_size_wid";
        $query .= "\n      ,cut_rear_wing_size_vert";
        $query .= "\n      ,work_rear_wing_size_wid";
        $query .= "\n      ,work_rear_wing_size_vert";
        $query .= "\n      ,seneca_size";
        $query .= "\n FROM  order_detail";
        $query .= "\nWHERE order_common_seqno=" . $seqno;

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else { 
            return true;
        }
    }

    /**
     * @brief member_seqno 조회
     *        order_file 테이블에 INSERT 하기위함.
     * @param $conn  = connection identifier
     * @param $seqno = 주문 일련번호
     */
    function selectMemberSeqno($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $seqno = $this->parameterEscape($conn, $seqno);
 
        $query  = "\n SELECT  member_seqno";
        $query .= "\n   FROM  order_common";
        $query .= "\n  WHERE  order_common_seqno = %s";

        $query  = sprintf($query, $seqno);
        return $conn->Execute($query);
    }
}
?>
