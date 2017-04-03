<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$param = array();
$param["output_seqno"] = $fb->form("seqno");

$rs = $dao->selectOutputInfoApply($conn, $param);

echo $rs->fields["output_name"] . "♪" . 
$rs->fields["manu_name"] . "♪" .
$rs->fields["affil"] . "♪" .
$rs->fields["wid_size"] . "♪" .
$rs->fields["vert_size"] . "♪" .
$rs->fields["board"] . "♪" .
$rs->fields["extnl_brand_seqno"] . "♪" .
$rs->fields["output_seqno"];

$conn->close();
?>
