<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/member/member_mng/MemberCommonListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$memberCommonListDAO = new MemberCommonListDAO();

$check = 1;
$conn->StartTrans();

//회원 배송관리 수정
$param = array();
$param["order_way"] = $fb->form("order_way");
$param["dlvr_dvs"] = $fb->form("dlvr_dvs");
$param["dlvr_code"] = $fb->form("dlvr_code");
$param["is_use_direct"] = $fb->form("is_use_direct");
$param["member_seqno"] = $fb->form("seqno");

$rs = $memberCommonListDAO->updateMemberDlvrWay($conn, $param);

if (!$rs) {
    $check = 0;
}

echo $check;
$conn->CompleteTrans();
$conn->close();
?>
