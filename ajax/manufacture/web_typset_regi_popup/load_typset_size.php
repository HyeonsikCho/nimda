<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");

$fb = new FormBean();

$affil = $fb->form("affil");
$subpaper = $fb->form("subpaper");

if ($subpaper == "전절") {
    $subpaper = 1;
} else {
    $subpaper = str_replace("절", "", $subpaper);
}

if ($affil == "46") {
    $wid_size = TYPE_46_SIZE[$subpaper]["WID"];
    $vert_size = TYPE_46_SIZE[$subpaper]["VERT"];
} else if ($affil == "국") {
    $wid_size = TYPE_GUK_SIZE[$subpaper]["WID"];
    $vert_size = TYPE_GUK_SIZE[$subpaper]["VERT"];
} else {
    $wid_size = "";
    $vert_size = "";
}

echo $wid_size . "♪" . $vert_size;
?>
