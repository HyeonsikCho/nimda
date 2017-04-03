<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");

$fb = new FormBean();

$affil = $fb->form("affil");
$subpaper = str_replace("절", "", $fb->form("subpaper"));

if ($subpaper == "전") {
    $subpaper = 1;
}

if ($affil == "국") {
    $wid = TYPE_GUK_SIZE[$subpaper]["WID"];
    $vert = TYPE_GUK_SIZE[$subpaper]["VERT"];
} else if($affil == "46") {
    $wid = TYPE_46_SIZE[$subpaper]["WID"];
    $vert = TYPE_46_SIZE[$subpaper]["VERT"];
}

echo $wid . "*" . $vert;
?>
