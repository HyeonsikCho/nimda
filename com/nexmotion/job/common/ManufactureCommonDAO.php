<?
//CommonDAO extends ErpCommonDAO
//ErpCommonDAO extends ManufactureCommonDAO 

include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');

//생산 공통 DAO
class ManufactureCommonDAO extends CommonDAO {

    function __construct() {
    }

    //기존 작업일지 작업완료 시간 입력
	function updateWorkReport($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query = "\n UPDATE " . $param["table"]  . " SET";

        $i = 0;
        $col = "";
        $value = "";

        foreach ($param["col"] as $key => $val) {

            $inchr = $conn->qstr($val,get_magic_quotes_gpc());

            if ($i == 0) {
                $value  .= "\n " . $key . "=" . $inchr;
            } else {
                $value  .= "\n ," . $key . "=" . $inchr;
            }

            $i++;
        }

        $query .= $value;
        $query .= "\n WHERE " . $param["prk"] . "=" . $conn->qstr($param["prkVal"], get_magic_quotes_gpc());
		$query .= "\n    AND valid_yn = 'Y'";

        return $conn->Execute($query);
	}

    /**
     * @brief 종이발주리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperOpMngList($conn, $param) {

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
            $query  = "\nSELECT  A.paper_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,A.op_num ";
            $query .= "\n       ,A.op_date ";
            $query .= "\n       ,A.stor_date ";
            $query .= "\n       ,A.name ";
            $query .= "\n       ,A.dvs ";
            $query .= "\n       ,A.color ";
            $query .= "\n       ,A.basisweight ";
            $query .= "\n       ,A.op_affil ";
            $query .= "\n       ,A.op_size ";
            $query .= "\n       ,A.stor_subpaper ";
            $query .= "\n       ,A.stor_size ";
            $query .= "\n       ,A.grain ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.memo ";
            $query .= "\n       ,A.typ ";
            $query .= "\n       ,A.typ_detail ";
            $query .= "\n       ,A.orderer ";
            $query .= "\n       ,A.warehouser ";
            $query .= "\n       ,A.flattyp_dvs ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,A.regi_date ";
            $query .= "\n       ,A.extnl_brand_seqno ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,D.manu_name AS storplace";
        }
        $query .= "\n  FROM  paper_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n       ,extnl_etprs AS D ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.storplace = D.extnl_etprs_seqno ";
 
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND  A.state = " . $param["state"];
        }
        if ($this->blankParameterCheck($param ,"paper_op_seqno")) {
            $query .= "\n   AND  A.paper_op_seqno = " . $param["paper_op_seqno"];
        }
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        if ($this->blankParameterCheck($param ,"storplace")) {
            $query .= "\n   AND  A.storplace = " . $param["storplace"];
        }
        if ($this->blankParameterCheck($param ,"search_val")) {
            $val = substr($param["cnd_val"], 1, -1);
            $query .= "\n   AND  A." . $val. " = " . $param["search_val"];
        }
        if ($this->blankParameterCheck($param ,"typset_num")) {
            $query .= "\n   AND  A.typset_num = " . $param["typset_num"];
        }
        $query .= "\n   ORDER BY A.paper_op_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 종이발주 상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperOpView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.paper_op_seqno ";
        $query .= "\n       ,A.typset_num ";
        $query .= "\n       ,A.name ";
        $query .= "\n       ,A.dvs ";
        $query .= "\n       ,A.color ";
        $query .= "\n       ,A.basisweight ";
        $query .= "\n       ,C.manu_name ";
        $query .= "\n       ,A.op_affil ";
        $query .= "\n       ,A.op_size ";
        $query .= "\n       ,A.stor_subpaper ";
        $query .= "\n       ,A.stor_size ";
        $query .= "\n       ,A.storplace ";
        $query .= "\n       ,A.state ";
        $query .= "\n       ,A.grain ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.typ ";
        $query .= "\n       ,A.typ_detail ";
        $query .= "\n       ,A.extnl_brand_seqno ";
        $query .= "\n  FROM  paper_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.paper_op_seqno = " . $param["paper_op_seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 제조사_종이_제고_상세
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectLastStockAmt($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\n  SELECT  stock_amt";
        $query .= "\n    FROM  manu_paper_stock_detail";
        $query .= "\n   WHERE  paper_name = " . $param["paper_name"];
        $query .= "\n     AND  paper_dvs = " . $param["paper_dvs"];
        $query .= "\n     AND  paper_color = " . $param["paper_color"];
        $query .= "\n     AND  paper_basisweight = " . $param["paper_basisweight"];
        $query .= "\n     AND  manu = " . $param["manu"];
        $query .= "\nORDER BY manu_paper_stock_detail_seqno DESC ";
        $query .= "\n   LIMIT  1 ";

        return $conn->Execute($query);
    }

    /**
     * @brief 종이명 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperName($conn) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\nSELECT   distinct name ";
        $query .= "\n  FROM   paper ";
        $query .= "\nORDER BY paper_seqno DESC";

        return $conn->Execute($query);
    }
 
    /**
     * @brief 종이구분 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperDvs($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  distinct dvs ";
        $query .= "\n  FROM  paper ";
        $query .= "\n WHERE  1=1 ";
 
        //종이명
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = " . $param["name"];
        }

        $query .= "\n   ORDER BY paper_seqno DESC";

        return $conn->Execute($query);
    }

    /**
     * @brief 종이색상 검색 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperColor($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  distinct color ";
        $query .= "\n  FROM  paper ";
        $query .= "\n WHERE  1=1 ";
 
        //종이명
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = " . $param["name"];
        }

        //종이구분
        if ($this->blankParameterCheck($param ,"dvs")) {
            $query .= "\n   AND  dvs = " . $param["dvs"];
        }

        $query .= "\n   ORDER BY paper_seqno DESC";

        return $conn->Execute($query);
    }
    
    /**
     * @brief 종이평량 검색 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperBasisweight($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  distinct concat(basisweight,basisweight_unit) basisweight";
        $query .= "\n  FROM  paper ";
        $query .= "\n WHERE  1=1 ";
 
        //종이명
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = " . $param["name"];
        }

        //종이구분
        if ($this->blankParameterCheck($param ,"dvs")) {
            $query .= "\n   AND  dvs = " . $param["dvs"];
        }

        //색상
        if ($this->blankParameterCheck($param ,"color")) {
            $query .= "\n   AND  color = " . $param["color"];
        }

        $query .= "\n   ORDER BY basisweight_unit DESC, basisweight+0";

        return $conn->Execute($query);
    }
}
?>
