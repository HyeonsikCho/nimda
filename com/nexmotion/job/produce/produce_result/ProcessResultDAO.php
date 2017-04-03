<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/produce_result/ProcessResultListHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/produce/produce_result/ProcessResultPopupDOC.php');

/**
 * @file ProcessResultDAO.php
 *
 * @brief 생산 - 생산결과 - 공정별결과 DAO
 */
class ProcessResultDAO extends ProduceCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 공정별결과 - 출력
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputOpProcessResultList($conn, $param) {

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
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,D.work_price ";
            $query .= "\n       ,D.adjust_price ";
            $query .= "\n       ,D.output_work_report_seqno ";
            $query .= "\n       ,D.valid_yn ";
        }
        $query .= "\n  FROM  output_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n       ,output_work_report AS D ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.output_op_seqno = D.output_op_seqno ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        $query .= "\nORDER BY output_work_report_seqno DESC";
        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 공정별결과 - 인쇄
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintOpProcessResultList($conn, $param) {

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
            $query .= "\n       ,A.name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.size ";
            $query .= "\n       ,A.beforeside_tmpt ";
            $query .= "\n       ,A.beforeside_spc_tmpt ";
            $query .= "\n       ,A.aftside_tmpt ";
            $query .= "\n       ,A.aftside_spc_tmpt ";
            $query .= "\n       ,A.tot_tmpt ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,D.work_price ";
            $query .= "\n       ,D.adjust_price ";
            $query .= "\n       ,D.print_work_report_seqno ";
            $query .= "\n       ,D.valid_yn ";
        }
        $query .= "\n  FROM  print_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n       ,print_work_report AS D ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.print_op_seqno = D.print_op_seqno ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY print_work_report_seqno DESC";
        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
 
    /**
     * @brief 공정별결과 - 후공정
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterOpProcessResultList($conn, $param) {

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
            $query  = "\nSELECT  A.after_op_seqno ";
            $query .= "\n       ,A.after_name ";
            $query .= "\n       ,A.depth1 ";
            $query .= "\n       ,A.depth2 ";
            $query .= "\n       ,A.depth3 ";
            $query .= "\n       ,A.amt ";
            $query .= "\n       ,A.amt_unit ";
            $query .= "\n       ,A.orderer ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,F.cate_name ";
            $query .= "\n       ,D.work_price ";
            $query .= "\n       ,D.adjust_price ";
            $query .= "\n       ,D.valid_yn ";
            $query .= "\n       ,D.after_work_report_seqno ";
            $query .= "\n       ,E.order_num ";
        }
        $query .= "\n  FROM  after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n       ,after_work_report AS D ";
        $query .= "\n       ,order_common AS E ";
        $query .= "\n       ,cate AS F ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.after_op_seqno = D.after_op_seqno ";
        $query .= "\n   AND  A.order_common_seqno = E.order_common_seqno ";
        $query .= "\n   AND  E.cate_sortcode = F.sortcode ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY after_work_report_seqno DESC";
        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
}
?>
