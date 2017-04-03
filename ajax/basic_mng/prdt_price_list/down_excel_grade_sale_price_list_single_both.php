<?
/*
 * Copyright (c) 2016 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *============================================================================
 * 2016/10/13 엄준현 생성
 * 2016/12/01 엄준현 수정(쿼리 order by 추가, 배열값 이상한부분 수정)
 *============================================================================
 *
 */
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ErpCommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/GradeSalePriceExcelUtil.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdt_price_mng/PrdtPriceListDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PrdtPriceListDAO();
$util = new ErpCommonUtil();
$excelUtil = new GradeSalePriceExcelUtil();

$fb = $fb->getForm();

$cate_sortcode = $fb["cate_sortcode"];
$sell_site     = $fb["sell_site"];
$mono_yn       = intval($fb["mono_yn"]);
$etprs_dvs     = $fb["etprs_dvs"];

$param = array();

//$conn->debug = 1;

//* 판매채널에 해당하는 합판/계산형 가격테이블 검색
$table_name = $dao->selectPriceTableName($conn,
                                         $mono_yn,
                                         $sell_site,
                                         $etprs_dvs);

//* 카테고리에 해당하는 수량단위 검색
$amt_unit = $dao->selectCateAmtUnit($conn, $cate_sortcode);

//* 종이 정보가 넘어온 것이 있을 경우 종이 맵핑코드 검색
$info_fld_arr = array(
    "name",
    "dvs",
    "color",
    "basisweight"
);

// 합판가격은 계열 상관 없으므로 계산형일 때 만 추가
if ($mono_yn === 1) {
    $info_fld_arr[] = "affil";
}

$param["cate_sortcode"] = $cate_sortcode;
if (empty($fb["paper_name"]) === false) {
    // 종이 정보가 있을경우 파라미터 추가
    $param["name"]        = $fb["paper_name"];
    $param["dvs"]         = $fb["paper_dvs"];
    $param["color"]       = $fb["paper_color"];
    $param["basisweight"] = $fb["paper_basisweight"];
}
$paper_rs = $dao->selectPaperInfoAll($conn, $param);

$paper_total_arr = $util->makeTotalInfoArr($paper_rs, $info_fld_arr);
$paper_mpcode_arr = $paper_total_arr["mpcode"];
$paper_info_arr = $paper_total_arr["info"];

unset($paper_rs);
unset($paper_total_arr);
unset($param);
unset($info_fld_arr);

//* 인쇄도수가 선택된 경우 인쇄방식 맵핑코드 검색
if (empty($fb["bef_print_tmpt"]) === false) {
    $param["cate_sortcode"] = $cate_sortcode;
    $param["tmpt"]          = $fb["bef_print_tmpt"];

    $print_rs = $dao->selectCatePrintMpcode($conn, $param);

    $mpcode_arr = $util->rs2arr($print_rs, "mpcode");
    $mpcode_arr = $dao->parameterArrayEscape($conn, $mpcode_arr);
    $print_mpcode = $util->arr2delimStr($mpcode_arr);

    unset($mpcode_arr);
    unset($print_rs);
    unset($param);

    $param["print_mpcode"] = $print_mpcode;
}

$param["cate_sortcode"] = $cate_sortcode;
$param["stan_mpcode"] = $fb["output_size"];

// 종이 맵핑코드 수 만큼 가격 검색
$paper_mpcode_arr_count = count($paper_mpcode_arr);

// 정보를 저장할 배열들
$amt_arr   = array(); // 수량 배열
$title_arr = array(); // 제목 배열
$price_arr = array(); // 가격 배열
$grade_arr = array(); // 등급 배열

if ($etprs_dvs === "new") {
    $grade_arr = array(
        1 => "판매가",
        2 => "10등급",
        3 => "9등급",
        0 => "8등급"
    );
} else {
    $grade_arr = array(
        1 => "판매가",
        2 => "7등급",
        3 => "6등급",
        4 => "5등급",
        5 => "4등급",
        6 => "3등급",
        7 => "2등급",
        0 => "1등급"
    );
}

//* 선택한 카테고리에 해당하는 사이즈, 인쇄 맵핑코드, 이름을 가져온다
//* 관련정보는 있되 가격이 없는 부분이 있는 것을 생성하기 위함
$info_rs = $dao->selectCatePriceInfoListExcel($conn, $param);
unset($param);

// 관련정보가 존재하지 않는경우 종료
if ($info_rs->EOF === true) {
    $conn->Close();
    echo "NOT_INFO";
    exit;
}

// 회원등급별 할인 정보 검색
$param["cate_sortcode"] = $cate_sortcode;
$param["etprs_dvs"]     = $etprs_dvs;
$grade_sale_arr = array();
$grade_sale_rs = $dao->selectMemberGradeRangeSale($conn, $param);

while ($grade_sale_rs && !$grade_sale_rs->EOF) {
    $fields = $grade_sale_rs->fields;

    $grade_sale_arr[$fields["grade"]] = doubleval($fields["rate"]);

    $grade_sale_rs->MoveNext();
}
unset($grade_sale_rs);

// 카테고리명|종이정보|사이즈!사이즈유형|페이지 정보|전면인쇄도수|전면추가인쇄도수|후면인쇄도수|후면추가인쇄도수|수량단위
$title_form = "%s|%s!%s!%s!%s!%s|%s!%s|%s!%sp!%s|%s|-|-|-|%s";

//* 검색한 맵핑코드와 종이 맵핑코드를 결함해서 가격 검색
for ($i = 0; $i < $paper_mpcode_arr_count; $i++) {
    $paper_mpcode = $paper_mpcode_arr[$i];
    $paper_info = $paper_info_arr[$i];

    while ($info_rs && !$info_rs->EOF) {
        $flattyp_yn = $info_rs->fields["flattyp_yn"];
        $cate_name  = $info_rs->fields["cate_name"];
        $stan_name  = $info_rs->fields["stan_name"];
        $stan_typ   = $info_rs->fields["stan_typ"];
        $print_tmpt = $info_rs->fields["print_tmpt"];

        $stan_mpcode  = $info_rs->fields["stan_mpcode"];
        $print_mpcode = $info_rs->fields["print_mpcode"];


        if (empty($paper_info["affil"]) === true) {
            $paper_info["affil"] = '-';
        }

        $sheet_name = $paper_info["name"];

        $param["paper_mpcode"]     = $paper_mpcode;
        $param["stan_mpcode"]      = $stan_mpcode;
        $param["bef_print_mpcode"] = $print_mpcode;

        $price_rs = null;

        if ($mono_yn === 0) {
            $price_rs = $dao->selectCatePriceListExcel($conn, $table_name, $param);
        } else {
            $param["affil"] = $paper_info["affil"];
            $price_rs = $dao->selectCateCalcPriceListExcel($conn, $table_name, $param);
        }

        if ($price_rs->EOF === true) {
            // 계산형 가격 테이블은 폼 생성 안하고 건너뜀
            if ($mono_yn === 1) {
                $info_rs->MoveNext();
                continue;
            }

            // 합판 가격 테이블에 가격이 존재하지 않을 경우
            // 입력할 수 있도록 폼을 생성
            $page_dvs = "내지";
            $page     = '4';
            if ($flattyp_yn === 'Y') {
                $page_dvs = "표지";
                $page     = '2';
            }

            $price = "|||";
            if ($etprs_dvs === "exist") {
                $price = "|||||||";
            }

            $title = sprintf($title_form, $cate_name
                                        , $paper_info["affil"]
                                        , $paper_info["name"]
                                        , $paper_info["dvs"]
                                        , $paper_info["color"]
                                        , $paper_info["basisweight"]
                                        , $stan_name
                                        , $stan_typ
                                        , $page_dvs
                                        , $page
                                        , "페이지_상세"
                                        , $print_tmpt
                                        , $amt_unit);

            $title_arr[$sheet_name][$title] = $title;
            $amt_arr[''] = '';
            $price_arr[$title][''] = $price;

            $info_rs->MoveNext();
            continue;
        }

        $temp = array("paper_info" => $paper_info,
                      "cate_name"  => $cate_name,
                      "stan_name"  => $stan_name,
                      "stan_typ"   => $stan_typ,
                      "sheet_name" => $sheet_name,
                      "print_tmpt" => $print_tmpt,
                      "etprs_dvs"  => $etprs_dvs,
                      "amt_unit"   => $amt_unit,
                      "util"       => $util);


        if ($mono_yn === 0) {
            makePlyInfoArr($price_rs,
                           $amt_arr,
                           $title_arr,
                           $price_arr,
                           $grade_sale_arr,
                           $temp);
        } else {
            /*
            makeCalcInfoArr($price_rs,
                            $amt_arr,
                            $title_arr,
                            $price_arr,
                            $temp);
                            */
        }

        $info_rs->MoveNext();
    }

    $info_rs->MoveFirst();
}


//* 생성된 정보배열 인덱스 정수로 바꿔서 정렬
// 수량배열 정렬
$amt_arr = $util->sortDvsArr($amt_arr);

// 제목배열, 가격배열 정렬
$title_arr_sort = array();
$price_arr_sort = array();

foreach ($title_arr as $sheet_name => $title_arr_temp) {
    $price_arr_temp = $price_arr[$sheet_name];

	$i = 0;
    foreach ($title_arr_temp as $key => $val) {
        $title_arr_sort[$sheet_name][$i] = $val;
        $price_arr_sort[$sheet_name][$i++] = $price_arr_temp[$key];
    }
}

unset($title_arr);
unset($price_arr);

$info_dvs_arr = array(1  => "카테고리",
                      2  => "종이",
                      3  => "사이즈",
                      4  => "페이지",
                      5  => "전면도수",
                      6  => "전면추가도수",
                      7  => "후면도수",
                      8  => "후면추가도수",
                      9  => "수량단위",
                      10 => "등급",
                      11 => "수량");

if ($mono_yn === 0) {
    if ($etprs_dvs === "new") {
        $price_dvs_arr = array(0 => '-' . $grade_sale_arr['8'],
                               1 => "신규업체\n정상판매가",
                               2 => '-' . $grade_sale_arr['10'],
                               3 => '-' . $grade_sale_arr['9']);
    } else {
        $price_dvs_arr = array(0 => '-' . $grade_sale_arr["1"],
                               1 => "기존업체\n정상판매가",
                               2 => '-' . $grade_sale_arr['7'],
                               3 => '-' . $grade_sale_arr['6'],
                               4 => '-' . $grade_sale_arr['5'],
                               5 => '-' . $grade_sale_arr['4'],
                               6 => '-' . $grade_sale_arr['3'],
                               7 => '-' . $grade_sale_arr['2']);
                               
    }
} else {
    /*
    $price_dvs_arr = array(0 => "판매가격",
                           1 => "종이가격",
                           2 => "인쇄가격",
                           3 => "출력가격");
                           */
}

$excelUtil->initExcelFileWriteInfo((count($info_dvs_arr) - 1),
                                   count($price_dvs_arr),
                                   1);

foreach ($title_arr_sort as $sheet_name => $title_arr) {
    $excelUtil->makeGradeSalePriceExcelSheet($sheet_name,
                                             $info_dvs_arr,
                                             $title_arr,
                                             $price_dvs_arr,
                                             $amt_arr,
                                             $price_arr_sort[$sheet_name],
                                             $grade_arr,
                                             $etprs_dvs,
                                             $mono_yn);
}

$file_name = uniqid();

$file_path = $excelUtil->createExcelFile($file_name);

if (is_file($file_path)) {
    echo "grade_sale_price_list!" . $file_name;
} else {
    echo "FALSE";
}

$conn->Close();
exit;

NOT_PRICE:
    $conn->Close();
    echo "NOT_PRICE";
    exit;

/******************************************************************************
                            함수 영역
 *****************************************************************************/

/**
 * @brief 가격정보 검색결과를 확정형 가격에 맞춰서 정보배열 생성
 *
 * @param &$rs             = 검색결과
 * @param &$amt_arr        = 수량 배열
 * @param &$title_arr      = 제목 배열
 * @param &$price_arr      = 가격 배열
 * @param &$grade_sale_arr = 회원 할인율 배열
 * @param $param           = $title 생성용 파라미터, 
 *                           종이 정보 배열, 엑셀 시트명, 인쇄도수, 수량단위
 *
 * @return 가공된 배열
 */
function makePlyInfoArr(&$rs,
                        &$amt_arr,
                        &$title_arr,
                        &$price_arr,
                        &$grade_sale_arr,
                        $param) {
    // 각 정보항목 폼
    // 카테고리명|종이정보|사이즈!사이즈유형|페이지 정보|전면인쇄도수|전면추가인쇄도수|후면인쇄도수|후면추가인쇄도수|수량단위
    $title_form = "%s|%s!%s!%s!%s!%s|%s!%s|%s!%sp!%s|%s|-|-|-|%s";

    $util = $param["util"];

    $paper_info = $param["paper_info"];
    $cate_name  = $param["cate_name"];
    $stan_name  = $param["stan_name"];
    $stan_typ   = $param["stan_typ"];
    $sheet_name = $param["sheet_name"];
    $print_tmpt = $param["print_tmpt"];
    $amt_unit   = $param["amt_unit"];
    $etprs_dvs  = $param["etprs_dvs"];

    if ($etprs_dvs === "new") {
        $price_form = "%s|%s|%s|%s";
    } else {
        $price_form = "%s|%s|%s|%s|%s|%s|%s|%s";
    }

    while ($rs && !$rs->EOF) {
        $amt         = $rs->fields["amt"];
        $new_price   = round(doubleval($rs->fields["new_price"]) / 1.1);

        $page        = $rs->fields["page"];
        $page_dvs    = $rs->fields["page_dvs"];
        $page_detail = $rs->fields["page_detail"];

        if (empty($page_detail) === true) {
            $page_detail = '-';
        }


        $title = sprintf($title_form, $cate_name
                                    , $paper_info["affil"]
                                    , $paper_info["name"]
                                    , $paper_info["dvs"]
                                    , $paper_info["color"]
                                    , $paper_info["basisweight"]
                                    , $stan_name
                                    , $stan_typ
                                    , $page_dvs
                                    , $page
                                    , $page_detail
                                    , $print_tmpt
                                    , $amt_unit);

        if ($etprs_dvs === "new") {
            $grade_10 = $util->calcPrice($grade_sale_arr[10], $new_price);
            $grade_9  = $util->calcPrice($grade_sale_arr[9] , $new_price);
            $grade_8  = $util->calcPrice($grade_sale_arr[8] , $new_price);

            $price = sprintf($price_form, $new_price
                                        , $new_price + $grade_10
                                        , $new_price + $grade_9
                                        , $new_price + $grade_8);
        } else {
            $grade_7 = $util->calcPrice($grade_sale_arr[7], $new_price);
            $grade_6 = $util->calcPrice($grade_sale_arr[6], $new_price);
            $grade_5 = $util->calcPrice($grade_sale_arr[5], $new_price);
            $grade_4 = $util->calcPrice($grade_sale_arr[4], $new_price);
            $grade_3 = $util->calcPrice($grade_sale_arr[3], $new_price);
            $grade_2 = $util->calcPrice($grade_sale_arr[2], $new_price);
            $grade_1 = $util->calcPrice($grade_sale_arr[1], $new_price);

            $price = sprintf($price_form, $new_price
                                        , $new_price + $grade_7
                                        , $new_price + $grade_6
                                        , $new_price + $grade_5
                                        , $new_price + $grade_4
                                        , $new_price + $grade_3
                                        , $new_price + $grade_2
                                        , $new_price + $grade_1);
        }

        $title_arr[$sheet_name][$title] = $title;
        $amt_arr[$amt] = $amt;
        $price_arr[$sheet_name][$title][$amt] = $price;

        $rs->MoveNext();
    }
}

/**
 * @brief 가격정보 검색결과를 계산형 가격에 맞춰서 정보배열 생성
 *
 * @param &$rs = 검색결과
 * @param &$amt_arr   = 수량 배열
 * @param &$price_arr = 가격 배열
 * @param $param      = $title 생성용 파라미터, 
 *                      종이 정보 배열, 엑셀 시트명, 인쇄도수, 수량단위
 *
 * @return 가공된 배열
function makeCalcInfoArr(&$rs,
                         &$amt_arr,
                         &$title_arr,
                         &$price_arr,
                         $param) {
    // 각 정보항목 폼
    // 카테고리명|종이정보|사이즈!사이즈유형|페이지 정보|전면인쇄도수|전면추가인쇄도수|후면인쇄도수|후면추가인쇄도수|수량단위
    $title_form = "%s|%s!%s!%s!%s!%s|%s!%s|%s!%sp!%s|%s|-|-|-|%s";
    // 종이가격|인쇄가격|출력금액|판매가격
    $price_form = "%s|%s|%s|%s";

    $paper_info = $param["paper_info"];
    $cate_name  = $param["cate_name"];
    $stan_name  = $param["stan_name"];
    $stan_typ   = $param["stan_typ"];
    $sheet_name = $param["sheet_name"];
    $print_tmpt = $param["print_tmpt"];
    $amt_unit   = $param["amt_unit"];

    while ($rs && !$rs->EOF) {
        $amt          = $rs->fields["amt"];
        $paper_price  = $rs->fields["paper_price"];
        $print_price  = $rs->fields["print_price"];
        $output_price = $rs->fields["output_price"];
        $sum_price    = $rs->fields["sum_price"];

        $page        = $rs->fields["page"];
        $page_dvs    = $rs->fields["page_dvs"];
        $page_detail = $rs->fields["page_detail"];


        if (empty($page_detail) === true) {
            $page_detail = '-';
        }

        $title = sprintf($title_form, $cate_name
                                    , $paper_info["affil"]
                                    , $paper_info["name"]
                                    , $paper_info["dvs"]
                                    , $paper_info["color"]
                                    , $paper_info["basisweight"]
                                    , $stan_name
                                    , $stan_typ
                                    , $page_dvs
                                    , $page
                                    , $page_detail
                                    , $print_tmpt
                                    , $amt_unit);

        $price = sprintf($price_form, $paper_price
                                    , $print_price
                                    , $output_price
                                    , $sum_price);

        $title_arr[$sheet_name][$title] = $title;
        $amt_arr[$amt] = $amt;
        $price_arr[$sheet_name][$title][$amt] = $price;

        $rs->MoveNext();
    }
}
 */
?>
