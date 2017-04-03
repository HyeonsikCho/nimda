<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdc_prdt_mng/OutputMngDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$outputDAO = new OutputMngDAO();

//검색어
$search = $fb->form("search_str");
$manu_seqno  = $fb->form("manu_seqno");
$brand_seqno = $fb->form("brand_seqno");

$param = array();
$param["search"] = $search;
$param["etprs_seqno"] = $manu_seqno;
$param["brand_seqno"] = $brand_seqno;

$result = $outputDAO->selectPrdcOutputName($conn, $param);

$arr = [];
$arr["col"] = "name";
$arr["val"] = "name";
$arr["type"] = "name";

$buff = makeSearchList($result, $arr);

echo $buff;
$conn->close();
?>
