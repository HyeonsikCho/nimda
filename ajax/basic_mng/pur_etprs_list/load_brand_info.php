<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/basic_mng/pur_etprs_mng/PurEtprsListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PurEtprsListDAO();

$seqno = $fb->form("seqno");

$param = array();
$param["table"] = "extnl_brand";
$param["col"] = "name";
$param["where"]["extnl_brand_seqno"] = $seqno;

$rs = $dao->selectData($conn, $param);

echo $rs->fields["name"];
$conn->close();
?>
