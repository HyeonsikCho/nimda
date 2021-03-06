<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');

class OptPriceListDAO extends BasicMngCommonDAO {

    function __construct() {
    }

    /**
     * @brief 카테고리에 속한 옵션 검색
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
    function selectCateOptHtml($conn, $dvs, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $rs = $this->selectCateOptInfo($conn, $dvs, $param);

        return makeOptionHtml($rs, "", strtolower($dvs));
    }

    /**
     * @brief 옵션 가격 검색
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateOptPriceList($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.cate_opt_price_seqno AS price_seqno";
        $query .= "\n        ,A.amt";
        $query .= "\n        ,A.basic_price";
        $query .= "\n        ,A.sell_rate";
        $query .= "\n        ,A.sell_aplc_price";
        $query .= "\n        ,A.sell_price ";

        $query .= "\n   FROM  cate_opt_price AS A";

        $query .= "\n  WHERE  1 = 1";

        if ($this->blankParameterCheck($param, "opt_mpcode")) {
            $query .= "\n    AND  A.cate_opt_mpcode = ";
            $query .= $param["opt_mpcode"];
        }
        if ($this->blankParameterCheck($param, "sell_site")) {
            $query .= "\n    AND  A.cpn_admin_seqno   = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param, "price_seqno")) {
            $query .= "\n    AND  A.cate_opt_price_seqno = ";
            $query .= $param["price_seqno"];
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
    function updateCateOptPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();

        $temp["table"] = "cate_opt_price";

        $temp["col"]["sell_rate"]       = $param["sell_rate"];
        $temp["col"]["sell_aplc_price"] = $param["sell_aplc_price"];
        $temp["col"]["sell_price"]      = $param["sell_price"];

        $temp["prk"] = "cate_opt_price_seqno";
        $temp["prkVal"] = $param["price_seqno"];

        return $this->updateData($conn, $temp);
    }
}
?>
