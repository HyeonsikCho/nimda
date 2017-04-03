<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/after_mng/AfterMngHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/after_mng/AfterMngDOC.php');

/**
 * @file BasicAfterListDAO.php
 *
 * @brief 생산 - 후공정관리 - 조판별-후공정 DAO
 */
class BasicAfterListDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 생산공정관리 - 조판별 - 후공정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBasicAfterList($conn, $param) {

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
            $query  = "\nSELECT  A.basic_after_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,A.after_name ";
            $query .= "\n       ,A.depth1 ";
            $query .= "\n       ,A.depth2 ";
            $query .= "\n       ,A.depth3 ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.orderer ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,C.specialty_items ";
        }
        $query .= "\n  FROM  basic_after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,sheet_typset AS C ";
        $query .= "\n       ,typset_format AS D ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  A.typset_num = C.typset_num ";
        $query .= "\n   AND  C.typset_format_seqno = D.typset_format_seqno ";

        if ($this->blankParameterCheck($param ,"preset_cate")) {
            $query .= "\n   AND  D.preset_cate = " . $param["preset_cate"];
        }
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND  A.state = " . $param["state"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        $query .= "\nORDER BY A.regi_date DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 조판별 - 후공정공정 작업일지 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBasicAfterProcessView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.basic_after_op_seqno ";
        $query .= "\n       ,A.after_name ";
        $query .= "\n       ,A.orderer ";
        $query .= "\n       ,B.extnl_etprs_seqno ";
        $query .= "\n       ,B.extnl_brand_seqno ";
        $query .= "\n       ,A.depth1 ";
        $query .= "\n       ,A.depth2 ";
        $query .= "\n       ,A.depth3 ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.state ";
        $query .= "\n       ,A.flattyp_dvs ";
        $query .= "\n       ,A.typset_num ";
        $query .= "\n  FROM  basic_after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  A.basic_after_op_seqno = " . $param["seqno"];

        return $conn->Execute($query);
    }
}
?>
