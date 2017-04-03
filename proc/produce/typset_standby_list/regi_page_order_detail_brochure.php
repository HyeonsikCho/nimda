<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetStandbyListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetStandbyListDAO();
$check = 1;

$order_detail_dvs_num = $fb->form("seqno");
if ($fb->form("tot_count") != $fb->form("new_tot_count")) {
    echo 2;
    exit;
}

$conn->StartTrans();

$param = array();
$param["table"] = "page_order_detail_brochure";
$param["prk"] = "order_detail_dvs_num";
$param["prkVal"] = $order_detail_dvs_num;

$rs = $dao->deleteData($conn, $param);

if (!$rs) {
    $check = 0;
}

$page_arr = explode(",", $fb->form("count"));

$param = array();
$param["table"] = "page_order_detail_brochure";
$param["col"]["order_detail_dvs_num"] = $order_detail_dvs_num;
$param["col"]["state"] = "410";

for ($i = 0; $i < count($page_arr); $i++) {

    $param["col"]["page"] = $page_arr[$i];

    $rs = $dao->insertData($conn, $param);

    if (!$rs) {
        $check = 0;
    }
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
