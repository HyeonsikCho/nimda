<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/ConnectionPool.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/ErpCommonUtil.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/member/member_mng/MemberCommonListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$util = new ErpCommonUtil();
$fb = new FormBean();
$memberCommonListDAO = new MemberCommonListDAO();

$param = array();
$param["table"] = "member_certi";
$param["col"] = "file_path, save_file_name, origin_file_name";
$param["where"]["member_certi_seqno"] = $fb->form("seqno");

$rs = $memberCommonListDAO->selectData($conn, $param);

$file_path = $_SERVER["DOCUMENT_ROOT"] . $rs->fields["file_path"] . $rs->fields["save_file_name"];
$file_size = filesize($file_path);

if (!is_file($file_path)) {
    $util->error('파일이 존재 하지 않습니다.');
}

$down_file_name = $rs->fields["origin_file_name"];
if ($util->isIe() === true) {
    $down_file_name = $util->utf2euc($down_file_name);
}

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$down_file_name\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $file_size");

ob_clean();
flush();
readfile($file_path);
?>
