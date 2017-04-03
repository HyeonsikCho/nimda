<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperStockMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperStockMngDAO();

$param = array();
$param["table"] = "extnl_etprs";
$param["col"] = "manu_name";
$param["where"]["extnl_etprs_seqno"] = $fb->form("manu");

$manu = $dao->selectData($conn, $param)->fields["manu_name"];

$param = array();
$param["paper_name"] = $fb->form("name");
$param["paper_dvs"] = $fb->form("dvs");
$param["paper_color"] = $fb->form("color");
$param["paper_basisweight"] = $fb->form("basisweight");
$param["manu"] = $manu;
$last_amt = $dao->selectLastStockAmt($conn, $param)->fields["stock_amt"];

if ($last_amt < 1 && $last_amt > 0) {
    echo number_format($last_amt, 1) . "R";
} else {
    echo number_format($last_amt) . "R";
} 

$conn->close();
?>
