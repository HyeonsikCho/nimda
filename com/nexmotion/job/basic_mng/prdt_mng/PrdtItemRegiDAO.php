<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/basic_mng/prdt_mng/PrdtItemRegiListHtml.php');

class PrdtItemRegiDAO extends BasicMngCommonDAO {

    function __construct() {
    }
  
    /**
     * @brief 카테고리 상품 mpcode 가져옴
     * table prdt_sort 
     */
    function selectCateMpcode($conn, $param) {
        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $table = substr($param["table"], 1, -1);
        $seqno = substr($param["seqno"], 1, -1);

        $query  = "\n SELECT mpcode ";         
        $query .= "\n   FROM $table";            
        $query .= "\n  WHERE $table" . "_seqno IN ($seqno)";

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 후공정 중복체크
     *
     * @param $conn = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 결과값이 존재하면 true, 아니면 false
     */
    function selectCateAfterDupChk($conn, $param) {
        if (!$this->connectionCheck($conn)) {
            return false;
        }
        
        $except_arr = array("table" => true);

        $param = $this->parameterArrayEscape($conn, $param, $except_arr);

        $query  = "\n SELECT 1";
        $query .= "\n   FROM %s";
        $query .= "\n  WHERE cate_sortcode    = %s";
        $query .= "\n    AND prdt_after_seqno = %s";
        $query .= "\n    AND basic_yn         = %s";
        $query .= "\n    AND size             = %s";
        $query .= "\n    AND crtr_unit        = %s";

        $query  = sprintf($query, $param["table"]
                                , $param["prdt_after_seqno"]
                                , $param["cate_sortcode"]
                                , $param["basic_yn"]
                                , $param["size"]
                                , $param["crtr_unit"]);

        $rs = $conn->Execute($query);

        return $rs->EOF ? false : true;
    }

    /**
     * @brief 마지막 맵핑코드 검색
     *
     * @param $conn = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 마지막 맵핑코드
     */
    function selectLastMpcode($conn, $param) {
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        $query  = "\n SELECT MAX(mpcode) AS mpcode";
        $query .= "\n   FROM %s";

        $query  = sprintf($query, $param["table"]);

        $rs = $conn->Execute($query);

        $mpcode = 0;

        if (!$rs->EOF) {
            $mpcode = intval($rs->fields["mpcode"]) + 1;
        }

        return $mpcode;
    }

    /**
     * @brief 마지막 맵핑코드 검색
     *
     * @param $conn = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 마지막 맵핑코드
     */
    function selectCateSize($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT DISTINCT A.name";

        $query .= "\n   FROM  prdt_stan AS A";
        $query .= "\n        ,cate_stan AS B";

        $query .= "\n  WHERE  B.cate_sortcode = %s";
        $query .= "\n    AND  A.prdt_stan_seqno = B.prdt_stan_seqno";

        $query  = sprintf($query, $param["cate_sortcode"]);

        $rs = $conn->Execute($query);

        return $rs;
    }
}
?>
