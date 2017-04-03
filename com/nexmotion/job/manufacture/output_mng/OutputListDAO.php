<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/output_mng/OutputMngHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/output_mng/OutputMngDOC.php');

/**
 * @file OutputListDAO.php
 *
 * @brief 생산 - 출력관리 - 출력리스트 DAO
 */
class OutputListDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 생산공정관리 - 출력
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputList($conn, $param) {

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
            $query  = "\nSELECT  A.output_op_seqno ";
            $query .= "\n       ,A.typset_num ";
            $query .= "\n       ,A.name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.size ";
            $query .= "\n       ,A.board ";
            $query .= "\n       ,A.memo ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,A.flattyp_dvs ";
            $query .= "\n       ,B.dlvrboard ";
            $query .= "\n       ,A.subpaper ";
            $query .= "\n       ,B.specialty_items ";
            $query .= "\n       ,C.preset_name ";
        }
        $query .= "\n  FROM  output_op AS A ";
        $query .= "\n       ,sheet_typset AS B ";
        $query .= "\n       ,typset_format AS C ";
        $query .= "\n WHERE  A.typset_num = B.typset_num ";
        $query .= "\n   AND  B.typset_format_seqno = C.typset_format_seqno ";

        if ($this->blankParameterCheck($param ,"preset_cate")) {
            $query .= "\n   AND  C.preset_cate = " . $param["preset_cate"];
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
        if ($this->blankParameterCheck($param ,"typset_num")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.name LIKE '% " . $val . "%' ";
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
     * @brief 출력공정 상세 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputDetailView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.name AS output_name";
        $query .= "\n       ,A.output_op_seqno ";
        $query .= "\n       ,A.typset_num ";
        $query .= "\n       ,A.orderer ";
        $query .= "\n       ,B.extnl_brand_seqno ";
        $query .= "\n       ,B.extnl_etprs_seqno ";
        $query .= "\n       ,A.typ ";
        $query .= "\n       ,A.typ_detail ";
        $query .= "\n       ,A.subpaper ";
        $query .= "\n       ,A.size ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.board ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.state ";
        $query .= "\n       ,A.flattyp_dvs ";
        $query .= "\n  FROM  output_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";

        //일련번호 
        if ($this->blankParameterCheck($param ,"seqno")) {
            $query .= "\n   AND  A.output_op_seqno = " . $param["seqno"];
        }

        //조판번호
        if ($this->blankParameterCheck($param ,"typset_num")) {
            $query .= "\n   AND  A.typset_num = " . $param["typset_num"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 생산지시 인쇄뷰
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectProduceOrdPrint($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query .= "\nSELECT C.nick";
        $query .= "\n      ,B.receipt_mng";
        $query .= "\n      ,D.office_nick";
        $query .= "\n      ,A.title";
        $query .= "\n      ,B.work_size_wid";
        $query .= "\n      ,B.work_size_vert";
        $query .= "\n      ,B.tot_tmpt";
        $query .= "\n      ,B.amt";
        $query .= "\n      ,B.amt_unit_dvs";
        $query .= "\n      ,A.page_cnt";
        $query .= "\n      ,B.produce_memo";
        $query .= "\n      ,E.invo_cpn";
        $query .= "\n  FROM order_common AS A";
        $query .= "\n      ,order_detail AS B";
        $query .= "\n      ,cpn_admin AS C";
        $query .= "\n      ,member AS D";
        $query .= "\n      ,order_dlvr AS E";
        $query .= "\n WHERE A.order_common_seqno = B.order_common_seqno";
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  A.order_common_seqno = " . $param["order_common_seqno"];
        }
        $query .= "\n   AND A.cpn_admin_seqno = C.cpn_admin_seqno";
        $query .= "\n   AND A.member_seqno = D.member_seqno";
        $query .= "\n   AND A.order_common_seqno = E.order_common_seqno";
        $query .= "\n   AND E.tsrs_dvs = '수신'";

        return $conn->Execute($query);
    }
}
?>
