<?
/*
 * Copyright (c) 2016 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *=============================================================================
 * 2016/09/26 엄준현 추가
 *=============================================================================
 *
 */
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/basic_mng/prdt_price_mng/PrdtPriceListDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PrdtPriceListDAO();

$cate_sortcode = $fb->form("cate_sortcode");
$typ           = $fb->form("typ");

if (empty($typ)) {
    echo '';
    exit;
}

$param = array();
$param["cate_sortcode"] = $cate_sortcode;
$param["typ"]           = $typ;

$size = $dao->selectCateSizeHtml($conn, $param);

echo $size;
$conn->close();
?>
