<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/ProcessMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ProcessMngDAO();

$param = array();
$param["order_detail_num"] = explode('|', $fb->form("order_detail_num"));
$param["state"] = 3220;
/*
foreach($arr_order_num as $order_num) {
    $param["order_detail_num"] .= "'" . $order_num . "',";
}

$param["order_detail_num"] = substr($param["order_detail_num"], 0, -1);
*/

$rs = $dao->updateOrderState($conn, $param);

if($rs != null) {
    echo "1";
} else {
    echo "0";
}

$conn->close();

?>