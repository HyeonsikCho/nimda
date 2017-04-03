<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/paper_materials_mng/PaperMaterialsMngListHTML.php');
 
/**
 * @file PrintProducePlanDAO.php
 *
 * @brief 생산 - 생산계획 - 인쇄생산기획 DAO
 */
class PrintProducePlanDAO extends ProduceCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 인쇄생산기획 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintProductPlanList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.print_produce_sch_seqno ";
        $query .= "\n       ,A.sch_date ";
        $query .= "\n       ,A.regi_date ";
        $query .= "\n       ,A.seoul_directions ";
        $query .= "\n       ,A.seoul_exec ";
        $query .= "\n       ,A.region_directions ";
        $query .= "\n       ,A.region_exec ";
        $query .= "\n       ,A.tot_directions ";
        $query .= "\n       ,A.tot_exec ";
        $query .= "\n       ,A.extnl_etprs_seqno ";
        $query .= "\n       ,B.manu_name ";
        $query .= "\n       ,C.print_work_report_seqno ";
        $query .= "\n       ,C.worker_memo ";
        $query .= "\n       ,C.worker ";
        $query .= "\n       ,C.work_start_hour ";
        $query .= "\n       ,C.work_end_hour ";
        $query .= "\n       ,C.adjust_price ";
        $query .= "\n       ,C.perform_date ";
        $query .= "\n       ,C.expec_perform_mark ";
        $query .= "\n       ,C.expec_perform_paper ";
        $query .= "\n       ,C.expec_perform_bucket ";
        $query .= "\n       ,C.work_price ";
        $query .= "\n       ,C.subpaper ";
        $query .= "\n       ,C.print_op_seqno ";
        $query .= "\n FROM  print_produce_sch A, extnl_etprs B, print_work_report C ";
        $query .= "\nWHERE  A.extnl_etprs_seqno = B.extnl_etprs_seqno ";
        $query .= "\n  AND  A.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n  AND  C.valid_yn = 'Y' ";
 
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"date")) {
            $query .= "\n   AND  C.perform_date = " . $param["date"];
        }

        if ($this->blankParameterCheck($param ,"search_cnd") 
                && $this->blankParameterCheck($param ,"from")
                && $this->blankParameterCheck($param ,"to")) {
            
            $search_cnd = substr($param["search_cnd"], 1, -1);
            
            if (!strcmp($search_cnd, "sch_date")) {
                $query .= "\n    AND  A.sch_date > $param[from] ";
                $query .= "\n    AND  A.sch_date < $param[to] ";
            } else { 
                $query .= "\n    AND  C.perform_date > $param[from] ";
                $query .= "\n    AND  C.perform_date < $param[to] ";
            }

        }


        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  A.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }

        $query .= "\n   ORDER BY A.print_produce_sch_seqno ASC";

        //$conn->debug = 1;
        return $conn->Execute($query);
    }
 
    /**
     * @brief 인쇄생산기획 상세 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPopPrintPlanList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  B.print_op_seqno ";
        $query .= "\n       ,B.typset_num ";
        $query .= "\n       ,B.beforeside_tmpt ";
        $query .= "\n       ,B.beforeside_spc_tmpt ";
        $query .= "\n       ,B.aftside_tmpt ";
        $query .= "\n       ,B.aftside_spc_tmpt ";
        $query .= "\n       ,B.tot_tmpt ";
        $query .= "\n       ,B.amt ";
        $query .= "\n       ,B.amt_unit ";
        $query .= "\n       ,B.name ";
        $query .= "\n       ,B.affil ";
        $query .= "\n       ,B.size ";
        $query .= "\n       ,B.memo ";
        $query .= "\n       ,B.typ ";
        $query .= "\n       ,B.flattyp_dvs ";
        $query .= "\n       ,B.typ_detail ";
        $query .= "\n       ,B.regi_date ";
        $query .= "\n       ,B.state ";
        $query .= "\n       ,B.orderer ";
        $query .= "\n       ,B.extnl_brand_seqno ";
        $query .= "\n       ,A.subpaper ";
        $query .= "\n       ,A.print_work_report_seqno ";
        $query .= "\n       ,C.manu_name ";
        $query .= "\n FROM  print_work_report A, print_op B, extnl_etprs C ";
        $query .= "\nWHERE  A.print_op_seqno = B.print_op_seqno ";
        $query .= "\n  AND  A.extnl_etprs_seqno = C.extnl_etprs_seqno ";
 
        if ($this->blankParameterCheck($param ,"print_op_seqno")) {
            $query .= "\n   AND  B.print_op_seqno = " . $param["print_op_seqno"];
        }

        $query .= "\n   ORDER BY A.print_work_report_seqno ASC";
      
        return $conn->Execute($query);
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
        $query .= "\n   , seoul_directions ";
        $query .= "\n   , region_directions ";
        $query .= "\n   , tot_directions ";
        $query .= "\n   , seoul_exec ";
        $query .= "\n   , region_exec ";
        $query .= "\n   , tot_exec ";
        $query .= "\n ) VALUES ( ";
        $query .= "\n     SYSDATE() ";
        $query .= "\n   , SYSDATE() ";
        $query .= "\n   , %s ";
        $query .= "\n   , %s ";
        $query .= "\n   , %s ";
        $query .= "\n   , %s "; 
        $query .= "\n   , 0 "; 
        $query .= "\n   , 0 "; 
        $query .= "\n   , 0 "; 
        $query .= "\n )";

        $query = sprintf( $query
                        , $param["extnl_etprs_seqno"]
                        , $param["seoul_directions"]
                        , $param["region_directions"]
                        , $param["tot_directions"] );

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
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
                        , substr($param["dvs"], 1, -1) 
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
 
        //주문일 접수일
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
        $query .= "\n   SET  seoul_directions = %s ";
        $query .= "\n       ,region_directions = %s ";
        $query .= "\n       ,tot_directions = %s ";
        $query .= "\n WHERE  print_produce_sch_seqno = %s ";

        $query = sprintf($query
                        , $param["seoul_directions"]
                        , $param["region_directions"]
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
     * @brief 인쇄생산대비실적 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintPlanResultList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.print_produce_sch_seqno ";
        $query .= "\n       ,A.sch_date ";
        $query .= "\n       ,A.regi_date ";
        $query .= "\n       ,ifnull(A.seoul_directions,0) seoul_directions ";
        $query .= "\n       ,ifnull(A.seoul_exec,0) seoul_exec ";
        $query .= "\n       ,ifnull(A.region_directions,0) region_directions ";
        $query .= "\n       ,ifnull(A.region_exec,0) region_exec ";
        $query .= "\n       ,ifnull(A.tot_directions,0) tot_directions ";
        $query .= "\n       ,ifnull(A.tot_exec,0) tot_exec ";
        $query .= "\n       ,A.extnl_etprs_seqno ";
        $query .= "\n       ,B.manu_name ";
        $query .= "\n       ,C.expect_perform ";
        $query .= "\n       ,C.print_op_seqno ";
        $query .= "\n FROM  print_produce_sch A, extnl_etprs B, print_work_report C ";
        $query .= "\nWHERE  A.extnl_etprs_seqno = B.extnl_etprs_seqno ";
        $query .= "\n  AND  A.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n  AND  C.valid_yn = 'Y'";
 
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"date")) {
            $query .= "\n   AND  A.sch_date = " . $param["date"];
        }

        $query .= "\n   ORDER BY A.print_produce_sch_seqno ASC";
        return $conn->Execute($query);
    }
 

}
?>
