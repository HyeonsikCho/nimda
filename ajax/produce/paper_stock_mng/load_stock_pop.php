<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/produce/paper_stock_mng/PaperStockMngDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/doc/produce/paper_stock_mng/PaperStockMngDOC.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperStockMngDAO();

$today = date("Y.m.d");

$param = array();
$param["today"] = $today;
$param["paper_name"] = $fb->form("paperName");
$param["paper_dvs"] = $fb->form("paperDvs");
$param["paper_color"] = $fb->form("paperColor");
$param["paper_basisweight"] = $fb->form("paperBasisweight");
$param["manu"] = $fb->form("manu");

$param["save"] = "<button type=\"button\" class=\"btn btn-sm btn-success\" onclick=\"regiPaperStockDetail();\">저장</button>";

$list = makeStockMngPop($param);

echo $list;
$conn->close();
?>
