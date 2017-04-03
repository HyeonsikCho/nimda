<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();
$check = 1;

$conn->StartTrans();

$param = array();
$param["table"] = "basic_after_op";
$param["col"]["after_name"] = $fb->form("after_name");
$param["col"]["depth1"] = $fb->form("depth1");
$param["col"]["depth2"] = $fb->form("depth2");
$param["col"]["depth3"] = $fb->form("depth3");
$param["col"]["amt"] = $fb->form("amt");
$param["col"]["memo"] = $fb->form("memo");
$param["col"]["extnl_brand_seqno"] = $fb->form("brand_seqno");
$param["prk"] = "basic_after_op_seqno";
$param["prkVal"] = $fb->form("basic_after_op_seqno");

$rs = $dao->updateData($conn, $param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
