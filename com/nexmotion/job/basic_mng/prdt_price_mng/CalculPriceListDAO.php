<?
/*
 *
 * Copyright (c) 2015-2016 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *=============================================================================
 * 2016/11/02 엄준현 수정(계산형 카테고리 검색부분 추가)
 * 2016/11/06 엄준현 수정(수량별 종이 할인 부분 추가)
 *=============================================================================
 *
 */
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');

class CalculPriceListDAO extends BasicMngCommonDAO {

    function __construct() {
    }

    /**
     * @brief 계산형 가격 카테고리 소분류 검색
     *
     * @param $conn = connection identifier
     *
     * @return option html
     */
    function selectCalcCate($conn) {
        $query  = "\n SELECT  sortcode";
        $query .= "\n        ,cate_name";
        $query .= "\n   FROM  cate AS A";
        $query .= "\n  WHERE  A.cate_level = '3'";
        $query .= "\n    AND  A.use_yn     = 'Y'";
        $query .= "\n    AND  A.mono_dvs IN ('1', '3')";
        $query .= "\n ORDER BY A.sortcode";

        $rs = $conn->Execute($query);

        $basic_option = "소분류(전체)";

        return makeOptionHtml($rs, "sortcode", "cate_name", $basic_option);
    }

    //////////////////////////////////////////////////////// 이하 종이

    /**
     * @brief 상품 종이 검색해서 option html 반환
     *
     * @param $conn = connection identifer
     * @param $dvs  = 가져올 필드 정보 구분값
     * @param $param = 검색 파라미터
     *
     * @return option html
     */
    function selectPrdtPaperInfoHtml($conn, $dvs, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $rs = $this->selectPaperInfo($conn, $dvs, $param);

        return makeOptionHtml($rs, "", strtolower($dvs));
    }

    /**
     * @brief 상품 종이 맵핑코드 검색
     * 엑셀 생성시에는 정보배열용 컬럼까지 반환함
     *
     * @param $conn       = connection identifer
     * @param $param      = 검색 파라미터
     * @param $excel_flag = 엑셀 생성인지 구분
     *
     * @return 검색결과
     */
    function selectPrdtPaperMpcode($conn, $param, $excel_flag = false) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  mpcode";
        if ($excel_flag === true) {
            $query .= "\n       ,sort";
            $query .= "\n       ,name";
            $query .= "\n       ,dvs";
            $query .= "\n       ,color";
            $query .= "\n       ,CONCAT(basisweight, basisweight_unit) AS basisweight";
            $query .= "\n       ,affil";
            $query .= "\n       ,size";
            $query .= "\n       ,crtr_unit";
        }

        $query .= "\n   FROM prdt_paper";

        $query .= "\n  WHERE sort = %s";
        $query .= "\n    AND name = %s";

        if ($this->blankParameterCheck($param ,"paper_dvs")) {
            $query .= "\n    AND dvs = " . $param["paper_dvs"];
        }
        if ($this->blankParameterCheck($param ,"paper_color")) {
            $query .= "\n    AND color = " . $param["paper_color"];
        }
        if ($this->blankParameterCheck($param ,"paper_basisweight")) {
            $query .= "\n    AND basisweight = " . $param["paper_basisweight"];
        }
        if ($this->blankParameterCheck($param ,"paper_affil")) {
            $query .= "\n    AND affil = " . $param["paper_affil"];
        }
        if ($this->blankParameterCheck($param ,"paper_size")) {
            $query .= "\n    AND size = " . $param["paper_size"];
        }
        if ($this->blankParameterCheck($param ,"basisweight_unit")) {
            $query .= "\n    AND basisweight_unit = " . $param["basisweight_unit"];
        }

        $query  = sprintf($query, $param["paper_sort"]
                                , $param["paper_name"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 상품 종이 가격 검색
     *
     * @detail $param["mpcode"] 같은 경우는 배열 혹은 문자열이 넘어온다
     *
     * @param $conn  = connection identifer
     * @param $param = 검색조건
     *
     * @return 검색결과
     */
    function selectPrdtPaperPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.sort";
        $query .= "\n        ,A.name";
        $query .= "\n        ,A.dvs";
        $query .= "\n        ,A.color";
        $query .= "\n        ,A.affil";
        $query .= "\n        ,A.size";
        $query .= "\n        ,CONCAT(A.basisweight, A.basisweight_unit) AS basisweight";
        $query .= "\n        ,A.crtr_unit";
        $query .= "\n        ,B.prdt_paper_price_seqno AS price_seqno";
        $query .= "\n        ,B.basic_price";
        $query .= "\n        ,B.sell_rate";
        $query .= "\n        ,B.sell_aplc_price";
        $query .= "\n        ,B.sell_price";

        $query .= "\n   FROM  prdt_paper       AS A";
        $query .= "\n        ,prdt_paper_price AS B";

        $query .= "\n  WHERE  A.mpcode = B.prdt_paper_mpcode";
        $query .= "\n    AND  A.mpcode = %s";
        $query .= "\n    AND  B.cpn_admin_seqno = %s";

        $query  = sprintf($query, $param["mpcode"]
                                , $param["sell_site"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 상품 종이 가격 검색
     *
     * @detail 가격 수정시에도 사용됨
     *
     * @param $conn  = connection identifer
     * @param $param = 검색 파라미터
     *
     * @return 검색결과
     */
    function selectPrdtPaperPriceExcel($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $except_arr = array("mpcode" => true);

        $param = $this->parameterArrayEscape($conn, $param, $except_arr);

        $query  = "\n SELECT  prdt_paper_price_seqno AS price_seqno";
        $query .= "\n        ,prdt_paper_mpcode";
        $query .= "\n        ,basic_price";
        $query .= "\n        ,sell_rate";
        $query .= "\n        ,sell_aplc_price";
        $query .= "\n        ,sell_price";

        $query .= "\n   FROM  prdt_paper_price";

        $query .= "\n  WHERE  1 = 1";
        if ($this->blankParameterCheck($param, "mpcode")) {
            $query .= "\n    AND  prdt_paper_mpcode IN (";
            $query .= $param["mpcode"];
            $query .= ")";
        }
        if ($this->blankParameterCheck($param, "sell_site")) {
            $query .= "\n    AND  cpn_admin_seqno   = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param, "price_seqno")) {
            $query .= "\n    AND  prdt_paper_price_seqno = ";
            $query .= $param["price_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 종이 가격 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 쿼리 성공여부
     */
    function updatePrdtPaperPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();

        $temp["table"] = "prdt_paper_price";

        $temp["col"]["sell_rate"]       = $param["sell_rate"];
        $temp["col"]["sell_aplc_price"] = $param["sell_aplc_price"];
        $temp["col"]["sell_price"]      = $param["sell_price"];

        $temp["prk"] = "prdt_paper_price_seqno";
        $temp["prkVal"] = $param["price_seqno"];

        return $this->updateData($conn, $temp);
    }

    //////////////////////////////////////////////////////// 이하 출력

    /**
     * @brief 상품 출력 검색해서 option html 반환
     *
     * @param $conn  = connection identifer
     * @param $dvs   = 가져올 필드 정보 구분값
     * @param $param = 검색 파라미터
     *
     * @return option html
     */
    function selectPrdtOutputInfoHtml($conn, $dvs, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $rs = $this->selectOutputInfo($conn, $dvs, $param);

        if ($dvs === "NAME") {
            $dvs = "output_name";
        } else if ($dvs === "BOARD") {
            $dvs = "output_board_dvs";
        }

        return makeOptionHtml($rs, "", $dvs);
    }

    /**
     * @brief 상품 출력 맵핑코드 검색
     * 엑셀 생성시에는 정보배열용 컬럼까지 반환함
     *
     * @param $conn       = connection identifer
     * @param $param      = 검색 파라미터
     * @param $excel_flag = 엑셀 생성인지 구분
     *
     * @return 검색결과
     */
    function selectPrdtOutputMpcode($conn, $param, $excel_flag = false) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  mpcode";
        if ($excel_flag === true) {
            $query .= "\n        ,output_name";
            $query .= "\n        ,output_board_dvs";
        }
        $query .= "\n  FROM  prdt_output_info";

        $query .= "\n WHERE  output_name = %s";

        if ($this->blankParameterCheck($param, "output_board_dvs")) {
            $query .= "\n    AND output_board_dvs = ";
            $query .= $param["output_board_dvs"];
        }

        $query  = sprintf($query, $param["output_name"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 상품 출력 가격검색
     *
     * @param $conn  = connection identifer
     * @param $param = 검색 파라미터
     *
     * @return 검색결과
     */
    function selectPrdtOutputPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n SELECT  A.output_name";
        $query .= "\n        ,A.output_board_dvs";
        $query .= "\n        ,B.prdt_stan_price_seqno AS price_seqno";
        $query .= "\n        ,B.board_amt";
        $query .= "\n        ,B.basic_price";
        $query .= "\n        ,B.sell_rate";
        $query .= "\n        ,B.sell_aplc_price";
        $query .= "\n        ,B.sell_price";

        $query .= "\n   FROM  prdt_output_info AS A";
        $query .= "\n        ,prdt_stan_price  AS B";

        $query .= "\n  WHERE  A.mpcode = B.prdt_output_info_mpcode";
        $query .= "\n    AND  A.mpcode = %s";
        $query .= "\n    AND  B.cpn_admin_seqno = %s";

        $query  = sprintf($query, $param["mpcode"]
                                , $param["sell_site"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 상품 출력 가격검색
     *
     * @param $conn  = connection identifer
     * @param $param = 검색 파라미터
     *
     * @return 검색결과
     */
    function selectPrdtOutputPriceExcel($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  prdt_stan_price_seqno AS price_seqno";
        $query .= "\n        ,board_amt";
        $query .= "\n        ,basic_price";
        $query .= "\n        ,sell_rate";
        $query .= "\n        ,sell_aplc_price";
        $query .= "\n        ,sell_price";

        $query .= "\n   FROM  prdt_stan_price";

        $query .= "\n  WHERE  1 = 1";

        if ($this->blankParameterCheck($param, "mpcode")) {
            $query .= "\n    AND  prdt_output_info_mpcode = ";
            $query .= $param["mpcode"];
        }
        if ($this->blankParameterCheck($param, "sell_site")) {
            $query .= "\n    AND  cpn_admin_seqno         = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param, "price_seqno")) {
            $query .= "\n    AND  prdt_stan_price_seqno = ";
            $query .= $param["price_seqno"];
        }

        $query  = sprintf($query, $param["mpcode"]
                                , $param["sell_site"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 출력 가격 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 쿼리 성공여부
     */
    function updatePrdtOutputPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();

        $temp["table"] = "prdt_stan_price";

        $temp["col"]["sell_rate"]       = $param["sell_rate"];
        $temp["col"]["sell_aplc_price"] = $param["sell_aplc_price"];
        $temp["col"]["sell_price"]      = $param["sell_price"];

        $temp["prk"] = "prdt_stan_price_seqno";
        $temp["prkVal"] = $param["price_seqno"];

        return $this->updateData($conn, $temp);
    }

    //////////////////////////////////////////////////////// 이하 인쇄

    /**
     * @brief 상품 인쇄 검색해서 option html 반환
     *
     * @param $conn  = connection identifer
     * @param $dvs   = 가져올 필드 정보 구분값
     * @param $param = 검색 파라미터
     *
     * @return option html
     */
    function selectPrdtPrintInfoHtml($conn, $dvs, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $rs = $this->selectPrintInfo($conn, $dvs, $param);

        if ($dvs === "NAME") {
            $dvs = "print_name";
        } else if ($dvs === "PURP") {
            $dvs = "purp_dvs";
        }

        return makeOptionHtml($rs, "", $dvs);
    }

    /**
     * @brief 상품 인쇄 맵핑코드 검색
     * 엑셀 생성시에는 정보배열용 컬럼까지 반환함
     *
     * @param $conn       = connection identifer
     * @param $param      = 검색 파라미터
     * @param $excel_flag = 엑셀 생성인지 구분
     *
     * @return 검색결과
     */
    function selectPrdtPrintMpcode($conn, $param, $excel_flag = false) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.mpcode";
        if ($excel_flag === true) {
            $query .= "\n        ,B.cate_name";
            $query .= "\n        ,A.print_name";
            $query .= "\n        ,A.purp_dvs";
            $query .= "\n        ,A.affil";
            $query .= "\n        ,A.crtr_unit";
        }
        $query .= "\n  FROM  prdt_print_info AS A";
        if ($excel_flag === true) {
            $query .= "\n       ,cate AS B";
        }

        $query .= "\n WHERE  1 = 1";

        if ($this->blankParameterCheck($param, "print_name")) {
            $query .= "\n    AND  A.print_name = ";
            $query .= $param["print_name"];
        }
        if ($this->blankParameterCheck($param, "purp_dvs")) {
            $query .= "\n    AND  A.purp_dvs = ";
            $query .= $param["print_purp_dvs"];
        }
        if ($this->blankParameterCheck($param, "print_affil")) {
            $query .= "\n    AND  A.affil = ";
            $query .= $param["output_board_affil"];
        }
        if ($this->blankParameterCheck($param, "cate_sortcode")) {
            $cate_sortcode = substr($param["cate_sortcode"], 1, -1);

            $query .= "\n    AND  A.cate_sortcode LIKE '";
            $query .= $cate_sortcode;
            $query .= "%%'";
        }
        if ($excel_flag === true) {
            $query .= "\n    AND  A.cate_sortcode = B.sortcode";
        }

        $query  = sprintf($query, $param["print_name"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 상품 인쇄 가격검색
     *
     * @param $conn  = connection identifer
     * @param $param = 검색 파라미터
     *
     * @return 검색결과
     */
    function selectPrdtPrintPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  C.cate_name";
        $query .= "\n        ,A.print_name";
        $query .= "\n        ,A.purp_dvs";
        $query .= "\n        ,A.crtr_unit";
        $query .= "\n        ,B.prdt_print_price_seqno AS price_seqno";
        $query .= "\n        ,B.amt";
        $query .= "\n        ,B.basic_price";
        $query .= "\n        ,B.sell_rate";
        $query .= "\n        ,B.sell_aplc_price";
        $query .= "\n        ,B.sell_price";

        $query .= "\n   FROM  prdt_print_info  AS A";
        $query .= "\n        ,prdt_print_price AS B";
        $query .= "\n        ,cate             AS C";

        $query .= "\n  WHERE  A.mpcode = B.prdt_print_info_mpcode";
        $query .= "\n    AND  A.cate_sortcode = C.sortcode";
        $query .= "\n    AND  A.mpcode = %s";
        $query .= "\n    AND  B.cpn_admin_seqno = %s";

        $query  = sprintf($query, $param["mpcode"]
                                , $param["sell_site"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 상품 출력 가격검색
     *
     * @param $conn  = connection identifer
     * @param $param = 검색 파라미터
     *
     * @return 검색결과
     */
    function selectPrdtPrintPriceExcel($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  prdt_print_price_seqno AS price_seqno";
        $query .= "\n        ,amt";
        $query .= "\n        ,basic_price";
        $query .= "\n        ,sell_rate";
        $query .= "\n        ,sell_aplc_price";
        $query .= "\n        ,sell_price";

        $query .= "\n   FROM  prdt_print_price";

        $query .= "\n  WHERE  1 = 1";

        if ($this->blankParameterCheck($param, "mpcode")) {
            $query .= "\n    AND  prdt_print_info_mpcode = ";
            $query .= $param["mpcode"];
        }
        if ($this->blankParameterCheck($param, "sell_site")) {
            $query .= "\n    AND  cpn_admin_seqno        = ";
            $query .= $param["sell_site"];
        }
        if ($this->blankParameterCheck($param, "price_seqno")) {
            $query .= "\n    AND  prdt_print_price_seqno = ";
            $query .= $param["price_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 인쇄 가격 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 쿼리 성공여부
     */
    function updatePrdtPrintPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();

        $temp["table"] = "prdt_print_price";

        $temp["col"]["sell_rate"]       = $param["sell_rate"];
        $temp["col"]["sell_aplc_price"] = $param["sell_aplc_price"];
        $temp["col"]["sell_price"]      = $param["sell_price"];

        $temp["prk"] = "prdt_print_price_seqno";
        $temp["prkVal"] = $param["price_seqno"];

        return $this->updateData($conn, $temp);
    }

    //////////////////////////////////////////////////////// 이하 수량별 종이 할인

    /**
     * @brief 카테고리 물려있는 종이정보로 상품 종이정보 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 검색결과
     */
    function selectCatePrdtPaper($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  B.name";
        $query .= "\n        ,B.dvs";
        $query .= "\n        ,B.color";
        $query .= "\n        ,A.basisweight";
        $query .= "\n        ,B.size";
        $query .= "\n        ,B.crtr_unit";
        $query .= "\n        ,A.mpcode AS cate_mpcode";
        $query .= "\n        ,B.mpcode AS prdt_mpcode";
        $query .= "\n   FROM  cate_paper AS A";
        $query .= "\n        ,prdt_paper AS B";
        $query .= "\n  WHERE  A.cate_sortcode = %s";
        $query .= "\n    AND  B.affil = %s";
        $query .= "\n    AND  B.search_check = CONCAT(A.name, '|',";
        $query .= "\n                                 A.dvs, '|',";
        $query .= "\n                                 A.color, '|',";
        $query .= "\n                                 A.basisweight)";
        if ($this->blankParameterCheck($param, "paper_name")) {
            $query .= "\n    AND  A.name = ";
            $query .= $param["paper_name"];
        }
        if ($this->blankParameterCheck($param, "paper_dvs")) {
            $query .= "\n    AND  A.dvs = ";
            $query .= $param["paper_dvs"];
        }
        if ($this->blankParameterCheck($param, "paper_color")) {
            $query .= "\n    AND  A.color = ";
            $query .= $param["paper_color"];
        }
        if ($this->blankParameterCheck($param, "paper_basisweight")) {
            $query .= "\n    AND  A.basisweight = ";
            $query .= $param["paper_basisweight"];
        }

        $query = sprintf($query, $param["cate_sortcode"]
                               , $param["paper_affil"]);

        return $conn->Execute($query);
    }

    /**
     * @brief 수량별 종이 할인정보 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 검색결과
     */
    function selectAmtPaperSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.rate";
        $query .= "\n        ,A.aplc_price";
        $query .= "\n        ,A.amt_paper_sale_seqno";
        $query .= "\n   FROM  amt_paper_sale AS A";
        $query .= "\n  WHERE  A.cpn_admin_seqno   = %s";
        $query .= "\n    AND  A.cate_sortcode     = %s";
        $query .= "\n    AND  A.cate_paper_mpcode = %s";
        $query .= "\n    AND  A.cate_stan_mpcode  = %s";
        if ($this->blankParameterCheck($param, "amt")) {
            $query .= "\n    AND  A.amt       = " . $param["amt"];
        }

        $query  = sprintf($query, $param["sell_site"]
                                , $param["cate_sortcode"]
                                , $param["cate_paper_mpcode"]
                                , $param["cate_stan_mpcode"]);

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 카테고리 종이 맵핑코드 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 검색결과
     */
    function selectCatePaperMpcode($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  A.mpcode";
        $query .= "\n   FROM  cate_paper AS A";
        $query .= "\n  WHERE  A.cate_sortcode = %s";
        $query .= "\n    AND  A.name          = %s";
        $query .= "\n    AND  A.dvs           = %s";
        $query .= "\n    AND  A.color         = %s";
        $query .= "\n    AND  A.basisweight   = %s";

        $query  = sprintf($query, $param["cate_sortcode"]
                                , $param["name"]
                                , $param["dvs"]
                                , $param["color"]
                                , $param["basisweight"]);

        $rs = $conn->Execute($query);

        return $rs->fields["mpcode"];
    }

    /**
     * @brief 수량_종이_할인 입력
     *
     * @param $conn  = connection identifier
     * @param $param = 입력용 파라미터
     *
     * @return 쿼리수행결과
     */
    function insertAmtPaperSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();
        $temp["table"] = "amt_paper_sale";

        $temp["col"]["cate_sortcode"]     = $param["cate_sortcode"];
        $temp["col"]["cate_paper_mpcode"] = $param["cate_paper_mpcode"];
        $temp["col"]["cate_stan_mpcode"]  = $param["cate_stan_mpcode"];
        $temp["col"]["amt"]               = $param["amt"];
        $temp["col"]["rate"]              = $param["rate"];
        $temp["col"]["aplc_price"]        = $param["aplc_price"];
        $temp["col"]["cpn_admin_seqno"]   = $param["sell_site"];
        $temp["col"]["typ"]               = $param["typ"];
        $temp["col"]["page_amt"]          = $param["page_amt"];

        return $this->insertData($conn, $temp);
    }

    /**
     * @brief 인쇄 가격 수정
     *
     * @param $conn  = connection identifier
     * @param $param = 조건용 파라미터
     *
     * @return 쿼리 성공여부
     */
    function updateAmtPaperSale($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();
        $temp["table"] = "amt_paper_sale";

        if ($this->blankParameterCheck($param, "rate")) {
            $temp["col"]["rate"]       = $param["rate"];
        }
        if ($this->blankParameterCheck($param, "aplc_price")) {
            $temp["col"]["aplc_price"] = $param["aplc_price"];
        }

        $temp["prk"] = "amt_paper_sale_seqno";
        $temp["prkVal"] = $param["amt_paper_sale_seqno"];

        return $this->updateData($conn, $temp);
    }
}
?>
