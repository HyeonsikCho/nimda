<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/basic_mng/pur_etprs_mng/PurEtprsListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$purDAO = new PurEtprsListDAO();

$param = array();
$param["table"] = "extnl_brand";
$param["col"] = "name, extnl_brand_seqno";
$param["where"]["extnl_etprs_seqno"] = $fb->form("etprs_seqno");

$result = $purDAO->selectData($conn, $param);

echo makeBrandList($result);
$conn->close();
?>
