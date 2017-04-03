<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

//옵션 발주 리스트
$param = array();
$param["table"] = "opt";
$param["col"] = "opt_seqno, name ,depth1 ,depth2 , depth3 ,amt ,crtr_unit";

$rs = $dao->selectData($conn, $param);
$list = makeOptInfoListHtml($rs, $param);

echo $list;
$conn->close();
?>
