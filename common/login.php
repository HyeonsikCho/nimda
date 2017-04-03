<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$dao = new CommonDAO();
$fb = new FormBean();

$id = $fb->form("id");
$pw = $fb->form("pw");

$pw = $dao->selectPassword($conn, $pw);

$rs = $dao->selectEmpl($conn, $id);

$pw_hash = $rs->fields["passwd"];

if ($pw != $pw_hash) {
    echo "false";
    exit;
}

$empl_seqno  = $rs->fields["empl_seqno"];
$name        = $rs->fields["name"];
$login_date  = date("Y-m-d H:i:s",time());
$cpn_admin_seqno = $rs->fields["cpn_admin_seqno"];

$param = array();
$param["table"] = "depar_admin";
$param["col"] = "depar_name";
$param["where"]["depar_code"] = $rs->fields["depar_code"];

$rs = $dao->selectData($conn, $param);

$depar_name = $rs->fields["depar_name"];

// 주문상태값 배열 저장
$state_arr = array();

$state_rs  = $dao->selectStateAdmin($conn);
while ($state_rs && !$state_rs->EOF) {
    $fields = $state_rs->fields;

    $state_arr[$fields["erp_state_name"]] = $fields["state_code"];

    $state_rs->MoveNext();
}
unset($state_rs);

$fb->addSession("sell_site" , '1');
$fb->addSession("id"        , $id);
$fb->addSession("name"      , $name);
$fb->addSession("login_date", $login_date);
$fb->addSession("empl_seqno", $empl_seqno);
$fb->addSession("sell_site" , $cpn_admin_seqno);
$fb->addSession("depar_name", $depar_name);
$fb->addSession("state_arr" , $state_arr);

echo "true";
?>
