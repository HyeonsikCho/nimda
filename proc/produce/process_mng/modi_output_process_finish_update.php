<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/ProcessMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ProcessMngDAO();
$util = new CommonUtil();

$check = 1;
$output_op_seqno = $fb->form("seqno");
$amt = $fb->form("amt");
$memo = $fb->form("memo");

$param = array();
$param["table"] = "output_work_report";
$param["col"] = "worker_memo";
$param["where"]["output_op_seqno"] = $output_op_seqno;

$worker_memo = $dao->selectData($conn, $param)->fields["worker_memo"];

$conn->StartTrans();

//기존 작업일지 수정
$param = array();
$param["table"] = "output_work_report";
$param["col"]["work_end_hour"] = date("Y-m-d H:i:s");
$param["col"]["worker_memo"] = $worker_memo . "<br />" . $memo;
$param["prk"] = "output_op_seqno";
$param["prkVal"] = $output_op_seqno;

$rs = $dao->updateWorkReport($conn, $param);

if (!$rs) {
    $check = 0;
}

$param = array();
$param["table"] = "output_op";
$param["col"]["amt"] = $amt;
$param["prk"] = "output_op_seqno";
$param["prkVal"] = $output_op_seqno;

$rs = $dao->updateData($conn, $param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
