<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperOpMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperOpMngDAO();

$param = array();
$param["paper_seqno"] = $fb->form("seqno");

$rs = $dao->selectPaperInfoApply($conn, $param);

echo $rs->fields["name"] . "♪" . 
$rs->fields["dvs"] . "♪" .
$rs->fields["color"] . "♪" .
$rs->fields["basisweight"] . "♪" .
$rs->fields["basisweight_unit"] . "♪" .
$rs->fields["manu_name"] . "♪" .
$rs->fields["affil"] . "♪" .
$rs->fields["wid_size"] . "♪" .
$rs->fields["vert_size"] . "♪" .
$rs->fields["extnl_brand_seqno"] . "♪" .
$rs->fields["paper_seqno"];

$conn->close();
?>
