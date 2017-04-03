<?
/* adodb 캐쉬저장경로 설정 */
$ADODB_CACHE_DIR = dirname(__FILE__) . '/cache';
define("ADODB_CACHE_DIR", $ADODB_CACHE_DIR);

/******************** 파일 저장 경로 ****************/
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_config.php");
?>
