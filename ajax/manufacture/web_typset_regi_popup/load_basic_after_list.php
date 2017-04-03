<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$typset_num = $fb->form("typset_num");

$param = array();
$param["table"] = "basic_after_op";
$param["col"] = "basic_after_op_seqno, after_name,
    depth1, depth2, depth3, amt, amt_unit, memo, 
    extnl_brand_seqno";
$param["where"]["typset_num"] = $typset_num;

$rs = $dao->selectData($conn, $param);
$list = makeAfterOpListHtml($conn, $dao, $rs);

echo $list;
$conn->close();
?>
