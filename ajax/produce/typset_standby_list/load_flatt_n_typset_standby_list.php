<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetStandbyListDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetStandbyListDAO();

//한페이지에 출력할 게시물 갯수
$list_num = $fb->form("showPage"); 

//현재 페이지
$page = $fb->form("page");

//리스트 보여주는 갯수 설정
if (!$fb->form("showPage")) {
    $list_num = 30;
}

//블록 갯수
$scrnum = 5; 

// 페이지가 없으면 1 페이지
if (!$page) {
    $page = 1; 
}

$s_num = $list_num * ($page-1);

$from_date = $fb->form("date_from");
$from_time = "";
$to_date = $fb->form("date_to");
$to_time = "";

if ($from_date) {
    $from_time = $fb->form("time_from");
    $from = $from_date . " " . $from_time . ":00:00";
}

if ($to_date) {
    $to_time = " " . $fb->form("time_to") + 1;
    $to =  $to_date . " " . $to_time . ":59:59";
}

$param = array();
$param["s_num"] = $s_num;
$param["list_num"] = $list_num;
$param["cate_sortcode"] = $fb->form("cate_sortcode");
$param["depar_code"] = $fb->form("depar_code");
$param["order_state"] = $fb->form("order_state");
$param["search_cnd2"] = $fb->form("search_cnd2");
$param["sell_site"] = $fb->form("sell_site");
$param["from"] = $from;
$param["to"] = $to;
$param["dvs"] = "SEQ";

if ($fb->form("search_cnd") == "title") {
    $param["title"] = $fb->form("search_txt");
} else if ($fb->form("search_cnd") == "order_num") {
    $param["order_common_seqno"] = $fb->form("search_txt");
} else {
    $param["member_seqno"] = $fb->form("search_txt");
}

$rs = $dao->selectFlattNTypsetStandbyList($conn, $param);
$list = makeFlattNTypsetStandbyListHtml($rs, $param);

$param["dvs"] = "COUNT";
$count_rs = $dao->selectFlattNTypsetStandbyList($conn, $param);
$rsCount = $count_rs->fields["cnt"];

$paging = mkDotAjaxPage($rsCount, $page, $scrnum, $list_num, "movePage", "flatt_n");

echo $list . "♪" . $paging;
$conn->close();
?>
