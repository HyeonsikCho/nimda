<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/calcul_mng/settle/IncomeDataDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/calcul_mng/income_data/WithdrawPopupDOC.php');

$connectionPool = new ConnectionPool(); $conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new IncomeDataDAO();
$util = new CommonUtil();


$seqno = $fb->form("member_seqno");
$from_date = $fb->form("from_date");
$to_date = $fb->form("to_date");
$option_html = "<option value=\"%s\" %s>%s</option>";

//상세보기 출력 발주
$param = array();
$param["member_seqno"] = $seqno;
$param["from_date"] = $from_date;
$param["to_date"] = $to_date;

$rs = $dao->selectWithDraw($conn, $param);

$i = 0;

while($rs && !$rs->EOF) {
    $depo_way = $rs->fields['depo_way'];
    $depo_price = $rs->fields['depo_price'];
    $deal_date = $rs->fields['deal_date'];

    $html_param .= <<<HTML
    <tr>
        <td>$depo_way</td>
        <td>$depo_price</td>
        <td></td>
        <td>$deal_date</td>
        </tr>
HTML;
    $rs->MoveNext();
}

echo getWithdrawPopup($html_param);
$conn->close();
?>
