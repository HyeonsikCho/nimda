<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/mkt/mkt_mng/CpMngDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/file/FileAttachDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$conn->StartTrans();

$fb = new FormBean();
$cpDAO = new CpMngDAO();
$fileDAO = new FileAttachDAO();

$check = 1;

$param = array();
$param["table"] = "cp";
//쿠폰명
$param["col"]["cp_name"] = $fb->form("pop_cp_name");
$param["col"]["use_yn"] = "Y";
//판매 사이트
$param["col"]["cpn_admin_seqno"] = $fb->form("pop_sell_site");
//할인 구분
$param["col"]["unit"] = $fb->form("sale_dvs");

//할인 구분 요율 일때
if ($fb->form("sale_dvs") == "%") {

    //값
    $param["col"]["val"] = $fb->form("per_val");
    //최대 할인 금액
    $param["col"]["max_sale_price"] = $fb->form("max_sale_price");


//할인 구분 원 일때
} else {

    //값
    $param["col"]["val"] = $fb->form("won_val");
    //최대 할인 금액
    $param["col"]["min_order_price"] = $fb->form("min_order_price");

}

//발행일
$param["col"]["public_period_start_date"] = $fb->form("public_period_start_date");
$param["col"]["public_period_end_date"] = $fb->form("public_period_end_date");

if ($fb->form("usehour_yn") == 'Y') {
    $param["col"]["usehour_yn"]  = 'Y';
    //시간제
    $param["col"]["usehour_start_hour"]  = $fb->form("start_hour");
    $param["col"]["usehour_start_hour"] .= $fb->form("start_min");
    $param["col"]["usehour_end_hour"]  = $fb->form("end_hour");
    $param["col"]["usehour_end_hour"] .= $fb->form("end_min");
} else {
    $param["col"]["usehour_yn"]  = 'N';
}

//만료일(발행후/날짜지정/만료일없음)
/*
 * 소멸날짜 계산
 * 1. 만료일에서 발행후 몇일을 선택했을경우 (종료날짜 + 발행일 날짜 = 소멸날짜)
 * 2. 만료일에서 소멸날짜를 선택했을경우 선택한 날이 소멸날짜가 됨
 * 3. 소멸날짜 없음
 */
$param["col"]["expire_dvs"] = $fb->form("expire_dvs");

if ($fb->form("expire_dvs") == 1) {
    //발행후
    $param["col"]["expire_public_day"] = $fb->form("expire_public_day");
    $param["col"]["cp_extinct_date"] = date("Y-m-d", strtotime($fb->form("public_period_end_date") ."+". $fb->form("expire_public_day") ."days"));
} else if ($fb->form("expire_dvs") == 2) {
    //날짜지정
    $param["col"]["expire_extinct_date"] = $fb->form("expire_extinct_date");
    $param["col"]["cp_extinct_date"] = $fb->form("expire_extinct_date");
} else if ($fb->form("expire_dvs") == 3) {
    //만료일없음
    $param["col"]["cp_extinct_date"] = "NULL";
}

//대상 지정 여부
$param["col"]["object_appoint_way"] = $fb->form("object_appoint");
//대상 미지정일때 발행매수 설정
if ($fb->form("object_appoint") == "N") {

    $param["col"]["public_amt"] = $fb->form("public_amt");

}
//쿠폰 노출여부
$param["col"]["cp_expo_yn"] = $fb->form("cp_expo_yn");

//카테고리 중분류
$cateArr = array();
$cateArr = $fb->form('cate_sortcode');

$cp_seqno = $fb->form("cp_seqno");

//쿠폰 수정
if (empty($cp_seqno) === false) {

    $param["prk"] = "cp_seqno";
    $param["prkVal"] = $cp_seqno;

    $result = $cpDAO->updateData($conn, $param);

    if (!$result) {

        $check = 0;
    }

    if ($fb->form("upload_file")) {

        //쿠폰 파일 삭제
        $param = array();
        $param["table"] = "cp_file";
        $param["prk"] = "cp_seqno";
        $param["prkVal"] = $cp_seqno;

        $result = $cpDAO->deleteData($conn, $param);
        if (!$result) {

            $check = 0;
        }

        //파일 업로드 경로
        $param =  array();
        $param["file_path"] = SITE_DEFAULT_CP_FILE;
        $param["tmp_name"] = $_FILES["upload_btn"]["tmp_name"];
        $param["origin_file_name"] = $_FILES["upload_btn"]["name"];

        //파일을 업로드 한 후 저장된 경로를 리턴한다.
        $result= $fileDAO->upLoadFile($param);

        //쿠폰 파일 추가
        $param = array();
        $param["table"] = "cp_file";
        $param["col"]["cp_seqno"] = $cp_seqno;
        $param["col"]["origin_file_name"] = $_FILES["upload_btn"]["name"];
        $param["col"]["save_file_name"] = $result["save_file_name"];
        $param["col"]["file_path"] = $result["file_path"];

        $result = $cpDAO->insertData($conn,$param);


        if (!$result) {
            $check = 0;
        }
    }

    $cp_cate_rs = $cpDAO->selectCpCate($conn, $cp_seqno);

    $chk_arr = array();

    while ($cp_cate_rs && !$cp_cate_rs->EOF) {
        $cp_cate_sortcode = $cp_cate_rs->fields["cp_cate_sortcode"];
        $chk_arr[$cp_cate_sortcode] = true;

        $cp_cate_rs->MoveNext();
    }

    // 쿠폰 카테고리 추가
    foreach ($cateArr as $cate_sortcode) { 
        if ($chk_arr[$cate_sortcode] === true) {
            continue;
        }

        $param = array();
        $param["table"] = "cp_cate";
        $param["col"]["cp_cate_sortcode"] = $cate_sortcode;
        $param["col"]["cp_seqno"] = $cp_seqno;

        $result = $cpDAO->insertData($conn, $param);

        if (!$result) {
            $check = 0;
        }
    }
} else {
    //쿠폰 추가

    $param["col"]["regi_date"] = date("Y-m-d H:i:s",time());
    $result = $cpDAO->insertData($conn, $param);
    $cp_seqno = $conn->insert_ID();

    //cp_cate 테이블에 값 Insert 
    foreach ($cateArr as $cate_sortcode) { 
        $param = array();
        $param["table"] = "cp_cate";
        $param["col"]["cp_cate_sortcode"] = $cate_sortcode;
        $param["col"]["cp_seqno"] = $cp_seqno;

        $result = $cpDAO->insertData($conn, $param);

        if (!$result) {
            $check = 0;
        }
    }

    if ($fb->form("upload_file")) {
        //파일 업로드 경로
        $param =  array();
        $param["file_path"] = SITE_DEFAULT_CP_FILE;
        $param["tmp_name"] = $_FILES["upload_btn"]["tmp_name"];
        $param["origin_file_name"] = $_FILES["upload_btn"]["name"];

        //파일을 업로드 한 후 저장된 경로를 리턴한다.
        $result= $fileDAO->upLoadFile($param);

        //쿠폰 파일 추가
        $param = array();
        $param["table"] = "cp_file";
        $param["col"]["origin_file_name"] = $_FILES["upload_btn"]["name"];
        $param["col"]["save_file_name"] = $result["save_file_name"];
        $param["col"]["file_path"] = $result["file_path"];
        $param["col"]["cp_seqno"] = $cp_seqno;

        $result = $cpDAO->insertData($conn, $param);

        if (!$result) {
            $check = 0;
        }
    }
}

if ($check == 1) {
    echo "1";
} else {
    echo "2";
}
$conn->CompleteTrans();
$conn->close();

?>

