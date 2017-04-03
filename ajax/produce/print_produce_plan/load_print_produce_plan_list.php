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

    $html = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
    $html = sprintf($html, $rs->fields["manu_name"]
                     , $rs->fields["seoul_directions"]
                     , $rs->fields["seoul_exec"]
                     , $rs->fields["region_directions"]
                     , $rs->fields["region_exec"]
                     , $rs->fields["tot_directions"]
                     , $rs->fields["tot_exec"]);
    $list .= $html;
    $rs->moveNext();
}

echo $list;
$conn->close();
?>
