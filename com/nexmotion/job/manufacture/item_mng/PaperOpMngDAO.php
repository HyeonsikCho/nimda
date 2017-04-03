<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/item_mng/PaperOpMngHtml.php');

/**
 * @file PaperOpMngDAO.php
 *
 * @brief 생산 - 자재관리 - 종이발주관리 DAO
 */
class PaperOpMngDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }
   
    /**
     * @brief 조판등록 - 종이 등록업체리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperInfoList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,B.name AS brand_name ";
        $query .= "\n       ,A.name AS paper_name ";
        $query .= "\n       ,A.dvs ";
        $query .= "\n       ,A.color ";
        $query .= "\n       ,A.basisweight ";
        $query .= "\n       ,A.basisweight_unit ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.paper_seqno ";
        $query .= "\n  FROM  paper AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  A.name = " . $param["name"];
        }
        if ($this->blankParameterCheck($param ,"dvs")) {
            $query .= "\n   AND  A.dvs = " . $param["dvs"];
        }
        if ($this->blankParameterCheck($param ,"color")) {
            $query .= "\n   AND  A.color = " . $param["color"];
        }
        if ($this->blankParameterCheck($param ,"basisweight") && 
                $this->blankParameterCheck($param ,"basisweight_unit")) {
            $query .= "\n   AND  A.basisweight = " . $param["basisweight"];
            $query .= "\n   AND  A.basisweight_unit = " . $param["basisweight_unit"];
        }
        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type;
            }
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 종이 등록업체리스트 -적용
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperInfoApply($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,A.name ";
        $query .= "\n       ,A.dvs ";
        $query .= "\n       ,A.color ";
        $query .= "\n       ,A.basisweight ";
        $query .= "\n       ,A.basisweight_unit ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.paper_seqno ";
        $query .= "\n       ,A.extnl_brand_seqno ";
        $query .= "\n  FROM  paper AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //일련번호 
        if ($this->blankParameterCheck($param ,"paper_seqno")) {
            $query .= "\n   AND  A.paper_seqno = " . $param["paper_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 종이 발주
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperOpLastNum($conn) {
        $today = date("Y-m-d");

        $query  = "\n   SELECT op_num";
        $query .= "\n     FROM paper_op";
        $query .= "\n    WHERE '%s 00:00:00' <= regi_date";
        $query .= "\n      AND regi_date <= '%s 23:59:59'";
        $query .= "\n ORDER BY paper_op_seqno DESC";
        $query .= "\n    LIMIT 1";

        $query  = sprintf($query, $today, $today);

        $rs = $conn->Execute($query);

        if ($rs->EOF) {
            $last_num = 1;
        } else {
            $last_num = intval(substr($rs->fields["op_num"], 6)) + 1;
        }

        return $last_num;
    }
}
?>
