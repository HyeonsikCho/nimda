<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 생산공정 구분 정의 인쇄 리스트 HTML
 */
function makePrintListHtml($rs, $param) {

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
    $html .= "\n    <td width=\"150px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openDetailView('%s');\">보기</button> <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openImgView('%s');\">이미지</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["인쇄준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);

            if ($state == "조판후공정대기") {
                $state = "인쇄완료";
            }

            $preset_name_arr = explode("_", $rs->fields["preset_name"]);
 
            $amt = "";
            if ($rs->fields["amt"] < 1) {
                $amt = number_format($rs->fields["amt"], 1);
            } else {
                $amt = number_format($rs->fields["amt"]);
            }

            $rs_html .= sprintf($html, $class,
                    $rs->fields["print_op_seqno"],
                    $rs->fields["typset_num"],
                    $rs->fields["paper_name"] . $rs->fields["paper_dvs"] . 
                    $rs->fields["paper_color"] . $rs->fields["paper_basisweight"],
                    $preset_name_arr[0] . $preset_name_arr[1],
                    $preset_name_arr[2],
                    $amt . $rs->fields["amt_unit"],
                    $rs->fields["specialty_items"],
                    $state,
                    $rs->fields["print_op_seqno"],
                    $rs->fields["print_op_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}
?>
