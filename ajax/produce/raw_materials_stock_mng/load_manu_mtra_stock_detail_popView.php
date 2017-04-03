<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/produce/paper_stock_mng/PaperStockMngDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/produce/raw_materials_stock_mng/RawMaterialStockMngDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new RawMaterialStockMngDAO();

$param = array();
$param["seq"] = $fb->form("seq");
$rs = $dao->selectMtraStockMngDetailView($conn, $param);

$ret = array();
$ret["regi_date"] = $rs->fields["modi_date"];
$ret["manu"] = $rs->fields["manu"];
$ret["name"] = $rs->fields["name"];
$ret["stock_amt"] = $rs->fields["stock_amt"];
$ret["realstock_amt"] = $rs->fields["realstock_amt"];
$ret["adjust_reason"] = $rs->fields["adjust_reason"];


echo json_encode($ret);
$conn->close();
?>
