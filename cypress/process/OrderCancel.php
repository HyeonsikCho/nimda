<?
 /***********************************************************************************
 *** 프로 젝트 : CyPress
 *** 개발 영역 : 주문취소요청
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.06.21
 ***********************************************************************************/

 /***********************************************************************************
 *** 기본 인클루드
 ***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . "/define/cypress_init.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/cypress/process/dao/mod_order_dao.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/cypress_com.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/cypress_file.php");


/***********************************************************************************
 *** 클래스 선언
 ***********************************************************************************/

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$fb = new FormBean();
$COrder = new CLS_Order;
$CCommon = new CLS_Common;
$CCFile = new CLS_File;


 /***********************************************************************************
 *** 리퀘스트
 ***********************************************************************************/

$OrderNum  = $fb->form("OrderNum");
$WorkerID  = $fb->form("WorkerID");


/***********************************************************************************
*** 변수값 유무 체크
***********************************************************************************/

 if (strlen($OrderNum) <= 0 || strlen($WorkerID) <= 0) {
	 echo "Res="._CYP_ORD_NUM_ERR_CD_01."&"._CYP_ORD_NUM_ERR_DC_01;
	 exit;
 }


/***********************************************************************************
*** 변수 정의
***********************************************************************************/

$rData['order_num']  = $OrderNum;
$rData['work_id']  = iconv("euckr", "utf-8", $WorkerID);


/***********************************************************************************
*** 주문취소 체크
***********************************************************************************/

$chkRes = $COrder->getOrderCancelDataCheck($conn, $rData);


/***********************************************************************************
*** 결과
***********************************************************************************/

if ($chkRes == "SUCCESS") {
	$ordRes = $COrder->setOrderCancelDataUpdateComplete($conn, $rData);

	if ($ordRes == "SUCCESS") {
		$nRes = _CYP_SUCCESS;
	} else {
		$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
	}
} else if ($chkRes == "FAILED") {
	$nRes = _CYP_ORD_NUM_ERR_CD_03."&"._CYP_ORD_NUM_ERR_DC_03;
} else if ($chkRes == "ERROR") {
	$nRes = _CYP_COMM_ERR_CD_01."&"._CYP_COMM_ERR_DC_01;
} else {
	$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
}


/***********************************************************************************
 *** DB 컨넥션 종료
 ***********************************************************************************/

$conn->close();


/***********************************************************************************
*** 출력
***********************************************************************************/

echo "Res=".$nRes."\n";

?>
