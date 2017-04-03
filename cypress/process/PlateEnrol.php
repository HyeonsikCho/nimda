<?
/***********************************************************************************
 *** 프로 젝트 : CyPress
 *** 개발 영역 : 판등록 및 추가
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.06.23
 ***********************************************************************************/

/***********************************************************************************
 *** 기본 인클루드
 ***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . "/define/cypress_init.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/cypress/process/dao/mod_order_dao.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/cypress/process/dao/mod_state_dao.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/cypress_com.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/cypress_file.php");


/***********************************************************************************
 *** 클래스 선언
 ***********************************************************************************/

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$fb = new FormBean();
$COrder = new CLS_Order;
$CState = new CLS_State;
$CCommon = new CLS_Common;
$CCFile = new CLS_File;


/***********************************************************************************
 *** 리퀘스트
 ***********************************************************************************/

$PEN  = $fb->form("PEN");
$PENO  = $fb->form("PENO");
$PD  = $fb->form("PD");
$PW  = $fb->form("PW");
$PT  = $fb->form("PT");
$PS  = $fb->form("PS");
$PSW  = $fb->form("PSW");
$PSH  = $fb->form("PSH");
$PL  = $fb->form("PL");
$PP  = $fb->form("PP");
$PQ  = $fb->form("PQ");
$PN  = $fb->form("PN");

for ($i = 1; $i <= $PN; $i++) {
	$PC[$i]  = $fb->form("PC".$i);
	$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." 파라미터 PN [".$i."] -> ".$PC[$i]."\n", "a+");
}

$AWN  = $fb->form("AWN");

for ($i = 1; $i <= $AWN; $i++) {
	$AW[$i] = $fb->form("AW".$i);
	$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." 파라미터 AWN [".$i."] -> ".$AW[$i]."\n", "a+");
}

$ONS  = $fb->form("ONS");

for ($i = 1; $i <= $ONS; $i++) {
	$ON[$i]  = $fb->form("ON".$i);
	$OC[$i]  = $fb->form("OC".$i);
	$OP[$i]  = $fb->form("OP".$i);

	$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." 파라미터 ONS [".$i."] -> ".$ON[$i]."/".$OC[$i]."/".$OP[$i]."\n", "a+");
}

$PM  = $fb->form("PM");
$FILES  = $fb->form("FILES");
$PRC  = $fb->form("PRC");
$PRN  = $fb->form("PRN");


/***********************************************************************************
 *** 변수값 유무 체크 1
 ***********************************************************************************/

if (strlen($PEN) <= 0 && strlen($PENO) <= 0) {
	echo "Res="._CYP_ORD_PEN_ERR_CD_01."&"._CYP_ORD_PEN_ERR_DC_01;
	exit;
}

if (strlen($PENO) > 0) {
	if (strlen($PD) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_01."&"._CYP_ORD_PENO_ERR_DC_01;
		exit;
	}

	if (strlen($PW) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_02."&"._CYP_ORD_PENO_ERR_DC_02;
		exit;
	}

	if (strlen($PT) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_03."&"._CYP_ORD_PENO_ERR_DC_03;
		exit;
	}

	if (strlen($PS) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_04."&"._CYP_ORD_PENO_ERR_DC_04;
		exit;
	}

	if (strlen($PSW) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_05."&"._CYP_ORD_PENO_ERR_DC_05;
		exit;
	}

	if (strlen($PSH) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_06."&"._CYP_ORD_PENO_ERR_DC_06;
		exit;
	}

	if (strlen($PL) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_07."&"._CYP_ORD_PENO_ERR_DC_07;
		exit;
	}

	if (strlen($PP) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_08."&"._CYP_ORD_PENO_ERR_DC_08;
		exit;
	}

	if (strlen($PQ) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_09."&"._CYP_ORD_PENO_ERR_DC_09;
		exit;
	}

	if (strlen($PN) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_10."&"._CYP_ORD_PENO_ERR_DC_10;
		exit;
	}

	if (strlen($AWN) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_11."&"._CYP_ORD_PENO_ERR_DC_11;
		exit;
	}

	if (strlen($ONS) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_12."&"._CYP_ORD_PENO_ERR_DC_12;
		exit;
	}

	if (strlen($FILES) <= 0) {
		echo "Res="._CYP_ORD_PENO_ERR_CD_13."&"._CYP_ORD_PENO_ERR_DC_13;
		exit;
	}

	/*if (strlen($PRC) <= 0) {
        echo "Res="._CYP_ORD_PENO_ERR_CD_14."&"._CYP_ORD_PENO_ERR_DC_14;
        exit;
    }

    if (strlen($PRN) <= 0) {
        echo "Res="._CYP_ORD_PENO_ERR_CD_15."&"._CYP_ORD_PENO_ERR_DC_15;
        exit;
    }*/
}


/***********************************************************************************
 *** 변수 정의
 ***********************************************************************************/

$rData['pen']  = $PEN;
$rData['peno']  = $PENO;
$rData['pd']  = $PD;
$rData['pw']  = $PW;
$rData['pt']  = $PT;
$rData['ps']  = $PS;
$rData['psw']  = $PSW;
$rData['psh']  = $PSH;
$rData['pl']  = $PL;
$rData['pp']  = $PP;
$rData['pq']  = $PQ;
$rData['pn']  = $PN;
$rData['awn']  = $AWN;
$rData['ons']  = $ONS;
$rData['pm']  = $PM;
$rData['files']  = $FILES;
$rData['prc']  = $PRC;
$rData['prn']  = $PRN;


/***********************************************************************************
 *** 처리1
 ***********************************************************************************/

if (strlen($PEN) > 0) {
	$ordRes = $COrder->getPlateEnrolPenCheck($conn, $rData);

	if (is_array($ordRes)) {
		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PEN PE -> ".$ordRes['st_idx']."_".$ordRes['typset_num']."\n", "a+");
	} else {
		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PEN PE -> ".$ordRes."\n", "a+");
	}

	if (is_array($ordRes)) {
		$pedRes = $COrder->setPlateEnrolPenDataDeleteComplete($conn, $ordRes['st_idx']);
		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PEN DATA DEL -> ".$pedRes."\n", "a+");

		if ($pedRes == "ERROR") {
			$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
		} else {
			$pfdRes = $COrder->setPlateEnrolPenFileDataDeleteComplete($conn, $ordRes['st_idx']);
			$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PEN FILE DEL -> ".$pfdRes."\n", "a+");

			if ($pfdRes == "ERROR") {
				$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
			} else {
				$filRes = $COrder->setPlateEnrolPenPreviewFileDataDeleteComplete($conn, $ordRes['st_idx']);
				$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PEN PREVIEW DEL -> ".$filRes."\n", "a+");

				if ($filRes == "ERROR") {
					$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
				} else {
					$prwRes = $COrder->setPlateEnrolProcessFlowDataDeleteComplete($conn, $ordRes['typset_num']);

					if ($prwRes == "SUCCESS") {
						$nRes = _CYP_SUCCESS;
					} else {
						$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
					}
				}
			}
		}
	} else if ($ordRes == "SUCCESS") {
		$nRes = _CYP_SUCCESS;
	} else {
		$nRes = _CYP_COMM_ERR_CD_02."&"._CYP_COMM_ERR_DC_02;
	}
} else {
	$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." ---------------------------------------------- 조판 시작 ----------------------------------------------\n", "a+");
	$tpsRes = $COrder->setPlateEnrolPenoITypeSetInsertDataComplete($conn, $rData, $ON, $PC);  // 조판
	$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PENO 조판 -> ".$tpsRes."\n", "a+");

	if ($tpsRes == "SUCCESS") {
        /*
		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." ---------------------------------------------- 종이발주 시작 ----------------------------------------------\n", "a+");
		$papRes = $COrder->setPlateEnrolPenoIPaperOPInsertDataComplete($conn, $rData);  // 종이발주
		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PENO 종이발주 -> ".$papRes."\n", "a+");
        */

		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." ---------------------------------------------- 출력발주 시작 ----------------------------------------------\n", "a+");
		$opoRes = $COrder->setPlateEnrolPenoIOutPutOPInsertDataComplete($conn, $rData, $ON, $PC);  // 출력발주
		$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PENO 출력발주 -> ".$opoRes."\n", "a+");

		if ($opoRes == "SUCCESS") {
			$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." ---------------------------------------------- 인쇄발주 시작 ----------------------------------------------\n", "a+");
			$prtRes = $COrder->setPlateEnrolPenoIPrintOPInsertDataComplete($conn, $rData, $ON, $PC);  // 인쇄발주
			$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PENO 인쇄발주 -> ".$prtRes."\n", "a+");

			if ($prtRes == "SUCCESS") {
				$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." ---------------------------------------------- 후공정발주 시작 ----------------------------------------------\n", "a+");
				$aftRes = $COrder->setPlateEnrolPenoIAfterOPInsertDataComplete($conn, $rData);  // 후공정 발주
				$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PENO 후공정 발주 -> ".$aftRes."\n", "a+");

				if ($aftRes == "SUCCESS") {
					$nRes = _CYP_SUCCESS;

					$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." ---------------------------------------------- 상태 업데이트 시작 ----------------------------------------------\n", "a+");

					for ($i = 1; $i <= $ONS; $i++) {
						$CState->setStateValueDataChangeComplete($conn, $ON[$i], _CYP_STS_CD_GOING, _CYP_STS_CD_OUTPUT);
						$CCFile->FileWrite(_WPATH, "\n".date("H:i:s")." PENO 상태 -> ".$ON[$i]."_"._CYP_STS_CD_GOING."_"._CYP_STS_CD_OUTPUT."\n", "a+");
					}
				} else if ($prtRes == "FAILED") {
					$nRes = _CYP_COMM_ERR_CD_03 . "&4" . _CYP_COMM_ERR_DC_03;
				} else {
					$nRes = _CYP_COMM_ERR_CD_02 . "&4" . _CYP_COMM_ERR_DC_02;
				}

			} else if ($prtRes == "FAILED") {
				$nRes = _CYP_COMM_ERR_CD_03 . "&3" . _CYP_COMM_ERR_DC_03;
			} else {
				$nRes = _CYP_COMM_ERR_CD_02 . "&3" . _CYP_COMM_ERR_DC_02;
			}

		} else if ($opoRes == "FAILED") {
			$nRes = _CYP_COMM_ERR_CD_03 . "&2" . _CYP_COMM_ERR_DC_03;
		} else {
			$nRes = _CYP_COMM_ERR_CD_02 . "&2" . _CYP_COMM_ERR_DC_02;
		}

	} else if ($tpsRes == "FAILD-FILE") {
		$nRes = _CYP_DIR_NUM_ERR_CD_02 . "&1" . _CYP_DIR_NUM_ERR_DC_02;
	} else if ($tpsRes == "FAILD-DIR") {
		$nRes = _CYP_DIR_NUM_ERR_CD_01 . "&1" . _CYP_DIR_NUM_ERR_DC_01;
	} else if ($tpsRes == "FAILED-TS") {
		$nRes = _CYP_TSF_NUM_ERR_CD_01 . "&1" . _CYP_TSF_NUM_ERR_DC_01;
	} else if ($tpsRes == "ERROR-TS") {
		$nRes = _CYP_TSF_NUM_ERR_CD_02 . "&1" . _CYP_TSF_NUM_ERR_DC_02;
	} else if ($tpsRes == "FAILED") {
		$nRes = _CYP_COMM_ERR_CD_03 . "&1" . _CYP_COMM_ERR_DC_03;
	} else {
		$nRes = _CYP_COMM_ERR_CD_02 . "&1" . _CYP_COMM_ERR_DC_02;
	}
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
