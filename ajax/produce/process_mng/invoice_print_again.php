<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/classes/cjparcel/CJparcel.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");

$fb = new FormBean();
$param = array();

$param["order_detail_num"] = explode('|', $fb->form("order_num"));

$cParcel = new CJparcel();
$isSuccess = $cParcel->printAgain($fb->form("order_num"));

if($isSuccess == true) { // 성공
    echo 1;
} else {
    echo 0;
}

?>