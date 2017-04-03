<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/business/claim_mng/ClaimListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ClaimListDAO();

$conn->StartTrans();
$order_claim_seqno = $fb->form("seqno");

$param = array();
$param["table"] = "order_claim"; 
$param["col"] = "state";
$param["where"]["order_claim_seqno"] = $order_claim_seqno;

$rs = $dao->selectData($conn, $param);

$state = $rs->fields["state"];

if ($state == "처리중") {
    $param = array();
    $param["table"] = "order_claim"; 
    $param["col"]["empl_seqno"] = NULL;
    $param["col"]["state"] = "요청";
    $param["prk"] = "order_claim_seqno";
    $param["prkVal"] = $order_claim_seqno;

    $dao->updateData($conn, $param);
}

$conn->CompleteTrans();
$conn->Close();
?>
