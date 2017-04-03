<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/dataproc_mng/set/PaperInfoMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperInfoMngDAO();

$fb = $fb->getForm();

$dvs = $fb["dvs"];
$paper_name = $fb["paper_name"];
$paper_dvs  = $fb["paper_dvs"];

$param = array();
$param["name"] = $paper_name;
$param["dvs"]  = $paper_dvs;

$html = $dao->selectPrdtPaperInfo($conn, $dvs, $param);

echo $html;
?>
