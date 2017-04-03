<?
//CommonDAO extends ErpCommonDAO
//ErpCommonDAO extends ProduceCommonDAO

include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');

//생산 공통 DAO
class ProduceCommonDAO extends CommonDAO {

    function __construct() {
    }
  
    /**
     * @brief 조판등록 - 종이 등록업체리스트 -적용
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
     * @brief 종이발주 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperDirectionsList($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\n  SELECT  A.paper_op_seqno ";
        $query .= "\n         ,A.name AS paper_name ";
        $query .= "\n         ,A.op_affil ";
        $query .= "\n         ,A.amt ";
        $query .= "\n         ,A.amt_unit ";
        $query .= "\n         ,C.manu_name ";
        $query .= "\n         ,A.orderer ";
        $query .= "\n         ,A.typ_detail ";
        $query .= "\n         ,A.op_date ";
        $query .= "\n         ,A.state ";
        $query .= "\n    FROM  paper_op AS A ";
        $query .= "\n         ,extnl_brand AS B ";
        $query .= "\n         ,extnl_etprs AS C ";
        $query .= "\n   WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n     AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n     AND  A.typset_num = " . $param["typset_num"];
        $query .= "\nORDER BY  A.paper_op_seqno DESC";

        return $conn->Execute($query);
    }

    /**
     * @brief 종이발주 상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperDirectionsView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.paper_op_seqno ";
        $query .= "\n       ,A.name ";
        $query .= "\n       ,A.dvs ";
        $query .= "\n       ,A.color ";
        $query .= "\n       ,A.basisweight ";
        $query .= "\n       ,C.manu_name ";
        $query .= "\n       ,A.op_affil ";
        $query .= "\n       ,A.op_size ";
        $query .= "\n       ,A.stor_subpaper ";
        $query .= "\n       ,A.stor_size ";
        $query .= "\n       ,A.storplace ";
        $query .= "\n       ,A.grain ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.typ ";
        $query .= "\n       ,A.typ_detail ";
        $query .= "\n       ,A.extnl_brand_seqno ";
        $query .= "\n  FROM  paper_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n   AND  A.paper_op_seqno = " . $param["paper_op_seqno"];

        return $conn->Execute($query);
    }
     
    /**
     * @brief 주문 상태 변경
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateOrderState($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  order_common ";
        $query .= "\n       SET  order_state = %s ";
        $query .= "\n     WHERE  order_common_seqno = %s ";

        $query = sprintf($query,
                         $param["order_state"],
                         $param["order_common_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 발주상태값 조회
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function selectOpState($conn, $seq) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    SELECT  state ";
        $query .= "\n      FROM  paper_op";
        $query .= "\n     WHERE  paper_op_seqno = %s ";

        $query = sprintf($query, $seq);
        $rs = $conn->Execute($query);

        return $rs->fields["state"];
    }
 
    /**
     * @brief 발주상태변경
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updateOpState($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  paper_op ";
        $query .= "\n       SET  state = '520'";
        $query .= "\n          , op_date = SYSDATE() ";
        $query .= "\n     WHERE  paper_op_seqno = %s ";

        $query = sprintf($query, $param["paper_op_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 주문 상태 조회
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderState($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  order_state";
        $query .= "\n  FROM  order_common ";
        $query .= "\n WHERE  order_common_seqno = " . $param["order_common_seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 인쇄공정 작업일지 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintProcessView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.name AS print_name";
        $query .= "\n       ,A.print_op_seqno ";
        $query .= "\n       ,A.typset_num ";
        $query .= "\n       ,A.orderer ";
        $query .= "\n       ,B.extnl_brand_seqno ";
        $query .= "\n       ,B.extnl_etprs_seqno ";
        $query .= "\n       ,A.typ ";
        $query .= "\n       ,A.typ_detail ";
        $query .= "\n       ,A.size ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.beforeside_tmpt ";
        $query .= "\n       ,A.beforeside_spc_tmpt ";
        $query .= "\n       ,A.aftside_tmpt ";
        $query .= "\n       ,A.aftside_spc_tmpt ";
        $query .= "\n       ,A.tot_tmpt ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.state ";
        $query .= "\n       ,A.flattyp_dvs ";
        $query .= "\n  FROM  print_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";

        //일련번호 
        if ($this->blankParameterCheck($param ,"seqno")) {
            $query .= "\n   AND  A.print_op_seqno = " . $param["seqno"];
        }

        //조판번호
        if ($this->blankParameterCheck($param ,"typset_num")) {
            $query .= "\n   AND  A.typset_num = " . $param["typset_num"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 조판별 - 후공정공정 작업일지 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBasicAfterProcessView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT  A.basic_after_op_seqno ";
        $query .= "\n       ,A.after_name ";
        $query .= "\n       ,A.orderer ";
        $query .= "\n       ,B.extnl_etprs_seqno ";
        $query .= "\n       ,B.extnl_brand_seqno ";
        $query .= "\n       ,A.op_typ ";
        $query .= "\n       ,A.op_typ_detail ";
        $query .= "\n       ,A.depth1 ";
        $query .= "\n       ,A.depth2 ";
        $query .= "\n       ,A.depth3 ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit ";
        $query .= "\n       ,A.state ";
        $query .= "\n       ,A.flattyp_dvs ";
        $query .= "\n       ,A.typset_num ";
        $query .= "\n       ,A.cate_sortcode ";
        $query .= "\n  FROM  basic_after_op AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  A.basic_after_op_seqno = " . $param["seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 주문별 - 후공정공정 작업일지 보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterProcessView($conn, $param) {
 
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
 
        $query  = "\nSELECT A.cate_sortcode";
        $query .= "\n      ,A.after_name";
        $query .= "\n      ,A.depth1";
        $query .= "\n      ,A.depth2";
        $query .= "\n      ,A.depth3";
        $query .= "\n      ,A.amt";
        $query .= "\n      ,A.amt_unit";
        $query .= "\n      ,A.memo";
        $query .= "\n      ,A.specialty_items";
        $query .= "\n      ,A.dlvrboard";
        $query .= "\n      ,A.state";
        $query .= "\n      ,B.order_num";
        $query .= "\n      ,C.extnl_brand_seqno";
        $query .= "\n      ,C.extnl_etprs_seqno"; 
        $query .= "\n  FROM after_op AS A";
        $query .= "\n      ,order_common AS B";
        $query .= "\n      ,extnl_brand AS C";
        $query .= "\n WHERE A.after_op_seqno = " . $param["seqno"];
        $query .= "\n   AND A.order_common_seqno = B.order_common_seqno";
        $query .= "\n   AND A.extnl_brand_seqno = C.extnl_brand_seqno";

        return $conn->Execute($query);
    }
}
