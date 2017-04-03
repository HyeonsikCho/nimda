<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperStockMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperStockMngDAO();

//제조사
$param = array();
$param["table"] = "extnl_etprs";
$param["col"] = "extnl_etprs_seqno ,manu_name";
$param["where"]["pur_prdt"] = "종이";

$rs = $dao->selectData($conn, $param);
$paper_manu_html = "<option value=\"\">종이제조사(전체)</option>";
$option_html = "<option value=\"%s\">%s</option>";
while ($rs && !$rs->EOF) {

    $paper_manu_html .= sprintf($option_html
            , $rs->fields["extnl_etprs_seqno"]
            , $rs->fields["manu_name"]);

    $rs->moveNext();
}

$param = array();
$param["today"] = date("Y-m-d");
$param["manu"] = $paper_manu_html;
$param["worker"] = $fb->session("name");

echo getPaperStockMngPopup($param);
$conn->close();
?>
