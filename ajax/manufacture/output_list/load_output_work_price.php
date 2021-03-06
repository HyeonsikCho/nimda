<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/output_mng/OutputListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new OutputListDAO();

$param = array();
$param["table"] = "output";
$param["col"] = "basic_price";
$param["where"]["extnl_brand_seqno"] = $fb->form("extnl_brand_seqno");
$param["where"]["search_check"] = $fb->form("output_name") . "|" . $fb->form("board") . "|" . $fb->form("size");

$rs = $dao->selectData($conn, $param);

$basic_price = $rs->fields["basic_price"];
$price = number_format(intVal($basic_price) * intVal($fb->form("amt")));

echo $price;
$conn->close();
?>
