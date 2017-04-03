<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/calcul_mng/tab/SalesTabListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new SalesTabListDAO();

$member_seqno   = $fb->form("member_seqno");

$param = array();
$param["member_seqno"] = $member_seqno;

//회원 정보 조회
$rs = $dao->selectMemberInfo($conn, $param);

echo $rs->fields["tel_num"] . "♪♭♬" . 
     $rs->fields["corp_name"] . "♪♭♬" . 
     $rs->fields["repre_name"] . "♪♭♬" . 
     $rs->fields["crn"] . "♪♭♬" . 
     $rs->fields["bc"] . "♪♭♬" . 
     $rs->fields["tob"] . "♪♭♬" . 
     $rs->fields["addr"] . " " . $rs->fields["addr_detail"] . "♪♭♬" . 
     $rs->fields["zipcode"];
     
$conn->close();
?>
	
 
