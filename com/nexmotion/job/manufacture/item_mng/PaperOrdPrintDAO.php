<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/item_mng/PaperOrdPrintHtml.php');

/**
 * @file PaperOrdPrintDAO.php
 *
 * @brief 생산 - 자재관리 - 종이발발주서인쇄 DAO
 */
class PaperOrdPrintDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 종이발주인쇄 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperOpMngPrintList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.paper_op_seqno ";
        $query .= "\n       ,A.op_num ";
        $query .= "\n       ,A.op_degree ";
        $query .= "\n       ,A.op_date ";
        $query .= "\n       ,A.name ";
        $query .= "\n       ,A.dvs ";
        $query .= "\n       ,A.color ";
        $query .= "\n       ,A.basisweight ";
        $query .= "\n       ,A.stor_subpaper ";
        $query .= "\n       ,A.stor_size ";
        $query .= "\n       ,A.grain ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,C.manu_name AS paper_comp ";
        $query .= "\n       ,D.manu_name AS storplace";
        $query .= "\n  FROM  paper_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n       ,extnl_etprs AS D ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.storplace = D.extnl_etprs_seqno ";
        $query .= "\n   AND  A.state >= " . $param["state"];
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $query .= "\n   AND  A.op_date >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $query .= "\n   AND  A.op_date <= " . $param["to"];
        }

        return $conn->Execute($query);
    }
}
