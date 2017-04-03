<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');
/**
 * @brief 접수 리스트 HTML
 */
function makeReceiptListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s건</td>";
    $html .= "\n    <td>%s%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width55\" onclick=\"showReceiptPop('%s', '%s', '%s', '%s');\">%s</button></td>";
    $html .= "\n  </tr>";
    $i = $param["count"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $param = array();
        $param["table"] = "depar_admin";
        $param["col"] = "depar_name";
        $param["where"]["depar_code"] = $rs->fields["deparcode"];

        $sel_rs = $dao->selectData($conn, $param);
        $state = $util->statusCode2status($rs->fields["order_state"]);
        
        $rs_html .= sprintf($html, $class, 
                $i,
                $sel_rs->fields["depar_name"],
                $rs->fields["order_num"],
                $rs->fields["member_name"] . "(" . $rs->fields["office_nick"] . ")",
                $rs->fields["title"],
                $rs->fields["order_detail"],
                date("Y-m-d", strtotime($rs->fields["order_regi_date"])),
                $rs->fields["count"],
                $rs->fields["amt"],
                $rs->fields["amt_unit_dvs"],
                $rs->fields["order_common_seqno"],
                $rs->fields["order_state"],
                $rs->fields["count"],
                $rs->fields["order_detail_dvs_num"],
                $state);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 상태변경관리 접수 리스트 HTML
 */
function makeStatusReceiptListHtml($rs, $param, $conn, $dao) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {
        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $seqno = $rs->fields["order_common_seqno"];
        $order_detail_seqno = $rs->fields["order_detail_seqno"];
        $order_detail_dvs_num = $rs->fields["order_detail_dvs_num"];

        $button = "";
        if ($rs->fields["order_state"] === $util->status2statusCode("접수대기")) {
            $rs_html .= "";
        } else {
            if ($rs->fields["order_state"] === $util->status2statusCode("접수중")) {
                $button = "<button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"changeStatus('$seqno', '" . $util->status2statusCode("접수대기") . "', 'N', '접수대기');\">대기</button>";
            } else if ($rs->fields["order_state"] === $util->status2statusCode("시안요청중")) {
                $button  = "<button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"changeStatus('$seqno', '" . $util->status2statusCode("접수보류") . "', 'N', '접수보류');\">보류</button>";
                $button .= "\n<button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"changeStatus('$seqno', '" . $util->status2statusCode("조판대기") . "', 'N', '접수완료');\">완료</button>";
            } else if ($rs->fields["order_state"] === $util->status2statusCode("접수보류")) {
                /* 보류->완료 변경은 아래 조건이 만족했을때 가능
                 * 1. order_detail_count_file 에 파일 업로드 유무
                 * 2. 후공정 유무  
                 *    후공정 있을경우, after_op 의 order_detail_dvs_num 값 확인
                 */
                
                /* 1. order_detail_count_file 에 파일 업로드 유무 */
                //order_detail 의 count 값 
                $countParam = array();
                $countParam["table"] = "order_detail";
                $countParam["col"] = "count";
                $countParam["where"]["order_detail_seqno"] = $order_detail_seqno;
                $rs_detail_count = $dao->selectData($conn, $countParam);
                $detail_count = $rs_detail_count->fields["count"];

                //order_detail_count_file 의 개수
                $countParam = array();
                $countParam["table"] = "order_detail_count_file";
                $countParam["where"]["order_detail_seqno"] = $order_detail_seqno;
                $rs_detail_file_count = $dao->countData($conn, $countParam);
                $detail_file_count = $rs_detail_file_count->fields["cnt"];

                $disabledHtml = "";
                if ($detail_count != $detail_file_count) {
                    $disabledHtml = "disabled";
                }

                /* 2. 후공정 유무  
                 *    후공정 있을경우, after_op 의 order_detail_dvs_num 값 확인 */
                $afterParam = array();
                $afterParam["table"] = "order_after_history";
                $afterParam["col"] = "COUNT(*) AS cnt";
                $afterParam["where"]["order_detail_dvs_num"] = $order_detail_dvs_num;
                $afterParam["where"]["basic_yn"] = "N";

                $rs_after = $dao->selectData($conn, $afterParam);

                $history_cnt = $rs_after->fields["cnt"];

                $afterParam = array();
                $afterParam["table"] = "after_op";
                $afterParam["col"] = "COUNT(*) AS cnt";
                $afterParam["where"]["order_detail_dvs_num"] = $order_detail_dvs_num;
                $afterParam["where"]["basic_yn"] = "N";

                $rs_after = $dao->selectData($conn, $afterParam);

                $op_cnt = $rs_after->fields["cnt"];

                if ($history_cnt != $op_cnt) {
                    $disabledHtml = "disabled";
                }

                $button  = "<button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"changeStatus('$seqno', '" . $util->status2statusCode("접수대기") . "', 'N', '접수대기');\">대기</button>";
                $button .= "\n<button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"changeStatus('$seqno', '" . $util->status2statusCode("조판대기") . "', 'N', '접수완료');\" ". $disabledHtml .">완료</button>";
            }

            $rs_html .= sprintf($html, $class, 
                    $rs->fields["order_num"],
                    $rs->fields["member_name"],
                    $rs->fields["title"],
                    $rs->fields["cate_name"],
                    $rs->fields["receipt_mng"],
                    $util->statusCode2status($rs->fields["order_state"]),
                    $button);
        }
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
