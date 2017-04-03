<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdc_prdt_mng/TypsetMngDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$typsetDAO = new TypsetMngDAO();
$conn->StartTrans();

$check = 1;

$seqno_set = explode(",", $fb->form("select_typset"));

for ($i = 0; $i < count($seqno_set); $i++) {

    $param = array();
    $param["table"] = "typset_format_file";
    $param["prk"] = "typset_format_seqno";
    $param["prkVal"] = $seqno_set[$i];
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;

    $param["table"] = "basic_produce_paper";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;

    $param["table"] = "basic_produce_output";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;
 
    $param["table"] = "basic_produce_print";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;

    $param["table"] = "basic_produce_after";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;

    $param["table"] = "basic_produce_opt";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;

    $param["table"] = "basic_produce_stor_release";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;
 
    $param["table"] = "basic_produce_dlvr";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;

    $param["table"] = "typset_format";
    $result = $typsetDAO->deleteData($conn, $param);

    if (!$result) $check = 0;
}

$conn->CompleteTrans();
$conn->close();
echo $check;
?>

