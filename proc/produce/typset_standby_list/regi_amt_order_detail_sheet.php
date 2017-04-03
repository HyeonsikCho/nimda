<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetStandbyListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetStandbyListDAO();
$check = 1;

if ($fb->form("tot_count") != $fb->form("new_tot_count")) {
    echo 2;
    exit;
}

$conn->StartTrans();

$param = array();
$param["table"] = "amt_order_detail_sheet";
$param["prk"] = "order_detail_count_file_seqno";
$param["prkVal"] = $fb->form("seqno");

$rs = $dao->deleteData($conn, $param);

if (!$rs) {
    $check = 0;
}

$amt_arr = explode(",", $fb->form("count"));

$param = array();
$param["table"] = "amt_order_detail_sheet";
$param["col"]["order_detail_count_file_seqno"] = $fb->form("seqno");
$param["col"]["state"] = "2120";

for ($i = 0; $i < count($amt_arr); $i++) {

    $param["col"]["amt"] = $amt_arr[$i];

    $rs = $dao->insertData($conn, $param);

    if (!$rs) {
        $check = 0;
    }
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
