<?
 /***********************************************************************************
 *** 프로 젝트 : CyPress
 *** 개발 영역 : 프리셋
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.09.20
 ***********************************************************************************/

/***********************************************************************************
 *** 기본 인클루드
 ***********************************************************************************/

$curDirectory = dirname(__FILE__);
require_once $curDirectory."/config/set/webinfo/init.php";
require_once $curDirectory."/config/lib/local/include.php";


/***********************************************************************************
 *** 리퀘스트
 ***********************************************************************************/

$ONS  = $CCommon->STags($CRequest->GetValue("ONS"));

for ($i = 1; $i <= $ONS; $i++) {
    $rData['ON'.$i] = $CCommon->STags($CRequest->GetValue("ON".$i));
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
*** 모듈 인클루드
***********************************************************************************/

require_once _MOD_DIR."/mod_order.php";
$COrder = new CLS_Order;

require_once _MOD_DIR."/mod_state.php";
$CState = new CLS_State;


/***********************************************************************************
 *** 결과
 ***********************************************************************************/

$ordRes = $COrder->getOrderComposeAvailCheck(_DB_SERVER, $rData);

if ($ordRes == "SUCCESS") {
    $nRes = _CYP_SUCCESS;
} else if ($ordRes == "ERROR") {
    $nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
} else {
    $nRes = $ordRes;
}


/***********************************************************************************
 *** 상태 업데이트
 ***********************************************************************************/

if ($nRes == 0) {
    for ($i = 1; $i <= $ONS; $i++) {
         $CState->setStateValueDataChangeComplete(_DB_SERVER, $rData['ON'.$i], _CYP_STS_CD_READY, _CYP_STS_CD_GOING);
    }
}


/***********************************************************************************
 *** 출력
 ***********************************************************************************/

echo "Res=".$nRes."\n";

?>