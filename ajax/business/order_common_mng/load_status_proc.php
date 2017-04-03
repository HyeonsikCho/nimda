<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CommonDAO();

$val = $fb->form("val");

$rs = $dao->selectStateAdmin($conn, $val);

$proc_html = option("전체", '');

while ($rs && !$rs->EOF) {
    $fields = $rs->fields;

    $proc_html .= option($fields["erp_state_name"], $fields["state_code"]);

    $rs->MoveNext();
}

echo $proc_html;
?>
