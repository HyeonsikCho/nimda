<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/dataproc_mng/set/PaperInfoMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_config.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperInfoMngDAO();

$fb = $fb->getForm();

$paper_name  = $fb["paper_name"];
$paper_dvs   = $fb["paper_dvs"];
$paper_color = $fb["paper_color"];

$param = array();
$param["name"]  = $paper_name;
$param["dvs"]   = $paper_dvs;
$param["color"] = $paper_color;

$rs = $dao->selectPaperPreviewInfo($conn, $param);

$json = "{\"seqno\" : \"%s\", \"path\" : \"%s\", \"name\" : \"%s\"}";

if ($rs->EOF) {
    $json = sprintf($json, '', NO_IMAGE, '');
} else{
    $rs = $rs->fields;

    $json = sprintf($json, $rs["paper_preview_seqno"]
                         , $rs["file_path"] . DIRECTORY_SEPARATOR . $rs["save_file_name"]
                         , $rs["origin_file_name"]);
}

echo $json;
?>
