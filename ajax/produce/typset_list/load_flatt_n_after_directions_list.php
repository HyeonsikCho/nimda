<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$param = array();
$param["table"] = "after_op";
$param["col"] = "MAX(seq) AS maxseq";
$param["where"]["order_common_seqno"] = $fb->form("order_common_seqno");

$sel_rs = $dao->selectData($conn, $param);

//후공정 발주 리스트
$param = array();
$param["order_common_seqno"] = $fb->form("order_common_seqno");
$param["maxseq"] = $sel_rs->fields["maxseq"];
$rs = $dao->selectAfterDirectionsList($conn, $param);
$list = makeAfterOpListHtml($rs, $param);

echo $list;
$conn->close();
?>
