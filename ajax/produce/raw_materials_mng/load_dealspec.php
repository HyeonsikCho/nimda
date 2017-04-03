<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/raw_materials_mng/RawMaterialsMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/doc/produce/raw_materials_mng/RawMaterialsMngDOC.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new RawMaterialsMngDAO();

//수정할 seq_num
$seq = $fb->form("seq");

$param = array();
$param["dealspec_seqno"] = $seq;
$rs = $dao->selectDealspecView($conn, $param);

$param["dealspec_seqno"] = "value=\"".$seq."\"";
$param["stan"] = "value=\"".$rs->fields['stan']."\"";
$param["amt"] = "value=\"".$rs->fields["amt"]."\"";
$param["unitprice"] = "value=\"".$rs->fields["unitprice"]."\"";
$param["price"] = "value=\"".$rs->fields["price"]."\"";
$param["memo"] = $rs->fields["memo"];
    
if ($rs->fields["vat_yn"] == "Y"){
    $param["vat_y"] = "checked";
    $param["vat_n"] = "";
} else {
    $param["vat_y"] = "";
    $param["vat_n"] = "checked";
}
$param["edit"] = "<button type=\"button\" class=\"btn btn-sm btn-success\" onclick=\"modiRawMaterials();\">수정</button>";
$param["del"] = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"delRawMaterials();\">삭제</button>";

//기타제조사
$optRs = $dao->selectPurPrdtEtc($conn);

$opt = "";
while ($optRs && !$optRs->EOF) {
    $opt .= "<option value=\"" . $optRs->fields["extnl_etprs_seqno"] . "\">" . $optRs->fields["manu_name"] . "</option>";
    $optRs->moveNext();
}
$param["opt"] = $opt;


$list = makeDealspecPop($param);

echo $list . "♪" . $rs->fields["name"] . "♪" . $rs->fields["extnl_etprs_seqno"];
$conn->close();
?>
