<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ErpCommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdt_price_mng/CalculPriceListDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$util = new ErpCommonUtil();
$dao = new CalculPriceListDAO();

// 공통사용 정보
$val = $fb->form("val");
$val = ($val[0] === '.') ? '0' . $val : $val; // 소수점 처리
$dvs = $fb->form("dvs");
$sell_site  = $fb->form("sell_site");

// 개별수정시 넘어오는 정보
$price_seqno = $fb->form("price_seqno");

//$conn->debug = 1;
if (empty($price_seqno) === true) {
    // 일괄수정
    $mpcode = $fb->form("mpcode");

    //* 가격 업데이트 대상 검색
    $param["sell_site"] = $sell_site;
    $param["mpcode"]    = $mpcode;

    $rs = $dao->selectPrdtPrintPriceExcel($conn, $param);

    unset($param);

    $conn->StartTrans();
    while ($rs && !$rs->EOF) {
        $price_seqno = $rs->fields["price_seqno"];
        $basic_price = $rs->fields["basic_price"];
        $sell_rate =
            ($dvs === 'R') ? $val : $rs->fields["sell_rate"];
        $sell_aplc_price =
            ($dvs === 'A') ? $val : $rs->fields["sell_aplc_price"];

        $param["price_seqno"] = $price_seqno;
        $param["sell_rate"]        = $sell_rate;
        $param["sell_aplc_price"]  = $sell_aplc_price;
        $param["sell_price"]       = $util->getNewPrice($basic_price,
                                                        $sell_rate,
                                                        $sell_aplc_price);

        $update_ret = $dao->updatePrdtPrintPrice($conn, $param);

        if (!$update_ret) {
            goto ERR;
        }

        $rs->MoveNext();
    }
    $conn->CompleteTrans();

} else {
    // 개별수정
    //* 가격 업데이트 대상 검색
    $param["price_seqno"] = $price_seqno;

    $rs = $dao->selectPrdtPrintPriceExcel($conn, $param);

    $basic_price = $rs->fields["basic_price"];
    $sell_rate =
        ($dvs === 'R') ? $val : $rs->fields["sell_rate"];
    $sell_aplc_price =
        ($dvs === 'A') ? $val : $rs->fields["sell_aplc_price"];

    $param["sell_rate"]        = $sell_rate;
    $param["sell_aplc_price"]  = $sell_aplc_price;
    $param["sell_price"]       = $util->getNewPrice($basic_price,
                                                    $sell_rate,
                                                    $sell_aplc_price);

    $update_ret = $dao->updatePrdtPrintPrice($conn, $param);

    if (!$update_ret) {
        goto ERR;
    }
}

echo "T";
$conn->Close();
exit;

ERR :
    $conn->CompleteTrans();
    $conn->Close();
    echo "";
?>
