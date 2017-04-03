<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();
$check = 1;

$seqno = $fb->form("seqno");

$conn->StartTrans();
//낱장조판파일 검색
$param = array();
$param["table"] = "sheet_typset_file";
$param["col"] = "file_path ,save_file_name";
$param["where"]["sheet_typset_file_seqno"] = $seqno;

$sel_rs = $dao->selectData($conn, $param);

$file_path = $sel_rs->fields["file_path"] . $sel_rs->fields["save_file_name"];

if ($file_path) {
    unlink($_SERVER["DOCUMENT_ROOT"] . $file_path);
}

//낱장조판파일삭제
$param = array();
$param["table"] = "sheet_typset_file";
$param["prk"] = "sheet_typset_file_seqno";
$param["prkVal"] = $seqno;

$rs = $dao->deleteData($conn, $param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
