<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperStockMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperStockMngDAO();

$param = array();
$param["table"] = "extnl_brand";
$param["col"] = "extnl_brand_seqno";
$param["where"]["extnl_etprs_seqno"] = $fb->form("manu");

$extnl_brand_seqno = $dao->selectData($conn, $param)->fields["extnl_brand_seqno"];

//기본검색정보 : 종이평량
$param = array();
$param["table"] = "paper";
$param["col"] = "DISTINCT CONCAT(basisweight,basisweight_unit) AS basisweight";
$param["where"]["extnl_brand_seqno"] = $extnl_brand_seqno;
$param["where"]["name"] = $fb->form("name");
$param["where"]["dvs"] = $fb->form("dvs");
$param["where"]["color"] = $fb->form("color");

$paperRs = $dao->selectData($conn, $param);
$opt_basisweight = "<option value=\"\">평량(전체)</option>";
while ($paperRs && !$paperRs->EOF) {
    $opt_basisweight .= "<option value=\"". $paperRs->fields["basisweight"] ."\">" . $paperRs->fields["basisweight"] . "</option>";
    $paperRs->moveNext();
}

echo $opt_basisweight;
$conn->close();
?>
