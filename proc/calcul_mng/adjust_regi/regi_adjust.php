<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/calcul_mng/settle/AdjustRegiDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$adjustDAO = new AdjustRegiDAO();
$conn->StartTrans();
$check = 1;

//거래일자
$deal_date = $fb->form("deal_date");
//마감일자 확인
$result = $adjustDAO->selectCloseDate($conn, $param);
$close_date = $result->fields["close_date"];
//마감일자가 증빙일자보다 크면
if ($close_date >= $deal_date) {
    echo "3";
    exit;
}

$now_date = date("Y-m-d", time());
//거래일자가 현재일자보다 크면
if ($now_date < $deal_date) {

    echo "4";
    exit;
}
$discount_yn = "";
$param = array();
$param["table"] = "input_dvs_detail";
$param["col"] = "discount_yn";
$param["where"]["input_dvs_name"] = $fb->form("dvs");
$param["where"]["name"] = $fb->form("dvs_detail");
$result = $adjustDAO->selectData($conn, $param);
$discount_yn = $result->fields["discount_yn"];

//조정 테이블에 입력
$param = array();
$param["table"] = "adjust";
$param["col"]["member_seqno"] = $fb->form("member_seqno");
$param["col"]["cont"] = $fb->form("cont");
$param["col"]["deal_date"] = $deal_date;
$param["col"]["regi_date"] = date("Y-m-d H:i:s", time());
$param["col"]["price"] = $fb->form("price");
$param["col"]["input_dvs"] = $fb->form("dvs");
$param["col"]["input_dvs_detail"] = $fb->form("dvs_detail");
$param["col"]["cpn_admin_seqno"] = $fb->form("sell_site");
$param["col"]["empl_seqno"] = $_SESSION["empl_seqno"];

$result = $adjustDAO->insertData($conn, $param);
if (!$result) $check = 0;

//회원 테이블에서 예치금 검색
$param = array();
$param["member_seqno"] = $fb->form("member_seqno");

$result = $adjustDAO->selectMemberPrepay($conn, $param);
if (!$result) $check = 0;

//회원 예치금 수정
$param = array();
$param["table"] = "member";
if ($fb->form("dvs") == "충전") {

    $param["prepay_price"] = $result->fields["prepay_price"] + $fb->form("price");

} else if ($fb->form("dvs") == "차감"){

    $param["prepay_price"] = $result->fields["prepay_price"] - $fb->form("price");

}
$param["member_seqno"] = $fb->form("member_seqno");
$result = $adjustDAO->updateMemberPrepay($conn, $param);
if (!$result) $check = 0;

//충전에 가상계좌, 방문현금일때, 발급가능 금액(세금계산서) 테이블에 insert
if ($fb->form("dvs") == "충전" && ($fb->form("dvs_detail") == "가상계좌" || $fb->form("dvs_detail") == "방문현금")) {

    $param = array();
    $param["member_seqno"] = $fb->form("member_seqno");
    $result = $adjustDAO->selectMemberIssueAblePrice($conn, $param);
    if (!$result) {
        $check = 0;
    } else {
        $able_price = $result->fields["price"];
        if (!$able_price) {
            $param = array();
            $param["table"] = "issue_able_price";
            $param["col"]["member_seqno"] = $fb->form("member_seqno");
            $param["col"]["price"] = $fb->form("price");
            $result = $adjustDAO->insertData($conn, $param);
            if (!$result) $check = 0;

        } else {

            $param = array();
            $param["table"] = "issue_able_price";
            $param["col"]["member_seqno"] =  $fb->form("member_seqno");
            $param["col"]["price"] = (int)($able_price) + (int)($fb->form("price"));
            $param["prk"] = "member_seqno";
            $param["prkVal"] = $fb->form("member_seqno");
            $result = $adjustDAO->updateData($conn, $param);
            if (!$result) $check = 0;
        }
    }
}

echo $check;

$conn->CompleteTrans();
$conn->close();
?>
