<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$after_name = $fb->form("after_name");
$depth1 = $fb->form("after_depth1");
$depth2 = $fb->form("after_depth2");
$depth3 = $fb->form("after_depth3");

if (!$depth1) {
    $depth1 = "-";
}
if (!$depth2) {
    $depth2 = "-";
}
if (!$depth3) {
    $depth3 = "-";
}

//출력 정보리스트
$param = array();
$param["extnl_etprs_seqno"] = $fb->form("extnl_etprs_seqno");
$param["sorting"] = $fb->form("sorting");
$param["sorting_type"] = $fb->form("sorting_type");

if ($fb->form("after_name")) {
    $param["search_check"] = $after_name . "|". 
        $depth1 . "|". 
        $depth2 . "|". 
        $depth3;
}

$rs = $dao->selectAfterInfoList($conn, $param);
$list = makeAfterInfoListHtml($rs, $param);

echo $list;
$conn->close();
?>
