<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');
/**
 * @brief 종이입고 리스트 HTML
 */
function makePaperStorMngListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
//    $html .= "\n    <td><input type=\"checkbox\" name=\"chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class=\"fwb\">%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class='%s fwb'>%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $state_arr = $_SESSION["state_arr"];
        $state = "";
        $state_color = "fwb";
        $buttons = "";

        $state = $util->statusCode2status($rs->fields["state"]);

        if ($rs->fields["state"] == $state_arr["종이발주완료"]) {
            $state_color = "fwb";
            $state = "종이입고대기";
            $buttons = "\n    <button type='button' class='green btn_pu btn fix_height22' onclick='paperStor(%s)'>종이입고</button>";
            $buttons = sprintf($buttons, 
                    $rs->fields["paper_op_seqno"]);

        } else if ($rs->fields["state"] == $state_arr["종이입고완료"]) { 
            $state_color = "blue_text01";
        } else {
            $state_color = "red_text01";
        }
       
        $stor_date = "";
        if ($rs->fields["stor_date"]) {
            $stor_date = $rs->fields["stor_date"];
        }

        $paper_name = $rs->fields["name"]; 
        $paper_dvs = $rs->fields["dvs"]; 
        $paper_color = $rs->fields["color"]; 
        $paper_basisweight = $rs->fields["basisweight"];

        $paper = $paper_name . " ";
        if ($paper_dvs && $paper_dvs != "-") {
            $paper .= $paper_dvs . " ";
        }
        $paper .= $paper_color . " ";
        $paper .= $paper_basisweight . " ";

        if ($rs->fields["stor_subpaper"] == 1) {
            $stor_subpaper = "전절";
        } else {
            $stor_subpaper = $rs->fields["stor_subpaper"] . "절";
        }

        $amt = "";
        if ($rs->fields["amt"] < 1) {
            $amt = number_format($rs->fields["amt"], 1);
        } else {
            $amt = number_format($rs->fields["amt"]);
        }

        $rs_html .= sprintf($html, 
                $class,
 //               $rs->fields["paper_op_seqno"],
                $rs->fields["op_num"],
                $rs->fields["typset_num"],
                $paper,
                $rs->fields["op_affil"] . " " . $stor_subpaper . $rs->fields["stor_size"],
                $rs->fields["grain"],
                $amt . $rs->fields["amt_unit"],
                $rs->fields["manu_name"],
                $rs->fields["orderer"],
                $rs->fields["op_date"],
                $rs->fields["storplace"],
                $stor_date,
                $rs->fields["warehouser"],
                $state_color,
                $state,
                $buttons);

        $rs->moveNext();
        $i++;
    }

    return $rs_html;
}


?>
