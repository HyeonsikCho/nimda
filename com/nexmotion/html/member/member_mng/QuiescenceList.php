<?
/**
 * @brief 휴면 대상 회원 리스트 HTML
 */
function makeQuiescenceListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"checkbox\" class=\"check_box\" name=\"chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>";
    $html .= "\n        <button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"showDetail('%s');\">보기</button>";
    $html .= "\n    </td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $param = array();
        $param["table"] = "cp_issue";
        $param["col"] = "COUNT(cp_issue_seqno) AS cp_cnt";
        $param["where"]["member_seqno"] = $rs->fields["member_seqno"];

        $cp_rs = $dao->selectData($conn, $param);

        $rs_html .= sprintf($html, $class, 
                $rs->fields["member_seqno"],
                $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>",
                date("Y-m-d", strtotime($rs->fields["final_login_date"])),
                $rs->fields["own_point"],
                $cp_rs->fields["cp_cnt"],
                number_format($rs->fields["prepay_price"]). "원",
                $rs->fields["tel_num"],
                $rs->fields["mail"],
                $rs->fields["cell_num"],
                $rs->fields["member_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
