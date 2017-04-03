<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/calcul_mng/tab/PurTabListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PurTabListDAO();

$sell_site   = $fb->form("sell_site");
$manu_name = $fb->form("search_val");

if (!$sell_site) {
    $sell_site = $fb->session("sell_site");
}

$param = array();
$param["sell_site"]   = $sell_site;
$param["manu_name"] = $manu_name;

$rs = $dao->selectEtprsInfo($conn, $param);

echo makeSearchManuList($rs);
$conn->Close();
?>
