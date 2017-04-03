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

//기타제조사
$rs = $dao->selectPurPrdtEtc($conn);

$opt = "";
while ($rs && !$rs->EOF) {
    $opt .= "<option value=\"" . $rs->fields["extnl_etprs_seqno"] . "\">" . $rs->fields["manu_name"] . "</option>";
    $rs->moveNext();
}
$param["opt"] = $opt;

$param["vat_y"] = "checked";
$param["save"] = "<button type=\"button\" class=\"btn btn-sm btn-success\" onclick=\"regiRawMaterials();\">저장</button>";
$list = makeDealspecPop($param);

echo $list;
$conn->close();
?>
