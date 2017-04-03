<?
/**
 * @brief 공정별결과 출력 리스트 HTML
 */
function makeOutputOpProcessResultListHtml($rs, $param) {
  
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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"getView('%s', 'output');\">보기</button>";
    $html .= "\n  </tr>";
    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["output_work_report_seqno"],
                $rs->fields["typset_num"],
                $rs->fields["name"],
                $rs->fields["affil"],
                $rs->fields["size"],
                $rs->fields["board"],
                $rs->fields["amt"],
                $rs->fields["amt_unit"],
                $rs->fields["manu_name"],
                number_format($rs->fields["work_price"]) . "원",
                number_format($rs->fields["adjust_price"]) . "원",
                $rs->fields["valid_yn"],
                $rs->fields["output_work_report_seqno"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 공정별결과 인쇄 리스트 HTML
 */
function makePrintOpProcessResultListHtml($rs, $param) {
  
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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"getView('%s', 'print');\">보기</button>";
    $html .= "\n  </tr>";
    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($rs->fields["state"] != "705") {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $rs_html .= sprintf($html, $class, 
                    $rs->fields["print_work_report_seqno"],
                    $rs->fields["typset_num"],
                    $rs->fields["name"],
                    $rs->fields["affil"],
                    $rs->fields["beforeside_spc_tmpt"],
                    $rs->fields["aftside_tmpt"],
                    $rs->fields["aftside_spc_tmpt"],
                    $rs->fields["tot_tmpt"],
                    $rs->fields["size"],
                    $rs->fields["amt"],
                    $rs->fields["amt_unit"],
                    $rs->fields["manu_name"],
                    number_format($rs->fields["work_price"]) . "원",
                    number_format($rs->fields["adjust_price"]) . "원",
                    $rs->fields["valid_yn"],
                    $rs->fields["print_work_report_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 공정별결과 후공정 리스트 HTML
 */
function makeAfterOpProcessResultListHtml($rs, $param) {
  
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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"getView('%s', 'after');\">보기</button>";
    $html .= "\n  </tr>";
    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($rs->fields["state"] != "805") {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $name = $rs->fields["after_name"];
            $depth1 = $rs->fields["depth1"];
            $depth2 = $rs->fields["depth2"];     
            $depth3 = $rs->fields["depth3"]; 

            $rs_html .= sprintf($html, $class, 
                    $i,
                    $rs->fields["order_num"],
                    $rs->fields["cate_name"],
                    $name,
                    $depth1,
                    $depth2,
                    $depth3,
                    $rs->fields["amt"],
                    $rs->fields["amt_unit"],
                    $rs->fields["orderer"],
                    $rs->fields["manu_name"],
                    number_format($rs->fields["work_price"]) . "원",
                    number_format($rs->fields["adjust_price"]) . "원",
                    $rs->fields["valid_yn"],
                    $rs->fields["after_work_report_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

?>
