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

/*
    여기 수정하는 경우에는
    /home/dprinting/nimda/engine/oevent_update.php
    파일도 수정 필요

    수정후
    /home/dprinting/nimda/engine/stopDaemon.sh 
    /home/dprinting/nimda/engine/startDaemon.sh 
    스크립트 실행 필요
*/
$result = $eventDAO->selectOeventHtml($conn);

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

$oevent_html = makeOeventHtml($result);

$fp = fopen(OEVENT_HTML, "w+") or die("can't open file");
fwrite($fp, $oevent_html);
fclose($fp);

$conn->close();
?>
