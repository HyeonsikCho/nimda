<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeOutputListHtml($rs, $param) {

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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"200px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openDetailView('%s');\">보기</button> <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openImgView('%s');\">이미지</button> <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openPrint('%s');\" %s>인쇄</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["출력준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);

            if ($state == "인쇄대기" || $state == "후공정대기") {
                $state = "출력완료";
            }

            $amt = "";
            if ($rs->fields["amt"] < 1) {
                $amt = number_format($rs->fields["amt"], 1);
            } else {
                $amt = number_format($rs->fields["amt"]);
            }

            $disabled = "";
            if ($rs->fields["flattyp_dvs"] == "Y" && strrpos((string)$rs->fields["typset_num"], 'J') === false) {
                $disabled = "disabled=\"disabled\"";
            } else {
                $disabled = "";
            }

            $preset_name_arr = explode("_", $rs->fields["preset_name"]);
            $tmpt_info = $preset_name_arr[2];

            $rs_html .= sprintf($html, $class,
                    $rs->fields["output_op_seqno"],
                    $rs->fields["typset_num"],
                    $rs->fields["name"],
                    $rs->fields["affil"] . $rs->fields["subpaper"] . $rs->fields["size"],
                    $tmpt_info,
                    $rs->fields["board"],
                    $rs->fields["dlvrboard"],
                    $amt . $rs->fields["amt_unit"],
                    $rs->fields["specialty_items"],
                    $state,
                    $rs->fields["output_op_seqno"],
                    $rs->fields["output_op_seqno"],
                    $rs->fields["typset_num"],
                    $disabled);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}
?>
