<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");


$fb = new FormBean();
$year = $fb->form("year");
$mon = $fb->form("mon");
$day = 1;

while(checkdate($mon, $day, $year)) {

    $day++;
}

$day = $day -1;


echo "달을찍어보자" . $day . "==";




?>
