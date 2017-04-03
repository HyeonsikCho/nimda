<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/paper_materials_mng/PaperMaterialsMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/pageLib.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperMaterialsMngDAO();

$conn->StartTrans();

//취소할 발주id
$paper_op_seqno = $fb->form("paper_op_seqno");

$param = array();
$param["paper_op_seqno"] = $fb->form("paper_op_seqno");

if( $dao->updatePaperMaterialsMngCancel($conn, $param) ) {
    echo "취소되었습니다.";
}else{
    echo "취소실패했습니다.";
}

$conn->CompleteTrans();
$conn->close();
?>
