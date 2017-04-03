<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ManufactureCommonDAO();

//브랜드
$param = array();
$param["table"] = "extnl_brand";
$param["col"] = "extnl_brand_seqno ,name";
$param["where"]["extnl_etprs_seqno"] = $fb->form("seqno");

$rs = $dao->selectData($conn, $param);

$option_html = "\n<option value=\"%s\">%s</option>";
$brand_html = "";
while ($rs && !$rs->EOF) {

    $brand_html .= sprintf($option_html
            , $rs->fields["extnl_brand_seqno"]
            , $rs->fields["name"]);

    $rs->moveNext();
}

echo $brand_html;
$conn->close();
?>
