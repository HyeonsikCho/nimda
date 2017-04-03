<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/paper_materials_mng/PaperMaterialsMngListHTML.php');

/**
 * @file PaperStockMngDAO.php
 *
 * @brief 생산 - 자재관리 - 종이재고관리 DAO
 */
class PaperStockMngDAO extends ProduceCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 종이명 셀렉트
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
     * @brief 종이구분 셀렉트
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
     * @brief 종이색상 셀렉트
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
     * @brief 종이평량 셀렉트
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
    
    /**
     * @brief 종이재고관리 상단리스트
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
            $query  = "\nSELECT  manu_paper_stock_day_seqno ";
            $query .= "\n       ,DATE_FORMAT(regi_date, '%Y-%m-%d') regi_date ";
            $query .= "\n       ,DATE_FORMAT(regi_date, '%Y.%m.%d') date ";
            $query .= "\n       ,manu ";
            $query .= "\n       ,paper_name ";
            $query .= "\n       ,paper_dvs ";
            $query .= "\n       ,paper_color ";
            $query .= "\n       ,paper_basisweight ";
            $query .= "\n       ,stor_amt ";
            $query .= "\n       ,use_amt ";
            $query .= "\n       ,stock_amt ";
        }
        $query .= "\n   FROM  manu_paper_stock_day ";
        $query .= "\n   WHERE  1=1 ";
 
        //등록일
        if ($this->blankParameterCheck($param ,"regi_date")) {
            $query .= "\n   AND  DATE_FORMAT(regi_date, '%Y-%m-%d') = " . $param["regi_date"];
        }

        if ($this->blankParameterCheck($param ,"paper_name")) {
            $query .= "\n   AND  paper_name = " . $param["paper_name"];
        }

        if ($this->blankParameterCheck($param ,"paper_dvs")) {
            $query .= "\n   AND  paper_dvs = " . $param["paper_dvs"];
        }

        if ($this->blankParameterCheck($param ,"paper_color")) {
            $query .= "\n   AND  paper_color = " . $param["paper_color"];
        }

        if ($this->blankParameterCheck($param ,"paper_basisweight")) {
            $query .= "\n   AND  paper_basisweight = " . $param["paper_basisweight"];
        }

        $query .= "\n   ORDER BY manu_paper_stock_day_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
    
   
    /**
     * @brief 종이재고관리 하단 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperStockMngDetailList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);
        $adjust_reason = substr($param["adjust_reason"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  manu_paper_stock_detail_seqno ";
            $query .= "\n       ,DATE_FORMAT(regi_date, '%Y.%m.%d') regi_date ";
            $query .= "\n       ,DATE_FORMAT(ifnull(modi_date, regi_date), '%Y.%m.%d') modi_date ";
            $query .= "\n       ,cont ";
            $query .= "\n       ,stor_yn ";
            $query .= "\n       ,paper_name ";
            $query .= "\n       ,paper_dvs ";
            $query .= "\n       ,paper_color ";
            $query .= "\n       ,paper_basisweight ";
            $query .= "\n       ,manu ";
            $query .= "\n       ,stor_amt ";
            $query .= "\n       ,use_amt ";
            $query .= "\n       ,typset_num ";
            $query .= "\n       ,stock_amt ";
            $query .= "\n       ,realstock_amt ";
            $query .= "\n       ,adjust_reason ";
        }
        $query .= "\n   FROM  manu_paper_stock_detail ";
        $query .= "\n   WHERE  1=1 ";
 
        //등록일
        if ($this->blankParameterCheck($param ,"regi_date")) {
            $query .= "\n   AND  DATE_FORMAT(regi_date, '%Y-%m-%d') = " . $param["regi_date"];
        }

        if ($this->blankParameterCheck($param ,"paper_name")) {
            $query .= "\n   AND  paper_name = " . $param["paper_name"];
        }

        if ($this->blankParameterCheck($param ,"paper_dvs")) {
            $query .= "\n   AND  paper_dvs = " . $param["paper_dvs"];
        }

        if ($this->blankParameterCheck($param ,"paper_color")) {
            $query .= "\n   AND  paper_color = " . $param["paper_color"];
        }

        if ($this->blankParameterCheck($param ,"paper_basisweight")) {
            $query .= "\n   AND  paper_basisweight = " . $param["paper_basisweight"];
        }

        if ($this->blankParameterCheck($param ,"adjust_reason")) {
            $query .= "\n   AND  adjust_reason LIKE '%" . $adjust_reason . "%'";
        }

        $query .= "\n   ORDER BY manu_paper_stock_detail_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }


    /**
     * @brief 종이재고조정 등록
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function insertPaperStock($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $adjustFlag = substr($param["adjustFlag"], 1, -1);

        $query .= "\n INSERT INTO manu_paper_stock_detail (";
        $query .= "\n     regi_date";
        $query .= "\n   , modi_date";
        $query .= "\n   , cont ";
        $query .= "\n   , manu";
        $query .= "\n   , paper_name";
        $query .= "\n   , paper_dvs";
        $query .= "\n   , paper_color";
        $query .= "\n   , paper_basisweight";
        //$query .= "\n   , stor_yn";
        
        if ($adjustFlag == "realstock_amt") {
            $query .= "\n   , realstock_amt";
        } else {
            $query .= "\n   , stock_amt";
        }

        $query .= "\n   , adjust_reason";
        $query .= "\n ) VALUES (";
        $query .= "\n     SYSDATE()";
        $query .= "\n   , SYSDATE()";
        $query .= "\n   , '종이재고조정'";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        //$query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s"; 
        $query .= "\n )";

        //if ($param["adjustFlag"] == "stor") {
        //    $flag = "'Y'";
        //} else {
        //    $flag = "'N'";
        //}

        $query = sprintf( $query
                        , $param["manu"]
                        , $param["paper_name"]
                        , $param["paper_dvs"]
                        , $param["paper_color"]
                        , $param["paper_basisweight"]
                        //, $flag
                        , $param["amt"]
                        , $param["adjust_reason"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

   
    /**
     * @brief 종이재고관리 팝업리스트 수정보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperStockMngDetailView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  manu_paper_stock_detail_seqno ";
        $query .= "\n       ,DATE_FORMAT(regi_date, '%Y.%m.%d') regi_date ";
        $query .= "\n       ,DATE_FORMAT(ifnull(modi_date, regi_date), '%Y.%m.%d') modi_date ";
        $query .= "\n       ,cont ";
        $query .= "\n       ,stor_yn ";
        $query .= "\n       ,paper_name ";
        $query .= "\n       ,paper_dvs ";
        $query .= "\n       ,paper_color ";
        $query .= "\n       ,paper_basisweight ";
        $query .= "\n       ,manu ";
        $query .= "\n       ,stor_amt ";
        $query .= "\n       ,use_amt ";
        $query .= "\n       ,typset_num ";
        $query .= "\n       ,stock_amt ";
        $query .= "\n       ,realstock_amt ";
        $query .= "\n       ,adjust_reason ";
        $query .= "\n       ,worker ";
        $query .= "\n   FROM  manu_paper_stock_detail ";
        $query .= "\n   WHERE  1=1 ";
 
        if ($this->blankParameterCheck($param ,"seq")) {
            $query .= "\n   AND  manu_paper_stock_detail_seqno = " . $param["seq"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 종이 실재고 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updatePaperStock($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE manu_paper_stock_detail ";
        $query .= "\n SET ";
        $query .= "\n         modi_date = SYSDATE() ";
        $query .= "\n         worker = " . $param["admin"];
        if ($this->blankParameterCheck($param ,"amt")) {
            $query .= "\n   , realstock_amt = " . $param["amt"];
        }
        if ($this->blankParameterCheck($param ,"adjust_reason")) {
            $query .= "\n   , adjust_reason = " . $param["adjust_reason"];
        }
        $query .= "\n WHERE manu_paper_stock_detail_seqno = " . $param["seq"];

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @brief 종이재고조정 삭제
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function deletePaperStock($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n DELETE FROM manu_paper_stock_detail ";
        $query .= "\n WHERE manu_paper_stock_detail_seqno = %s";

        $query = sprintf( $query
                        , $param["manu_paper_stock_detail_seqno"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
   
}
?>
