<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/paper_materials_mng/PaperMaterialsMngListHTML.php');

/**
 * @file RawMaterialStockMngDAO.php
 *
 * @brief 생산 - 자재관리 - 원자재재고관리 DAO
 */
class RawMaterialStockMngDAO extends ProduceCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 거래명세서 기타품목 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 파라미터
     *
     * @return 검색결과
     */
    function selectPurPrdtEtc($conn) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\nSELECT  extnl_etprs_seqno ";
        $query .= "\n       ,manu_name ";
        $query .= "\n       ,cpn_name ";
        $query .= "\n       ,tel_num ";
        $query .= "\n       ,fax ";
        $query .= "\n       ,hp ";
        $query .= "\n       ,mail ";
        $query .= "\n       ,zipcode ";
        $query .= "\n       ,addr";
        $query .= "\n       ,addr_detail";
        $query .= "\n       ,deal_yn ";
        $query .= "\n       ,pur_prdt ";
        $query .= "\nFROM   extnl_etprs ";
        $query .= "\nWHERE  pur_prdt = '기타' ";
        $query .= "\n  AND  deal_yn = 'Y' ";

        return $conn->Execute($query);
    }


    
    /**
     * @brief 원자재 재고관리 상단리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMtraStockMngList($conn, $param) {

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
            $query  = "\nSELECT  manu_mtra_stock_day_seqno ";
            $query .= "\n       ,DATE_FORMAT(regi_date, '%Y-%m-%d') regi_date ";
            $query .= "\n       ,DATE_FORMAT(regi_date, '%Y.%m.%d') date ";
            $query .= "\n       ,manu ";
            $query .= "\n       ,name ";
            $query .= "\n       ,stor_amt ";
            $query .= "\n       ,use_amt ";
            $query .= "\n       ,stock_amt ";
        }
        $query .= "\n   FROM  manu_mtra_stock_day ";
        $query .= "\n   WHERE  1=1 ";
 
        //등록일
        if ($this->blankParameterCheck($param ,"regi_date")) {
            $query .= "\n   AND  DATE_FORMAT(regi_date, '%Y-%m-%d') = " . $param["regi_date"];
        }

        if ($this->blankParameterCheck($param ,"manu")) {
            $query .= "\n   AND  manu = " . $param["manu"];
        }

        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = " . $param["name"];
        }


        $query .= "\n   ORDER BY manu_mtra_stock_day_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
    
   
    /**
     * @brief 원자재재고관리 하단 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMtraStockMngDetailList($conn, $param) {

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
            $query  = "\nSELECT  manu_mtra_stock_detail_seqno ";
            $query .= "\n       ,DATE_FORMAT(regi_date, '%Y.%m.%d') regi_date ";
            $query .= "\n       ,DATE_FORMAT(ifnull(modi_date, regi_date), '%Y.%m.%d') modi_date ";
            $query .= "\n       ,cont ";
            $query .= "\n       ,stor_yn ";
            $query .= "\n       ,name ";
            $query .= "\n       ,manu ";
            $query .= "\n       ,stor_amt ";
            $query .= "\n       ,use_amt ";
            $query .= "\n       ,typset_num ";
            $query .= "\n       ,stock_amt ";
            $query .= "\n       ,realstock_amt ";
            $query .= "\n       ,adjust_reason ";
        }
        $query .= "\n   FROM  manu_mtra_stock_detail ";
        $query .= "\n   WHERE  1=1 ";
 
        //등록일
        if ($this->blankParameterCheck($param ,"regi_date")) {
            $query .= "\n   AND  DATE_FORMAT(regi_date, '%Y-%m-%d') = " . $param["regi_date"];
        }

        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = " . $param["name"];
        }

        if ($this->blankParameterCheck($param ,"adjust_reason")) {
            $query .= "\n   AND  adjust_reason LIKE '%" . $adjust_reason . "%'";
        }

        $query .= "\n   ORDER BY manu_mtra_stock_detail_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }


    /**
     * @brief 원자재재고조정 등록
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function insertMtraStock($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $adjustFlag = substr($param["adjustFlag"], 1, -1);

        $query .= "\n INSERT INTO manu_mtra_stock_detail (";
        $query .= "\n     regi_date";
        $query .= "\n   , modi_date";
        $query .= "\n   , cont ";
        $query .= "\n   , manu";
        $query .= "\n   , name";
        //$query .= "\n   , stor_yn";
                
        if ($adjustFlag == "realstock_amt") {
            $query .= "\n   , realstock_amt";
        } else {
            $query .= "\n   , stock_amt";
        }

        $query .= "\n   , adjust_reason";
        $query .= "\n   , worker";
        $query .= "\n ) VALUES (";
        $query .= "\n     SYSDATE()";
        $query .= "\n   , SYSDATE()";
        $query .= "\n   , '원자재재고조정'";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        //$query .= "\n   , %s";
        $query .= "\n   , %s";
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
                        , $param["name"]
                        //, $flag
                        , $param["amt"]
                        , $param["adjust_reason"]
                        , $param["admin"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 원자재재고관리 하단 리스트 수정 뷰
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMtraStockMngDetailView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        
        $query  = "\nSELECT  manu_mtra_stock_detail_seqno ";
        $query .= "\n       ,DATE_FORMAT(regi_date, '%Y.%m.%d') regi_date ";
        $query .= "\n       ,DATE_FORMAT(ifnull(modi_date, regi_date), '%Y.%m.%d') modi_date ";
        $query .= "\n       ,cont ";
        $query .= "\n       ,stor_yn ";
        $query .= "\n       ,name ";
        $query .= "\n       ,manu ";
        $query .= "\n       ,stor_amt ";
        $query .= "\n       ,use_amt ";
        $query .= "\n       ,typset_num ";
        $query .= "\n       ,stock_amt ";
        $query .= "\n       ,realstock_amt ";
        $query .= "\n       ,adjust_reason ";
        $query .= "\n       ,worker ";
        $query .= "\n   FROM  manu_mtra_stock_detail ";
        $query .= "\n   WHERE  1=1 ";
 
        if ($this->blankParameterCheck($param ,"seq")) {
            $query .= "\n   AND  manu_mtra_stock_detail_seqno = " . $param["seq"];
        }
        
        return $conn->Execute($query);
    }

    /**
     * @brief 원자재 실재고 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updateMtraStock($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE manu_mtra_stock_detail ";
        $query .= "\n SET ";
        $query .= "\n         modi_date = SYSDATE() ";
        $query .= "\n         worker = " . $param["admin"];
        if ($this->blankParameterCheck($param ,"amt")) {
            $query .= "\n   , realstock_amt = " . $param["amt"];
        }
        if ($this->blankParameterCheck($param ,"adjust_reason")) {
            $query .= "\n   , adjust_reason = " . $param["adjust_reason"];
        }
        $query .= "\n WHERE manu_mtra_stock_detail_seqno = " . $param["seq"];

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
