<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$basicDAO = new BasicMngCommonDAO();

//검색 조건에 들어갈 이름
$name = $fb->form("name");
//load 하려는 depth
$depth = $fb->form("depth");

$param = array();
$param["name"] = $name;
$param["depth"] = $depth;

$result = $basicDAO->selectAfterPrdcDepthName($conn, $param);

$arr = [];
$arr["flag"] = "Y";
$arr["def"] = $depth . "(전체)";
$arr["dvs"] = $depth;
$arr["val"] = $depth;

$buff = makeSelectOptionHtml($result, $arr);

echo $buff;
$conn->close();
?>
