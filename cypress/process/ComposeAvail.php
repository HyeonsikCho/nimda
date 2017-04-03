<?
 /***********************************************************************************
 *** 프로 젝트 : CyPress
 *** 개발 영역 : 조판가능질의
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

$ONS  = $fb->form("ONS");

for ($i = 1; $i <= $ONS; $i++) {
     $rData['ON'.$i] = $fb->form("ON".$i);
}


/***********************************************************************************
*** 변수값 유무 체크
***********************************************************************************/

if (strlen($ONS) <= 0) {
	echo "Res="._CYP_ORD_NUM_ERR_CD_01."&"._CYP_ORD_NUM_ERR_DC_01;
	exit;
}

for ($i = 1; $i <= $ONS; $i++) {
     if (strlen($rData['ON'.$i]) <= 0) {
         echo "Res="._CYP_ORD_NUM_ERR_CD_01."&"._CYP_ORD_NUM_ERR_DC_01;
         exit;
     }
}


/***********************************************************************************
*** 변수 정의
***********************************************************************************/

$rData['ONS']  = $ONS;


/***********************************************************************************
*** 결과
***********************************************************************************/

$ordRes = $COrder->getOrderComposeAvailCheck($conn, $rData);

if ($ordRes == "SUCCESS") {
	$nRes = _CYP_SUCCESS;
} else if ($ordRes == "ERROR") {
	$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
} else {
	$nRes = $ordRes;
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