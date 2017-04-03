<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperOrdPrintDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperOrdPrintDAO();
$state_arr = $fb->session("state_arr");

$state = $state_arr["종이발주완료"];

$date = $fb->form("date");

if ($date) {
    $from = $date . " 00:00:00";
}

if ($date) {
    $to =  $date . " 23:59:59";
}

$param = array();

$param = array();
$param["state"] = $state;
$param["from"] = $from;
$param["to"] = $to;
$param["extnl_etprs_seqno"] = $fb->form("extnl_etprs_seqno");

$rs = $dao->selectPaperOpMngPrintList($conn, $param);

$list1 = makeTotalOrd($rs);

$rs->moveFirst();
$list2 = makePaperOrd($rs);

echo $list1 . $list2;
$conn->close();
?>
