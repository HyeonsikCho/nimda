<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/calcul_mng/settle/AdjustRegiDAO.php');


$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$adjustDAO = new AdjustRegiDAO();

//계정 상세
$param = array();
$param["table"] = "input_dvs_detail";
$param["col"] = "name, input_dvs_detail_seqno";
$param["where"]["input_dvs_name"] = $fb->form("dvs");
/*
if ($fb->form("dvs") == "충전") {
    $param["where"]["discount_yn"] = "Y";
}
*/

$result = $adjustDAO->selectData($conn, $param);

//셀렉트 옵션 셋팅
$param["flag"] = "N";
$param["val"] = "name";
$param["dvs"] = "name";
$detail_html = makeSelectOptionHtml($result, $param);

if ($detail_html == "") {

    $detail_html = "\n  <option value=\"\">상세없음</option>";

}

echo $detail_html;
?>
