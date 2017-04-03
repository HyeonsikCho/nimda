<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/produce_plan/PrintProducePlanDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PrintProducePlanDAO();

$date = $fb->form("date");

$param = array();
$param["date"] = $date;

$rs = $dao->selectPrintProductPlanList($conn, $param);
$list = "";
while ($rs && !$rs->EOF) {

    $html = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"viewPlan(%s); return false;\">보기</button></td></tr>";
    $html = sprintf($html, $rs->fields["manu_name"]
                     , $rs->fields["tot_directions"]
                     , $rs->fields["tot_exec"]
                     , $rs->fields["expect_perform"]
                     , $rs->fields["print_op_seqno"]);
    $list .= $html;
    $rs->moveNext();
}

echo $list;
$conn->close();
?>
