<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/mkt/mkt_mng/CpMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$cpDAO = new CpMngDAO();

//등록 or 수정
$type = $fb->form("type");

//cp_cate_sortcode 초기화
$cp_cate_sortcode = "";

//카테고리 중분류 쿼리 실행
$param = array();
$param["table"] = "cate";
$param["col"] = "cate_name, sortcode";
$param["where"]["cate_level"] = "2";

$result = $cpDAO->selectData($conn, $param);
//카테고리 중분류 체크박스 셋팅
$cate_mid = makeCateMidList($result);

$amt_dspl = "style=\"display:none\"";

$param = array();
$param["submit"] = "등록";

//수정모드 일때
if ($type == "edit") {
    $param["submit"] = "수정";

    //쿠폰 일련번호
    $cp_seqno = $fb->form("cp_seqno");

    //파일 셋팅
    $f_param = array();
    $f_param["table"] = "cp_file";
    $f_param["col"] = "cp_file_seqno, origin_file_name, file_path, cp_file_seqno";
    $f_param["where"]["cp_seqno"] = $cp_seqno;

    $f_result = $cpDAO->selectData($conn, $f_param);
    $f_param["file_seqno"] = $f_result->fields["cp_file_seqno"];
    $f_param["file_name"] = $f_result->fields["origin_file_name"];
    $f_param["file_seqno"] = $f_result->fields["cp_file_seqno"];

    if ($f_param["file_name"]) {
        $file_html = getFileHtml($f_param);
    }

    $cp_param = array();
    $cp_param["cp_seqno"] = $cp_seqno;

    //쿠폰 정보 가져오기
    $result = $cpDAO->selectCpList($conn, $cp_param);

    $param["cp_seqno"] = $cp_seqno;
    $param["file_html"] = $file_html;
    $param["cp_name"] = $result->fields["cp_name"];

    $object_yn = $result->fields["object_appoint_way"];
    
    if ($object_yn == "Y") {
    //사용중일때
        $param["object_y"] = "checked";
        $param["object_n"] = "";
        $param["cp_expo_disabled"] = "disabled='disabled'";
    } else {
    //미사용일때
        $param["object_n"] = "checked";
        $param["object_y"] = "";
        $param["cp_expo_disabled"] = "";

        $param["public_amt"] = $result->fields["public_amt"];
        $amt_dspl = "";
    }

    $unit = $result->fields["unit"];
    
    if ($unit == "%") {
    //할인 요율 일때
        $per_check="checked";
        $won_check="";

        $param["per_val"] = $result->fields["val"];
        $param["max_sale_price"] = $result->fields["max_sale_price"];

    } else {
    //할인 금액 일때
        $won_check="checked";
        $per_check="";

        $param["won_val"] = $result->fields["val"];
        $param["min_order_price"] = $result->fields["min_order_price"];

    }
    $param["won_check"] = $won_check;
    $param["per_check"] = $per_check;


    //발행일, 만료일 값
    $public_period_start_date = $result->fields["public_period_start_date"];
    $public_period_end_date= $result->fields["public_period_end_date"];
    $usehour_yn = $result->fields["usehour_yn"];
    $usehour_start_hour = $result->fields["usehour_start_hour"];
    $usehour_end_hour = $result->fields["usehour_end_hour"];
    $expire_dvs = $result->fields["expire_dvs"];
    $expire_extinct_date = $result->fields["expire_extinct_date"];
    $expire_public_day = $result->fields["expire_public_day"];
    
    //발행일
    $param["public_period_start_date"] = $public_period_start_date;
    $param["public_period_end_date"] = $public_period_end_date;
        
    //시간제 기본값 셋팅
    $start_hour = "00";
    $start_min = "00";
    $end_hour = "00";
    $end_min = "00";
    $usehour_yn_check = "";
    if ($usehour_yn === "Y") {
        //시간제
        $param["usehour_yn_check"] = "checked";
        $start_hour = substr($usehour_start_hour, 0, 2);
        $start_min = substr($usehour_start_hour, -2);
        $end_hour = substr($usehour_end_hour, 0, 2);
        $end_min = substr($usehour_end_hour, -2);
    } 

    /**
     * 만료 구분 확인
     *  만료구분 1 : 발행후
     *  만료구분 2 : 날짜지정
     *  만료구분 3 : 만료일없음
     */
    //만료일 구분
    $param["expire_dvs"] = $expire_dvs;

    if ($expire_dvs == 1) {
        $param["expire_public_day"] = $expire_public_day;
        $param["expire_dvs_1"] = "checked";
    } else if ($expire_dvs == 2) {
        $param["expire_extinct_date"] = $expire_extinct_date;
        $param["expire_dvs_2"] = "checked";
    } else if ($expire_dvs == 3) {
        $param["expire_dvs_3"] = "checked";
    
    }

    $cp_expo_yn = $result->fields["cp_expo_yn"];

    //사용중일때
    if ($cp_expo_yn == "Y") {
        $param["cp_expo_y"] = "checked";
        $param["cp_expo_n"] = "";
    } else {
    //미사용일때
        $param["cp_expo_n"] = "checked";
        $param["cp_expo_y"] = "";
    }

    //선택된 대상상품 세팅
    $cateParam = array();
    $cateParam ["table"] = "cp_cate";
    $cateParam ["col"] = "cp_cate_sortcode";
    $cateParam ["where"]["cp_seqno"] = $cp_seqno;

    $cateResult = $cpDAO->selectData($conn, $cateParam);

    while ($cateResult && !$cateResult->EOF) {
        //sortcode 구분자 => :!:
        $cp_cate_sortcode .= ":!:";
        $cp_cate_sortcode .= $cateResult->fields["cp_cate_sortcode"];
        $cateResult->moveNext();
    }
}
//선택된 대상상품 구분자 제거
$cp_cate_sortcode = substr($cp_cate_sortcode, 3);

$param["amt_dspl"] = $amt_dspl;
//수량 html
$param["amt_html"] = getAmtHtml($param);
//카테고리 중분류
$param["cate_mid"] = $cate_mid;
//판매채널 검색
$param["sell_site"] = $cpDAO->selectSellSite($conn);
//시간 콤보박스 셋팅
$param["hour_list"] = makeOptionTimeHtml(0,23);
//분 콤보박스 셋팅
$param["min_list"] = makeOptionTimeHtml(0,59);

$html = getCpView($param);

$select_box_val = $result->fields["cpn_admin_seqno"] . "♪♡♭" . 
                  $cp_cate_sortcode . "♪♡♭" . 
                  $start_hour . "♪♡♭" . $start_min . "♪♡♭" . 
                  $end_hour . "♪♡♭" . $end_min . "♪♡♭";

echo $html . "♪♥♭" . $select_box_val;

$conn->close();
?>
