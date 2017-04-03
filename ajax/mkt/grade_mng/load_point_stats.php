<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/mkt/mkt_mng/GradeMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$gradeDAO = new GradeMngDAO();

//월별 회원 등급 포인트 통계
$param = Array();
$param["table"] = "mon_member_grade_point_stats";
$param["col"] = "year, dvs, member_grade, mon, point";
$param["where"]["year"] = $fb->form("year");
$param["where"]["mon"] = $fb->form("mon");
$param["where"]["cpn_admin_seqno"] = $fb->form("sell_site");
$param["where"]["dvs"] = "적립";

$give_rs = $gradeDAO->selectData($conn, $param);

$param["where"]["dvs"] = "사용";
$use_rs = $gradeDAO->selectData($conn, $param);

//회원 등급 정책 리스트 가져오기
$param = Array();
$param["table"] = "member_grade_policy";
$param["col"] = "grade_name, grade";

$result = $gradeDAO->selectData($conn, $param);

$gradearr = Array();
while ($result && !$result->EOF) {
    $gradearr[$result->fields["grade"]] = $result->fields["grade_name"];

    $result->moveNext();
}

//등급 list
$grade_list = makeGradePointList($give_rs, $use_rs, $gradearr);

echo $grade_list;

$conn->close();
?>
