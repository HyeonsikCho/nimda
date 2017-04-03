<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperOpMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperOpMngDAO();
$check = 1;

$conn->StartTrans();

$state_arr = $fb->session("state_arr");
$state = $state_arr["종이발주취소"];

$param = array();
$param["table"] = "paper_op";
$param["col"]["state"] = $state;
$param["prk"] = "paper_op_seqno";
$param["prkVal"] = $fb->form("paper_op_seqno");

$rs = $dao->updateData($conn, $param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->close();
echo $check;
?>
