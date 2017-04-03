<?
/***********************************************************************************
*** 프로 젝트 : CyPress
*** 개발 영역 : 주문조회
*** 개  발  자 : 김성진
*** 개발 날짜 : 2016.06.15
***********************************************************************************/

/***********************************************************************************
*** 기본 인클루드
***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
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


/***********************************************************************************
*** 변수값 유무 체크
**********************************************************************************/

if (strlen($OrderNum) <= 0) {
	echo "Res="._CYP_ORD_NUM_ERR_CD_01."&"._CYP_ORD_NUM_ERR_DC_01;
	exit;
}


/***********************************************************************************
*** 주문정보 가져오기
***********************************************************************************/

$ordRes = $COrder->getOrderInfoDataValue($conn, $OrderNum);


 /***********************************************************************************
 *** 결과
***********************************************************************************/

 if (is_array($ordRes)) {
	 $nRes = _CYP_SUCCESS;												    	// 성공 리턴

	 // [State]
	 $nStateName = $ordRes['oc_state_name'];							    	// 상태이름
	 $nStateCode = $ordRes['oc_ord_state'];								    	// 상태코드
	 $nPressGo = $CCommon->getPocessGoCode($ordRes['oc_ord_state']);	    	// 진행코드

	 // [Product]
	 $nOrderNum = $ordRes['odcf_ord_dtl_file_num'];						    	// 주문번호
	 $nOS = $ordRes['oc_oper_sys'];										    	// 작업OS
	 $nName = $ordRes['oc_title'];										    	// 주문제목
	 $nPName = $ordRes['od_ord_detail'];								        // 주문상세
     if (strrpos($nPName, "(투터치)")) {
         $nProductName = "F-" . $ordRes['od_prdt_name'];			         	// 제품명칭
     } else {
         $nProductName = $ordRes['od_prdt_name'];					        	// 제품명칭
     }
	 $nProductCode = $ordRes['od_prdt_code'];							    	// 제품코드값
	 $nItemName = $ordRes['od_item_name'];								    	// 품폭명칭
	 $nItemCode = $ordRes['od_item_code'];								    	// 품목코드값
	 $nKindName = $ordRes['od_kind_name'];								    	// 종류명칭
	 $nKindCode = $ordRes['od_kind_code'];								    	// 종류코드값
     if ($ordRes['od_tomson_yn'] == "Y") {
	     $nSide = 2;	                                         		    	// 면수
     } else {
	     $nSide = $CCommon->getSideName($ordRes['od_side_dvs']);			    
     }
	 $nColorName = $ordRes['od_sc_data'];								    	// 도수명칭
	 $nColorCode = $ordRes['od_tot_tmpt'];								    	// 도수코드값
	 $nColorValue = $ordRes['od_tot_tmpt'];								    	// 도수값
	 $nSizeName = $ordRes['od_size_name'];								    	// 사이즈명칭
	 $nSizeCode = $CCommon->getAstricsFormat($ordRes['od_size_code']);	    	// 사이즈코드값

     $nWidth = $ordRes['od_wk_sz_width'];					     		    	// 작업사이즈 가로
     $nPWidth = $ordRes['od_cut_sz_width'];			     					  	// 재단사이즈 가로
     $nHeight = $ordRes['od_wk_sz_height'];								    	// 작업사이즈 세로
	 $nPHeight = $ordRes['od_cut_sz_height'];							    	// 재단사이즈 세로

	 //$nSizeRegular = $CCommon->getStandardNonStandardFormat($ordRes['od_cut_sz_width'], $ordRes['od_cut_sz_height']);	// 규격, 비규격
     if ($ordRes['od_stan_name'] == "비규격") {
         $nSizeRegular = 0;
     } else {
         $nSizeRegular = 1;
     }

	 $nPaperName = $ordRes['od_paper_name'];							    	// 지질(종이)명칭
	 $nPaperCode = $ordRes['od_paper_code'];							    	// 지질(종이)코드값
	 $nQuantityName = $ordRes['od_amt'].$ordRes['od_amt_unit_dvs'];		    	// 수량명칭
	 $nQuantityCode = $ordRes['od_amt'];								    	// 수량코드값
	 $nQuantityValue = $ordRes['oc_page_cnt'];							    	// 수량값
	 $nCaseName = 1;													    	// 건수명칭
	 $nCaseCode = 1;													    	// 건수코드값
	 $nCaseValue = 1;													    	// 건수값
	 $nAworks = $ordRes['bs_after'];									    	// 기존 후공정
	 $nRefc = $CCommon->getColorCodeFormat($ordRes['clr_name']);		    	// 색상

	 // [Delivery]
     if ($ordRes['od_dely_dlvr_way'] == "02") {
	     $nMethod = $ordRes['od_dely_cpn_name']."<0>::";	// 택배[동부택배]
     } else {
	     $nMethod = $ordRes['od_dely_cpn_name'] . "(" . DLVR_PAY_TYP[$ordRes['od_dely_dlvr_pay_way']] . ")<0>::";	// 택배[동부택배]
     }
	 $nPerson = $ordRes['od_dely_name'];								    	// 수령인
	 $nPhone = $ordRes['od_dely_phone'];								    	// 수신전화
	 $nMobile = $ordRes['od_dely_mobile'];								    	// 수신휴대폰
	 $nAddress = $ordRes['od_dely_addr'];								    	// 배송주소
	 $nQuantity = "";													    	// 배송수량 (제외)
	 $nCoast = "";														    	// 배송비 (제외)

	 // [Memo]
	 //$nWMemo = $ordRes['od_rec_memo'];								    	// 작업메모
	 $nWMemo = $ordRes['oc_produce_memo'];				     			    	// 작업메모
	 $nCMemo = "";														    	// 재단메모
	 $nDMemo = $ordRes['oc_dlvr_produce_dvs'];		    		     	    	// 배송메모 테스트4

	 // [Price]
	 $nUnit = _CYP_MON_UNIT;											    	// 화폐단위
	 $nValue = $ordRes['od_pay_price'];									    	// 금액

	 // [Receipt]
	 $nRMet = $ordRes['od_rec_dvs'];									    	// 접수방법 (A : 오토, M : 수동)
	 $nRDiv = $ordRes['div'];											    	// 접수구분
	 $nPurchaseDate = $CCommon->getDateToFormat($ordRes['oc_ord_regi_date']);	// 주문일
	 $nReceiptDate = $CCommon->getDateToFormat($ordRes['od_rec_fns_date']);		// 접수일
	 $nReceiverID = $ordRes['od_empl_id'];								    	// 접수자ID
	 $nReceiverName = $ordRes['od_rec_mng'];							    	// 접수자 이름
	 $nReceiverStateName = $ordRes['oc_state_name'];					    	// 접수상태
	 $nReceiverStateCode = $ordRes['oc_ord_state'];						    	// 상태코드

	 // [Customer]
	 $nUType = $ordRes['od_cus_dvs'];									    	// 회원형태 (개인, 회사, 외국인)
	 $nUID = $ordRes['od_cus_id'];										    	// 회원id
	 $nUCom = $ordRes['od_cus_cp_name'];								    	// 회사명
	 $nUName = $ordRes['od_cus_name'];									    	// 담당자명
	 $nUTel = $ordRes['od_cus_phone'];									    	// 연락처
	 $nUMobile = $ordRes['od_cus_mobile'];								    	// 휴대폰
	 $nUAddr = $ordRes['od_cus_addr'];									    	// 주소

	 // [Repository]
	 $nFileURL = "";
	 $nPDFPath = $CCommon->getPDFPath($ordRes);
	 $nPreviewPath = $CCommon->getPreviewPath();
 } else if ($ordRes == "FAILED") {
	 $nRes = _CYP_ORD_NUM_ERR_CD_02."&"._CYP_ORD_NUM_ERR_DC_02;
 } else if ($ordRes == "ERROR") {
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
	 echo "[State]\n";
	 echo "StateName=".$nStateName."\n";
	 echo "StateCode=".$nStateCode."\n";
	 echo "ProcessGo=".$nPressGo."\n";

	 echo "[Product]\n";
	 echo "OrderNum=".$OrderNum."\n";
	 echo "OS=".$nOS."\n";
	 echo "Name=".$nName."\n";
	 echo "PName=".$nPName."\n";
	 echo "ProductName=".$nProductName."\n";
	 echo "ProductCode=".$nProductCode."\n";
	 echo "ItemName=".$nItemName."\n";
	 echo "ItemCode=".$nItemCode."\n";
	 echo "KindName=".$nKindName."\n";
	 echo "KindCode=".$nKindCode."\n";
	 echo "Side=".$nSide."\n";
	 echo "ColorName=".$nColorName."\n";
	 echo "ColorCode=".$nColorCode."\n";
	 echo "ColorValue=".$nColorValue."\n";
	 echo "SizeName=".$nSizeName."\n";
	 echo "SizeCode=".$nSizeCode."\n";
	 echo "SizeRegular=".$nSizeRegular."\n";
	 echo "Width=".$nWidth."\n";
	 echo "Height=".$nHeight."\n";
	 echo "PWidth=".$nPWidth."\n";
	 echo "PHeight=".$nPHeight."\n";
	 echo "PaperName=".$nPaperName."\n";
	 echo "PaperCode=".$nPaperCode."\n";
	 echo "QuantityName=".$nQuantityName."\n";
	 echo "QuantityCode=".$nQuantityCode."\n";
	 echo "QuantityValue=".$nQuantityValue."\n";
	 echo "CaseName=".$nCaseName."건\n";
	 echo "CaseCode=".$nCaseCode."\n";
	 echo "CaseValue=".$nCaseValue."\n";
	 echo "Aworks=".$nAworks."\n";
	 echo "Refc=".$nRefc."\n";

	 echo "[Delivery]\n";
	 echo "Method=".$nMethod."\n";
	 echo "Person=".$nPerson."\n";
	 echo "Phone=".$nPhone."\n";
	 echo "Mobile=".$nMobile."\n";
	 echo "Address=".$nAddress."\n";
	 echo "Quantity=".$nQuantity."\n";
	 echo "Coast=".$nCoast."\n";

	 echo "[Memo]\n";
	 echo "WMemo=".$nWMemo."\n";
	 echo "CMemo=".$nCMemo."\n";
	 echo "DMemo=".$nDMemo."\n";

	 echo "[Price]\n";
	 echo "Unit=".$nUnit."\n";
	 echo "Value=".$nValue."\n";

	 echo "[Receipt]\n";
	 echo "RMet=".$nRMet."\n";
	 echo "RDiv=".$nRDiv."\n";
	 echo "PurchaseDate=".$nPurchaseDate."\n";
	 echo "ReceiptDate=".$nReceiptDate."\n";
	 echo "ReceiverID=".$nReceiverID."\n";
	 echo "ReceiverName=".$nReceiverName."\n";
	 echo "StateName=".$nReceiverStateName."\n";
	 echo "StateCode=".$nReceiverStateCode."\n";

	 echo "[Customer]\n";
	 echo "UType=".$nUType."\n";
	 echo "UID=".$nUID."\n";
	 echo "UCom=".$nUCom."\n";
	 echo "UName=".$nUName."\n";
	 echo "UTel=".$nUTel."\n";
	 echo "UMobile=".$nUMobile."\n";
	 echo "UAddr=".$nUAddr."\n";

	 echo "[Repository]\n";
	 echo "FileURL=".$nFileURL."\n";
	 echo "PDFPath=".$nPDFPath."\n";
	 echo "PreviewPath=".$nPreviewPath."\n";
 }

?>
