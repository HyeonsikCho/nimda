<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/typset_mng/TypsetFormatDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetFormatDAO();

$el = $fb->form("el");
$seqno = $fb->form("seqno");

$param = array();
$param["table"] = "basic_produce_print";
$param["col"] = "print_seqno, extnl_etprs_seqno";
$param["where"]["typset_format_seqno"] = $seqno;

$rs = $dao->selectData($conn, $param);

if ($rs->EOF == 1) {
    $process_yn = "N";
} else {
    $process_yn = "Y";
}

$param = array();
$param["table"] = "extnl_etprs";
$param["col"] = "pur_prdt";
$param["where"]["extnl_etprs_seqno"] = $rs->fields["extnl_etprs_seqno"];

$pur_rs = $dao->selectData($conn, $param);

$param = array();
$param["table"] = "extnl_etprs";
$param["col"] = "extnl_etprs_seqno, manu_name";
$param["where"]["pur_prdt"] = $pur_rs->fields["pur_prdt"];

$sel_rs = $dao->selectData($conn, $param);

$html = makeOptionHtml($sel_rs, "extnl_etprs_seqno", "manu_name", "업체(전체)");

echo $html . "♪" . $rs->fields["print_seqno"] 
. "♪" . $pur_rs->fields["pur_prdt"] 
. "♪" . $rs->fields["extnl_etprs_seqno"] 
. "♪" . $process_yn;

$conn->Close();
?>
