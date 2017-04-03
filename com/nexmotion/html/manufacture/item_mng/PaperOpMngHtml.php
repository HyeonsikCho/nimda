<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');
/**
 * @brief 종이발주 리스트 HTML
 */
function makePaperOpMngListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
    $html .= "\n    <td><input type=\"checkbox\" name=\"chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class=\"fwb\">%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class='%s fwb'>%s</span></td>";
    $html .= "\n    <td>%s</td>";
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

        $buttons = "<button type='button' class='green btn_pu btn fix_height22 fix_width40' onclick='paperOpView(%s)'>보기</button> <button type='button' class='bred btn_pu btn fix_height20 fix_width40' onclick='paperOpCancel(%s)'>취소</button>";
        $buttons = sprintf($buttons, 
                $rs->fields["paper_op_seqno"],
                $rs->fields["paper_op_seqno"]);

        if ($rs->fields["state"] == $state_arr["종이발주대기"]) {
            $state_color = "fwb";

        } else if ($rs->fields["state"] == $state_arr["종이발주완료"]) {
            $state_color = "blue_text01";

        } else if ($rs->fields["state"] == $state_arr["종이입고완료"]) {
            $state_color = "grey_text01";
            $buttons = "<button type='button' class='green btn_pu btn fix_height22 fix_width40' onclick='paperOpView(%s)'>보기</button>";
            $buttons = sprintf($buttons, 
                            $rs->fields["paper_op_seqno"]);

        } else if ($rs->fields["state"] == $state_arr["종이발주취소"]) {
            $state_color = "red_text01";
            $buttons = "<button type='button' class='green btn_pu btn fix_height22 fix_width40' onclick='paperOpView(%s)'>보기</button>";
            $buttons = sprintf($buttons, 
                            $rs->fields["paper_op_seqno"]);
        }
        
        $state = $util->statusCode2status($rs->fields["state"]);
       
        $op_date = "";
        if ($rs->fields["op_date"]) {
            $op_date = $rs->fields["op_date"];
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
        if ($rs->fields["amt"] < 1 && $rs->fields["amt"] > 0) {
            $amt = number_format($rs->fields["amt"], 1);
        } else {
            $amt = number_format($rs->fields["amt"]);
        }

        $rs_html .= sprintf($html, 
                $class,
                $rs->fields["paper_op_seqno"],
                $rs->fields["op_num"],
                $paper,
                $rs->fields["op_affil"] . " " . $stor_subpaper . $rs->fields["stor_size"],
                $rs->fields["grain"],
                $amt . $rs->fields["amt_unit"],
                $rs->fields["manu_name"],
                $rs->fields["orderer"],
                $op_date,
                $rs->fields["storplace"],
                $state_color,
                $state,
                $rs->fields["memo"],
                $buttons);

        $rs->moveNext();
        $i++;
    }

    return $rs_html;
}

/**
 * @brief 종이 등록업체 리스트 HTML
 */
function makePaperInfoListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"paper_info\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class=\"fwb\">%s*%s</span></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $paper_name = $rs->fields["paper_name"] . " " .
            $rs->fields["dvs"] . " " .
            $rs->fields["color"] . " " .
            $rs->fields["basisweight"] . $rs->fields["basisweight_unit"];


        $rs_html .= sprintf($html, $class, 
                $rs->fields["paper_seqno"],
                $rs->fields["manu_name"],
                $rs->fields["brand_name"],
                $paper_name,
                $rs->fields["affil"],
                $rs->fields["wid_size"],
                $rs->fields["vert_size"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
