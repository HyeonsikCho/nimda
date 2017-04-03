<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/produce/typset_mng/TypsetListDOC.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/typset_mng/TypsetListHTML.php');

/**
 * @file TypsetListDAO.php
 *
 * @brief 생산 - 조판관리 - 조판리스트 DAO
 */
class TypsetListDAO extends ProduceCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 조판리스트 - 낱장
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFlattYTypsetList($conn, $param) {

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
            $query .= "\nSELECT  A.typset_num";
            $query .= "\n       ,A.print_title";
            $query .= "\n       ,A.regi_date";
            $query .= "\n       ,B.name";
            $query .= "\n       ,A.sheet_typset_seqno";
            $query .= "\n       ,A.state";
        }
        $query .= "\n  FROM  sheet_typset AS A";
        $query .= "\n       ,empl AS B";
        $query .= "\n WHERE  A.empl_seqno = B.empl_seqno";

        //인쇄물제목
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.print_title LIKE '%$val%' ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 조판리스트 - 책자형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFlattNTypsetList($conn, $param) {

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
            $query .= "\nSELECT  A.typset_num";
            $query .= "\n       ,A.print_title";
            $query .= "\n       ,A.regi_date";
            $query .= "\n       ,B.name";
            $query .= "\n       ,A.brochure_typset_seqno";
            $query .= "\n       ,A.state";
        }
        $query .= "\n  FROM  brochure_typset AS A ";
        $query .= "\n       ,empl AS B";
        $query .= "\n WHERE  A.empl_seqno = B.empl_seqno";

        //인쇄물제목
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.print_title LIKE '%$val%' ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 조판등록 - 조판대기리스트 - 낱장형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFlattYTypsetStandbyList($conn, $param) {

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
            $query .= "\n   SELECT  E.member_name";
            $query .= "\n          ,E.office_nick";
            $query .= "\n          ,C.title";
            $query .= "\n          ,F.cate_name";
            $query .= "\n          ,C.order_detail"; 
            $query .= "\n          ,C.req_cont"; 
            $query .= "\n          ,D.sell_site"; 
            $query .= "\n          ,B.order_common_seqno";        
            $query .= "\n          ,B.order_detail_seqno"; 
            $query .= "\n          ,B.stan_name"; 
            $query .= "\n          ,B.print_tmpt_name";    
            $query .= "\n          ,B.amt_unit_dvs"; 
            $query .= "\n          ,A.amt"; 
            $query .= "\n          ,A.amt_order_detail_sheet_seqno"; 
            $query .= "\n          ,A.sheet_typset_seqno";
            $query .= "\n          ,A.state";
            $query .= "\n          ,G.order_detail_count_file_seqno";
            $query .= "\n          ,G.seq";
            $query .= "\n          ,G.order_detail_file_num";
        }
        $query .= "\n     FROM  amt_order_detail_sheet AS A"; 
        $query .= "\n          ,order_detail AS B"; 
        $query .= "\n          ,order_common AS C"; 
        $query .= "\n          ,cpn_admin AS D"; 
        $query .= "\n          ,member AS E"; 
        $query .= "\n          ,cate AS F"; 
        $query .= "\n          ,order_detail_count_file G";
        $query .= "\n    WHERE  B.order_detail_seqno = G.order_detail_seqno";
        $query .= "\n      AND  A.order_detail_count_file_seqno = G.order_detail_count_file_seqno";
        $query .= "\n      AND  A.sheet_typset_seqno = " . $param["seqno"];
        $query .= "\n      AND  B.order_common_seqno = C.order_common_seqno"; 
        $query .= "\n      AND  C.cpn_admin_seqno = D.cpn_admin_seqno"; 
        $query .= "\n      AND  C.member_seqno = E.member_seqno"; 
        $query .= "\n      AND  C.cate_sortcode = F.sortcode"; 

        //인쇄물제목
        if ($this->blankParameterCheck($param ,"title")) {
            $val = substr($param["title"], 1, -1);
            $query .= "\n   AND  C.title LIKE '%$val%' ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY  C.order_num ASC ";

		// echo $conn->debug = 1;

        if ($dvs == "SEQ" && $this->blankParameterCheck($param ,"s_num") && $this->blankParameterCheck($param ,"list_num")) {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 조판등록 - 조판대기리스트 - 책자형
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFlattNTypsetStandbyList($conn, $param) {

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
            $query  = "\nSELECT  D.sell_site ";
            $query .= "\n       ,B.order_detail_num ";
            $query .= "\n       ,E.member_name ";
            $query .= "\n       ,C.title ";
            $query .= "\n       ,F.cate_name ";
            $query .= "\n       ,C.order_detail ";
            $query .= "\n       ,B.stan_name ";
            $query .= "\n       ,B.print_tmpt_name ";
            $query .= "\n       ,B.order_detail_dvs_num";
            $query .= "\n       ,C.cate_sortcode ";
            $query .= "\n       ,A.page ";
            $query .= "\n       ,C.amt_unit_dvs ";
            $query .= "\n       ,C.memo ";
            $query .= "\n       ,A.page_order_detail_brochure_seqno ";
            $query .= "\n       ,B.order_detail_brochure_seqno ";
            $query .= "\n       ,C.order_common_seqno ";
        }
        $query .= "\n  FROM  page_order_detail_brochure AS A ";
        $query .= "\n       ,order_detail_brochure AS B ";
        $query .= "\n       ,order_common AS C ";
        $query .= "\n       ,cpn_admin AS D ";
        $query .= "\n       ,member AS E ";
        $query .= "\n       ,cate AS F ";
        $query .= "\n WHERE  A.order_detail_dvs_num = B.order_detail_dvs_num ";
        $query .= "\n   AND  A.brochure_typset_seqno = " . $param["seqno"];
        $query .= "\n   AND  B.order_common_seqno = C.order_common_seqno ";
        $query .= "\n   AND  C.cpn_admin_seqno = D.cpn_admin_seqno ";
        $query .= "\n   AND  C.member_seqno = E.member_seqno ";
        $query .= "\n   AND  C.cate_sortcode = F.sortcode ";

        //인쇄물제목
        if ($this->blankParameterCheck($param ,"title")) {
            $val = substr($param["title"], 1, -1);
            $query .= "\n   AND  C.title LIKE '%$val%' ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY  C.order_num ASC ";

        if ($dvs == "SEQ" && $this->blankParameterCheck($param ,"s_num") && $this->blankParameterCheck($param ,"list_num")) {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
    /**
     * @brief 조판등록 - 조판 정보리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectTypsetInfoList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.typset_format_seqno ";
        $query .= "\n       ,A.name ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.subpaper ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.dscr ";
        $query .= "\n       ,A.process_yn ";
        $query .= "\n       ,A.honggak_yn ";
        $query .= "\n       ,A.purp ";
        $query .= "\n       ,A.dlvrboard ";
        $query .= "\n  FROM  typset_format AS A ";
        $query .= "\n WHERE  1 = 1 ";
        //카테고리 분류코드
        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $query .= "\n   AND  A.cate_sortcode = " . $param["cate_sortcode"];
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
     * @brief 조판등록 - 조판 정보리스트 적용
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectTypsetInfoApply($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.typset_format_seqno ";
        $query .= "\n       ,A.name ";
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.subpaper ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.dscr ";
        $query .= "\n       ,A.process_yn ";
        $query .= "\n       ,A.honggak_yn ";
        $query .= "\n       ,A.purp ";
        $query .= "\n       ,A.dlvrboard ";
        $query .= "\n  FROM  typset_format AS A ";
        //일련번호
        if ($this->blankParameterCheck($param ,"typset_format_seqno")) {
            $query .= "\n WHERE  A.typset_format_seqno = " . $param["typset_format_seqno"];
        }
        return $conn->Execute($query);
    }

    /**
     * @brief 책자형 지시서공정 상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectReceiptDirectionsView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n   SELECT  order_detail ";
        $query .= "\n          ,stan_name ";
        $query .= "\n          ,print_tmpt_name ";
        $query .= "\n          ,memo";
        $query .= "\n          ,count ";
        $query .= "\n     FROM  order_common ";
        $query .= "\n    WHERE  order_common_seqno = " . $param["order_common_seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 책자형 지시서공정 배송정보
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectReceiptOrderDlvr($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n   SELECT  addr";
        $query .= "\n     FROM  order_dlvr";
        $query .= "\n    WHERE  order_common_seqno = " . $param["order_common_seqno"];
        $query .= "\n      AND  tsrs_dvs = " . $param["tsrs_dvs"];

        return $conn->Execute($query);
    }


    /**
     * @brief 조판지시서상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectTypsetDirectionsView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n   SELECT  A.typset_num ";
        $query .= "\n          ,C.name AS typset_name ";
        $query .= "\n          ,B.depar_code ";
        $query .= "\n          ,B.name AS empl_name ";
        $query .= "\n          ,C.wid_size ";
        $query .= "\n          ,C.vert_size ";
        $query .= "\n          ,C.affil ";
        $query .= "\n          ,C.subpaper ";
        $query .= "\n          ,A.beforeside_tmpt ";
        $query .= "\n          ,A.beforeside_spc_tmpt ";
        $query .= "\n          ,A.aftside_tmpt ";
        $query .= "\n          ,A.aftside_spc_tmpt ";
        $query .= "\n          ,A.honggak_yn ";
        $query .= "\n          ,A.after_list ";
        $query .= "\n          ,A.opt_list ";
        $query .= "\n          ,A.print_amt ";
        $query .= "\n          ,A.print_amt_unit ";
        $query .= "\n          ,A.dlvrboard ";
        $query .= "\n          ,A.memo ";
        $query .= "\n          ,A.op_typ ";
        $query .= "\n          ,A.op_typ_detail ";
        $query .= "\n          ,A.sheet_typset_seqno ";
        $query .= "\n          ,A.typset_format_seqno ";
        $query .= "\n     FROM  sheet_typset AS A ";
        $query .= "\nLEFT JOIN  empl AS B ";
        $query .= "\n       ON  A.empl_seqno = B.empl_seqno ";
        $query .= "\nLEFT JOIN  typset_format AS C ";
        $query .= "\n       ON  A.typset_format_seqno = C.typset_format_seqno ";
        $query .= "\n    WHERE  A.sheet_typset_seqno = " . $param["seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 책자형 조판지시서상세보기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBrochureTypsetDirectionsView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n   SELECT  A.typset_num ";
        $query .= "\n          ,C.name AS typset_name ";
        $query .= "\n          ,B.depar_code ";
        $query .= "\n          ,B.name AS empl_name ";
        $query .= "\n          ,C.wid_size ";
        $query .= "\n          ,C.vert_size ";
        $query .= "\n          ,C.affil ";
        $query .= "\n          ,C.subpaper ";
        $query .= "\n          ,A.beforeside_tmpt ";
        $query .= "\n          ,A.beforeside_spc_tmpt ";
        $query .= "\n          ,A.aftside_tmpt ";
        $query .= "\n          ,A.aftside_spc_tmpt ";
        $query .= "\n          ,A.honggak_yn ";
        $query .= "\n          ,A.after_list ";
        $query .= "\n          ,A.opt_list ";
        $query .= "\n          ,A.print_amt ";
        $query .= "\n          ,A.print_amt_unit ";
        $query .= "\n          ,A.dlvrboard ";
        $query .= "\n          ,A.memo ";
        $query .= "\n          ,A.op_typ ";
        $query .= "\n          ,A.op_typ_detail ";
        $query .= "\n          ,A.brochure_typset_seqno ";
        $query .= "\n          ,A.typset_format_seqno ";
        $query .= "\n     FROM  brochure_typset AS A ";
        $query .= "\nLEFT JOIN  empl AS B ";
        $query .= "\n       ON  A.empl_seqno = B.empl_seqno ";
        $query .= "\nLEFT JOIN  typset_format AS C ";
        $query .= "\n       ON  A.typset_format_seqno = C.typset_format_seqno ";
        $query .= "\n    WHERE  A.brochure_typset_seqno = " . $param["seqno"];

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
        $query .= "\n       ,A.affil ";
        $query .= "\n       ,A.wid_size ";
        $query .= "\n       ,A.vert_size ";
        $query .= "\n       ,A.paper_seqno ";
        $query .= "\n  FROM  paper AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        //제조사
        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND  B.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }
        //검색 체크
        if ($this->blankParameterCheck($param ,"search_check")) {
            $query .= "\n   AND  A.search_check = " . $param["search_check"];
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

    /**
     * @brief 지시서공정 후공정팝업 상세사항 - 주문 상세
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectBrochureReceiptView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nSELECT  A.order_num ";
        $query .= "\n       ,B.member_name ";
        $query .= "\n       ,B.office_nick ";
        $query .= "\n       ,A.title ";
        $query .= "\n       ,C.cate_name ";
        $query .= "\n       ,A.amt ";
        $query .= "\n       ,A.amt_unit_dvs ";
        $query .= "\n       ,D.order_detail ";
        $query .= "\n       ,D.stan_name ";
        $query .= "\n       ,D.print_tmpt_name ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.stor_release_yn ";
        $query .= "\n       ,D.receipt_mng ";
        $query .= "\n       ,D.order_detail_dvs_num";
        $query .= "\n  FROM  order_common AS A ";
        $query .= "\n       ,member AS B ";
        $query .= "\n       ,cate AS C ";
        $query .= "\n       ,order_detail_brochure AS D ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  A.cate_sortcode = C.sortcode ";
        $query .= "\n   AND  A.order_common_seqno = D.order_common_seqno ";
        $query .= "\n   AND  A.del_yn = 'N' ";

        //주문공통 일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  A.order_common_seqno = " . $param["order_common_seqno"];
        }

        return $conn->Execute($query);
    }


    //order_common order_state 변경
	function updateOrderCommonState($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n UPDATE order_common set order_state = " . $param['state'];
		$query .= "\n  WHERE order_common_seqno = ". $param['order_common_seqno'];

        return $conn->Execute($query);
	}

}
?>
