<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdc_prdt_mng/PaperMngDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperMngDAO();

//검색어
$manu_seqno  = $fb->form("manu_seqno");
$brand_seqno = $fb->form("brand_seqno");

$param = array();
$param["etprs_seqno"] = $manu_seqno;
$param["brand_seqno"] = $brand_seqno;

$rs = $dao->selectPrdcPaperName($conn, $param);

$option = "<option value=\"\">전체</option>";

while ($rs && !$rs->EOF) {
    $name = $rs->fields["name"];

    $option .= sprintf("<option value=\"%s\">%s</option>", $name, $name);

    $rs->MoveNext();
}

echo $option;

$conn->close();
?>
