<?
/**
 * @brief 생산공정 구분 정의 협력품목생산리스트 HTML
 */
function makeCooperatorListHtml($conn, $dao, $rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
    $html .= "\n    <td><input type=\"checkbox\" name=\"chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"200px;\">";
    $html .= "\n      <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"pdfFileDown('%s'); return false;\">PDF</button>";
    $html .= "\n      <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" %s onclick=\"storProcess('%s'); return false;\">입고처리</button>";
    $html .= "\n      <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"regiDeliveryInfo('%s'); return false;\">배송처리</button>";
    $html .= "\n    </td>";

    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $amt = "";
        if ($rs->fields["amt"] < 1) {
            $amt = number_format($rs->fields["amt"], 1);
        } else {
            $amt = number_format($rs->fields["amt"]);
        }

        $proc_disabled = "";
        $dele_disabled = "";

        if ($rs->fields["state"] == 2120) {
            $state = "생산대기";
        } else if ($rs->fields["state"] == 2330) {
            $state = "생산중";
        } else if ($rs->fields["state"] == 3220) {
            $state = "입고완료";
            $proc_disabled = "disabled=\"disabled\"";
        } else if ($rs->fields["state"] == 3330) {
            $state = "배송중";
            $proc_disabled = "disabled=\"disabled\"";
        }

        $param = array();
        $param["table"] = "order_detail_count_file"; 
        $param["col"] = "order_detail_count_file_seqno";
        $param["where"]["order_detail_seqno"] = $rs->fields["order_detail_seqno"];

        $order_detail_count_seqno = $dao->selectData($conn, $param)->fields["order_detail_count_file_seqno"];

        $rs_html .= sprintf($html, $class,
                $rs->fields["order_detail_seqno"],
                $rs->fields["oper_sys"],
                $rs->fields["order_detail_dvs_num"],
                $rs->fields["title"],
                $rs->fields["order_detail"],
                $rs->fields["receipt_finish_date"],
                $amt . $rs->fields["amt_unit_dvs"] . "(" . $rs->fields["count"] . ")",
                $state,
                $order_detail_count_seqno,
                $proc_disabled,
                $rs->fields["order_detail_seqno"],
                $rs->fields["order_detail_seqno"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
