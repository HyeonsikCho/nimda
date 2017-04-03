<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdt_mng/PrdtBasicRegiDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$prdtBasicRegiDAO = new PrdtBasicRegiDAO();

$param = array();
$param["name"] = $fb->form("print_name");

//인쇄도수 인쇄 용도구분
$tmpt_purp_dvs_rs = $prdtBasicRegiDAO->selectPrintInfo($conn, "PURP", $param);
$tmpt_purp_dvs_html = makeOptionHtml($tmpt_purp_dvs_rs, "", "purp_dvs", "", "N");

echo $tmpt_purp_dvs_html;
$conn->close();
?>
