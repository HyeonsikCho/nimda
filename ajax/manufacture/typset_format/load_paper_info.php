<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetFormatDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetFormatDAO();

$el = $fb->form("el");
$seqno = $fb->form("seqno");

$param = array();
$param["table"] = "paper";
$param["col"] = "name, extnl_brand_seqno";
$param["where"]["paper_seqno"] = $seqno;

$rs = $dao->selectData($conn, $param);

$name = $rs->fields["name"];

$param = array();
$param["table"] = "extnl_brand";
$param["col"] = "extnl_etprs_seqno";
$param["where"]["extnl_brand_seqno"] = $rs->fields["extnl_brand_seqno"];

$rs = $dao->selectData($conn, $param);

$param = array();
$param["table"] = "extnl_etprs";
$param["col"] = "manu_name";
$param["where"]["extnl_etprs_seqno"] = $rs->fields["extnl_etprs_seqno"];

$rs = $dao->selectData($conn, $param);

$manu = $rs->fields["manu_name"];

echo $name . "♪" . $manu;
$conn->Close();
?>
