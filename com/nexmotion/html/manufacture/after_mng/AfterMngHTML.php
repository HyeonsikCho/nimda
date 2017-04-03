<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 생산공정 구분 정의 조판 후공정 리스트 HTML
 */
function makeBasicAfterListHtml($rs, $param) {

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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"150px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openDetailView('%s');\">보기</button> <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openImgView('%s');\">이미지</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["조판후공정준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);
            if ($state == "주문후공정대기") {
                $state = "조판후공정완료";
            }

            $amt = "";
            if ($rs->fields["amt"] < 1) {
                $amt = number_format($rs->fields["amt"], 1);
            } else {
                $amt = number_format($rs->fields["amt"]);
            }

            $rs_html .= sprintf($html, $class,
                    $rs->fields["basic_after_op_seqno"],
                    $rs->fields["typset_num"],
                    $rs->fields["after_name"],
                    $rs->fields["depth1"],
                    $rs->fields["depth2"],
                    $rs->fields["depth3"],
                    $amt . $rs->fields["amt_unit"],
                    $rs->fields["specialty_items"],
                    $state,
                    $rs->fields["basic_after_op_seqno"],
                    $rs->fields["basic_after_op_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 생산공정 구분 정의 조판 후공정 리스트 HTML
 */
function makeAfterListHtml($rs, $param) {

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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"150px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openDetailView('%s');\">보기</button> <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openImgView('%s');\">이미지</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["주문후공정준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);
            if ($state == "입고대기") {
                $state = "주문후공정완료";
            }

            $amt = "";
            if ($rs->fields["amt"] < 1) {
                $amt = number_format($rs->fields["amt"], 1);
            } else {
                $amt = number_format($rs->fields["amt"]);
            }

            $rs_html .= sprintf($html, $class,
                    $rs->fields["after_op_seqno"],
                    $rs->fields["order_num"],
                    $rs->fields["after_name"],
                    $rs->fields["depth1"],
                    $rs->fields["depth2"],
                    $rs->fields["depth3"],
                    $amt . $rs->fields["amt_unit"],
                    $rs->fields["specialty_items"],
                    $state,
                    $rs->fields["after_op_seqno"],
                    $rs->fields["after_op_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}
?>
