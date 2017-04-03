<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/ProcessMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ProcessMngDAO();

$date = $fb->form("date");;
//$date = "2016-09-29";

$param = array();
//$param["ord_dvs"] = $fb->form("ord_dvs");
$param["date"] = $date;

$html = makeProduceOrd($conn, $dao, $param);

echo $html;
$conn->close();
?>
