<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/print_mng/PrintProcessExcHtml.php');
 
/**
 * @file PrintProduceOrdDAO.php
 *
 * @brief 생산 - 조판관리 - 인쇄생산지시 DAO
 */
class PrintProduceExcDAO extends ManufactureCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 인쇄생산이행
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintProduceExc($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT B.manu_name";
        $query .= "\n      ,A.theday_directions";
        $query .= "\n      ,A.theday_exec";
        $query .= "\n      ,A.basic_seoul_directions";
        $query .= "\n      ,A.basic_seoul_exec";
        $query .= "\n      ,A.basic_region_directions";
        $query .= "\n      ,A.basic_region_exec";
        $query .= "\n      ,A.tot_directions";
        $query .= "\n      ,A.tot_exec";
        $query .= "\n      ,A.extnl_etprs_seqno";
        $query .= "\n      ,A.print_produce_sch_seqno";
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
     * @brief 이행작업 업데이트
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updateExec($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nUPDATE  print_produce_sch ";
        $query .= "\n   SET  %s = %s ";
        $query .= "\n       ,tot_exec = tot_exec %s ";
        $query .= "\n WHERE  print_produce_sch_seqno = %s ";

        $query = sprintf($query
                        , substr($param["fields"], 1, -1) 
                        , $param["value"]
                        , substr($param["tot_value"], 1, -1)
                        , $param["print_produce_sch_seqno"]);

        $resultSet = $conn->Execute($query);
        
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
