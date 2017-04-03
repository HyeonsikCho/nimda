<?
/*
 * Copyright (c) 2015-2016 Nexmotion, Inc. All rights reserved. 
 * 
 * REVISION HISTORY (reverse chronological order)
 *=============================================================================
 * 2016/10/11 엄준현 생성
 *=============================================================================
 *
 */
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/basic_mng/cate_mng/CateListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CateListDAO();

$cate_sortcode = $fb->form("cate_sortcode");
$member_seqno  = $fb->form("member_seqno");

$param = array();
$param["cate_sortcode"] = $cate_sortcode;
$param["member_seqno"]  = $member_seqno;

$conn->StartTrans();

$ret = $dao->deleteCateMemberSale($conn, $param); 

$conn->CompleteTrans();

echo $ret ? 'T' : 'F';

$conn->close();
?>
