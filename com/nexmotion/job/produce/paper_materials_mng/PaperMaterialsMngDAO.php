<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/paper_materials_mng/PaperMaterialsMngListHTML.php');

/**
 * @file PaperMaterialMngDAO.php
 *
 * @brief 생산 - 자재관리 - 종이자재관리 DAO
 */
class PaperMaterialsMngDAO extends ProduceCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 접수리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperMaterialsMngList($conn, $param) {

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
            $query  = "\nSELECT  paper_op_seqno ";
            $query .= "\n       ,typset_num ";
            $query .= "\n       ,op_date ";
            $query .= "\n       ,name ";
            $query .= "\n       ,dvs ";
            $query .= "\n       ,color ";
            $query .= "\n       ,basisweight ";
            $query .= "\n       ,op_affil ";
            $query .= "\n       ,op_size ";
            $query .= "\n       ,stor_subpaper ";
            $query .= "\n       ,stor_size ";
            $query .= "\n       ,grain ";
            $query .= "\n       ,amt ";
            $query .= "\n       ,amt_unit ";
            $query .= "\n       ,memo ";
            $query .= "\n       ,typ ";
            $query .= "\n       ,typ_detail ";
            $query .= "\n       ,orderer ";
            $query .= "\n       ,flattyp_dvs ";
            $query .= "\n       ,state ";
            $query .= "\n       ,regi_date ";
            $query .= "\n       ,extnl_brand_seqno ";
            $query .= "\n       ,storplace ";
        }
        $query .= "\n   FROM  paper_op ";
        $query .= "\n   WHERE  1=1 ";
 
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd1"], 1, -1);
            $query .= "\n   AND  " . $val ." >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd1"], 1, -1);
            $query .= "\n   AND  " . $val. " <= " . $param["to"];
        }

        //발주상태
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND  state = " . $param["state"];
        }

        //발주번호
        if ($this->blankParameterCheck($param ,"paper_op_seqno")) {
            $query .= "\n   AND  paper_op_seqno = " . $param["paper_op_seqno"];
        }

        //수주처
        if ($this->blankParameterCheck($param ,"extnl_brand_seqno")) {
            $query .= "\n   AND  extnl_brand_seqno = " . $param["extnl_brand_seqno"];
        }

        //입고처
        if ($this->blankParameterCheck($param ,"storplace")) {
            $query .= "\n   AND  storplace = " . $param["storplace"];
        }

        $query .= "\n   ORDER BY paper_op_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
    
    /**
     * @brief 종이발주 취소
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updatePaperMaterialsMngCancel($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nUPDATE  paper_op ";
        $query .= "\n   SET  state = '530' ";
        $query .= "\n WHERE  paper_op_seqno = %s ";

        $query = sprintf($query, $param["paper_op_seqno"]);

        $resultSet = $conn->Execute($query);
        
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
?>
