<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetListDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$param = array();
$param["typset_num"] = $fb->form("typset_num");

$rs = $dao->selectTypsetPaperOpMngList($conn, $param);
$list = makeTypsetPaperOpMngListHtml($rs, $param);

if ($fb->form("typset_num")) {
    echo $list;
} else {
    echo "";
}
$conn->close();
?>
