<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/MemberCommonDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/member/member_mng/MemberCommonList.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/doc/member/member_mng/MemberCommonInfo.php");

/**
 * @file MemberCommonListDAO.php
 *
 * @brief 회원 - 회원관리 - 회원통합리스트 DAO
 */
class MemberCommonListDAO extends MemberCommonDAO {

    function __construct() {
    }

    /**
     * @brief 회원통합리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberInfo($conn, $dvs, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  ="\nSELECT  A.member_seqno ";
            $query .="\n       ,A.member_name ";
            $query .="\n       ,A.office_nick ";
            $query .="\n       ,A.tel_num ";
            $query .="\n       ,A.cell_num ";
            $query .="\n       ,A.member_typ ";
            $query .="\n       ,A.grade ";
            $query .="\n       ,A.office_eval ";
            $query .="\n       ,A.first_join_date ";
            $query .="\n       ,A.final_order_date ";
            $query .="\n       ,A.final_login_date ";
        }

        $query .="\n  FROM  member A ";

        //팀
        if ($this->blankParameterCheck($param ,"depar_code")) { 
            $query .="\n       ,member_mng B ";
            $query .="\n WHERE  A.member_seqno = B.member_seqno ";
            $query .="\n   AND  B.mng_dvs = '일반'";
            $query .="\n   AND  withdraw_dvs = 1 ";
        } else {
            $query .="\n WHERE  withdraw_dvs = 1 "; 
        }

        $query .="\n   AND  withdraw_dvs = 1 ";
        $query .="\n   AND  member_dvs in('개인', '기업', '외국인') ";

        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .="\n   AND  A.cpn_admin_seqno = $param[sell_site] ";
        }
        //팀
        if ($this->blankParameterCheck($param ,"depar_code")) {
            $query .="\n   AND  B.resp_deparcode = $param[depar_code] ";
        }
        //사내닉네임(회원명) -> 일련번호로 변경해서 검색
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  A.member_seqno = $param[member_seqno] ";
        }
        //등급
        if ($this->blankParameterCheck($param ,"grade")) {
            $query .="\n   AND  A.grade = $param[grade] ";
        }
        //회원종류
        if ($this->blankParameterCheck($param ,"member_typ")) {
            $query .="\n   AND  A.member_typ = $param[member_typ] ";
        }
        //최근로그인일자, 최초가입일, 최근주문일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val >= $param[from] ";
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val <= $param[to] ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }
        return $conn->Execute($query);
    }

    /**
     * @brief 회원정보
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberCommonInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\n   SELECT  T1.* ";
        $query .= "\n          ,T2.fix_oa ";
        $query .= "\n          ,T2.bad_oa ";
        $query .= "\n          ,T2.loan_limit_price ";
        $query .= "\n     FROM ( ";
        $query .= "\n               SELECT  A.member_seqno ";
        $query .= "\n                      ,A.member_name ";
        $query .= "\n                      ,A.member_id ";
        $query .= "\n                      ,A.member_dvs ";
        $query .= "\n                      ,B.sell_site ";
        $query .= "\n                      ,A.cell_num ";
        $query .= "\n                      ,A.tel_num ";
        $query .= "\n                      ,A.mail ";
        $query .= "\n                      ,A.birth ";
        $query .= "\n                      ,A.new_yn ";
        $query .= "\n                      ,A.member_typ ";
        $query .= "\n                      ,A.onefile_etprs_yn ";
        $query .= "\n                      ,A.card_pay_yn ";
        $query .= "\n                 FROM  member AS A ";
        $query .= "\n                      ,cpn_admin AS B ";
        $query .= "\n                WHERE  A.cpn_admin_seqno = B.cpn_admin_seqno) AS T1 ";
        $query .= "\nLEFT JOIN  excpt_member AS T2 ";
        $query .= "\n       ON  T1.member_seqno = T2.member_seqno ";

        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n    WHERE  T1.member_seqno = $param[member_seqno] ";
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원 배송정보
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberDlvrInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  dlvr_friend_yn ";
        $query .= "\n       ,dlvr_friend_main ";
        $query .= "\n       ,dlvr_dvs ";
        $query .= "\n       ,order_way ";
        $query .= "\n       ,dlvr_code ";
        $query .= "\n       ,direct_dlvr_yn ";
        $query .= "\n  FROM  member ";
        $query .= "\n WHERE  1 = 1 ";

        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  member_seqno = $param[member_seqno] ";
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원 배송관리 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateMemberDlvrWay($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  order_way = %s ";
        $query .= "\n           ,dlvr_dvs = %s ";
        $query .= "\n           ,dlvr_code = %s ";
        $query .= "\n           ,direct_dlvr_yn = %s ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, $param["order_way"], $param["dlvr_dvs"],
                         $param["dlvr_code"], $param["is_use_direct"], $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
 

    /**
     * @brief 회원 기본정보 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateMemberBasicInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param["final_modi_date"] = date("Y-m-d H:i:s");;

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  member_dvs = %s ";
        $query .= "\n           ,tel_num = %s ";
        $query .= "\n           ,cell_num = %s ";
        $query .= "\n           ,mail = %s ";
        $query .= "\n           ,birth = %s ";
        $query .= "\n           ,member_typ = %s ";
        $query .= "\n           ,onefile_etprs_yn = %s ";
        $query .= "\n           ,card_pay_yn = %s ";
        $query .= "\n           ,final_modi_date = %s ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, $param["member_dvs"], $param["tel_num"], $param["cell_num"],
                         $param["mail"], $param["birth"], 
                         $param["member_typ"], $param["onefile_etprs_yn"], 
                         $param["card_pay_yn"], $param["final_modi_date"],
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
 
    /**
     * @brief 회원요약정보
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberSummaryInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.member_id ";
        $query .= "\n       ,A.office_nick ";
        $query .= "\n       ,A.mailing_yn ";
        $query .= "\n       ,A.sms_yn ";
        $query .= "\n       ,B.grade_name ";
        $query .= "\n       ,A.member_typ ";
        $query .= "\n       ,A.own_point ";
        $query .= "\n       ,A.office_eval ";
        $query .= "\n       ,A.first_join_date ";
        $query .= "\n       ,A.first_order_date ";
        $query .= "\n       ,A.final_order_date ";
        $query .= "\n       ,A.nc_release_resp ";
        $query .= "\n       ,A.bl_release_resp ";
        $query .= "\n       ,A.cpn_admin_seqno ";
        $query .= "\n  FROM  member A ";
        $query .= "\n       ,member_grade_policy B ";
        $query .= "\n WHERE  A.grade = B.grade ";

        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  A.member_seqno = $param[member_seqno] ";
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원정보 기본정보 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateMemberDetailBasicInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param["final_modi_date"] = date("Y-m-d H:i:s");;

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  office_nick = %s ";
        $query .= "\n           ,mailing_yn = %s ";
        $query .= "\n           ,sms_yn = %s ";
        $query .= "\n           ,state = %s ";
        $query .= "\n           ,office_eval = %s ";
        $query .= "\n           ,nc_release_resp = %s ";
        $query .= "\n           ,bl_release_resp = %s ";
        $query .= "\n           ,final_modi_date = %s ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, $param["office_nick"], $param["mailing_yn"],
                         $param["sms_yn"], $param["member_state"], $param["office_eval"], 
                         $param["nc_release_resp"], $param["bl_release_resp"], 
                         $param["final_modi_date"],
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 회원정보 현금영수증정보 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateMemberDetailCashInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param["final_modi_date"] = date("Y-m-d H:i:s");

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  cashreceipt_card_num = %s ";
        $query .= "\n           ,cashreceipt_cell_num = %s ";
        $query .= "\n           ,final_modi_date = %s ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, $param["cashreceipt_card_num"],
                         $param["cashreceipt_cell_num"],
                         $param["final_modi_date"],
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 회원정보탭 - 정산정보
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberCalculInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n   SELECT  A.prepay_price ";
        $query .= "\n          ,A.order_lack_price ";
        $query .= "\n          ,A.member_name ";
        $query .= "\n          ,B.bank_name ";
        $query .= "\n          ,B.ba_num ";
        $query .= "\n     FROM  member  AS A ";
        $query .= "\nLEFT JOIN  virt_ba_admin AS B ";
        $query .= "\n       ON  A.member_seqno = B.member_seqno ";
        $query .= "\n    WHERE  1 = 1 ";

        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n      AND  A.member_seqno = $param[member_seqno] ";
        }
 
        return $conn->Execute($query);
    }

    /**
     * @brief 회원정보 회원탈퇴
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateMemberWithdraw($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param["final_modi_date"] = date("Y-m-d H:i:s");

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  withdraw_dvs = %s ";
        $query .= "\n           ,final_modi_date = %s ";
        $query .= "\n           ,own_point = NULL ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, 3,
                         $param["final_modi_date"],
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 회원매출리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberSalesInfo($conn, $dvs, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "TOTAL") {
            $query  ="\nSELECT  SUM(pay_price) AS sum ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.order_num ";
            $query .= "\n       ,A.title";
            $query .= "\n       ,A.order_detail ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.count ";
            $query .= "\n       ,A.pay_price ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.order_state ";
            $query .= "\n       ,A.order_common_seqno ";
        }

        $query .= "\n  FROM order_common AS A ";
        $query .= "\n WHERE 1 = 1 ";
        $query .= "\n   AND order_state > 1300 ";
 
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  A.member_seqno = $param[member_seqno] ";
        }

        //최근로인일자, 최초가입일, 최근주문일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val > $param[from] ";
        }

        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val <= $param[to] ";
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.title LIKE '%" . $val . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
 
        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type . ", A.order_common_seqno DESC";
            }
        } else {
            $query .= "\n ORDER BY A.order_common_seqno DESC";
        }

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원포인트 지급내역 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberPointReqInfo($conn, $dvs, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.point_name ";
            $query .= "\n       ,A.point ";
            $query .= "\n       ,A.req_empl_name ";
            $query .= "\n       ,A.aprvl_empl_name ";
            $query .= "\n       ,A.reason ";
            $query .= "\n       ,A.state ";
        }

        $query .= "\n  FROM  member_point_req AS A ";
        $query .= "\n WHERE 1 = 1 ";
 
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  A.member_seqno = $param[member_seqno] ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
 
        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원포인트 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberPointInfo($conn, $dvs, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query .= "\nSELECT  A.regi_date ";
            $query .= "\n       ,A.point_name ";
            $query .= "\n       ,A.point ";
            $query .= "\n       ,A.rest_point ";
            $query .= "\n       ,A.order_price ";
            $query .= "\n       ,A.give_reason ";
            $query .= "\n       ,A.dvs ";
            $query .= "\n       ,A.note ";
        }

        $query .= "\n  FROM  member_point_history AS A ";
        $query .= "\n WHERE 1 = 1 ";
 
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  A.member_seqno = $param[member_seqno] ";
        }

        //거래일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val > $param[from] ";
        }

        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val <= $param[to] ";
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.point_name LIKE '%" . $val . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
 
        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type . " ,A.member_point_history_seqno DESC";
            }
        } else {
            $query .= "\n ORDER BY A.member_point_history_seqno DESC";
        }

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원쿠폰 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberCouponInfo($conn, $dvs, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.cp_name";
            $query .= "\n       ,A.val";
            $query .= "\n       ,A.unit";
            $query .= "\n       ,A.max_sale_price";
            $query .= "\n       ,A.min_order_price";
            $query .= "\n       ,B.use_deadline";
            $query .= "\n       ,B.issue_date";
            $query .= "\n       ,B.use_yn";
        }
        $query .= "\n  FROM  cp AS A";
        $query .= "\n       ,cp_issue AS B";
        $query .= "\n WHERE  A.cp_seqno = B.cp_seqno";   


        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  B.member_seqno = $param[member_seqno] ";
        }

        //발급일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val > $param[from] ";
        }

        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val <= $param[to] ";
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.cp_name LIKE '%" . $val . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
 
        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type . ", cp_issue_seqno DESC";
            }
        } else {
            $query .= "\n ORDER BY cp_issue_seqno DESC";
        }

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원이벤트 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberEventInfo($conn, $dvs, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.event_typ ";
            $query .= "\n       ,A.prdt_name ";
            $query .= "\n       ,A.bnf ";
            $query .= "\n       ,A.regi_date ";
        }
        
        $query .= "\n  FROM  member_event AS A ";
        $query .= "\n WHERE  1 = 1 ";
 
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .="\n   AND  A.member_seqno = $param[member_seqno] ";
        }

        //등록일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val > $param[from] ";
        }

        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .="\n   AND  A.$val <= $param[to] ";
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.event_typ LIKE '%" . $val . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
 
        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type . " ,member_event_seqno DESC";
            }
        } else {
            $query .= "\n ORDER BY member_event_seqno DESC";
        }

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원에게 부여 된 가상계좌 반환
     *
     * @param $conn  = connection identifier
     *
     * @return 검색결과
     */
    function updateVirtBaAdmin($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param["final_modi_date"] = date("Y-m-d H:i:s");

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  virt_ba_admin ";
        $query .= "\n       SET  cpn_admin_seqno = %s ";
        $query .= "\n           ,member_seqno = %s ";
        $query .= "\n           ,state = 'N' ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, 'NULL',
                         'NULL',
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 회원 등급 수동여부
     *
     * @param $conn  = connection identifier
     *
     * @return 검색결과
     */
    function updateMemberAutoGrade($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param["final_modi_date"] = date("Y-m-d H:i:s");

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  auto_grade_yn = %s ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query, $param["auto_grade_yn"],
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 일반 접수팀
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectGeneInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $val = substr($param["depar_code"], 1, -1);

        $query  = "\nSELECT  A.depar_name ";
        $query .= "\n       ,A.depar_code ";
        $query .= "\n  FROM  depar_admin AS A ";
        $query .= "\n WHERE  depar_name IN ('영업1팀', '영업2팀', '방문')";
        $query .= "\n   AND  depar_level = 2";

        //카테고리 분류코드 빈값 체크
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = $param[sell_site] ";
        }

        return $conn->Execute($query);
    }
}
?>
