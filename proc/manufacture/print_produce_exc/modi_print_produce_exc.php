<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/print_mng/PrintProduceExcDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$conn->StartTrans();

$fb = new FormBean();
$dao = new PrintProduceExcDAO();

$check = 1;

$param = array();
$param["fields"] = $fb->form("fields");
$param["value"] = $fb->form("value");
$param["print_produce_sch_seqno"] = $fb->form("seqno");

if ($fb->form("tot_dvs") == "P") {
    $param["tot_value"] = "+1";
} else {
    $param["tot_value"] = "-1";
}

//인쇄생산계획 이행등록
$rs = $dao->updateExec($conn, $param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
