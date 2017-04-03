<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/dataproc_mng/organ_mng/OrganMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$conn->StartTrans();

$fb = new FormBean();
$organDAO = new OrganMngDAO();

$empl_seqno = $fb->form("mng_seq");

//비밀번호 0000으로 초기화
$query  = "\n UPDATE empl";
$query .= "\n    SET passwd = PASSWORD('0000')";
$query .= "\n  WHERE empl_seqno = '%s'";
$query  = sprintf($query, $empl_seqno);

$result = $conn->Execute($query);

if ($result === false) {
    echo false;
} else {
    echo true;
}


$conn->CompleteTrans();
$conn->close();
?>
