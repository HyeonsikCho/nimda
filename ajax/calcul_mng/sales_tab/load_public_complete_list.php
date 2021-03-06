<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/calcul_mng/tab/SalesTabListDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new SalesTabListDAO();

//한페이지에 출력할 게시물 갯수
$list_num = $fb->form("listSize"); 

//현재 페이지
$page = $fb->form("page");

//리스트 보여주는 갯수 설정
if (!$fb->form("listSize")) {
    $list_num = 30;
}

//블록 갯수
$scrnum = 5; 

// 페이지가 없으면 1 페이지
if (!$page) {
    $page = 1; 
}

$s_num = $list_num * ($page-1);

$sell_site = $fb->form("sell_site");
$member_dvs = $fb->form("member_dvs");
$year = $fb->form("year");
$mon = $fb->form("mon");
$public_dvs = $fb->form("public_dvs");

$param = array();
$param["s_num"] = $s_num;
$param["list_num"] = $list_num;
$param["sell_site"] = $sell_site;
$param["member_dvs"] = $member_dvs;
$param["year"] = $year;
$param["mon"] = $mon;
$param["tab_public"] = $tab_public;
$param["dvs"] = "SEQ";

if ($fb->form("search")) {
    $param["search"] = $fb->form("search");
    $param["search_dvs"] = $fb->form("search_dvs");
}

//마지막날짜구하기
$day = 1;
while(checkdate($mon, $day, $year)) {
    $day++;
}
$param["day"] = $day-1;

$rs = $dao->selectPublicCompleteList($conn, $param);
$list = makePublicCompleteListHtml($conn, $dao, $rs, $param);

$param["dvs"] = "COUNT";
$count_rs = $dao->selectPublicCompleteList($conn, $param);
$rsCount = $count_rs->fields["cnt"];

$paging = mkDotAjaxPage($rsCount, $page, $scrnum, $list_num, "moveCompletePage");
$total_rs = $dao->selectPublicCompleteSum($conn, $param);

$member_cnt = "0";
$pay_total = "0";
$card_total = "0";

//업체수가 있을때
if ($total_rs->fields["member_cnt"]) {

    $member_cnt = number_format($total_rs->fields["member_cnt"]);

}

//매출합계가 있을때
if ($total_rs->fields["pay_total"]) {
    
    $pay_total = $total_rs->fields["pay_total"];

}

//카드합계가 있을때
if ($total_rs->fields["card_total"]) {
    
    $card_total = $total_rs->fields["card_total"];

}

echo $list . "♪♭@"  . $paging . "♪♭@" . $member_cnt . "♪♭@" . 
     number_format($pay_total) . "♪♭@" .
     number_format($card_total);

$conn->close();
?>
