<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/typset_mng/PrintProcessOrdHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/typset_mng/PrintProcessOrdDOC.php');
 
/**
 * @file PrintProduceOrdDAO.php
 *
 * @brief 생산 - 조판관리 - 인쇄생산지시 DAO
 */
class PrintProduceOrdDAO extends ManufactureCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 인쇄생산지시
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintProduceOrd($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT B.manu_name";
        $query .= "\n      ,A.theday_directions";
        $query .= "\n      ,A.basic_seoul_directions";
        $query .= "\n      ,A.basic_region_directions";
        $query .= "\n      ,A.tot_directions";
        $query .= "\n      ,A.extnl_etprs_seqno";
        $query .= "\n  FROM print_produce_sch AS A";
        $query .= "\n      ,extnl_etprs AS B";
        $query .= "\n WHERE A.extnl_etprs_seqno = B.extnl_etprs_seqno";

        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND A.extnl_etprs_seqno = " .$param["extnl_etprs_seqno"];
        }

        $query .= "\n   AND A.sch_date >= " . $param["from"];
        $query .= "\n   AND A.sch_date <= " . $param["to"];

        return $conn->Execute($query);
    }

    /**
     * @brief 업체별 지시 등록현황
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectDirection($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.print_produce_sch_seqno ";
        $query .= "\n       ,A.sch_date ";
        $query .= "\n       ,A.regi_date ";
        $query .= "\n       ,A.extnl_etprs_seqno ";
        $query .= "\n       ,B.manu_name ";
        $query .= "\n FROM  print_produce_sch A, extnl_etprs B ";
        $query .= "\nWHERE  A.extnl_etprs_seqno = B.extnl_etprs_seqno ";
 
        if ($this->blankParameterCheck($param ,"date")) {
            $query .= "\n   AND  A.sch_date = " . $param["date"];
        }

        $query .= "\n   ORDER BY A.print_produce_sch_seqno ASC";

        return $conn->Execute($query);
    }
 
    /**
     * @brief 지시관리 삭제
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function deleteDirection($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n DELETE FROM print_produce_sch ";
        $query .= "\n WHERE print_produce_sch_seqno = %s";

        $query = sprintf( $query
                        , $param["print_produce_sch_seqno"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 지시관리 업데이트
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updateDirection($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nUPDATE  print_produce_sch ";
//        $query .= "\n   SET  theday_directions = %s ";
//        $query .= "\n       ,basic_seoul_directions = %s ";
        $query .= "\n   SET  basic_seoul_directions = %s ";
        $query .= "\n       ,basic_region_directions = %s ";
        $query .= "\n       ,tot_directions = %s ";
        $query .= "\n WHERE  print_produce_sch_seqno = %s ";

        $query = sprintf($query
//                        , $param["theday_directions"]
                        , $param["basic_seoul_directions"]
                        , $param["basic_region_directions"]
                        , $param["tot_directions"]
                        , $param["print_produce_sch_seqno"]);

        $resultSet = $conn->Execute($query);
        
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 인쇄생산계획 지시등록
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function insertDirection($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n INSERT INTO print_produce_sch (";
        $query .= "\n     sch_date ";
        $query .= "\n   , regi_date ";
        $query .= "\n   , extnl_etprs_seqno ";
//        $query .= "\n   , theday_directions ";
        $query .= "\n   , basic_seoul_directions ";
        $query .= "\n   , basic_region_directions ";
        $query .= "\n   , tot_directions ";
        $query .= "\n   , theday_exec ";
        $query .= "\n   , basic_seoul_exec ";
        $query .= "\n   , basic_region_exec ";
        $query .= "\n   , tot_exec ";
        $query .= "\n ) VALUES ( ";
        $query .= "\n     SYSDATE() ";
        $query .= "\n   , SYSDATE() ";
        $query .= "\n   , %s ";
//        $query .= "\n   , %s ";
        $query .= "\n   , %s ";
        $query .= "\n   , %s ";
        $query .= "\n   , %s "; 
        $query .= "\n   , 0 "; 
        $query .= "\n   , 0 "; 
        $query .= "\n   , 0 "; 
        $query .= "\n   , 0 "; 
        $query .= "\n )";

        $query = sprintf( $query
                        , $param["extnl_etprs_seqno"]
//                        , $param["theday_directions"]
                        , $param["basic_seoul_directions"]
                        , $param["basic_region_directions"]
                        , $param["tot_directions"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
?>
