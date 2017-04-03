<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$param = array();
$param["after_seqno"] = $fb->form("seqno");

$rs = $dao->selectAfterInfoApply($conn, $param);

echo $rs->fields["after_name"] . "♪" . 
$rs->fields["manu_name"] . "♪" .
$rs->fields["extnl_brand_seqno"] . "♪" .
$rs->fields["depth1"] . "♪" .
$rs->fields["depth2"] . "♪" .
$rs->fields["depth3"] . "♪" .
$rs->fields["after_seqno"];

$conn->close();
?>
