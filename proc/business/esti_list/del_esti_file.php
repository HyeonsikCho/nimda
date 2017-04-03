<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/business/esti_mng/EstiListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new EstiListDAO();

$admin_esti_file_seqno = $fb->form("admin_esti_file_seqno");

$root_path = $_SERVER["DOCUMENT_ROOT"];

$conn->StartTrans();

$param = array();
$param["admin_esti_file_seqno"] = $admin_esti_file_seqno;

$rs = $dao->selectAdminEstiFileList($conn, $param);
$fields = $rs->fields;

$file_path = $root_path .
             $fields["file_path"] .
             $fields["save_file_name"];

if (@unlink($file_path) === false) {
    if (@is_file($file_path) === true) {
        goto ERR;
    }
}

$param = array();
$param["table"] = "admin_esti_file";
$param["prk"] = "admin_esti_file_seqno";
$param["prkVal"] = $admin_esti_file_seqno;

$dao->deleteData($conn, $param);

if ($conn->HasFailedTrans() === true) {
    $conn->FailTrans();
    $conn->RollbackTrans();
    goto ERR;
}

$conn->CompleteTrans();

echo 'T';
$conn->Close();
exit;

ERR:
    echo 'F';
    $conn->Close();
    exit;
?>
