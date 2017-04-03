<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/cooperator_mng/CooperatorListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CooperatorListDAO();
$util = new CommonUtil();

$order_detail_seqno = $fb->form("order_detail_seqno");
$order_common_seqno = $fb->form("order_common_seqno");
$invo_cpn = $fb->form("invo_cpn");
$invo_num = $fb->form("invo_num");

$check = 1;
$conn->StartTrans();

$param = array();
$param["table"] = "order_dlvr";
$param["col"]["order_common_seqno"] = $order_common_seqno;
$param["col"]["invo_cpn"] = $invo_cpn;
$param["col"]["invo_num"] = $invo_num;
$param["prk"] = "order_common_seqno";
$param["prkVal"] = $order_common_seqno;

$rs = $dao->updateData($conn, $param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->close();
echo $check; 
?>
