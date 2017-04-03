<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/MktCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/mkt/mkt_mng/CpMngHTML.php");

class CpMngDAO extends MktCommonDAO {

    function __construct() {
    }

    /*
     * 쿠폰 list select 
     * $conn : db connection
     * $param["cpn_seqno"] : 회사 관리 일련번호
     * return : resultset 
     */ 
    function selectCpList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT   A.cp_name";
        $query .= "\n            ,A.val";
        $query .= "\n            ,A.max_sale_price";
        $query .= "\n            ,A.min_order_price";
        $query .= "\n            ,A.unit";
        $query .= "\n            ,A.regi_date";
        $query .= "\n            ,A.object_appoint_way";
        $query .= "\n            ,A.use_yn";
        $query .= "\n            ,A.public_amt";
        $query .= "\n            ,A.cp_seqno";
        $query .= "\n            ,A.public_period_start_date";
        $query .= "\n            ,A.public_period_end_date";
        $query .= "\n            ,A.usehour_yn";
        $query .= "\n            ,A.usehour_start_hour";
        $query .= "\n            ,A.usehour_end_hour";
        $query .= "\n            ,A.expire_dvs";
        $query .= "\n            ,A.expire_public_day";
        $query .= "\n            ,A.expire_extinct_date";
        $query .= "\n            ,A.cp_extinct_date";
        $query .= "\n            ,A.cp_expo_yn";
        $query .= "\n            ,B.sell_site";
        $query .= "\n            ,B.cpn_admin_seqno";
        $query .= "\n      FROM   cp A";
        $query .= "\n            ,cpn_admin B";
        $query .= "\n     WHERE  A.cpn_admin_seqno = B.cpn_admin_seqno";
        $query .= "\n       AND  A.use_yn = 'Y'";

        //판매사이트가 있을때
        if ($this->blankParameterCheck($param ,"cpn_seqno")) {

            $query .= "\n       AND  B.cpn_admin_seqno =" . $param["cpn_seqno"];
        }

        //쿠폰 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"cp_seqno")) {

            $query .= "\n       AND  A.cp_seqno =" . $param["cp_seqno"];
        }

        $result = $conn->Execute($query);

        return $result;

    }

    /*
     * 회원 정보  list select 
     * $conn : db connection
     * $param["cpn_seqno"] : 회사 관리 일련번호
     * return : resultset 
     */ 
    function selectMemberInfoList($conn, $param) {

        if (!$this->connectioncheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\n    SELECT   office_nick";
        $query .= "\n            ,grade";
        $query .= "\n            ,member_typ";
        $query .= "\n            ,cell_num";
        //$query .= "\n            ,office_member_mng";
        $query .= "\n            ,member_seqno";
        $query .= "\n      FROM   member";
        //그룹 아이디가 없을때
        $query .= "\n     WHERE    member_dvs != '기업개인'";
        $query .= "\n       AND    withdraw_dvs = '1'";

        //판매사이트가 있을때
        if ($this->blankParameterCheck($param ,"cpn_seqno")) {

            $query .= "\n       AND  cpn_admin_seqno =" . $param["cpn_seqno"];
        }

        //회원명 검색 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n       AND  member_seqno =" . $param["member_seqno"];
        }

        //팀 구분 검색 있을때
        if ($this->blankParameterCheck($param ,"depar_dvs")) {

            $query .= "\n       AND  ( biz_resp  =" . $param["depar_dvs"];
            $query .= "\n       OR     release_resp  =" . $param["depar_dvs"];
            $query .= "\n       OR     dlvr_resp  =" . $param["depar_dvs"] . ")";

        }

        //등급 검색 있을때
        if ($this->blankParameterCheck($param ,"grade")) {

            $query .= "\n       AND  grade =" . $param["grade"];
        }
 
        //회원 구분 있을때
        if ($this->blankParameterCheck($param ,"member_typ")) {

            $query .= "\n       AND  member_typ =" . $param["member_typ"];

        }

        $result = $conn->Execute($query);

        return $result;
 
    }

    /*
     * 쿠폰 발급  list select 
     * $conn : db connection
     * $param["cp_seqno"] : 쿠폰 일련번호
     * return : resultset 
     */ 
    function selectCpIssueList($conn, $param) {

        if (!$this->connectioncheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\n    SELECT   A.office_nick";
        $query .= "\n            ,A.member_seqno";
        $query .= "\n            ,B.cp_num";
        $query .= "\n      FROM   member A";
        $query .= "\n            ,cp_issue B";
        $query .= "\n            ,cp C";
        $query .= "\n     WHERE   A.member_seqno = B.member_seqno";
        $query .= "\n       AND   B.cp_seqno = C.cp_seqno";

        //쿠폰 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"cp_seqno")) {

            $query .= "\n       AND   B.cp_seqno =" . $param["cp_seqno"];
        }

        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 회원 list select 
     * $conn : db connection
     * $param["cpn_seqno"] : 회사 관리 일련번호
     * return : resultset 
     */ 
    function selectMemberNickList($conn, $param) {

        if (!$this->connectioncheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\n    SELECT   office_nick";
        $query .= "\n            ,member_seqno";
        $query .= "\n      FROM   member A";
        $query .= "\n     WHERE   cpn_admin_seqno=" . $param["cpn_seqno"];
        //그룹 아이디가 없을때
        $query .= "\n       AND   (group_id = ''";
        $query .= "\n        OR    group_id IS NULL)";
        //정상회원만
        $query .= "\n       AND   withdraw_dvs = '1'";
        

        //회원명 검색 있을때
        if ($this->blankParameterCheck($param ,"search")) {

            $search_str = substr($param["search"], 1, -1); 

            $query .= "\n       AND  office_nick like '%" . $search_str . "%' ";
        }

        $query .= "\n  ORDER BY member_seqno";

        $result = $conn->Execute($query);

        return $result;
    }


    /**
     * @brief 쿠폰 통계 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCpStatsList($conn, $param) {

        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT  mon_cp_use_stats_seqno";
        $query .= "\n           ,year ";
        $query .= "\n           ,mon ";
        $query .= "\n           ,cp_seqno ";
        $query .= "\n           ,cpn_admin_seqno ";
        $query .= "\n           ,issue_count ";
        $query .= "\n           ,use_count ";
        $query .= "\n           ,use_price ";
        $query .= "\n      FROM  mon_cp_use_stats";
        $query .= "\n     WHERE  1=1 ";
        $query .= "\n       AND  cpn_admin_seqno = " . $param["cpn_seqno"];
        $query .= "\n       AND  year = " . $param["year"];
        $query .= "\n       AND  mon = " . $param["mon"];

        return $conn->Execute($query);
    }
    
    /**
     * @brief 쿠폰 통계 카테고리 조회
     * 
     * @detail 
     *
     * @param $conn  = 디비 커넥션
     * @param $cp_seqno = 쿠폰번호
     *
     * @return
     */
    function selectCpStatsCate($conn, $cp_seqno) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }
        $query  = "\n SELECT cate_name";
        $query .= "\n   FROM cp_cate cc, cate c";
        $query .= "\n  WHERE cc.cp_cate_sortcode = c.sortcode";
        $query .= "\n    AND cc.cp_seqno = ". $cp_seqno;

        return $conn->Execute($query);
    }

    /**
     * @brief 쿠폰 카테고리 조회
     * 
     * @param $conn     = 디비 커넥션
     * @param $cp_seqno = 쿠폰 일련번호
     *
     * @return 검색결과
     */
    function selectCpCate($conn, $cp_seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $cp_seqno = $this->parameterEscape($conn, $cp_seqno);

        $query  = "\n SELECT cp_cate_sortcode";
        $query .= "\n   FROM cp_cate AS A";
        $query .= "\n  WHERE A.cp_seqno = ". $cp_seqno;

        return $conn->Execute($query);
    }
}
?>
