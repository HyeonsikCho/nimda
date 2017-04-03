<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CommonDAO();

$search_cnd = $fb->form("search_cnd");
$search_txt = $fb->form("search_txt");
$sell_site = $fb->form("sell_site");
$order_state = $fb->form("order_state");
$order_state_in = "";
$seqno_dvs = "";

if (!$sell_site) {
    $sell_site = $fb->session("sell_site");
}

$param = array();
$param["search_cnd"] = $search_cnd;
$param["search_txt"] = $search_txt;
$param["sell_site"] = $sell_site;
if ($order_state == "접수") {
    $order_state_in = "(310, 320, 330, 340, 410)";
} else if ($order_state == "조판") {
    $order_state_in = "(410)";
}
$param["order_state"] = $order_state_in;

$arr = array();
$arr["opt"] = $search_cnd;
$arr["func"] = "cnd";

if ($search_cnd == "order_num") {
    $rs = $dao->selectCndOrder($conn, $param);
    $seqno_dvs = "order_seqno";
    $arr["opt_val"] = "order_common_seqno";
} else {
    $rs = $dao->selectCndMember($conn, $param);
    $seqno_dvs = "member_seqno";
    $arr["opt_val"] = "member_seqno";
}

echo makeSearchListSub($rs, $arr);
$conn->Close();
?>
