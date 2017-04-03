<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/calcul_mng/tab/SalesTabHTML.php');

/**
 * @file SalesTabListDAO.php
 *
 * @brief 정산 - 계산서 - 매출계산서리스트 DAO
 */


class SalesTabListDAO extends CommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }
 
    /**
     * @brief 매출계산서 대기리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPublicStandByList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.member_name ";
            $query .= "\n       ,B.req_date ";
            $query .= "\n       ,B.public_date ";
            $query .= "\n       ,B.corp_name ";
            $query .= "\n       ,B.repre_name ";
            $query .= "\n       ,B.crn ";
            $query .= "\n       ,B.bc ";
            $query .= "\n       ,B.tob ";
            $query .= "\n       ,B.addr ";
            $query .= "\n       ,B.zipcode ";
            $query .= "\n       ,B.req_year ";
            $query .= "\n       ,B.req_mon ";
            $query .= "\n       ,B.tel_num ";
            $query .= "\n       ,B.supply_price ";
            $query .= "\n       ,B.unitprice ";
            $query .= "\n       ,B.pay_price ";
            $query .= "\n       ,B.card_price ";
            $query .= "\n       ,B.money_price ";
            $query .= "\n       ,B.etc_price ";
            $query .= "\n       ,B.vat ";
            $query .= "\n       ,A.member_seqno ";
            $query .= "\n       ,A.cpn_admin_seqno ";
            $query .= "\n       ,B.public_admin_seqno ";
            $query .= "\n       ,B.evid_dvs ";
            $query .= "\n       ,B.public_state ";
            $query .= "\n       ,B.tab_public ";
            $query .= "\n       ,B.oa ";
            $query .= "\n       ,B.before_oa ";
            $query .= "\n       ,B.object_price ";
        }
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '대기'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\n   ORDER BY public_admin_seqno DESC";

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
 
    /**
     * @brief 매출계산서 현금영수증리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCashreceiptList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.member_name ";
            $query .= "\n       ,B.req_date ";
            $query .= "\n       ,B.tel_num ";
            $query .= "\n       ,B.supply_price ";
            $query .= "\n       ,B.pay_price ";
            $query .= "\n       ,B.vat ";
            $query .= "\n       ,A.member_seqno ";
            $query .= "\n       ,B.public_admin_seqno ";
            $query .= "\n       ,B.evid_dvs ";
            $query .= "\n       ,B.tab_public ";
            $query .= "\n       ,B.print_title ";
            $query .= "\n       ,B.cashreceipt_num ";
            $query .= "\n       ,B.object_price ";
        }
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '현금영수증'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\n   ORDER BY public_admin_seqno DESC";

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
  
    /**
     * @brief 매출계산서 미발행(현금순매출)리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectUnissuedList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.member_name ";
            $query .= "\n       ,B.req_date ";
            $query .= "\n       ,B.tel_num ";
            $query .= "\n       ,B.supply_price ";
            $query .= "\n       ,B.pay_price ";
            $query .= "\n       ,B.vat ";
            $query .= "\n       ,A.member_seqno ";
            $query .= "\n       ,B.public_admin_seqno ";
            $query .= "\n       ,B.evid_dvs ";
            $query .= "\n       ,B.print_title ";
            $query .= "\n       ,B.cashreceipt_num ";
            $query .= "\n       ,B.object_price ";
        }
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '미발행'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //검색어
        if ($this->blankParameterCheck($param ,"search")) {

            $search = substr($param["search"], 1, -1); 
            $search_dvs = substr($param["search_dvs"], 1, -1);

            if ($search_dvs == "") {

                $query .= "\n   AND  (B.corp_name like '%" . $search . "%'";
                $query .= "\n    OR   B.cashreceipt_num like '%" . $search . "%')";

            } else if ($search_dvs == "name") {

                $query .= "\n   AND  B.corp_name like '%" . $search . "%'";

            } else {

                $query .= "\n   AND  A.cashreceipt_num like '%" . $search . "%'";

            }

        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\n   ORDER BY public_admin_seqno DESC";

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
 
    /**
     * @brief 세금계산서(발급완료) 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPublicCompleteList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.member_name ";
            $query .= "\n       ,B.req_date ";
            $query .= "\n       ,B.public_date ";
            $query .= "\n       ,B.repre_name ";
            $query .= "\n       ,B.crn ";
            $query .= "\n       ,B.bc ";
            $query .= "\n       ,B.tob ";
            $query .= "\n       ,B.addr ";
            $query .= "\n       ,B.zipcode ";
            $query .= "\n       ,B.req_year ";
            $query .= "\n       ,B.req_mon ";
            $query .= "\n       ,B.tel_num ";
            $query .= "\n       ,B.supply_price ";
            $query .= "\n       ,B.unitprice ";
            $query .= "\n       ,B.pay_price ";
            $query .= "\n       ,B.card_price ";
            $query .= "\n       ,B.money_price ";
            $query .= "\n       ,B.etc_price ";
            $query .= "\n       ,B.vat ";
            $query .= "\n       ,A.member_seqno ";
            $query .= "\n       ,A.cpn_admin_seqno ";
            $query .= "\n       ,B.public_admin_seqno ";
            $query .= "\n       ,B.evid_dvs ";
            $query .= "\n       ,B.public_state ";
            $query .= "\n       ,B.tab_public ";
            $query .= "\n       ,B.oa ";
            $query .= "\n       ,B.before_oa ";
            $query .= "\n       ,B.object_price ";
            $query .= "\n       ,B.corp_name ";
        }
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '완료'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //발급 유형
        if ($this->blankParameterCheck($param ,"tab_public")) {
            $query .= "\n   AND  B.tab_public = " . $param["tab_public"];
        }

        //검색어
        if ($this->blankParameterCheck($param ,"search")) {

            $search = substr($param["search"], 1, -1); 
            $search_dvs = substr($param["search_dvs"], 1, -1);

            if ($search_dvs == "") {

                $query .= "\n   AND  (B.corp_name like '%" . $search . "%'";
                $query .= "\n    OR   B.crn like '%" . $search . "%'";
                $query .= "\n    OR   B.repre_name like '%" . $search . "%')";

            } else if ($search_dvs == "name") {

                $query .= "\n   AND  B.corp_name like '%" . $search . "%'";

            } else if ($search_dvs == "num"){

                $query .= "\n   AND  B.crn like '%" . $search . "%'";
            } else {

                $query .= "\n   AND  B.repre_name like '%" . $search . "%'";
            }

        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\n   ORDER BY public_admin_seqno DESC";

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 매출계산서 예외리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPublicExceptList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.member_name ";
            $query .= "\n       ,B.req_date ";
            $query .= "\n       ,B.public_date ";
            $query .= "\n       ,B.public_dvs ";
            $query .= "\n       ,B.repre_name ";
            $query .= "\n       ,B.crn ";
            $query .= "\n       ,B.bc ";
            $query .= "\n       ,B.tob ";
            $query .= "\n       ,B.addr ";
            $query .= "\n       ,B.zipcode ";
            $query .= "\n       ,B.req_year ";
            $query .= "\n       ,B.req_mon ";
            $query .= "\n       ,B.tel_num ";
            $query .= "\n       ,B.req_mon ";
            $query .= "\n       ,B.supply_price ";
            $query .= "\n       ,B.unitprice ";
            $query .= "\n       ,B.pay_price ";
            $query .= "\n       ,B.card_price ";
            $query .= "\n       ,B.money_price ";
            $query .= "\n       ,B.etc_price ";
            $query .= "\n       ,B.vat ";
            $query .= "\n       ,A.member_seqno ";
            $query .= "\n       ,A.cpn_admin_seqno ";
            $query .= "\n       ,B.public_admin_seqno ";
            $query .= "\n       ,B.evid_dvs ";
            $query .= "\n       ,B.public_state ";
            $query .= "\n       ,B.tab_public ";
            $query .= "\n       ,B.oa ";
            $query .= "\n       ,B.before_oa ";
            $query .= "\n       ,B.object_price ";
            $query .= "\n       ,B.corp_name ";
        }
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '예외'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\n   ORDER BY public_admin_seqno DESC";

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
 
 
    /*
     * 세금계산서(대기)
     * 업체수, 매출합계, 카드합계, 발행합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectTabSumPrice($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            COUNT(DISTINCT B.member_seqno)  AS member_cnt";
        $query .= "\n           ,SUM(B.pay_price)   AS pay_total";
        $query .= "\n           ,SUM(B.card_price)   AS card_total";
        $query .= "\n      FROM  member AS A ";
        $query .= "\n           ,public_admin AS B ";
        $query .= "\n     WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n       AND  B.public_dvs = '세금계산서'";
        $query .= "\n       AND  B.public_state = '대기'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }
        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n   AND  A.member_dvs = '기업개인')";
            }
        }
        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }
        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //회원이름
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }


        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 현금영수증
     * 업체수, 매출합계, 카드합계, 발행합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectCashreceiptSum($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            COUNT(DISTINCT B.member_seqno)  AS member_cnt";
        $query .= "\n           ,SUM(B.pay_price)   AS pay_total";
        $query .= "\n           ,SUM(B.card_price)   AS card_total";
        $query .= "\n      FROM  member AS A ";
        $query .= "\n           ,public_admin AS B ";
        $query .= "\n     WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n       AND  B.public_dvs = '현금영수증'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }


        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 현금영수증 미발행
     * 업체수, 매출합계, 카드합계, 발행합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectUnissuedSum($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            COUNT(DISTINCT B.member_seqno)  AS member_cnt";
        $query .= "\n           ,SUM(B.pay_price)   AS pay_total";
        $query .= "\n           ,SUM(B.money_price)   AS money_total";
        $query .= "\n           ,SUM(B.card_price)   AS card_total";
        $query .= "\n      FROM  member AS A ";
        $query .= "\n           ,public_admin AS B ";
        $query .= "\n     WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n       AND  B.public_dvs = '미발행'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }


        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 업체수, 매출합계 가져오기 Select 
     * $conn : DB Connection * return : resultSet 
     */ 
    function selectPublicCompleteSum($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            COUNT(DISTINCT B.member_seqno)  AS member_cnt";
        $query .= "\n           ,SUM(B.pay_price)   AS pay_total";
        $query .= "\n           ,SUM(B.card_price)   AS card_total";
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '완료'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //발급 유형
        if ($this->blankParameterCheck($param ,"public_dvs")) {
            $query .= "\n   AND  B.tab_public = " . $param["public_dvs"];
        }

        //검색어
        if ($this->blankParameterCheck($param ,"search")) {

            $search = substr($param["search"], 1, -1); 
            $search_dvs = substr($param["search_dvs"], 1, -1);

            if ($search_dvs == "") {

                $query .= "\n   AND  (B.corp_name like '%" . $search . "%'";
                $query .= "\n    OR   B.crn like '%" . $search . "%'";
                $query .= "\n    OR   B.repre_name like '%" . $search . "%')";

            } else if ($search_dvs == "name") {

                $query .= "\n   AND  B.corp_name like '%" . $search . "%'";

            } else if ($search_dvs == "num"){

                $query .= "\n   AND  B.crn like '%" . $search . "%'";
            } else {

                $query .= "\n   AND  B.repre_name like '%" . $search . "%'";
            }

        }


        $result = $conn->Execute($query);

        return $result;
    }
 
    /*
     * 세금계산서(예외)
     * 업체수, 매출합계, 카드합계, 발행합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectPublicExceptSum($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            COUNT(DISTINCT B.member_seqno)  AS member_cnt";
        $query .= "\n           ,SUM(B.pay_price)   AS pay_total";
        $query .= "\n           ,SUM(B.card_price)   AS card_total";
        $query .= "\n           ,SUM(B.money_price)   AS money_total";
        $query .= "\n      FROM  member AS A ";
        $query .= "\n           ,public_admin AS B ";
        $query .= "\n     WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n       AND  B.public_dvs = '세금계산서'";
        $query .= "\n       AND  B.public_state = '예외'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }
        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n   AND  A.member_dvs = '기업개인')";
            }
        }
        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }
        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }


        $result = $conn->Execute($query);

        return $result;
    }

    /**
     * @brief 매출계산서 상세
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPublicDetail($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.member_name ";
        $query .= "\n       ,A.member_seqno ";
        $query .= "\n       ,B.public_admin_seqno ";
        $query .= "\n       ,B.public_dvs ";
        $query .= "\n       ,B.evid_dvs ";
        $query .= "\n       ,B.cashreceipt_num ";
        $query .= "\n       ,B.supply_corp ";
        $query .= "\n       ,B.repre_name ";
        $query .= "\n       ,B.addr ";
        $query .= "\n       ,B.tob ";
        $query .= "\n       ,B.crn ";
        $query .= "\n       ,B.bc ";
        $query .= "\n       ,B.public_date ";
        $query .= "\n       ,B.item ";
        $query .= "\n       ,B.supply_price ";
        $query .= "\n       ,B.vat ";
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_admin_seqno = ". $param["seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 관리사업자등록증 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectLicenseeList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  corp_name ";
        $query .= "\n       ,crn ";
        $query .= "\n       ,repre_name ";
        $query .= "\n       ,addr ";
        $query .= "\n       ,addr_detail ";
        $query .= "\n       ,bc ";
        $query .= "\n       ,tob ";
        $query .= "\n       ,admin_licenseeregi_seqno ";
        $query .= "\n  FROM  admin_licenseeregi  ";
        $query .= "\n WHERE  member_seqno = ". $param["member_seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 회원 발급 가능 금액 가져오기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectIssueAblePrice($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  public_object_price ";
        $query .= "\n  FROM  public_object_price  ";
        $query .= "\n WHERE  member_seqno = ". $param["member_seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 회원 발급한 금액 총합
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectIssuePriceSum($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT";
        $query .= "\n            SUM(object_price) AS issue_sum";
        $query .= "\n      FROM  public_admin";
        $query .= "\n     WHERE  member_seqno = ". $param["member_seqno"];
        $query .= "\n       AND  (public_dvs = '세금계산서'";
        $query .= "\n        OR   public_dvs = '현금영수증'";
        $query .= "\n        OR   public_dvs = '미발행')";
        $query .= "\n       AND   tab_public = '발행'";

        return $conn->Execute($query);
    }

    /**
     * @brief 회원 정보 가져오기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT  A.tel_num";
        $query .= "\n           ,A.member_name";
        $query .= "\n           ,B.corp_name";
        $query .= "\n           ,B.repre_name";
        $query .= "\n           ,B.crn";
        $query .= "\n           ,B.bc";
        $query .= "\n           ,B.tob";
        $query .= "\n           ,B.addr";
        $query .= "\n           ,B.addr_detail";
        $query .= "\n           ,B.zipcode";
        $query .= "\n      FROM  member AS A";
        $query .= "\n LEFT JOIN  licensee_info AS B";
        $query .= "\n        ON  A.member_seqno = B.member_seqno";
        $query .= "\n      WHERE A.member_seqno = ". $param["member_seqno"];

        return $conn->Execute($query);
    }


    /**
     * @brief 세금계산서(발급완료) 
     * 리스트에 있는 일련번호select 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCompleteListSeqno($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $except_arr = array("seqno" => true);
        $param = $this->parameterArrayEscape($conn, $param, $except_arr);

        $query  = "\nSELECT  B.public_admin_seqno ";
        $query .= "\n       ,B.req_year ";
        $query .= "\n       ,B.req_mon ";
        $query .= "\n       ,B.req_date ";
        $query .= "\n       ,B.crn ";
        $query .= "\n       ,B.supply_price ";
        $query .= "\n       ,B.vat ";
        $query .= "\n       ,B.print_title ";
        $query .= "\n       ,B.corp_name ";
        $query .= "\n       ,B.cashreceipt_num ";
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '완료'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //발급 유형
        if ($this->blankParameterCheck($param ,"tab_public")) {
            $query .= "\n   AND  B.tab_public = " . $param["tab_public"];
        }

        //검색어
        if ($this->blankParameterCheck($param ,"search")) {

            $search = substr($param["search"], 1, -1); 
            $search_dvs = substr($param["search_dvs"], 1, -1);

            if ($search_dvs == "") {

                $query .= "\n   AND  (B.corp_name like '%" . $search . "%'";
                $query .= "\n    OR   B.crn like '%" . $search . "%'";
                $query .= "\n    OR   B.repre_name like '%" . $search . "%')";

            } else if ($search_dvs == "name") {

                $query .= "\n   AND  B.corp_name like '%" . $search . "%'";

            } else if ($search_dvs == "num"){

                $query .= "\n   AND  B.crn like '%" . $search . "%'";
            } else {

                $query .= "\n   AND  B.repre_name like '%" . $search . "%'";
            }

        }

        //일련번호
        if ($this->blankParameterCheck($param ,"seqno")) {
            $query .= "\n   AND  B.public_admin_seqno IN (" . $param["seqno"] . ')';
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 세금계산서(대기) 
     * 리스트에 있는 일련번호select 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectStandbyListSeqno($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  B.public_admin_seqno ";
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '대기'";

        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 현금영수증
     * 리스트에 있는 일련번호select 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCashreceiptListSeqno($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $except_arr = array("seqno" => true);
        $param = $this->parameterArrayEscape($conn, $param, $except_arr);


        $query  = "\nSELECT  B.public_admin_seqno ";
        $query .= "\n       ,B.req_year ";
        $query .= "\n       ,B.req_mon ";
        $query .= "\n       ,B.req_date ";
        $query .= "\n       ,B.crn ";
        $query .= "\n       ,B.supply_price ";
        $query .= "\n       ,B.vat ";
        $query .= "\n       ,B.print_title ";
        $query .= "\n       ,B.corp_name ";
        $query .= "\n       ,B.cashreceipt_num ";
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '현금영수증'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }

        //계산서_발행
        if ($this->blankParameterCheck($param ,"tab_public")) {
            $query .= "\n   AND  B.tab_public = " . $param["tab_public"];
        }

        //일련번호
        if ($this->blankParameterCheck($param ,"seqno")) {
            $query .= "\n   AND  B.public_admin_seqno IN (" . $param["seqno"] . ')';
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 예외처리
     * 리스트에 있는 일련번호select 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectExceptListSeqno($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $except_arr = array("seqno" => true);
        $param = $this->parameterArrayEscape($conn, $param, $except_arr);

        $query  = "\nSELECT  B.public_admin_seqno ";
        $query .= "\n       ,B.req_year ";
        $query .= "\n       ,B.req_mon ";
        $query .= "\n       ,B.req_date ";
        $query .= "\n       ,B.crn ";
        $query .= "\n       ,B.supply_price ";
        $query .= "\n       ,B.vat ";
        $query .= "\n       ,B.print_title ";
        $query .= "\n       ,B.corp_name ";
        $query .= "\n       ,B.cashreceipt_num ";
        $query .= "\n  FROM  member AS A ";
        $query .= "\n       ,public_admin AS B ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  B.public_dvs = '세금계산서'";
        $query .= "\n   AND  B.public_state = '예외'";
 
        //판매채널
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = ". $param["sell_site"];
        }

        //회원구분
        if ($this->blankParameterCheck($param ,"member_dvs")) {
            $member_dvs = substr($param["member_dvs"], 1, -1);
            if ($member_dvs == "개인") { 

                $query .= "\n   AND  (A.member_dvs = '개인'";
                $query .= "\n    OR   A.member_dvs = '외국인')";

            } else {

                $query .= "\n   AND  (A.member_dvs = '기업'";
                $query .= "\n    OR   A.member_dvs = '기업개인')";
            }
        }

        //년도
        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  B.req_year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  B.req_mon = " . $param["mon"];
        }

        //업체명
        if ($this->blankParameterCheck($param ,"corp_name")) {

            $corp_name = substr($param["corp_name"], 1, -1); 
            $query .= "\n   AND  B.corp_name like '%" . $corp_name . "%'";
        }

        //일련번호
        if ($this->blankParameterCheck($param ,"seqno")) {
            $query .= "\n   AND  B.public_admin_seqno IN (" . $param["seqno"] . ')';
        }

        return $conn->Execute($query);
    }








 
}
?>
