<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdt_price_mng/AfterPriceListDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new AfterPriceListDAO();

$cate_sortcode = $fb->form("cate_sortcode");
$sell_site     = $fb->form("sell_site");
$after_name    = $fb->form("after_name");

$param = array();
$param["cate_sortcode"] = $cate_sortcode;
$param["after_name"] = $after_name;

$rs = $dao->selectCateAfterAmtUnit($conn, $param);

$param["mpcode"]   = $rs["mpcode"];
$param["amt_unit"] = $rs["crtr_unit"];
$amt = $dao->selectCateAfterAmtHtml($conn, $param);

echo $amt;

$conn->Close();
?>
