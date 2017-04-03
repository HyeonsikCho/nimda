<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$commonDAO = new CommonDAO();
$session = $fb->getSession();

$param = array();
$param["empl_seqno"] = $session["empl_seqno"];
$param["section"] = $fb->form("section");

$rs = $commonDAO->selectAuthPage($conn, $param);

if ($rs->fields["page_url"]) {
    echo $rs->fields["page_url"];
} else {
    echo false;
}

$conn->Close();
?>
