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
$param["page_order_detail_brochure_seqno"] = $seqno;

$rs = $dao->selectFlattNTypsetStandbyList($conn, $param);
$list = makeBrochureTmpTypsetStandbyListHtml($rs, $param);

echo $list;
$conn->close();
?>
