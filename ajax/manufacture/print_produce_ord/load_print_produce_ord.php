<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/PrintProduceOrdDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PrintProduceOrdDAO();

$date = $fb->form("date");

$from = $date . " 00:00:00";
$to = $date . " 23:59:59";

$param = array();
$param["from"] = $from;
$param["to"] = $to;
$param["extnl_etprs_seqno"] = $fb->form("extnl_etprs_seqno");

$rs = $dao->selectPrintProduceOrd($conn, $param);
echo makePrintProduceOrd($rs);
$conn->close();
?>
