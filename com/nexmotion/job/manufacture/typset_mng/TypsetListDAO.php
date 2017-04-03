<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/typset_mng/TypsetListHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/manufacture/typset_mng/TypsetListDOC.php');
 
/**
 * @file TypsetListDAO.php
 *
 * @brief 생산 - 조판관리 - 웹조판리스트 DAO
 */
class TypsetListDAO extends ManufactureCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 낱장현 수동 주문
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderList($conn, $param) {

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

            $query  = "\nSELECT C.sell_site ";
            $query .= "\n      ,A.order_num ";
            $query .= "\n      ,A.amt ";
            $query .= "\n      ,A.amt_unit_dvs ";
            $query .= "\n      ,A.page_cnt ";
            $query .= "\n      ,A.count ";
            $query .= "\n      ,D.member_name ";
            $query .= "\n      ,D.office_nick ";
            $query .= "\n      ,A.title ";
            $query .= "\n      ,A.order_detail ";
            $query .= "\n      ,A.order_state ";
            $query .= "\n      ,A.order_common_seqno ";
            $query .= "\n      ,B.typset_way ";
            $query .= "\n      ,E.amt_order_detail_sheet_seqno ";
        }
        $query .= "\n  FROM order_common AS A ";
        $query .= "\n      ,order_detail AS B ";
        $query .= "\n      ,cpn_admin AS C ";
        $query .= "\n      ,member AS D ";
        $query .= "\n      ,amt_order_detail_sheet AS E ";
        $query .= "\n      ,order_detail_count_file AS F ";
        $query .= "\n WHERE F.order_detail_count_file_seqno = E.order_detail_count_file_seqno"; 
        $query .= "\n   AND A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n   AND B.order_detail_seqno = F.order_detail_seqno ";
        $query .= "\n   AND B.typset_way = 'MANUAL'";
        $query .= "\n   AND A.cpn_admin_seqno = C.cpn_admin_seqno ";
        $query .= "\n   AND A.member_seqno = D.member_seqno ";
        $query .= "\n   AND A.order_state = 2120";

        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $cate_sortcode = substr($param["cate_sortcode"], 1, -1);
            $query .= "\n   AND A.cate_sortcode LIKE '" . $cate_sortcode . "%'";
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND A.order_state = " . $param["state"];
        }
        if ($this->blankParameterCheck($param ,"search_cnd") && $this->blankParameterCheck($param ,"search_txt")) {
            $search_cnd = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND " . $search_cnd . " = " . $param["search_txt"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND A." . $val. " <= " . $param["to"];
        }

        $query .= "\nORDER BY A.order_common_seqno DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 웹조판리스트 - 낱장형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectSheetTypsetList($conn, $param) {

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

            $query  = "\nSELECT A.typset_num ";
            $query .= "\n      ,A.state ";
            $query .= "\n      ,A.specialty_items ";
        }
        $query .= "\n  FROM sheet_typset AS A ";
        $query .= "\n WHERE A.typset_way = 'MANUAL'";
        $query .= "\n   AND A.state > 2100";
        $query .= "\n   AND A.state < 2200";
        if ($this->blankParameterCheck($param ,"from")) {
            $query .= "\n   AND A.regi_date  > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $query .= "\n   AND A.regi_date <= " . $param["to"];
        }

        $query .= "\nORDER BY A.regi_date DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    } 

    /**
     * @brief 웹조판 - 주문리스트 - 낱장형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectSheetTypsetOrderList($conn, $param) {

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
            $query  = "\nSELECT F.sell_site";
            $query .= "\n      ,E.order_num";
            $query .= "\n      ,G.member_name";
            $query .= "\n      ,G.office_nick";
            $query .= "\n      ,E.title";
            $query .= "\n      ,E.order_detail";
            $query .= "\n      ,E.page_cnt";
            $query .= "\n      ,E.amt";
            $query .= "\n      ,E.amt_unit_dvs";
            $query .= "\n      ,E.count ";
            $query .= "\n      ,E.dlvr_produce_dvs ";
            $query .= "\n      ,A.amt_order_detail_sheet_seqno";
        }
        $query .= "\n  FROM amt_order_detail_sheet AS A";
        $query .= "\n      ,sheet_typset AS B";
        $query .= "\n      ,order_detail_count_file AS C";
        $query .= "\n      ,order_detail AS D";
        $query .= "\n      ,order_common AS E";
        $query .= "\n      ,cpn_admin AS F";
        $query .= "\n      ,member AS G";
        $query .= "\n WHERE A.sheet_typset_seqno = B.sheet_typset_seqno";
        if ($this->blankParameterCheck($param ,"sheet_typset_seqno")) {
            $query .= "\n   AND A.sheet_typset_seqno  = " . $param["sheet_typset_seqno"];
       }
        $query .= "\n   AND A.order_detail_count_file_seqno = C.order_detail_count_file_seqno";
        $query .= "\n   AND C.order_detail_seqno = D.order_detail_seqno";
        $query .= "\n   AND D.order_common_seqno = E.order_common_seqno";
        $query .= "\n   AND E.cpn_admin_seqno = F.cpn_admin_seqno";
        $query .= "\n   AND E.member_seqno = G.member_seqno";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    } 

    /**
     * @brief 웹조판리스트 - 책자형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBrochureTypsetList($conn, $param) {

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

            $query  = "\nSELECT C.sell_site ";
            $query .= "\n      ,A.order_num ";
            $query .= "\n      ,D.member_name ";
            $query .= "\n      ,D.office_nick ";
            $query .= "\n      ,A.title ";
            $query .= "\n      ,A.order_detail ";
            $query .= "\n      ,A.order_state ";
            $query .= "\n      ,A.order_common_seqno ";
            $query .= "\n      ,B.typset_way ";
        }
        $query .= "\n  FROM order_common AS A ";
        $query .= "\n      ,cate AS B ";
        $query .= "\n      ,cpn_admin AS C ";
        $query .= "\n      ,member AS D ";
        $query .= "\n WHERE A.cate_sortcode = B.sortcode ";
        $query .= "\n   AND B.typset_way = 'AGFA'";
        $query .= "\n   AND A.cpn_admin_seqno = C.cpn_admin_seqno ";
        $query .= "\n   AND A.member_seqno = D.member_seqno ";
        $query .= "\n   AND A.order_state > 2100";
        $query .= "\n   AND A.order_state < 2200";

        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $cate_sortcode = substr($param["cate_sortcode"], 1, -1);
            $query .= "\n   AND A.cate_sortcode LIKE '" . $cate_sortcode . "%'";
        }
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND A.order_state = " . $param["state"];
        }
        if ($this->blankParameterCheck($param ,"search_cnd") && $this->blankParameterCheck($param ,"search_txt")) {
            $search_cnd = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND " . $search_cnd . " = " . $param["search_txt"];
        }
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND A." . $val ." > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["date_cnd"], 1, -1);
            $query .= "\n   AND A." . $val. " <= " . $param["to"];
        }

        $query .= "\nORDER BY A.order_common_seqno DESC ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 주문상세 책자 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderDetailBrochureList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n   SELECT A.order_detail_dvs_num ";
        $query .= "\n         ,A.cate_paper_mpcode ";
        $query .= "\n         ,A.typ ";
        $query .= "\n         ,A.order_detail ";
        $query .= "\n         ,A.page_amt ";
        $query .= "\n         ,A.amt ";
        $query .= "\n         ,A.amt_unit_dvs ";
        $query .= "\n         ,A.order_detail_brochure_seqno ";
        $query .= "\n         ,B.page_order_detail_brochure_seqno ";
        $query .= "\n         ,B.page ";
        $query .= "\n         ,B.state AS typset_state ";
        $query .= "\n         ,A.state AS order_state ";
        $query .= "\n         ,C.typset_num ";
        $query .= "\n     FROM order_detail_brochure AS A ";
        $query .= "\n         ,page_order_detail_brochure AS B ";
        $query .= "\nLEFT JOIN brochure_typset AS C ";
        $query .= "\n       ON B.brochure_typset_seqno = C.brochure_typset_seqno ";
        $query .= "\n    WHERE A.order_detail_dvs_num = B.order_detail_dvs_num ";
        $query .= "\n      AND A.order_common_seqno = " . $param["order_common_seqno"]; 

        $query .= "\nORDER BY A.order_detail_dvs_num ASC ";

        return $conn->Execute($query);
    }

    /**
     * @brief 웹조판 종이발주리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectTypsetPaperOpMngList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n  SELECT  A.paper_op_seqno ";
        $query .= "\n         ,A.typset_num ";
        $query .= "\n         ,A.op_num ";
        $query .= "\n         ,A.op_date ";
        $query .= "\n         ,A.stor_date ";
        $query .= "\n         ,A.name ";
        $query .= "\n         ,A.dvs ";
        $query .= "\n         ,A.color ";
        $query .= "\n         ,A.basisweight ";
        $query .= "\n         ,A.op_affil ";
        $query .= "\n         ,A.op_size ";
        $query .= "\n         ,A.stor_subpaper ";
        $query .= "\n         ,A.stor_size ";
        $query .= "\n         ,A.grain ";
        $query .= "\n         ,A.amt ";
        $query .= "\n         ,A.amt_unit ";
        $query .= "\n         ,A.memo ";
        $query .= "\n         ,A.typ ";
        $query .= "\n         ,A.typ_detail ";
        $query .= "\n         ,A.orderer ";
        $query .= "\n         ,A.warehouser ";
        $query .= "\n         ,A.flattyp_dvs ";
        $query .= "\n         ,A.state ";
        $query .= "\n         ,A.regi_date ";
        $query .= "\n         ,A.extnl_brand_seqno ";
        $query .= "\n         ,C.manu_name ";
        $query .= "\n         ,D.manu_name AS storplace";
        $query .= "\n    FROM  paper_op AS A ";
        $query .= "\n         ,extnl_brand AS B ";
        $query .= "\n         ,extnl_etprs AS C ";
        $query .= "\n         ,extnl_etprs AS D ";
        $query .= "\n   WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n     AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n     AND  A.storplace = D.extnl_etprs_seqno ";
        $query .= "\n     AND  A.typset_num = " . $param["typset_num"];
        $query .= "\nORDER BY  A.paper_op_seqno DESC";

        return $conn->Execute($query);
    }

    /**
     * @brief 조판등록 - 출력 등록업체리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputInfoList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.output_seqno ";
        $query .= "\n       ,C.manu_name ";
        $query .= "\n       ,B.name AS brand_name ";
        $query .= "\n       ,A.name AS output_name ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.board ";
        $query .= "\n  FROM  output AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
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
     * @brief 조판등록 - 출력 등록업체리스트 -적용
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputInfoApply($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,A.name AS output_name ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.board ";
        $query .= "\n       ,A.output_seqno ";
        $query .= "\n       ,A.extnl_brand_seqno ";
        $query .= "\n  FROM  output AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //일련번호
        if ($this->blankParameterCheck($param ,"output_seqno")) {
            $query .= "\n   AND  A.output_seqno = " . $param["output_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 출력지시서상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOutputDirectionsView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n   SELECT  A.output_op_seqno ";
        $query .= "\n          ,A.name AS output_name ";
        $query .= "\n          ,A.affil ";
        $query .= "\n          ,A.amt ";
        $query .= "\n          ,A.amt_unit ";
        $query .= "\n          ,C.manu_name ";
        $query .= "\n          ,A.orderer ";
        $query .= "\n          ,A.typ ";
        $query .= "\n          ,A.typ_detail ";
        $query .= "\n          ,A.size ";
        $query .= "\n          ,A.board ";
        $query .= "\n          ,A.memo ";
        $query .= "\n          ,A.extnl_brand_seqno ";
        $query .= "\n     FROM  output_op AS A ";
        $query .= "\nLEFT JOIN  extnl_brand AS B ";
        $query .= "\n       ON  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\nLEFT JOIN  extnl_etprs AS C ";
        $query .= "\n       ON  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n    WHERE  A.typset_num = " . $param["typset_num"];

        return $conn->Execute($query);
    }

    /**
     * @brief 조판등록 - 인쇄 등록업체리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintInfoList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,B.name AS brand_name ";
        $query .= "\n       ,A.name AS print_name ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.print_seqno ";
        $query .= "\n  FROM  print AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
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
     * @brief 조판등록 - 인쇄 등록업체리스트 -적용
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintInfoApply($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,A.name AS print_name ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.print_seqno ";
        $query .= "\n       ,A.extnl_brand_seqno ";
        $query .= "\n  FROM  print AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //일련번호
        if ($this->blankParameterCheck($param ,"print_seqno")) {
            $query .= "\n   AND  A.print_seqno = " . $param["print_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 인쇄지시서상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrintDirectionsView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n   SELECT  A.print_op_seqno ";
        $query .= "\n          ,A.name AS print_name ";
        $query .= "\n          ,A.affil ";
        $query .= "\n          ,A.amt ";
        $query .= "\n          ,A.amt_unit ";
        $query .= "\n          ,C.manu_name ";
        $query .= "\n          ,A.orderer ";
        $query .= "\n          ,A.typ ";
        $query .= "\n          ,A.typ_detail ";
        $query .= "\n          ,A.size ";
        $query .= "\n          ,A.beforeside_tmpt ";
        $query .= "\n          ,A.beforeside_spc_tmpt ";
        $query .= "\n          ,A.aftside_tmpt ";
        $query .= "\n          ,A.aftside_spc_tmpt ";
        $query .= "\n          ,A.tot_tmpt ";
        $query .= "\n          ,A.memo ";
        $query .= "\n          ,A.extnl_brand_seqno ";
        $query .= "\n     FROM  print_op AS A ";
        $query .= "\nLEFT JOIN  extnl_brand AS B ";
        $query .= "\n       ON  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\nLEFT JOIN  extnl_etprs AS C ";
        $query .= "\n       ON  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n    WHERE  A.typset_num = " . $param["typset_num"];

        return $conn->Execute($query);
    }

    /**
     * @brief 조판등록 - 후공정 등록업체리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterInfoList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,B.name AS brand_name ";
        $query .= "\n       ,A.name AS after_name ";
        $query .= "\n       ,A.depth1 ";
        $query .= "\n       ,A.depth2 ";
        $query .= "\n       ,A.depth3 ";
        $query .= "\n       ,A.after_seqno ";
        $query .= "\n  FROM  after AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //검색확인
        if ($this->blankParameterCheck($param ,"search_check")) {
            $query .= "\n   AND  A.search_check = " . $param["search_check"];
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
     * @brief 조판등록 - 후공정 등록업체리스트 -적용
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterInfoApply($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  C.manu_name ";
        $query .= "\n       ,B.name AS brand_name ";
        $query .= "\n       ,A.name AS after_name ";
        $query .= "\n       ,A.depth1 ";
        $query .= "\n       ,A.depth2 ";
        $query .= "\n       ,A.depth3 ";
        $query .= "\n       ,A.after_seqno ";
        $query .= "\n       ,A.extnl_brand_seqno ";
        $query .= "\n  FROM  after AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //일련번호
        if ($this->blankParameterCheck($param ,"after_seqno")) {
            $query .= "\n   AND  A.after_seqno = " . $param["after_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 후공정발주 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterDirectionsList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n  SELECT  A.after_op_seqno ";
        $query .= "\n         ,A.seq ";
        $query .= "\n         ,A.after_name ";
        $query .= "\n         ,A.amt ";
        $query .= "\n         ,A.amt_unit ";
        $query .= "\n         ,C.manu_name ";
        $query .= "\n         ,A.op_typ_detail ";
        $query .= "\n         ,A.order_common_seqno ";
        $query .= "\n         ,A.basic_yn ";
        $query .= "\n    FROM  after_op AS A ";
        $query .= "\n         ,extnl_brand AS B ";
        $query .= "\n         ,extnl_etprs AS C ";
        $query .= "\n   WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n     AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n     AND  A.order_common_seqno = " . $param["order_common_seqno"];
        $query .= "\nORDER BY  A.seq ASC";

        return $conn->Execute($query);
    }

    /**
     * @brief 후공정발주상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectAfterDirectionsView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n  SELECT  A.after_op_seqno ";
        $query .= "\n         ,A.seq ";
        $query .= "\n         ,A.after_name ";
        $query .= "\n         ,A.amt ";
        $query .= "\n         ,A.amt_unit ";
        $query .= "\n         ,C.manu_name ";
        $query .= "\n         ,A.op_typ ";
        $query .= "\n         ,A.op_typ_detail ";
        $query .= "\n         ,A.memo ";
        $query .= "\n         ,A.order_common_seqno ";
        $query .= "\n         ,A.basic_yn ";
        $query .= "\n         ,A.extnl_brand_seqno ";
        $query .= "\n    FROM  after_op AS A ";
        $query .= "\n         ,extnl_brand AS B ";
        $query .= "\n         ,extnl_etprs AS C ";
        $query .= "\n   WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n     AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n     AND  A.after_op_seqno = " . $param["after_op_seqno"];

        return $conn->Execute($query);
    }
}
?>
