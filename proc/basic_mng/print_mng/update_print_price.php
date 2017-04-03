<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ErpCommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdc_prdt_mng/PrintMngDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$util = new ErpCommonUtil();
$dao = new PrintMngDAO();

// 공통사용 정보
$val = $util->rmComma($fb->form("val"));
$val = ($val[0] === '.') ? '0' . $val : $val; // 소수점 처리
$dvs = $fb->form("dvs");

// 개별수정시 넘어오는 정보
$price_seqno = $fb->form("price_seqno");

//$conn->debug = 1;
if (empty($price_seqno) === true) {
    // 일괄수정
    $manu      = $fb->form("manu");
    $brand     = $fb->form("brand");
    $top       = $fb->form("top");
    $name      = $fb->form("name");
    $affil     = $fb->form("affil");
    $crtr_tmpt = $fb->form("crtr_tmpt");
    $crtr_unit = $fb->form("crtr_unit");

    //* 가격 업데이트 대상 검색
    $param = array();
    $param["manu"]      = $manu;
    $param["brand"]     = $brand;
    $param["top"]       = $top;
    $param["name"]      = $name;
    $param["affil"]     = $affil;
    $param["crtr_tmpt"] = $crtr_tmpt;
    $param["crtr_unit"] = $crtr_unit;

    $rs = $dao->selectPrdcPrintPriceModi($conn, $param);

    unset($param);

    $conn->StartTrans();
    while ($rs && !$rs->EOF) {
        $price_seqno = $rs->fields["price_seqno"];
        $basic_price = $rs->fields["basic_price"];
        $pur_rate =
            ($dvs === 'R') ? $val : $rs->fields["pur_rate"];
        $pur_aplc_price =
            ($dvs === 'A') ? $val : $rs->fields["pur_aplc_price"];

        $param["price_seqno"] = $price_seqno;
        $param["pur_rate"]       = $pur_rate;
        $param["pur_aplc_price"] = $pur_aplc_price;
        $param["pur_price"]      = $util->getNewPrice($basic_price,
                                                      $pur_rate,
                                                      $pur_aplc_price);

        $update_ret = $dao->updatePrdcPrintPrice($conn, $param);

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

    $rs = $dao->selectPrdcPrintPriceModi($conn, $param);

    $basic_price = $rs->fields["basic_price"];
    $pur_rate =
        ($dvs === 'R') ? $val : $rs->fields["pur_rate"];
    $pur_aplc_price =
        ($dvs === 'A') ? $val : $rs->fields["pur_aplc_price"];

    $param["price_seqno"] = $price_seqno;
    $param["pur_rate"]       = $pur_rate;
    $param["pur_aplc_price"] = $pur_aplc_price;
    $param["pur_price"]      = $util->getNewPrice($basic_price,
                                                  $pur_rate,
                                                  $pur_aplc_price);

    $update_ret = $dao->updatePrdcPrintPrice($conn, $param);

    if (!$update_ret) {
        goto ERR;
    }
}

echo "T";
$conn->close();
exit;

ERR:
    $conn->CompleteTrans();
    $conn->close();
    echo "";
?>
