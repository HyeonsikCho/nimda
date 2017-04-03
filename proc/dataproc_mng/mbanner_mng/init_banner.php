<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$conn->StartTrans();

$dao = new CommonDAO();
$check = 1;

$param = array();
$param["table"] = "main_banner";
$param["col"] = "file_path, save_file_name";

$rs = $dao->selectData($conn, $param);

while ($rs && !$rs->EOF) {

    unlink($_SERVER["DOCUMENT_ROOT"] . $rs->fields["file_path"] . $rs->fields["save_file_name"]);
    $rs->moveNext();
}

$query = "TRUNCATE main_banner";

$rs = $conn->Execute($query);

if (!$rs) {
    $check = 0;
} 

$query = "TRUNCATE main_banner_set";

$rs = $conn->Execute($query);

if (!$rs) {
    $check = 0;
} 

echo $check;
$conn->CompleteTrans();
$conn->close();
?>
