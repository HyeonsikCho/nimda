<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/basic_mng/pur_etprs_mng/PurEtprsListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$purDAO = new PurEtprsListDAO();

$conn->StartTrans();

$check = 1;
$etprs_seqno = $fb->form("etprs_seqno");
$brand_seqno = $fb->form("brand_seqno");

$param = array();
$param["table"] = "extnl_brand";
$param["col"]["name"] = $fb->form("name");
$param["col"]["extnl_etprs_seqno"] = $etprs_seqno;

if ($brand_seqno) {
    $param["prk"] = "extnl_brand_seqno";
    $param["prkVal"] = $brand_seqno;
    $result = $purDAO->updateData($conn, $param);
} else {
    $result = $purDAO->insertData($conn, $param);
}

if (!$result) {
    $check = 0;
}

echo $check;
$conn->CompleteTrans();
$conn->close();
?>
