<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');
/**
 * @brief 종이발주 리스트 HTML
 */
function makePaperMaterialsMngHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
    $html .= "\n    <td><input type='checkbox' name='selPaper' value='%s' /></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class='%s fwb'>%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = 0;

    while ($rs && !$rs->EOF) {

        if ( $i%2 )
            $class = "cellbg";
        else
            $class = "";

        $state = "";
        $state_color = "fwb";
        $disabled = "";
        $buttons = "<button type='button' class='green btn_pu btn fix_height22 fix_width40' onclick='paperMaterialsEdit(%s, %s)'>보기</button> <button type='button' class='bred btn_pu btn fix_height20 fix_width40' onclick='paperMaterialsCancel(%s, %s)'>취소</button>";
        $buttons = sprintf($buttons, 
                            $rs->fields["paper_op_seqno"],
                            $rs->fields["state"],
                            $rs->fields["paper_op_seqno"],
                            $rs->fields["state"]);

        if ($rs->fields["state"] == "510") {
            $state_color = "fwb";
        } else if ($rs->fields["state"] == "520") {
            $state_color = "blue_text01";
            $disabled = "disabled";
        } else if ($rs->fields["state"] == "530") {
            $state_color = "red_text01";
            $disabled = "disabled";
            $buttons = "";
        }
        
        $state = $util->statusCode2status($rs->fields["state"]);
       
        $op_date = "";
        if ($rs->fields["op_date"]) {
            $op_date = date("Y-m-d H:i:s", strtotime($rs->fields["op_date"]));
        }

        $rs_html .= sprintf($html, 
                $class,
                $rs->fields["paper_op_seqno"],
//                $disabled,
                $rs->fields["paper_op_seqno"],
                $rs->fields["name"],
                $rs->fields["op_affil"],
                $rs->fields["amt"],
                $rs->fields["amt_unit"],
                $rs->fields["orderer"],
                $rs->fields["typ"],
                $op_date,
                $state_color,
                $state,
                $buttons);

        $rs->moveNext();
        $i++;
    }

    return $rs_html;
}
?>
