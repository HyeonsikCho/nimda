<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/item_mng/PaperStockMngHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/item_mng/PaperStockMngDOC.php');

/**
 * @file PaperStockMngDAO.php
 *
 * @brief 생산 - 자재관리 - 종이재고관리 DAO
 */
class PaperStockMngDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 종이재고리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperStockMngList($conn, $param) {

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
            $query  = "\nSELECT regi_date";
            $query .= "\n      ,paper_name";
            $query .= "\n      ,paper_dvs";
            $query .= "\n      ,paper_color";
            $query .= "\n      ,paper_basisweight";
            $query .= "\n      ,manu";
            $query .= "\n      ,stor_amt";
            $query .= "\n      ,use_amt";
            $query .= "\n      ,stor_yn";
            $query .= "\n      ,stock_amt";
            $query .= "\n      ,cont";
            $query .= "\n      ,manu_paper_stock_detail_seqno";
        }
        $query .= "\n  FROM manu_paper_stock_detail";
        $query .= "\n WHERE 1=1";
 
        if ($this->blankParameterCheck($param ,"from")) {
            $query .= "\n   AND regi_date >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $query .= "\n   AND regi_date <= " . $param["to"];
        }
        if ($this->blankParameterCheck($param ,"manu")) {
            $query .= "\n   AND  manu = " . $param["manu"];
        }
        if ($this->blankParameterCheck($param ,"paper_name")) {
            $query .= "\n   AND paper_name = " . $param["paper_name"];
        }
        if ($this->blankParameterCheck($param ,"paper_dvs")) {
            $query .= "\n   AND paper_dvs = " . $param["paper_dvs"];
        }
        if ($this->blankParameterCheck($param ,"paper_color")) {
            $query .= "\n   AND paper_color = " . $param["paper_color"];
        }
        if ($this->blankParameterCheck($param ,"paper_basisweight")) {
            $query .= "\n   AND paper_basisweight  = " . $param["paper_basisweight"];
        }

        $query .= "\n   ORDER BY manu_paper_stock_detail_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
}
