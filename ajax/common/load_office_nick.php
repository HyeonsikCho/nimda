<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CommonDAO();

$sell_site   = $fb->form("sell_site");
$office_nick = $fb->form("search_val");

/*
if (!$sell_site) {
    $sell_site = $fb->session("sell_site");
}
*/

$param = array();
$param["sell_site"]   = $sell_site;
$param["office_nick"] = $office_nick;

//$conn->debug = 1;
$rs = $dao->selectOfficeName($conn, $param);

$arr = array();
$arr["opt"]  = "office_nick";
$arr["opt_val"]  = "member_seqno";
//$arr["view_name"]  = "full_name";
$arr["func"] = "name";

echo makeSearchListSub($rs, $arr);
//echo makeSearchListConcat($rs, $arr);
$conn->Close();
?>
