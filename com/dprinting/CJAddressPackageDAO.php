<?
/***********************************************************************************
 *** 프로 젝트 : CJ 송장 출력
 *** 개발 영역 : 퀄리 모듈
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.07.25
 ***********************************************************************************/

include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/typset_mng/ProcessMngHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/produce/typset_mng/ProcessPopupDOC.php');

class CJAddressPackageDAO extends ProduceCommonDAO
{

    function __construct ()
    {
    }

    /***********************************************************************************
    *** 주소 정제
    ***********************************************************************************/

    function getCJAddrPackageTransForm($_CJ_CGISDEV, $addr)
    {
        // *** CJ 입력값 변수선언
        $P_CLNTNUM = "30139938";        // 고객id
        $P_CLNTMGMCUSTCD = "30139938";  // 고객관리거래처코드
        $P_PRNGDIVCD = "01";            // 예약구분 01 : 일반, 02 :반품
        $P_CGOSTS = "91";               // 상품상태 11 : 잡화, 92 : 배달
        $P_ADDRESS = $addr;             // 배달지 주소

        //*** 주소 정체 프로시져
        $oc_query = "DECLARE    ";
        $oc_query .= "  P_CLNTNUM             VARCHAR2(400);";
        $oc_query .= "  P_CLNTMGMCUSTCD       VARCHAR2(400);";
        $oc_query .= "  P_PRNGDIVCD           VARCHAR2(400);";
        $oc_query .= "  P_CGOSTS              VARCHAR2(400);";
        $oc_query .= "  P_ADDRESS             VARCHAR2(400);";
        $oc_query .= "  P_ZIPNUM              VARCHAR2(400);";
        $oc_query .= "  P_ZIPID               VARCHAR2(400);";
        $oc_query .= "  P_OLDADDRESS          VARCHAR2(400);";
        $oc_query .= "  P_OLDADDRESSDTL       VARCHAR2(400);";
        $oc_query .= "  P_NEWADDRESS          VARCHAR2(400);";
        $oc_query .= "  P_NESADDRESSDTL       VARCHAR2(400);";
        $oc_query .= "  P_ETCADDR             VARCHAR2(400);";
        $oc_query .= "  P_SHORTADDR           VARCHAR2(400);";
        $oc_query .= "  P_CLSFADDR            VARCHAR2(400);";
        $oc_query .= "  P_CLLDLVBRANCD        VARCHAR2(400);";
        $oc_query .= "  P_CLLDLVBRANNM        VARCHAR2(400);";
        $oc_query .= "  P_CLLDLCBRANSHORTNM   VARCHAR2(400);";
        $oc_query .= "  P_CLLDLVEMPNUM        VARCHAR2(400);";
        $oc_query .= "  P_CLLDLVEMPNM         VARCHAR2(400);";
        $oc_query .= "  P_CLLDLVEMPNICKNM     VARCHAR2(400);";
        $oc_query .= "  P_CLSFCD              VARCHAR2(400);";
        $oc_query .= "  P_CLSFNM              VARCHAR2(400);";
        $oc_query .= "  P_SUBCLSFCD           VARCHAR2(400);";
        $oc_query .= "  P_RSPSDIV             VARCHAR2(400);";
        $oc_query .= "  P_NEWADDRYN           VARCHAR2(400);";
        $oc_query .= "  P_ERRORCD             VARCHAR2(400);";
        $oc_query .= "  P_ERRORMSG            VARCHAR2(400);";
        $oc_query .= "BEGIN    ";
        $oc_query .= "  PKG_RVAP_ADDRSEARCH.PR_RVAP_SEARCHADDRESS";
        $oc_query .= "  (";
        $oc_query .= "        :P_CLNTNUM";
        $oc_query .= "      , :P_CLNTMGMCUSTCD";
        $oc_query .= "      , :P_PRNGDIVCD";
        $oc_query .= "      , :P_CGOSTS";
        $oc_query .= "      , :P_ADDRESS";
        $oc_query .= "      , :P_ZIPNUM";
        $oc_query .= "      , :P_ZIPID";
        $oc_query .= "      , :P_OLDADDRESS";
        $oc_query .= "      , :P_OLDADDRESSDTL";
        $oc_query .= "      , :P_NEWADDRESS";
        $oc_query .= "      , :P_NESADDRESSDTL";
        $oc_query .= "      , :P_ETCADDR";
        $oc_query .= "      , :P_SHORTADDR";
        $oc_query .= "      , :P_CLSFADDR";
        $oc_query .= "      , :P_CLLDLVBRANCD";
        $oc_query .= "      , :P_CLLDLVBRANNM";
        $oc_query .= "      , :P_CLLDLCBRANSHORTNM";
        $oc_query .= "      , :P_CLLDLVEMPNUM";
        $oc_query .= "      , :P_CLLDLVEMPNM";
        $oc_query .= "      , :P_CLLDLVEMPNICKNM";
        $oc_query .= "      , :P_CLSFCD";
        $oc_query .= "      , :P_CLSFNM";
        $oc_query .= "      , :P_SUBCLSFCD";
        $oc_query .= "      , :P_RSPSDIV";
        $oc_query .= "      , :P_NEWADDRYN";
        $oc_query .= "      , :P_ERRORCD";
        $oc_query .= "      , :P_ERRORMSG";
        $oc_query .= "  );";
        $oc_query .= "END;";

        // *** CJ 오라클주소정제 DB 연결
        $oc_conn = oci_connect(_CJ_ADDR_REFINE_ID, _CJ_ADDR_REFINE_PW, $_CJ_CGISDEV, 'UTF8');

        if (!$oc_conn) {
            return "ORC_NOT_CONN";
            exit;
        }

        // *** CJ 오라클 파스
        $oc_stid = oci_parse($oc_conn, $oc_query);

        //입력변수
        oci_bind_by_name($oc_stid, ':P_CLNTNUM', $P_CLNTNUM);
        oci_bind_by_name($oc_stid, ':P_CLNTMGMCUSTCD', $P_CLNTMGMCUSTCD);
        oci_bind_by_name($oc_stid, ':P_PRNGDIVCD', $P_PRNGDIVCD);
        oci_bind_by_name($oc_stid, ':P_CGOSTS', $P_CGOSTS);
        oci_bind_by_name($oc_stid, ':P_ADDRESS', $P_ADDRESS);

        //출력변수
        oci_bind_by_name($oc_stid, ':P_ZIPNUM', $P_ZIPNUM, 400);
        oci_bind_by_name($oc_stid, ':P_ZIPID', $P_ZIPID, 400);
        oci_bind_by_name($oc_stid, ':P_OLDADDRESS', $P_OLDADDRESS, 400);
        oci_bind_by_name($oc_stid, ':P_OLDADDRESSDTL', $P_OLDADDRESSDTL, 400);
        oci_bind_by_name($oc_stid, ':P_NEWADDRESS', $P_NEWADDRESS, 400);
        oci_bind_by_name($oc_stid, ':P_NESADDRESSDTL', $P_NESADDRESSDTL, 400);
        oci_bind_by_name($oc_stid, ':P_ETCADDR', $P_ETCADDR, 400);
        oci_bind_by_name($oc_stid, ':P_SHORTADDR', $P_SHORTADDR, 400);
        oci_bind_by_name($oc_stid, ':P_CLSFADDR', $P_CLSFADDR, 400);
        oci_bind_by_name($oc_stid, ':P_CLLDLVBRANCD', $P_CLLDLVBRANCD, 400);
        oci_bind_by_name($oc_stid, ':P_CLLDLVBRANNM', $P_CLLDLVBRANNM, 400);
        oci_bind_by_name($oc_stid, ':P_CLLDLCBRANSHORTNM', $P_CLLDLCBRANSHORTNM, 400);
        oci_bind_by_name($oc_stid, ':P_CLLDLVEMPNUM', $P_CLLDLVEMPNUM, 400);
        oci_bind_by_name($oc_stid, ':P_CLLDLVEMPNM', $P_CLLDLVEMPNM, 400);
        oci_bind_by_name($oc_stid, ':P_CLLDLVEMPNICKNM', $P_CLLDLVEMPNICKNM, 400);
        oci_bind_by_name($oc_stid, ':P_CLSFCD', $P_CLSFCD, 400);
        oci_bind_by_name($oc_stid, ':P_CLSFNM', $P_CLSFNM, 400);
        oci_bind_by_name($oc_stid, ':P_SUBCLSFCD', $P_SUBCLSFCD, 400);
        oci_bind_by_name($oc_stid, ':P_RSPSDIV', $P_RSPSDIV, 400);
        oci_bind_by_name($oc_stid, ':P_NEWADDRYN', $P_NEWADDRYN, 400);

        oci_bind_by_name($oc_stid, ':P_ERRORCD', $P_ERRORCD, 400);
        oci_bind_by_name($oc_stid, ':P_ERRORMSG', $P_ERRORMSG, 400);

        // 쿼리 실행
        if (!oci_execute($oc_stid)) {
            return "ORC_QUERY_FAILED";
            exit;
        }

        //*** Oracle Colose
        oci_free_statement($oc_stid);
        oci_close($oc_conn);

        // **** result trim
        $P_ZIPNUM = trim($P_ZIPNUM);
        $P_ZIPID = trim($P_ZIPID);
        $P_OLDADDRESS = trim($P_OLDADDRESS);
        $P_OLDADDRESSDTL = trim($P_OLDADDRESSDTL);
        $P_NEWADDRESS = trim($P_NEWADDRESS);
        $P_NESADDRESSDTL = trim($P_NESADDRESSDTL);
        $P_ETCADDR = trim($P_ETCADDR);
        $P_SHORTADDR = trim($P_SHORTADDR);
        $P_CLSFADDR = trim($P_CLSFADDR);
        $P_CLLDLVBRANCD = trim($P_CLLDLVBRANCD);
        $P_CLLDLVBRANNM = trim($P_CLLDLVBRANNM);
        $P_CLLDLCBRANSHORTNM = trim($P_CLLDLCBRANSHORTNM);
        $P_CLLDLVEMPNUM = trim($P_CLLDLVEMPNUM);
        $P_CLLDLVEMPNM = trim($P_CLLDLVEMPNM);
        $P_CLLDLVEMPNICKNM = trim($P_CLLDLVEMPNICKNM);
        $P_CLSFCD = trim($P_CLSFCD);
        $P_CLSFNM = trim($P_CLSFNM);
        $P_SUBCLSFCD = trim($P_SUBCLSFCD);
        $P_RSPSDIV= trim($P_RSPSDIV);
        $P_NEWADDRYN= trim($P_NEWADDRYN);

        $P_ERRORCD = trim($P_ERRORCD);
        //$P_ERRORMSG = trim($P_ERRORMSG);

        if ($P_ERRORCD == 0) {
            $nRes['P_ZIPNUM'] = $P_ZIPNUM;                          // 우편번호
            $nRes['P_ZIPID'] = $P_ZIPID;                            // 우편번호 id
            $nRes['P_OLDADDRESS'] = $P_OLDADDRESS;                  // 지번주소
            $nRes['P_OLDADDRESSDTL'] = $P_OLDADDRESSDTL;            // 지번주소 상세
            $nRes['P_NEWADDRESS'] = $P_NEWADDRESS;                  // 도로명주소
            $nRes['P_NESADDRESSDTL'] = $P_NESADDRESSDTL;            // 도로명주소 상세
            $nRes['P_ETCADDR'] = $P_ETCADDR;
            $nRes['P_SHORTADDR'] = $P_SHORTADDR;                    // 주소약칭
            $nRes['P_CLSFADDR'] = $P_CLSFADDR;                      // 분류주소
            $nRes['P_CLLDLVBRANCD'] = $P_CLLDLVBRANCD;              // 집배송점소코드
            $nRes['P_CLLDLVBRANNM'] = $P_CLLDLVBRANNM;              // 집배송점소명
            $nRes['P_CLLDLCBRANSHORTNM'] = $P_CLLDLCBRANSHORTNM;    // 집배송점소약칭
            $nRes['P_CLLDLVEMPNUM'] = $P_CLLDLVEMPNUM;              // 집배송사원번호
            $nRes['P_CLLDLVEMPNM'] = $P_CLLDLVEMPNM;                // 집배송사원명
            $nRes['P_CLLDLVEMPNICKNM'] = $P_CLLDLVEMPNICKNM;        // 집배송사원분류코드
            $nRes['P_CLSFCD'] = $P_CLSFCD;                          // 분류터미널코드
            $nRes['P_CLSFNM'] = $P_CLSFNM;                          // 분류 터미널명
            $nRes['P_SUBCLSFCD'] = $P_SUBCLSFCD;                    // 소분류코드
            $nRes['P_RSPSDIV'] = $P_RSPSDIV;                        // 전담구분 (대한통운 고유의값)
            $nRes['P_NEWADDRYN'] = $P_NEWADDRYN;                    // 도로명주소여부
        } else {
            $sData = substr($P_ERRORCD, 1, strlen($P_ERRORCD));

            if ($sData == 20000) $nRes = "ORC_ADDR_FAILD_20000";
            else if ($sData == 20001) $nRes = "ORC_ADDR_FAILD_20001";
            else if ($sData == 20002) $nRes = "ORC_ADDR_FAILD_20002";
            else if ($sData == 20003) $nRes = "ORC_ADDR_FAILD_20003";
            else if ($sData == 20004) $nRes = "ORC_ADDR_FAILD_20004";
            else if ($sData == 20005) $nRes = "ORC_ADDR_FAILD_20005";
            else if ($sData == 20006) $nRes = "ORC_ADDR_FAILD_20006";
            else if ($sData == 20007) $nRes = "ORC_ADDR_FAILD_20007";
        }

        return $nRes;
    }


    /***********************************************************************************
     *** 송장 접수 체크
     ***********************************************************************************/

    function getCJShipReceiveRequestValueCheck($oc_conn, $strRes)
    {
        // *** CJ 입력값 변수선언
        $P_CLNTNUM = "30139938";                                    // 고객id
        $today = date("Ymd");                                       // 접수일자

        //*** 쿼리
        $oc_query = "SELECT CUST_ID FROM "._CJ_TBL_RECEIPT." ";
        $oc_query .= "WHERE CUST_ID = '".$P_CLNTNUM."' AND RCPT_YMD = '".$today."' AND CUST_USE_NO = '".$strRes['tr_oc_num']."'";

        // *** CJ 오라클 파스
        $oc_stid = oci_parse($oc_conn, $oc_query);

        // 쿼리 실행
        if (!oci_execute($oc_stid)) {
            $nRes = "ORC_QUERY_FAILED";
        } else {
            $nRes = "SUCCESS";
        }

        oci_free_statement($oc_stid);

        return $nRes;
    }


    /***********************************************************************************
     *** 송장 접수
     ***********************************************************************************/

    function setCJShipReceiveRequestInsertComplete($oc_conn, $strRes, $srvRes, $json_array)
    {
        // *** CJ 입력값 변수선언
        $P_CLNTNUM = "30139938";                                    // 고객id
        $today = date("Ymd");                                       // 접수일자
        $RCPT_DV = "01";                                            // 접수구분 01 : 일반, 02 :반품
        $WORK_DV_CD = "01";                                         // 작업구분코드 01 : 일반, 02 : 교환, 03 : A/S
        $REQ_DV_CD = "01";                                          // 요청구분코드 01 : 요청, 02 : 취소
        $MPCK_KEY = $today."_".$P_CLNTNUM."_".$strRes['tr_oc_num']; // 합포장키 (YYYYMMDD_고객id_고객사용번호)
        $MPCK_SEQ = "1";                                            // 합포장순번 (없음 무조건 1)
        $CAL_DV_CD = "01";                                          // 정산구분코드 01 : 계약운임, 02 : 자료운임(계약운임인지 업체에서 넣어주는 운임으로할지..)
        $FRT_DV_CD = "03";                                          // 운임구분코드 01 : 선불, 02 : 착불, 03 : 신용
        $CNTR_ITEM_CD = "01";                                       // 계약품목코드 01 : 일반 품목
        $BOX_TYPE_CD = "01";                                        // 박스타입코드 01 : 극소, 02 : 소, 03 : 중, 04 : 대 05 : 특대
        $BOX_QTY = "1";                                             // 박스수량
        $PRT_ST = "02";                                             // 출력상태 01 : 미출력 02 : 선출력 03 : 선발번 (반품은 선발번이 없음)
        $GDS_QTY = "1";                                             // 상품수량
        $DLV_DV = "01";                                             // 택배구분 01 : 택배, 02 : 중량물(설치물류), 03 : 중량물(비설치물류)
        $RCPT_ERR_YN = "N";                                         // 접수에러여부  N : 디폴트
        $EAI_PRGS_ST = "01";                                        // 접수에러메세지  01 : 디폴트
        $ACC_ID = "GDPRINTING";                                     // 계정아이디

        if (strlen($strRes['tr_phone']) > 0) {
            $tr_phone_data = explode("-", $strRes['tr_phone']);
        } else {
            $tr_phone_data[0] = "";
            $tr_phone_data[1] = "";
            $tr_phone_data[2] = "";
        }

        if (strlen($strRes['tr_mobile']) > 0) {
            $tr_mobile_data = explode("-", $strRes['tr_mobile']);
        } else {
            $tr_mobile_data[0] = "";
            $tr_mobile_data[1] = "";
            $tr_mobile_data[2] = "";
        }

        if (strlen($strRes['rv_phone']) > 0) {
            $rv_phone_data = explode("-", $strRes['rv_phone']);
        } else {
            $rv_phone_data[0] = "";
            $rv_phone_data[1] = "";
            $rv_phone_data[2] = "";
        }

        if (strlen($strRes['rv_mobile']) > 0) {
            $rv_mobile_data = explode("-", $strRes['rv_mobile']);
        } else {
            $rv_mobile_data[0] = "";
            $rv_mobile_data[1] = "";
            $rv_mobile_data[2] = "";
        }

        if (strlen($strRes['tr_mb_phone']) > 0) {
            $tr_mb_phone_data = explode("-", $strRes['tr_mb_phone']);
        } else {
            $tr_mb_phone_data[0] = "";
            $tr_mb_phone_data[1] = "";
            $tr_mb_phone_data[2] = "";
        }

        if (strlen($strRes['tr_mb_mobile']) > 0) {
            $tr_mobile_data = explode("-", $strRes['tr_mb_mobile']);
        } else {
            $tr_mobile_data[0] = "";
            $tr_mobile_data[1] = "";
            $tr_mobile_data[2] = "";
        }


        //*** 쿼리
        $oc_query = "INSERT INTO "._CJ_TBL_RECEIPT." (";
        $oc_query .= "CUST_ID, RCPT_YMD, CUST_USE_NO, RCPT_DV, WORK_DV_CD, REQ_DV_CD, MPCK_KEY, MPCK_SEQ, CAL_DV_CD, ";
        $oc_query .= "FRT_DV_CD, CNTR_ITEM_CD, BOX_TYPE_CD, BOX_QTY, FRT, CUST_MGMT_DLCM_CD, SENDR_NM, SENDR_TEL_NO1, ";
        $oc_query .= "SENDR_TEL_NO2, SENDR_TEL_NO3, SENDR_CELL_NO1, SENDR_CELL_NO2, SENDR_CELL_NO3, SENDR_SAFE_NO1, ";
        $oc_query .= "SENDR_SAFE_NO2, SENDR_SAFE_NO3, SENDR_ZIP_NO, SENDR_ADDR, SENDR_DETAIL_ADDR, RCVR_NM, ";
        $oc_query .= "RCVR_TEL_NO1, RCVR_TEL_NO2, RCVR_TEL_NO3, RCVR_CELL_NO1, RCVR_CELL_NO2, RCVR_CELL_NO3, ";
        $oc_query .= "RCVR_SAFE_NO1, RCVR_SAFE_NO2, RCVR_SAFE_NO3, RCVR_ZIP_NO, RCVR_ADDR, RCVR_DETAIL_ADDR, ORDRR_NM, ";
        $oc_query .= "ORDRR_TEL_NO1, ORDRR_TEL_NO2, ORDRR_TEL_NO3, ORDRR_CELL_NO1, ORDRR_CELL_NO2, ORDRR_CELL_NO3, ";
        $oc_query .= "ORDRR_SAFE_NO1, ORDRR_SAFE_NO2, ORDRR_SAFE_NO3, ORDRR_ZIP_NO, ORDRR_ADDR, ORDRR_DETAIL_ADDR, ";
        $oc_query .= "INVC_NO, ORI_INVC_NO, ORI_ORD_NO, COLCT_EXPCT_YMD, COLCT_EXPCT_HOUR, ";
        $oc_query .= "SHIP_EXPCT_YMD, SHIP_EXPCT_HOUR, PRT_ST, ARTICLE_AMT, REMARK_1, REMARK_2, REMARK_3, COD_YN, ";
        $oc_query .= "GDS_CD, GDS_NM, GDS_QTY, UNIT_CD, UNIT_NM, GDS_AMT, ETC_1, ETC_2, ETC_3, ETC_4, ETC_5, DLV_DV, ";
        $oc_query .= "RCPT_ERR_YN, RCPT_ERR_MSG, EAI_PRGS_ST, EAI_ERR_MSG, REG_EMP_ID, REG_DTIME, MODI_EMP_ID, MODI_DTIME";
        $oc_query .= ") VALUES (";
        $oc_query .= "'".$P_CLNTNUM."', '".$today."', '".$strRes['tr_oc_num']."', '".$RCPT_DV."', '".$WORK_DV_CD."', '".$REQ_DV_CD."', ";
        $oc_query .= "'".$MPCK_KEY."', '".$MPCK_SEQ."', '".$CAL_DV_CD."', '".$FRT_DV_CD."', '".$CNTR_ITEM_CD."', '".$BOX_TYPE_CD."', ";
        $oc_query .= "'".$BOX_QTY."', '', '".$P_CLNTNUM."', '".$strRes['tr_cp_name']."', ";
        $oc_query .= "'".$tr_phone_data[0]."', '".$tr_phone_data[1]."', '".$tr_phone_data[2]."', ";
        $oc_query .= "'".$tr_mobile_data[0]."', '".$tr_mobile_data[1]."', '".$tr_mobile_data[2]."', ";
        $oc_query .= "'', '', '', '".$strRes['tr_zipcode']."', '".$strRes['tr_addr']."', '".$strRes['tr_addr']."', ";
        $oc_query .= "'".$srvRes['rv_cp_name']."', '".$rv_phone_data[0]."', '".$rv_phone_data[1]."', '".$rv_phone_data[2]."', ";
        $oc_query .= "'".$rv_mobile_data[0]."', '".$rv_mobile_data[1]."', '".$rv_mobile_data[2]."', ";
        $oc_query .= "'', '', '', '".$srvRes['rv_zipcode']."', '".$json_array['from_address']."', '".$json_array['from_address']."', ";
        $oc_query .= "'".$srvRes['tr_mb_name']."', '".$tr_mb_phone_data[0]."', '".$tr_mb_phone_data[1]."', '".$tr_mb_phone_data[2]."', ";
        $oc_query .= "'".$tr_mobile_data[0]."', '".$tr_mobile_data[1]."', '".$tr_mobile_data[2]."', '', '', '', ";
        $oc_query .= "'', '', '', '".$json_array['invoiceNumber']."', '', '', '', '', '', '', '".$PRT_ST."', '".$strRes['tr_price']."', ";
        $oc_query .= "'', '', '', '', '', '".$json_array['title']."', '".$GDS_QTY."', '', '', '".$strRes['tr_price']."', ";
        $oc_query .= "'', '', '', '', '', '".$DLV_DV."', '".$RCPT_ERR_YN."', '', '".$EAI_PRGS_ST."', '', ";
        $oc_query .= "'".$ACC_ID."', to_date('".$today."', 'YYYYMMDD'),  '".$ACC_ID."', to_date('".$today."', 'YYYYMMDD')";
        $oc_query .= ")";

        //echo $oc_query; exit;

        // *** CJ 오라클 파스1
        $oc_stid = oci_parse($oc_conn, $oc_query);

        // 쿼리 실행
        if (!oci_execute($oc_stid, OCI_NO_AUTO_COMMIT)) {
            $nRes = "ORC_QUERY_FAILED";
        } else {
            $nRes = "SUCCESS";
        }

        return $nRes;
    }

}
?>
