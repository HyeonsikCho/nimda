<?
//인쇄생산지시
function makePrintProduceExc($rs) {

    $rs_html = "";
    $html  = "\n<tr>";
//    $html .= "\n  <td>%s</td>";
//    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n</tr>";
    while ($rs && !$rs->EOF) {

        $rs_html .= sprintf($html, $rs->fields["manu_name"]
//                , $rs->fields["theday_directions"]
//                , $rs->fields["theday_exec"]
                , $rs->fields["basic_seoul_directions"]
                , $rs->fields["basic_seoul_exec"]
                , $rs->fields["basic_region_directions"]
                , $rs->fields["basic_region_exec"]
                , $rs->fields["tot_directions"]
                , $rs->fields["tot_exec"]);
        $rs->moveNext();
    }

    return $rs_html;
}
?>
