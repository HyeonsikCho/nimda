<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/member/member_mng/MemberCommonListDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/file/FileAttachDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$fileDAO = new FileAttachDAO();
$dao = new MemberCommonListDAO();

$check = 1;
$member_seqno = $fb->form("member_seqno");

$conn->StartTrans();

$param = array();
$param["table"] = "virt_ba_admin";
$param["col"]["member_seqno"] = NULL;
$param["col"]["cpn_admin_seqno"] = NULL;
$param["col"]["state"] = "N";
$param["prk"] = "member_seqno";
$param["prkVal"] = $member_seqno;

$rs = $dao->updateData($conn,$param);

if (!$rs) {
    $check = 0;
}

echo $check;
$conn->CompleteTrans();
$conn->close();
?>
