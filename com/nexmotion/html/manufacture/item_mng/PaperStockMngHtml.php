<?
//종이 재고
function makePaperStockMngListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class='%s fwb'>%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type='button' class='green btn_pu btn fix_height22 fix_width40' onclick='paperStockView(%s)'>보기</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $paper = $rs->fields["paper_name"] . " " .
            $rs->fields["paper_dvs"] . " " .
            $rs->fields["paper_color"] . " " .
            $rs->fields["paper_basisweight"];

        $text_color = "red_text01";
        if ($rs->fields["stor_yn"] == "Y") {
            $text_color = "blue_text01";
        }

        $stor_amt = "0R";
        if ($rs->fields["stor_amt"]) {
            if ($rs->fields["stor_amt"] < 1) {
                $stor_amt = "<span class='blue_text01 fwb'>" . 
                    number_format($rs->fields["stor_amt"], 1) . "R</span>";
            } else {
                $stor_amt = "<span class='blue_text01 fwb'>" . 
                    number_format($rs->fields["stor_amt"]) . "R</span>";
            }
        }

        $use_amt = "0R";
        if ($rs->fields["use_amt"]) {
            if ($rs->fields["use_amt"] < 1) {
                $use_amt = "<span class='red_text01 fwb'>" . 
                    number_format($rs->fields["use_amt"], 1) . "R</span>";
            } else {
                $use_amt = "<span class='red_text01 fwb'>" . 
                    number_format($rs->fields["use_amt"]) . "R</span>";
            }
        }

        $stock_amt = "";
        if ($rs->fields["stock_amt"] < 1 && $rs->fields["stock_amt"] > 0) {
            $stock_amt = number_format($rs->fields["stock_amt"], 1);
        } else {
            $stock_amt = number_format($rs->fields["stock_amt"]);
        }

        $rs_html .= sprintf($html, 
                $class,
                $rs->fields["regi_date"],
                $rs->fields["manu"],
                $paper,
                $stor_amt,
                $use_amt,
                $text_color,
                $stock_amt . "R",
                $rs->fields["cont"],
                $rs->fields["manu_paper_stock_detail_seqno"]);

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
