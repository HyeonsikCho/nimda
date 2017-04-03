<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/produce/raw_materials_stock_mng/RawMaterialStockMngDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$conn->StartTrans();

$fb = new FormBean();
$dao = new RawMaterialStockMngDAO();
$check = "수정에 성공하였습니다.";

//종이재고조정 수정
$param = array();
$param["seq"] = $fb->form("seq");
$param["amt"] = $fb->form("amt");
$param["adjust_reason"] = $fb->form("adjustReason");
$param["admin"] = $fb->session("name");

$rs = $dao->updateMtraStock($conn, $param);
if (!$rs) {
    $check = "수정에 실패하였습니다.";
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
