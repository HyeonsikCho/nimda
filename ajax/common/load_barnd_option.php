<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CommonDAO();

$el = $fb->form("el");
$seqno = $fb->form("seqno");

if ($el == "output") {
    $pur_prdt = "출력";
} else if ($el == "print") {
    $pur_prdt = "인쇄";
} else if ($el == "after") {
    $pur_prdt = "후공정";
}

//브랜드
$param = array();
$param["table"] = "extnl_brand";
$param["col"] = "extnl_brand_seqno ,name";
$param["where"]["extnl_etprs_seqno"] = $seqno;

$rs = $dao->selectData($conn, $param);

$option_html = "\n<option value=\"%s\">%s</option>";
$brand_html = "\n<option value=\"\">브랜드(선택)</option>";

while ($rs && !$rs->EOF) {

    $brand_html .= sprintf($option_html
            , $rs->fields["extnl_brand_seqno"]
            , $rs->fields["name"]);

    $rs->moveNext();
}

echo $brand_html;
$conn->Close();
?>
