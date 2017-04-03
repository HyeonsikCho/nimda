<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/member/member_mng/MemberCommonListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new MemberCommonListDAO();

//회원 상세정보
$param = array();
$param["table"] = "member_detail_info";
$param["col"] = "use_pro";
$param["where"]["member_seqno"] = $fb->form("member_seqno");

$detail_rs = $dao->selectData($conn, $param);

//OS 별 사용프로그램
$param = array();
$param["table"] = "pro_typ";
$param["col"] = "pro";
$param["where"]["oper_sys"] = $fb->form("use_oper_sys");

$rs = $dao->selectData($conn, $param);

$pro_html = "\n<label class=\"form-radio form-normal\"><input type=\"radio\" name=\"use_pro\" class=\"radio_box\" value=\"%s\"%s>%s</label>";
$html = "";

while ($rs && !$rs->EOF) {

    $checked = "";
    if ($detail_rs->fields["use_pro"] == $rs->fields["pro"]) {
        $checked = " checked=\"checked\"";
    }
    $html .= sprintf($pro_html
            , $rs->fields["pro"]
            , $checked
            , $rs->fields["pro"]);
    $rs->moveNext();
}
$conn->Close();
echo $html;
?>
