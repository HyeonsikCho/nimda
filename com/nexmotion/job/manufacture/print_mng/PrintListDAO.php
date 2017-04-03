<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/print_mng/PrintMngHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/print_mng/PrintMngDOC.php');

/**
 * @file PrintListDAO.php
 *
 * @brief 생산 - 인쇄관리 - 인쇄리스트 DAO
 */
class PrintListDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 생산공정관리 - 인쇄
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintList($conn, $param) {

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
            $query  = "\nSELECT  A.print_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,C.paper_name ";
            $query .= "\n       ,C.paper_dvs ";
            $query .= "\n       ,C.paper_color ";
            $query .= "\n       ,C.paper_basisweight ";
            $query .= "\n       ,D.preset_name ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,C.specialty_items ";
        }
        $query .= "\n  FROM  print_op AS A ";
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
     * @brief 인쇄공정 작업일지 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintProcessView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.name AS print_name";
        $query .= "\n       ,A.print_op_seqno ";
        $query .= "\n       ,A.typset_num ";
        $query .= "\n       ,A.orderer ";
        $query .= "\n       ,B.extnl_brand_seqno ";
        $query .= "\n       ,B.extnl_etprs_seqno ";
        $query .= "\n       ,A.typ ";
        $query .= "\n       ,A.typ_detail ";
        $query .= "\n       ,A.size ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.subpaper ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.beforeside_tmpt ";
        $query .= "\n       ,A.beforeside_spc_tmpt ";
        $query .= "\n       ,A.aftside_tmpt ";
        $query .= "\n       ,A.aftside_spc_tmpt ";
        $query .= "\n       ,A.tot_tmpt ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.state ";
        $query .= "\n       ,A.flattyp_dvs ";
        $query .= "\n       ,A.paper_stor_yn ";
        $query .= "\n  FROM  print_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";

        //일련번호 
        if ($this->blankParameterCheck($param ,"seqno")) {
            $query .= "\n   AND  A.print_op_seqno = " . $param["seqno"];
        }

        //조판번호
        if ($this->blankParameterCheck($param ,"typset_num")) {
            $query .= "\n   AND  A.typset_num = " . $param["typset_num"];
        }

        return $conn->Execute($query);
    }
}
?>
