<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/mkt/mkt_mng/MktAprvlMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/pageLib.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/mkt/mkt_mng/GradeMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$fb = new FormBean();
$mktDAO = new MktAprvlMngDAO();
$gradeDAO = new GradeMngDAO();

//페이징
$list_num = $fb->form("list_num"); //한페이지에 출력할 게시물 개수
if (!$fb->form("list_num")) $list_num = 30;

$scrnum = 5; //블록 개수
$page = $fb->form("page");

if (!$page) $page = 1; // 페이지가 없으면 1 페이지

//등급 승인 테이블
$param = array();
$param["cpn_seqno"] = $fb->form("sell_site");
$param["member_seqno"] = $fb->form("member_seqno");
$param["date_dvs"] = $fb->form("date_dvs");
$param["aprvl_type"] = $fb->form("aprvl_type");

$from_date = $fb->form("date_from");
$to_date = $fb->form("date_to");
$from_time = "";
$to_time = "";

if ($from_date) {
    $from_time = $fb->form("time_from");
    $from = $from_date . " " . $from_time;
}

if ($to_date) {
    $to_time = " " . $fb->form("time_to") + 1;
    $to =  $to_date . " " . $to_time;
}

$param["from"] = $from;
$param["to"] = $to;

//페이징
$param["start"] = $list_num * ($page-1);
$param["end"] = $list_num;

//결과 값을 가져옴
$result = $mktDAO->selectGradeAprvlList($conn, $param);

$param["start"] = "";
$param["end"] = "";

$count_rs = $mktDAO->countGradeAprvlList($conn, $param);
$total_count = $count_rs->fields["cnt"]; //페이징할 총 글수

$ret = "";
$ret = mkDotAjaxPage($total_count, $page, $scrnum, $list_num, "searchResult");

$param = Array();
$param["table"] = "member_grade_policy";
$param["col"] = "grade_name, grade";
$rs = $gradeDAO->selectData($conn, $param);
$gradearr = Array();
while ($rs && !$rs->EOF) {
    $gradearr[$rs->fields["grade"]] = $rs->fields["grade_name"];

    $rs->moveNext();
}

//후공정 테이블 그리기
$list = "";
$list = makeGradeAprvlList($conn, $result, $list_num * ($page-1), $gradearr);

echo $list . "★" . $ret;
$conn->close();
?>
