<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");

$fb = new FormBean();

$affil = $fb->form("affil");
$subpaper = $fb->form("subpaper");

//46계열 일 경우
if ($affil == "46") {
   echo TYPE_46_SIZE[$subpaper]["WID"] . "♪" . TYPE_46_SIZE[$subpaper]["VERT"];
//국계열 일 경우
} else if ($affil == "국") {
   echo TYPE_GUK_SIZE[$subpaper]["WID"] . "♪" . TYPE_GUK_SIZE[$subpaper]["VERT"];
}
?>
