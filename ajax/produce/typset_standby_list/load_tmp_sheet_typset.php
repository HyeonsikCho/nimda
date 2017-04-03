<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetStandbyListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetStandbyListDAO();

$param = array();
$param["dvs"] = "SEQ";

$seqno = $fb->form("seqno");
if ($seqno == null || $seqno == "") {
    $seqno = 0;
}
$param["amt_order_detail_sheet_seqno"] = $seqno;
//$param["order_detail_count_file_seqno"] = $seqno;

$rs = $dao->selectFlattYTypsetStandbyList($conn, $param);
$list = makeTmpTypsetStandbyListHtml($rs, $param);

echo $list;
$conn->close();
?>
