<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/raw_materials_mng/RawMaterialsMngListHTML.php');

/**
 * @file PaperMaterialMngDAO.php
 *
 * @brief 생산 - 자재관리 - 원자재관리 DAO
 */
class RawMaterialsMngDAO extends ProduceCommonDAO {
 
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
    function selectRawMaterialsList($conn, $param) {

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
            $query  = "\nSELECT  a.dealspec_seqno ";
            $query .= "\n       ,a.name ";
            $query .= "\n       ,a.stan ";
            $query .= "\n       ,a.amt ";
            $query .= "\n       ,a.amt_unit ";
            $query .= "\n       ,a.unitprice ";
            $query .= "\n       ,a.price ";
            $query .= "\n       ,a.memo ";
            $query .= "\n       ,a.extnl_etprs_seqno ";
            $query .= "\n       ,a.vat_yn ";
            $query .= "\n       ,a.regi_date ";
            $query .= "\n       ,b.manu_name ";
        }
        $query .= "\n   FROM   dealspec a, extnl_etprs b";
        $query .= "\n   WHERE  a.extnl_etprs_seqno = b.extnl_etprs_seqno ";
 
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd1"], 1, -1);
            $query .= "\n   AND  " . $val ." >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd1"], 1, -1);
            $query .= "\n   AND  " . $val. " <= " . $param["to"];
        }

        //품목
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND a.name = " . $param["name"];
        }

        //공급처
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND a.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }

        $query .= "\n   ORDER BY dealspec_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);
        
        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 거래명세서 내용 
     *
     * @param $conn  = connection identifier
     * @param $param = 파라미터
     *
     * @return 검색결과
     */
    function selectDealspecView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  a.dealspec_seqno ";
        $query .= "\n       ,a.name ";
        $query .= "\n       ,a.stan ";
        $query .= "\n       ,a.amt ";
        $query .= "\n       ,a.amt_unit ";
        $query .= "\n       ,a.unitprice ";
        $query .= "\n       ,a.price ";
        $query .= "\n       ,a.memo ";
        $query .= "\n       ,a.extnl_etprs_seqno ";
        $query .= "\n       ,a.vat_yn ";
        $query .= "\n       ,a.regi_date ";
        $query .= "\n       ,b.manu_name ";
        $query .= "\nFROM   dealspec a, extnl_etprs b";
        $query .= "\nWHERE  a.extnl_etprs_seqno = b.extnl_etprs_seqno ";

        //SEQ번호 
        if ($this->blankParameterCheck($param ,"dealspec_seqno")) {
            $query .= "\n   AND a.dealspec_seqno = " . $param["dealspec_seqno"];
        }
        
        return $conn->Execute($query);
    }


    /**
     * @brief 거래명세서 등록
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function insertDealspec($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n INSERT INTO dealspec (";
        $query .= "\n     name";
        $query .= "\n   , extnl_etprs_seqno";
        $query .= "\n   , stan";
        $query .= "\n   , amt";
        $query .= "\n   , amt_unit";
        $query .= "\n   , unitprice";
        $query .= "\n   , memo";
        $query .= "\n   , price";
        $query .= "\n   , vat_yn";
        $query .= "\n   , regi_date";
        $query .= "\n ) VALUES (";
        $query .= "\n     %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , %s";
        $query .= "\n   , SYSDATE()";
        $query .= "\n )";

        $query = sprintf( $query
                        , $param["name"]
                        , $param["extnl_etprs_seqno"]
                        , $param["stan"]
                        , $param["amt"]
                        , $param["amt_unit"]
                        , $param["unitprice"]
                        , $param["memo"]
                        , $param["price"]
                        , $param["vat_yn"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @brief 거래명세서 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updateDealspec($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE dealspec ";
        $query .= "\n SET ";
        $query .= "\n     name = %s";
        $query .= "\n   , extnl_etprs_seqno = %s ";
        $query .= "\n   , stan = %s ";
        $query .= "\n   , amt = %s ";
        $query .= "\n   , amt_unit = %s ";
        $query .= "\n   , unitprice = %s ";
        $query .= "\n   , memo = %s ";
        $query .= "\n   , price = %s ";
        $query .= "\n   , vat_yn = %s ";
        $query .= "\n WHERE dealspec_seqno = %s";

        $query = sprintf( $query
                        , $param["name"]
                        , $param["extnl_etprs_seqno"]
                        , $param["stan"]
                        , $param["amt"]
                        , $param["amt_unit"]
                        , $param["unitprice"]
                        , $param["memo"]
                        , $param["price"]
                        , $param["vat_yn"]
                        , $param["dealspec_seqno"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @brief 거래명세서 삭제
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function deleteDealspec($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n DELETE FROM dealspec ";
        $query .= "\n WHERE dealspec_seqno = %s";

        $query = sprintf( $query
                        , $param["dealspec_seqno"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
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
        $query .= "\n       ,addr_front ";
        $query .= "\n       ,addr_rear ";
        $query .= "\n       ,deal_yn ";
        $query .= "\n       ,pur_prdt ";
        $query .= "\nFROM   extnl_etprs ";
        $query .= "\nWHERE  pur_prdt = '기타' ";
        $query .= "\n  AND  deal_yn = 'Y' ";

        return $conn->Execute($query);
    }

}
