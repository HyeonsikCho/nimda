<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/basic_mng/cate_mng/CateListHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/basic_mng/cate_mng/CateTreeHTML.php');

/**
 * @file CateListDAO.php
 *
 * @brief 기초관리 - 카테고리관리 - 카테고리리스트 DAO
 */
class CateListDAO extends BasicMngCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 카테고리 별 계산방식 정보 수정
     */
    function updateCalculWay($conn, $param) {

        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }
  
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        //카테고리 생산유형 빈값 체크
        if (!$this->blankParameterCheck($param ,"flattyp_yn")) {
            return false;
        }
 
        //카테고리 조판방식 빈값 체크
        if (!$this->blankParameterCheck($param ,"typset_way")) {
            return false;
        }
 
        //카테고리 사용여부 빈값 체크
        if (!$this->blankParameterCheck($param ,"use_yn")) {
            return false;
        }

        //카테고리 계산방식 구분 빈값 체크
        if (!$this->blankParameterCheck($param ,"mono_dvs")) {
            return false;
        }

        //카테고리 도수 구분 빈값 체크
        if (!$this->blankParameterCheck($param ,"tmpt_dvs")) {
            return false;
        }

        //카테고리 분류코드 빈값 체크
        if (!$this->blankParameterCheck($param ,"sortcode")) {
            return false;
        }

        $query  = "\n UPDATE cate ";
        $query .= "\n    SET mono_dvs = " . $param["mono_dvs"];
        $query .= "\n       ,cate_name = " . $param["cate_name"];
        $query .= "\n       ,flattyp_yn = " . $param["flattyp_yn"];
        $query .= "\n       ,typset_way = " . $param["typset_way"];
        $query .= "\n       ,outsource_etprs_cate = " . $param["outsource_etprs_cate"];
        $query .= "\n       ,use_yn = " . $param["use_yn"];
        $query .= "\n       ,amt_unit = " . $param["amt_unit"];
        $query .= "\n       ,tmpt_dvs = " . $param["tmpt_dvs"];
        $query .= "\n  WHERE sortcode = " . $param["sortcode"];

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 이름을 받아옴
     */
    function selectCateName($conn, $param) {
        
        if (!$this->connectionCheck($conn)) {
            return false;
        }
 
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        if (!$this->blankParameterCheck($param ,"high_sortcode")) {
            return false;
        }

        $query  = "\n    SELECT  cate_name";
        $query .= "\n           ,high_sortcode";
        $query .= "\n      FROM  cate ";
        $query .= "\n     WHERE  sortcode = " . $param["high_sortcode"]; 

        $result = $conn->Execute($query);
        $cate_name = $result->fields["cate_name"];
        $high_sortcode = $result->fields["high_sortcode"];

        return $cate_name . "♪" . $high_sortcode;
    }

    /**
     * @brief 카테고리 sortcode 생성
     */
    function getCateCode($conn, $param) {

        if (!$this->connectionCheck($conn)) {
            return false;
        }
 
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        if (!$this->blankParameterCheck($param ,"cate_level")) {
            return false;
        }

        $query  = "\n    SELECT max(sortcode) AS sortcode";
        $query .= "\n      FROM cate  ";
        $query .= "\n     WHERE cate_level = " . $param["cate_level"];
        $query .= "\n       AND high_sortcode = " . $param["high_sortcode"];

        $rs = $conn->Execute($query);
        
        $sortcode = $rs->fields["sortcode"];
        $level = intval(substr($param["cate_level"], 1, -1));

        //신규 카테고리 일 경우
        if ($sortcode == NULL) { 
            $sortcode = substr($param["high_sortcode"], 1, -1) . "000";
        }
        
        $rs = str_pad($sortcode+1, $level*3, "0", STR_PAD_LEFT);

        return $rs;
    }

    /**
     * @brief 카테고리 삽입
     */
    function insertCate($conn, $param) {

        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //insert parameter set
        $param["sortcode"] = $this->getCateCode($conn, $param); 

        $param["mono_yn"] = "2";
        $param["flattyp_yn"] = "N";
        $param["regi_date"] = date("Y-m-d H:i:s");;

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    INSERT ";
        $query .= "\n      INTO  cate  ";
        $query .= "\n            (sortcode ";
        $query .= "\n            ,mono_dvs ";
        $query .= "\n            ,flattyp_yn ";
        $query .= "\n            ,regi_date ";
        $query .= "\n            ,cate_name ";
        $query .= "\n            ,cate_level ";
        $query .= "\n            ,tot_name ";
        $query .= "\n            ,high_sortcode "; 
        $query .= "\n            ,tmpt_dvs"; 
        $query .= "\n            ,amt_unit) "; 
        $query .= "\n    VALUES (%s,%s,%s,%s,%s,%s, ";
        $query .= "\n            %s,%s, '0', '장') ";

        $query = sprintf($query, $param["sortcode"], 1, 
                         $param["flattyp_yn"], $param["regi_date"], 
                         $param["cate_name"], $param["cate_level"], 
                         $param["tot_name"], $param["high_sortcode"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 별 회원등급 할인정보 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateGradeInfo($conn, $param) {

        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션어택방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n         SELECT  B.grade_sale_price_seqno ";
        $query .= "\n                ,A.grade_name ";
        $query .= "\n                ,A.grade ";
        $query .= "\n                ,B.cate_sortcode ";
        $query .= "\n                ,B.rate ";
        $query .= "\n           FROM  member_grade_policy A ";
        $query .= "\nLEFT OUTER JOIN  grade_sale_price B ";
        $query .= "\n             ON  A.grade = B.grade ";

        if ($this->blankParameterCheck($param, "cate_sortcode")) {
            $query .= "\n            AND  B.cate_sortcode = ";
            $query .= $param["cate_sortcode"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 별 회원 할인정보 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateMemberSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션어택방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  B.member_seqno";
        $query .= "\n        ,A.rate";
        $query .= "\n        ,B.member_name";
        $query .= "\n   FROM  cate_member_sale AS A";
        $query .= "\n        ,member           AS B";
        $query .= "\n  WHERE  A.member_seqno  = B.member_seqno";
        $query .= "\n    AND  A.cate_sortcode = %s";
        
        if ($this->blankParameterCheck($param, "member_seqno")) {
            $query .= "\n            AND  A.member_seqno = ";
            $query .= $param["member_seqno"];
        }

        $query  = sprintf($query, $param["cate_sortcode"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 별 회원 할인정보 입력
     *
     * @param $conn  = connection identifier
     * @param $param = 입력값 파라미터
     *
     * @return 검색결과
     */
    function insertCateMemberSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();
        $temp["col"]["member_seqno"]  = $param["member_seqno"];
        $temp["col"]["cate_sortcode"] = $param["cate_sortcode"];
        $temp["col"]["rate"]          = $param["rate"];
        $temp["table"] = "cate_member_sale";

        return $this->insertData($conn, $temp);
    }

    /**
     * @brief 카테고리 별 회원 할인정보 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 수정값 파라미터
     *
     * @return 검색결과
     */
    function updateCateMemberSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE cate_member_sale";
        $query .= "\n    SET rate = %s";
        $query .= "\n  WHERE cate_sortcode = %s";
        $query .= "\n    AND member_seqno  = %s";

        $query  = sprintf($query, $param["rate"]
                                , $param["cate_sortcode"]
                                , $param["member_seqno"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 별 회원 할인정보 삭제
     *
     * @param $conn  = connection identifier
     * @param $param = 삭제값 파라미터
     *
     * @return 검색결과
     */
    function deleteCateMemberSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n DELETE";
        $query .= "\n   FROM cate_member_sale";
        $query .= "\n  WHERE cate_sortcode = %s";
        $query .= "\n    AND member_seqno  = %s";

        $query  = sprintf($query, $param["cate_sortcode"]
                                , $param["member_seqno"]);

        return $conn->Execute($query);
    }
}
?>
