<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$el = $fb->form("el");

$param = array();
$param["table"] = "sheet_typset";
$param["col"] = "typset_num";
$param["where"]["sheet_typset_seqno"] = $fb->form("seqno");

$rs = $dao->selectData($conn, $param);

if ($rs->fields["typset_num"]) {

    $param = array();
    $param["table"] = "produce_process";
    $param["col"] = $el . "_yn";
    $param["where"]["typset_num"] = $rs->fields["typset_num"];

    $rs = $dao->selectData($conn, $param);

    echo $rs->fields[$el . "_yn"];

} else {
    echo "";
}
$conn->close();
?>
