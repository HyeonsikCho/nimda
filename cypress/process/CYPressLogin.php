<?
 /***********************************************************************************
 *** 프로 젝트 : CyPress
 *** 개발 영역 : 로그인
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.06.15
 ***********************************************************************************/

 /***********************************************************************************
 *** 기본 인클루드
 ***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . "/define/cypress_init.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/cypress/process/dao/mod_members_dao.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/cypress_com.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/cypress_file.php");


/***********************************************************************************
 *** 클래스 선언
 ***********************************************************************************/

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$fb = new FormBean();
$CMembers = new CLS_Members;
$CCommon = new CLS_Common;
$CCFile = new CLS_File;


 /***********************************************************************************
 *** 리퀘스트
 ***********************************************************************************/

$id  = $fb->form("id");
$passwd  = $fb->form("passwd");


  /***********************************************************************************
 *** 변수값 유무 체크
 ***********************************************************************************/

 if (strlen($id) <= 0 && strlen($passwd) <= 0) {
	 echo "Res="._CYP_LOG_ERR_CD_01."&"._CYP_LOG_ERR_DC_01;
	 exit;
 } else if (strlen($id) <= 0) {
	 echo "Res="._CYP_LOG_ERR_CD_02."&"._CYP_LOG_ERR_DC_02;
	 exit;
 } else if (strlen($passwd) <= 0) {
	 echo "Res="._CYP_LOG_ERR_CD_03."&"._CYP_LOG_ERR_DC_03;
	 exit;
 }


 /***********************************************************************************
 *** 변수 정의
 ***********************************************************************************/

 $rData['usr_id']  = $id;
 $rData['usr_pw']  = $passwd;


 /***********************************************************************************
 *** 회원 로그인 정보 체크
 ***********************************************************************************/
 $chkpass = $CMembers->getPassWDCheck($conn, $rData);
 $mbRes = $CMembers->getEmplLoginCheck($conn, $rData);


 /***********************************************************************************
 *** 결과
 ***********************************************************************************/

 if (is_array($mbRes)) {
     if ($chkpass != $mbRes['passwd']) {
		 $nRes = _CYP_LOG_ERR_CD_05."&"._CYP_LOG_ERR_DC_05;
    } else {
		 $nRes = _CYP_SUCCESS;
		 $nName = $mbRes['name'];
	}
 } else if ($mbRes == "FAILED") {
	 $nRes = _CYP_LOG_ERR_CD_04."&"._CYP_LOG_ERR_DC_04;
 } else if ($mbRes == "ERROR") {
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

if ($nRes == "0") {
	echo "[Account]\n";
	echo "Name=".$nName."\n";
}

?>