<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/mkt/mkt_mng/EventMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/file/FileAttachDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$eventDAO = new EventMngDAO();
$fileDAO = new FileAttachDAO();

$result = $eventDAO->selectNowadaysHtml($conn);

//파일 썸네일 추가
while ($result && !$result->EOF) {
    $param = array();
    $param["fs"] = $result->fields["file_path"] . $result->fields["save_file_name"];
    $param["req_width"] = "300";
    $param["req_height"] = "300";

    $fileDAO->makeThumbnail($param);
    $result->moveNext();
}
$result->moveFirst();

$nowadays_html = makeNowADaysHtml($result);

$fp = fopen(NOWADAYS_HTML, "w+") or die("can't open file");
fwrite($fp, $nowadays_html);
fclose($fp);

$conn->close();
?>
