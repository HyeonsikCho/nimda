<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/dataproc_mng/bulletin_mng/BulletinMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$bulletinDAO = new BulletinMngDAO();

$param = array();
$param["start"] = "0";
$param["end"] = "5";
$result = $bulletinDAO->selectNoticeList($conn, $param);
$notice_html = makeNoticeSummary($result);

$fp = fopen(NOTICE_HTML, "w+") or die("can't open file");
fwrite($fp, $notice_html);
fclose($fp);

$conn->close();
?>
