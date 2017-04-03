<?
/***********************************************************************************
 *** 프로 젝트 : CJ 송장 출력
 *** 개발 영역 : 정의
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.07.26
 ***********************************************************************************/

/***********************************************************************************
 *** 초기 설정 영역
 ***********************************************************************************/

ob_start();
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('memory_limit', -1);
ini_set('display_errors', 'On');

set_time_limit(0);
date_default_timezone_set('Asia/Seoul');


/***********************************************************************************
 *** 테이블 정의 (웹 DB)
 ***********************************************************************************/

define("_TBL_MEMBERS", "member");
define("_TBL_CATE", "cate");
define("_TBL_ORDER", "order_common");
define("_TBL_ORDER_DETAIL", "order_detail");

define("_CJ_TBL_RECEIPT", "V_RCPT_GDPRINTING010");   // 접수
define("_CJ_TBL_TRACE", "V_TRACE_GDPRINTING020");    // 추적


/***********************************************************************************
 *** 주관고객코드 및 출고지 코드 동일
 ***********************************************************************************/

define("_CJ_CUST_CODE", "30139938");
define("_CJ_CUST_RESE_CODE", "01");
define("_CJ_CUST_PROD_CODE", "91");


/***********************************************************************************
 *** CJ 계정 정보
 ***********************************************************************************/

define("_CJ_OPEN_DB_ID", "gdprinting");
define("_CJ_OPEN_DB_PW", "gdprintingdev!#$1");
define("_CJ_ADDR_REFINE_ID", "gdprinting");
define("_CJ_ADDR_REFINE_PW", "gdprintingdev$#!1");


/***********************************************************************************
 *** CJ 서버 정보
 ***********************************************************************************/

define("_CJ_OPEN_DB_SERVER_IP","210.98.159.153");
define("_CJ_OPEN_DB_SERVER_PORT", "1523");
define("_CJ_ADDR_REFINE_SERVER_IP", "203.248.116.111");
define("_CJ_ADDR_REFINE_SERVER_PORT", "1521");

$_CJ_OPENDBTEST = _CJ_OPEN_DB_SERVER_IP.':'._CJ_OPEN_DB_SERVER_PORT."/OPENDBT";
$_CJ_CGISDEV = _CJ_ADDR_REFINE_SERVER_IP.':'._CJ_ADDR_REFINE_SERVER_PORT."/CGISDEV";


/***********************************************************************************
 *** CJ 화물 상태 (CRG_ST)
***********************************************************************************/

$_CJ_CRG_ST['01'] = "집하지시";
$_CJ_CRG_ST['11'] = "집하처리";
$_CJ_CRG_ST['12'] = "미집하";
$_CJ_CRG_ST['41'] = "간선상차";
$_CJ_CRG_ST['42'] = "간선하차";
$_CJ_CRG_ST['82'] = "배송출발";
$_CJ_CRG_ST['84'] = "미배달";
$_CJ_CRG_ST['91'] = "배송완료";


/***********************************************************************************
 *** CJ 미집하사유 (NO_CLDV_RSN_CD)
 ***********************************************************************************/

$_CJ_NO_CLDV_RSN_CD['49'] = "집화이관";
$_CJ_NO_CLDV_RSN_CD['02'] = "업체미출고";
$_CJ_NO_CLDV_RSN_CD['03'] = "기집화";
$_CJ_NO_CLDV_RSN_CD['01'] = "재고부족";
$_CJ_NO_CLDV_RSN_CD['06'] = "타택배";
$_CJ_NO_CLDV_RSN_CD['07'] = "천재지변";
$_CJ_NO_CLDV_RSN_CD['08'] = "주문취소(일반건)";
$_CJ_NO_CLDV_RSN_CD['09'] = "집배구역불일치";
$_CJ_NO_CLDV_RSN_CD['11'] = "집화예정";
$_CJ_NO_CLDV_RSN_CD['12'] = "토요휴무";
$_CJ_NO_CLDV_RSN_CD['13'] = "취급불가/규격외품";
$_CJ_NO_CLDV_RSN_CD['14'] = "반품취소/거부";
$_CJ_NO_CLDV_RSN_CD['16'] = "고객사용중";
$_CJ_NO_CLDV_RSN_CD['17'] = "지정일회수";
$_CJ_NO_CLDV_RSN_CD['18'] = "고객부재";
$_CJ_NO_CLDV_RSN_CD['21'] = "고객정보오류";
$_CJ_NO_CLDV_RSN_CD['22'] = "교환물건미도착";
$_CJ_NO_CLDV_RSN_CD['23'] = "회수건없음(업체오류)";
$_CJ_NO_CLDV_RSN_CD['25'] = "통화안됨(4일이상)";
$_CJ_NO_CLDV_RSN_CD['26'] = "합포장(미사용)";
$_CJ_NO_CLDV_RSN_CD['33'] = "시간부족";
$_CJ_NO_CLDV_RSN_CD['34'] = "차량고장";
$_CJ_NO_CLDV_RSN_CD['35'] = "포장미비";
$_CJ_NO_CLDV_RSN_CD['38'] = "도서/외곽지역";
$_CJ_NO_CLDV_RSN_CD['44'] = "중복예약";
$_CJ_NO_CLDV_RSN_CD['48'] = "기타";
$_CJ_NO_CLDV_RSN_CD['50'] = "보내는분 요청";
$_CJ_NO_CLDV_RSN_CD['51'] = "받는분 요청";


/***********************************************************************************
 *** CJ 미배달사유 (NO_DRVR_RSN_CD)
 ***********************************************************************************/

$_CJ_NO_DRVR_RSN_CD['01'] = "고객정보오류";
$_CJ_NO_DRVR_RSN_CD['02'] = "고객부재";
$_CJ_NO_DRVR_RSN_CD['05'] = "지연도락";
$_CJ_NO_DRVR_RSN_CD['06'] = "분류오류";
$_CJ_NO_DRVR_RSN_CD['08'] = "통화불가능";
$_CJ_NO_DRVR_RSN_CD['09'] = "수취거부";
$_CJ_NO_DRVR_RSN_CD['11'] = "천재지변";
$_CJ_NO_DRVR_RSN_CD['16'] = "착지변경";
$_CJ_NO_DRVR_RSN_CD['21'] = "상품사고(파손/분실)";
$_CJ_NO_DRVR_RSN_CD['23'] = "토요휴무";
$_CJ_NO_DRVR_RSN_CD['24'] = "지정일배달";
$_CJ_NO_DRVR_RSN_CD['32'] = "차량고장/사고";
$_CJ_NO_DRVR_RSN_CD['33'] = "도서/외곽지역";
$_CJ_NO_DRVR_RSN_CD['42'] = "특판잔류";
$_CJ_NO_DRVR_RSN_CD['55'] = "결재불가";
$_CJ_NO_DRVR_RSN_CD['56'] = "배달전취소";


/***********************************************************************************
 *** 배송구분
 ***********************************************************************************/

$_SHIP_DIV['01'] = "택배";
$_SHIP_DIV['02'] = "직배";
$_SHIP_DIV['03'] = "화물";
$_SHIP_DIV['04'] = "퀵";
$_SHIP_DIV['05'] = "지하철";


/***********************************************************************************
 *** 선불후불 구분
 ***********************************************************************************/

$_SHIP_WAY_DIV['01'] = "선불";
$_SHIP_WAY_DIV['02'] = "착불";


/***********************************************************************************
 *** 송장 출력 엔진서버 정보(C#)
***********************************************************************************/

define("_CSHAP_ENZIN_IP", "172.16.33.148");
define("_CSHAP_ENZIN_PORT", "31234");

?>