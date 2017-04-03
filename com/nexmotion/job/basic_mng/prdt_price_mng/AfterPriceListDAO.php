<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/basic_mng/prdt_price_list/PrdtPriceHtml.php');

class AfterPriceListDAO extends BasicMngCommonDAO {

    function __construct() {
    }

    /**
     * @brief 카테고리에 속한 후공정 검색
     *
     * @detail $dvs에 따라서 가져오는 정보가 틀려진다.
     * $dvs에 들어가는 값은 아래와 같다.
     * name        = 종이명
     * dvs         = 구분
     * color       = 색상
     *
     * basisweight = 평량
     * @param $conn  = connection identifier
     * @param $dvs   = 정보 구분
     * @param $param = 검색 조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateAftHtml($conn, $dvs, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $rs = $this->selectCateAftInfo($conn, $dvs, $param);

        return makeOptionHtml($rs, "", strtolower($dvs));
    }

    /**
     * @brief 후공정 가격 검색
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateAftPriceList($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.cate_after_price_seqno AS price_seqno";
        $query .= "\n        ,A.amt";
        $query .= "\n        ,A.basic_price";
        $query .= "\n        ,A.sell_rate";
        $query .= "\n        ,A.sell_aplc_price";
        $query .= "\n        ,A.sell_price ";

        $query .= "\n   FROM  cate_after_price AS A";

        $query .= "\n  WHERE  1 = 1";

        if ($this->blankParameterCheck($param, "after_mpcode")) {
            $query .= "\n    AND  A.cate_after_mpcode = ";
            $query .= $param["after_mpcode"];
        }
        if ($this->blankParameterCheck($param, "sell_site")) {
            $query .= "\n    AND  A.cpn_admin_seqno   = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param, "price_seqno")) {
            $query .= "\n    AND  A.cate_after_price_seqno = ";
            $query .= $param["price_seqno"];
        }
        if ($this->blankParameterCheck($param, "min_amt")) {
            $query .= "\n    AND  " . $param["min_amt"] . " <= (A.amt + 0)";
        }
        if ($this->blankParameterCheck($param, "max_amt")) {
            $query .= "\n    AND  (A.amt + 0) <= " . $param["max_amt"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 가격 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 쿼리 성공여부
     */
    function updateCateAftPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();

        $temp["table"] = "cate_after_price";

        $temp["col"]["sell_rate"]       = $param["sell_rate"];
        $temp["col"]["sell_aplc_price"] = $param["sell_aplc_price"];
        $temp["col"]["sell_price"]      = $param["sell_price"];

        $temp["prk"] = "cate_after_price_seqno";
        $temp["prkVal"] = $param["price_seqno"];

        return $this->updateData($conn, $temp);
    }

    /**
     * @brief 카테고리 후공정 수량정보 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 정보 배열
     *
     * @return option html
     */
    function selectCateAfterAmtHtml($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();
        $temp["col"]    = "amt";
        $temp["table"]  = "cate_after_price";
        $temp["where"]["cate_after_mpcode"] = $param["mpcode"];
        $temp["order"]  = "(amt + 0)";

        $rs = $this->distinctData($conn, $temp);

        return makeCateAmtOption($rs,
                                 $param["amt_unit"]);
    }

    /**
     * @brief 카테고리 후공정 수량단위, 수량검색용 맵핑코드 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 카테고리 수량단위
     */
    function selectCateAfterAmtUnit($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  B.crtr_unit";
        $query .= "\n        ,B.mpcode";
        $query .= "\n   FROM  prdt_after AS A";
        $query .= "\n        ,cate_after AS B";
        $query .= "\n  WHERE  A.prdt_after_seqno = B.prdt_after_seqno";
        $query .= "\n    AND  A.after_name    = %s";
        $query .= "\n    AND  B.cate_sortcode = %s";
        $query .= "\n  LIMIT  1";

        $query  = sprintf($query, $param["after_name"]
                                , $param["cate_sortcode"]);

        $rs = $conn->Execute($query);

        return $rs->fields;
    }
}
?>
