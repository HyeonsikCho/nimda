<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/produce/paper_stock_mng/PaperStockMngDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperStockMngDAO();

$param = array();
$param["seq"] = $fb->form("seq");
$rs = $dao->selectPaperStockMngDetailView($conn, $param);

$ret = array();
$ret["regi_date"] = $rs->fields["modi_date"];
$ret["manu"] = $rs->fields["manu"];
$ret["paper_name"] = $rs->fields["paper_name"];
$ret["paper_dvs"] = $rs->fields["paper_dvs"];
$ret["paper_color"] = $rs->fields["paper_color"];
$ret["paper_basisweight"] = $rs->fields["paper_basisweight"];
$ret["realstock_amt"] = $rs->fields["realstock_amt"];
$ret["adjust_reason"] = $rs->fields["adjust_reason"];

echo json_encode($ret);
$conn->close();
?>
