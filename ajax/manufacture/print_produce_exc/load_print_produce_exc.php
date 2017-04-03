<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/print_mng/PrintProduceExcDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PrintProduceExcDAO();

$date = $fb->form("date");

$from = $date . " 00:00:00";
$to = $date . " 23:59:59";

$param = array();
$param["from"] = $from;
$param["to"] = $to;
$param["extnl_etprs_seqno"] = $fb->form("extnl_etprs_seqno");

$rs = $dao->selectPrintProduceExc($conn, $param);
echo makePrintProduceExc($rs);
$conn->close();
?>
