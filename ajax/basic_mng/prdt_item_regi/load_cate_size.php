<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdt_mng/PrdtItemRegiDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PrdtItemRegiDAO();

$cate_sortcode = $fb->form("cate_sortcode");
unset($fb);

$param = array();
$param["cate_sortcode"] = $cate_sortcode;

$rs = $dao->selectCateSize($conn, $param);

$inner = "\"%s\" : true,";
$temp = '';

while ($rs && !$rs->EOF) {
    $fields = $rs->fields;

    $temp .= sprintf($inner, $fields["name"]);

    $rs->MoveNext();
}
unset($rs);

$json  = '{';
$json .= substr($temp, 0, -1);
$json .= '}';

echo $json;
$conn->Close();
?>
