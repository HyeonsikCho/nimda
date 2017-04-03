<?
class CommonUtil {
    /**
     * @brief adodb record set 캐쉬가 저장된 디렉토리를 비우는 함수
     * 기본적으로는 define/common_config.php에 지정된 경로를 비운다.
     */
    function rmCacheDir($dir = ADODB_CACHE_DIR) {
        if ($dir === "" || $dir === '' || $dir === '/') {
            return false;
        }

        $command = sprintf("rm -rf %s/*", $dir);
        $ret = NULL;

        system($command, $ret);

        return $ret;
    }

    /**
     * @brief 배열을 구분자가 붙어있는 문자열로 생성한다
     *
     * @param $arr       = 문자열로 변환할 배열
     * @param $delim     = 구분자
     * @param $enclosure = 문장 구분자
     *
     */
    function arr2delimStr($arr, $delim = ',', $enclosure = '"') {
        if (count($arr) === 0) {
            return '';
        }

        $ret = "";
        $delim_ptrn = "/" . $delim . "/";
        $enclosure_ptrn = "/" . $enclosure . "/";

        foreach ($arr as $key => $val) {
            $is_enclosure = preg_match($enclosure_ptrn, $val);

            if ($is_enclosure === true) {
                $val = preg_replace("/\"/", "\"\"", $val);
            }

            $is_delim = preg_match($delim_ptrn, $val);

            if ($is_delim === true) {
                $val = '"' . $val . '"';
            }

            $ret .= $val . $delim; 
        }

        return substr($ret, 0, -1);
    }

    /**
     * @brief 검색결과를 배열로 변환
     *
     * @param $rs = 검색결과
     * @param $field = 배열에 저장할 필드명
     *
     * @return 변환된 배열
     */
    function rs2arr($rs, $field = "mpcode") {
        $ret = array();

        $i = 0;
        while ($rs && !$rs->EOF) {
            $ret[$i++] = $rs->fields[$field];
            $rs->MoveNext();
        }

        return $ret;
    }

    /**
     * @brief ie에서 utf-8 파일명 다운로드 받을 때 euc-kr로 인코딩
     *
     * @param $str = 인코딩할 문자열
     *
     * @return 인코딩된 문자열
     */
    function utf2euc($str) {
        return iconv("UTF-8", "cp949//IGNORE", $str);
    }

    /**
     * @brief 현재 브라우저가 ie인지 확인
     *
     * @return ie면 true
     */
    function isIe() {
        if(!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
            return true; // IE7
        }
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
            return true; // IE8~11
        }
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'rv:') !== false) {
            return true; // IE11
        }

        return false;
    }

    /**
     * @brief 진행상태 코드값으로 주문, 진행상태값 반환
     *
     * @param $p_code = 진행상태 코드값
     *
     * @return 주문상태 + 진행상태값
     */
    function statusCode2status($p_code) {
        $state_arr = $_SESSION["state_arr"];

        foreach ($state_arr as $state => $code) {
            if ($code === $p_code) {
                return $state;
            }
        }

        return false;
    }

    /**
     * @brief 진행상태 코드값으로 주문, 진행상태값 반환
     *
     * @param $state = 진행상태 이름값
     *
     * @return 주문상태 + 진행상태값
     */
    function status2statusCode($state) {
        $state_arr = $_SESSION["state_arr"];

        return $state_arr[$state];
    }

    /**
     * @brief 실제 종이 인쇄 수량 계산
     *
     * @detail $param["amt"] = 상품 수량
     * $param["pos_num"] = 자리수
     * $param["page_num"] = 페이지수
     * $param["amt_unit"] = 상품 수량 단위
     * $param["crtr_unit"] = 종이 기준 단위
     *
     * @detail 실제 계산 공식은 아래와 같다
     * ((소수점_올림{
     *     (($수량$ / $자리수$) / (2 / $페이지수$)) / $카테고리 적용 절 수$
     * }) + $핀장수$ / $카테고리 적용 절 수$)[ / 500]
     *
     * @param $param = 가격검색에 필요한 정보배열
     *
     * @return 실제 종이 인쇄 수량
     */
    function getPaperRealPrintAmt($info) {
        $amt       = doubleval($info["amt"]);
        $pos_num   = doubleval($info["pos_num"]);
        $page_num  = doubleval($info["page_num"]);
        $amt_unit  = $info["amt_unit"];
        $crtr_unit = $info["crtr_unit"];

        // 0page일 경우 인쇄 수량 0 반환
        if ($page_num == 0) {
            return 0;
        }

        $ret = ($amt / $pos_num) / (2.0 / $page_num);

        $ret = $this->calcPaperAmtByCrtrUnit($crtr_unit, $amt_unit, $ret);

        return $ret;
    }

    /**
     * @brief 전달받은 문자열 배열 or 문자열을 json으로
     * 넘길 수 있도록 \n을 제거하고 "를 \"로 변경한다
     *
     * @param $str = 변환할 문자열 배열 or 문자열
     *
     * @return 변환결과
     */
    function convJsonStr($str) {
        if (is_array($str) === true) {
            foreach ($str as $key => $val) {
                $val = preg_replace("/\t/", "", $val);
                $val = preg_replace("/\r/", "", $val);
                $val = preg_replace("/\n/", "", $val);
                $val = preg_replace("/\"/", "\\\\\"", $val);

                $str[$key] = $val;
            }
        } else {
            $str = preg_replace("/\t/", "", $str);
            $str = preg_replace("/\r/", "", $str);
            $str = preg_replace("/\n/", "", $str);
            $str = preg_replace("/\"/", "\\\\\"", $str);
        }
        
        return $str;
    }

	/**
	 * @brief 연/월/일 로 이루어진 디렉토리 경로 반환
     *
     * @return 경로 문자열
	 */
	function getYmdDirPath() {
        $ret = sprintf("%s/%s/%s/", date("Y")
                                  , date("m")
                                  , date("d"));
		return $ret;
	}

    /**
	 * @brief 생산 공정 별 주문상태 변경
     *
     * @return 성공 or 실패
	 */
    function changeOrderState($conn, $dao, $param) {

        $check = 1;
        if ($param["flattyp_dvs"] == "Y") {
            $check = $this->changeSheetState($conn, $dao, $param);
        } else {
            $check = $this->changeBrochureState($conn, $dao, $param);
        }

        return $check;
    }

    /**
	 * @brief 생산 공정 별 주문상태 변경 - 낱장
     *
     * @return 성공 or 실패
	 */
    function changeSheetState($conn, $dao, $param) {

        $check = 1;
        $state = $param["state"];
        $typset_num = $param["typset_num"];

        unset($param);
  
        $param = array();
        $param["table"] = "sheet_typset";
        $param["col"] = "sheet_typset_seqno";
        $param["where"]["typset_num"] = $typset_num;

        $sheet_typset_seqno = $dao->selectData($conn, $param)->fields["sheet_typset_seqno"];

        $param = array();
        $param["table"] = "sheet_typset";
        $param["col"]["state"] = $state;
        $param["prk"] = "typset_num";
        $param["prkVal"] = $typset_num;

        $rs = $dao->updateData($conn, $param);

        if (!$rs) {
            $check = 0;
        }

        $param = array();
        $param["table"] = "amt_order_detail_sheet";
        $param["col"] = "amt_order_detail_sheet_seqno
                        ,order_detail_count_file_seqno";
        $param["where"]["sheet_typset_seqno"] = $sheet_typset_seqno;

        $sel_rs = $dao->selectData($conn, $param);

        //조판에 묶여 있는 주문
        while ($sel_rs && !$sel_rs->EOF) {

            //수량_주문_상세_낱장_일련번호 -  수량분판
            $amt_order_detail_sheet_seqno = $sel_rs->fields["amt_order_detail_sheet_seqno"];
            //주문_상세_건수_파일_일련번호
            $order_detail_count_file_seqno = $sel_rs->fields["order_detail_count_file_seqno"];

            //amt_order_detail_sheet 의 state 변경
            $param = array();
            $param["table"] = "amt_order_detail_sheet";
            $param["col"]["state"] = $state;
            $param["prk"] = "amt_order_detail_sheet_seqno";
            $param["prkVal"] = $amt_order_detail_sheet_seqno;

            $amt_up_rs = $dao->updateData($conn, $param);

            if (!$amt_up_rs) {
                $check = 0;
            }

            //order_detail_count_file 의 state 변경
            $param = array();
            $param["table"] = "order_detail_count_file";
            $param["col"]["state"] = $state;
            $param["prk"] = "order_detail_count_file_seqno";
            $param["prkVal"] = $order_detail_count_file_seqno;

            $count_up_rs = $dao->updateData($conn, $param);

            if (!$count_up_rs) {
                $check = 0;
            }

            //order_detail_seqno 값 구하기(무조건 값이 1개)
            $param = array();
            $param["table"] = "order_detail_count_file";
            $param["col"] = "order_detail_seqno";
            $param["where"]["order_detail_count_file_seqno"] = $order_detail_count_file_seqno;

            $order_detail_seqno = $dao->selectData($conn, $param)->fields["order_detail_seqno"];

            //주문_상세_건수_파일 전체 상태가 같은지 파악
            $param = array();
            $param["table"] = "order_detail_count_file";
            $param["col"] = "state";
            $param["where"]["order_detail_seqno"] = $order_detail_seqno;

            $detail_sel_rs = $dao->selectData($conn, $param);

            //상태 변경 여부 파악
            $detail_change = "Y";

            //주문상세
            while ($detail_sel_rs && !$detail_sel_rs->EOF) {

                if ($detail_sel_rs->fields["state"] != $state) {
                    $detail_change = "N";
                }
                $detail_sel_rs->moveNext();
            }

            //모든 주문_상세_건수_파일 상태가 같으면 주문_상세 상태 변경
            if ($detail_change == "Y") {
                //order_detail 의 state 변경
                $param = array();
                $param["table"] = "order_detail";
                $param["col"]["state"] = $state;
                $param["prk"] = "order_detail_seqno";
                $param["prkVal"] = $order_detail_seqno;

                $detail_up_rs = $dao->updateData($conn, $param);

                if (!$detail_up_rs) {
                    $check = 0;
                }
            }

            //주문_공통_일련번호 검색
            $param = array();
            $param["table"] = "order_detail";
            $param["col"] = "order_common_seqno";
            $param["where"]["order_detail_seqno"] = $order_detail_seqno;

            $order_common_seqno = $dao->selectData($conn, $param)->fields["order_common_seqno"];

            //주문_상세 전체 상태가 같은지 파악
            $param = array();
            $param["table"] = "order_detail";
            $param["col"] = "state";
            $param["where"]["order_common_seqno"] = $order_common_seqno;

            $common_sel_rs = $dao->selectData($conn, $param);

            //상태 변경 여부 파악
            $common_change = "Y";

            //주문상세
            while ($common_sel_rs && !$common_sel_rs->EOF) {

                if ($common_sel_rs->fields["state"] != $state) {
                    $common_change = "N";
                }
                $common_sel_rs->moveNext();
            }

            //모든 주문_상세 상태가 같으면 주문_공통 상태 변경
            if ($common_change == "Y") {
                //order_common 의 state 변경
                $param = array();
                $param["table"] = "order_common";
                $param["col"]["order_state"] = $state;
                $param["prk"] = "order_common_seqno";
                $param["prkVal"] = $order_common_seqno;

                $common_up_rs = $dao->updateData($conn, $param);

                if (!$common_up_rs) {
                    $check = 0;
                }

                //주문 후공정 발주 상태변경
                if ($state == $this->status2statusCode("주문후공정대기")) {

                    $param = array();
                    $param["table"] = "after_op";
                    $param["col"] = "after_op_seqno";
                    $param["where"]["order_common_seqno"] = $order_common_seqno;

                    $after_yn_rs = $dao->selectData($conn, $param);

                    if (!$after_yn_rs || $after_yn_rs->EOF) {
                        $state = $this->status2statusCode("입고대기");

                        $param = array();
                        $param["table"] = "order_common";
                        $param["col"]["order_state"] = $state;
                        $param["prk"] = "order_common_seqno";
                        $param["prkVal"] = $order_common_seqno;

                        $rs = $dao->updateData($conn, $param);

                        if (!$rs) {
                            $check = 0;
                        }

                        $param = array();
                        $param["table"] = "order_detail";
                        $param["col"] = "order_detail_seqno";
                        $param["where"]["order_common_seqno"] = $order_common_seqno;

                        $detail_rs = $dao->selectData($conn, $param);

                        while ($detail_rs && !$detail_rs->EOF) {

                            $param = array();
                            $param["table"] = "order_detail";
                            $param["col"]["state"] = $state;
                            $param["prk"] = "order_detail_seqno";
                            $param["prkVal"] = $detail_rs->fields["order_detail_seqno"];

                            $rs = $dao->updateData($conn, $param);

                            if (!$rs) {
                                $check = 0;
                            }

                            $detail_rs->moveNext();
                        }

                    } else {

                        $param = array();
                        $param["table"] = "after_op";
                        $param["col"]["state"] = $state;
                        $param["prk"] = "order_common_seqno";
                        $param["prkVal"] = $order_common_seqno;

                        $rs = $dao->updateData($conn, $param);

                        if (!$rs) {
                            $check = 0;
                        }
                    }
                }
            }

            $sel_rs->moveNext();
        }

        return $check;
    }

    /**
	 * @brief 생산 공정 별 주문상태 변경 - 책자
     *
     * @return 성공 or 실패
	 */
    function changeBrochureState($conn, $dao, $param) {

        $check = 1;
        $state = $param["state"];
        $typset_num = $param["typset_num"];

        unset($param);

        $param = array();
        $param["table"] = "brochure_typset";
        $param["col"]["state"] = $state;
        $param["prk"] = "typset_num";
        $param["prkVal"] = $typset_num;

        $rs = $dao->updateData($conn, $param);

        if (!$rs) {
            $check = 0;
        }

        $param = array();
        $param["table"] = "brochure_typset";
        $param["col"] = "brochure_typset_seqno";
        $param["where"]["typset_num"] = $typset_num;

        $sel_rs = $dao->selectData($conn, $param);

        $brochure_typset_seqno = $sel_rs->fields["brochure_typset_seqno"];

        $param = array();
        $param["table"] = "page_order_detail_brochure";
        $param["col"]["state"] = $state;
        $param["prk"] = "brochure_typset_seqno";
        $param["prkVal"] = $brochure_typset_seqno;

        $rs = $dao->updateData($conn, $param);

        if (!$rs) {
            $check = 0;
        }

        $param = array();
        $param["table"] = "page_order_detail_brochure";
        $param["col"] = "order_detail_dvs_num";
        $param["where"]["brochure_typset_seqno"] = $brochure_typset_seqno;

        $brochure_rs = $dao->selectData($conn, $param);

        while ($brochure_rs && !$brochure_rs->EOF) {

            $order_detail_dvs_num = $brochure_rs->fields["order_detail_dvs_num"];

            $param = array();
            $param["table"] = "page_order_detail_brochure";
            $param["col"] = "page_order_detail_brochure_seqno, state";
            $param["where"]["order_detail_dvs_num"] = $order_detail_dvs_num;

            $brochure_rs2 = $dao->selectData($conn, $param);

            $state_ck = "Y";
            while ($brochure_rs2 && !$brochure_rs2->EOF) {

                if ($state != $brochure_rs2->fields["state"]) {
                    $state_ck = "N";
                }
                $brochure_rs2->moveNext();
            }

            if ($state_ck == "Y") {

                //order_detail_brochure 의 state 변경
                $param = array();
                $param["table"] = "order_detail_brochure";
                $param["col"]["state"] = $state;
                $param["prk"] = "order_detail_dvs_num";
                $param["prkVal"] = $order_detail_dvs_num;

                $rs = $dao->updateData($conn, $param);

                if (!$rs) {
                    $check = 0;
                }
            }

            //order_common_seqno 값 구하기.
            $param = array();
            $param["table"] = "order_detail_brochure";
            $param["col"] = "order_common_seqno";
            $param["where"]["order_detail_dvs_num"] = $order_detail_dvs_num;
            $rs = $dao->selectData($conn, $param);

            $order_common_seqno = $rs->fields["order_common_seqno"];

            //주문_상세 전체 상태가 같은지 파악
            $param = array();
            $param["table"] = "order_detail_brochure";
            $param["col"] = "state";
            $param["where"]["order_common_seqno"] = $order_common_seqno;

            $common_sel_rs = $dao->selectData($conn, $param);

            //상태 변경 여부 파악
            $common_change = "Y";

            //주문상세
            while ($common_sel_rs && !$common_sel_rs->EOF) {

                if ($common_sel_rs->fields["state"] != $state) {
                    $common_change = "N";
                }
                $common_sel_rs->moveNext();
            }

            //모든 주문_상세 상태가 같으면 주문_공통 상태 변경
            if ($common_change == "Y") {
                //order_common 의 state 변경
                $param = array();
                $param["table"] = "order_common";
                $param["col"]["order_state"] = $state;
                $param["prk"] = "order_common_seqno";
                $param["prkVal"] = $order_common_seqno;

                $common_up_rs = $dao->updateData($conn, $param);

                if (!$common_up_rs) {
                    $check = 0;
                }

                //주문 후공정 발주 상태변경
                if ($state == $this->status2statusCode("주문후공정대기")) {

                    $param = array();
                    $param["table"] = "after_op";
                    $param["col"] = "after_op_seqno";
                    $param["where"]["order_common_seqno"] = $order_common_seqno;

                    $after_yn_rs = $dao->selectData($conn, $param);

                    if (!$after_yn_rs || $after_yn_rs->EOF) {
                        $state = $this->status2statusCode("입고대기");

                        $param = array();
                        $param["table"] = "order_common";
                        $param["col"]["order_state"] = $state;
                        $param["prk"] = "order_common_seqno";
                        $param["prkVal"] = $order_common_seqno;

                        $rs = $dao->updateData($conn, $param);

                        if (!$rs) {
                            $check = 0;
                        }

                        $param = array();
                        $param["table"] = "order_detail_brochure";
                        $param["col"] = "order_detail_brochure_seqno";
                        $param["where"]["order_common_seqno"] = $order_common_seqno;

                        $detail_rs = $dao->selectData($conn, $param);

                        while ($detail_rs && !$detail_rs->EOF) {

                            $param = array();
                            $param["table"] = "order_detail";
                            $param["col"]["state"] = $state;
                            $param["prk"] = "order_detail_seqno";
                            $param["prkVal"] = $detail_rs->fields["order_detail_brochure_seqno"];

                            $rs = $dao->updateData($conn, $param);

                            if (!$rs) {
                                $check = 0;
                            }

                            $detail_rs->moveNext();
                        }

                    } else {

                        $param = array();
                        $param["table"] = "after_op";
                        $param["col"]["state"] = $state;
                        $param["prk"] = "order_common_seqno";
                        $param["prkVal"] = $order_common_seqno;

                        $rs = $dao->updateData($conn, $param);

                        if (!$rs) {
                            $check = 0;
                        }
                    }
                }
            }

            $brochure_rs->moveNext(); 
        }
    }

    /**
     * @brief 넘어온 문자열의 쉼표 제거해서 반환
     *
     * @param $val = 쉼표를 제거할 문자열
     *
     * @return 쉼표가 제거된 문자열
     */
    function rmComma($val) {
        return doubleval(preg_replace("/,/", '', $val));
    }

    /**
     * @brief 요율이 적용된 가격 반환(소수점 반올림)
     *
     * @param $rate  = 요율
     * @param $price = 가격
     *
     * @return 계산된 가격
     */
    function calcPrice($rate, $price) {
        $rate = empty($rate) ? 0.0 : $rate;

        return round(($rate / 100.0) * $price);
    }

    /**
     * @brief 1원 단위 반올림
     * 부가세 단위가 10원으로 나오도록 하기 위함임
     *
     * @param $val = 올림할 값
     *
     * @return 계산된 값
     */
    function ceilVal($val) {
        $val = floatval($val);

        $val = round($val * 0.1) * 10;

        return $val;
    }

    /**
     * @brief 종이, 인쇄 기준단위에 따라 실 종이수량 장/R 변환
     *
     * @param $base_crtr_unit = 기준 수량단위
     * @param $crtr_unit      = 기준에 맞출 수량단위
     * @param $amt            = 실제 종이수량
     *
     * @return 계산된 종이수량
     */
    function calcPaperAmtByCrtrUnit($base_crtr_unit, $crtr_unit, $amt) {
        //echo "base_crtr_unit : $base_crtr_unit / crtr_unit : $crtr_unit / amt : $amt\n";
        if (empty($base_crtr_unit) || empty($crtr_unit)) {
            return $amt;
        }

        if ($base_crtr_unit === 'R' &&
                $crtr_unit === '장' ||
                $crtr_unit === '부' ||
                $crtr_unit === '권') {
            return $amt /= 500;
        }
        if ($base_crtr_unit === '장' ||
                $base_crtr_unit === '부' ||
                $base_crtr_unit === '권' &&
                $crtr_unit === 'R') {
            return $amt *= 500;
        }

        return $amt;
    }

    /**
     * @brief 자리수로 절수 반환
     *
     * @param $base_crtr_unit = 기준 수량단위
     * @param $crtr_unit      = 기준에 맞출 수량단위
     * @param $amt            = 실제 종이수량
     *
     * @return 계산된 종이수량
     */
    function getSubpaper($pos_num) {
        $subpaper = null;

        switch ($pos_num) {
        case '1' :
            $subpaper = "전절";
            break;
        default  :
            $subpaper = $pos_num . '절';
            break;
        }

        return $subpaper;
    }
}
?>
