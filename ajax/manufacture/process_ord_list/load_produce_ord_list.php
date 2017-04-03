<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/ProcessOrdListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ProcessOrdListDAO();

$param = array();
$param["ord_dvs"] = $fb->form("ord_dvs");
$param["date"] = $fb->form("date");

$html = makeProduceOrd($conn, $dao, $param);

$html2 = makeTotalList($conn, $dao, $param);

echo $html . $html2;
$conn->close();
?>
