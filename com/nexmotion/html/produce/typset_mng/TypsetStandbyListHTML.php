<?
/**
 * @brief 조판대기 리스트 - 낱장형 HTML
 */
function makeFlattYTypsetStandbyListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"checkbox\" name=\"flatt_y_chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s/%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"showDivPopup('%s', '%s', '%s');\">수정</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if (!$rs->fields["sheet_typset_seqno"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $amt = $rs->fields["amt"];

            if (!$amt) {
                $amt = $rs->fields["tot_amt"];
            }

            $rs_html .= sprintf($html, $class, 
                    $rs->fields["amt_order_detail_sheet_seqno"],
                    $rs->fields["sell_site"],
                    $rs->fields["order_detail_file_num"],
                    $rs->fields["member_name"] . "(" . $rs->fields["office_nick"] . ")",
                    $rs->fields["title"],
                    $rs->fields["order_detail"],
                    $rs->fields["oper_sys"],
                    $amt,
                    $rs->fields["tot_amt"],
                    $rs->fields["amt_unit_dvs"],
                    //$rs->fields["order_detail_seqno"],
                    $rs->fields["order_detail_count_file_seqno"],
                    $rs->fields["tot_amt"],
                    'flatt_y');
            $i++;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판대기 리스트 - 책자형 HTML
 */
function makeFlattNTypsetStandbyListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"checkbox\" name=\"flatt_n_chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s/%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"showDivPopup('%s', '%s', '%s');\">수정</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {
        //테스트중에 리스트값 나오게 하기 위해
        //if (!$rs->fields["brochure_typset_seqno"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $rs_html .= sprintf($html, $class, 
                    $rs->fields["page_order_detail_brochure_seqno"],
                    $rs->fields["sell_site"],
                    substr($rs->fields["order_detail_dvs_num"], 1),
                    $rs->fields["member_name"] . "(" . $rs->fields["office_nick"] . ")",
                    $rs->fields["title"],
                    $rs->fields["cate_name"],
                    $rs->fields["oper_sys"],
                    $rs->fields["page"],
                    $rs->fields["page_amt"],
                    //책자의 경우 낱장과 다르게 seqno 가 아닌 order_detail_dvs_num 사용
                    $rs->fields["order_detail_dvs_num"],
                    $rs->fields["page_amt"],
                    'flatt_n');
            $i++;
        //}
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판대기 낱장형 임시 리스트 HTML
 */
function makeTmpTypsetStandbyListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s' id=\"del_tr_flatt_y_%s\">";
    $html .= "\n    <td><input type=\"checkbox\" name=\"tmp_chk\" value=\"%s\" order_detail_paper=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s/%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    if ($rs->EOF == 1) {
        $rs_html = "<tr><td colspan=\"11\">추가 된 내용이 없습니다.</td></tr>";
    } else {
        while ($rs && !$rs->EOF) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $amt = $rs->fields["amt"];

            if (!$amt) {
                $amt = $rs->fields["tot_amt"];
            }

            $order_detail_paper_arr = explode(' / ', $rs->fields["order_detail"]);

            $rs_html .= sprintf($html, $class, 
                    $rs->fields["amt_order_detail_sheet_seqno"],
                    $rs->fields["amt_order_detail_sheet_seqno"],
                    $order_detail_paper_arr[1],
                    $rs->fields["sell_site"],
                    $rs->fields["order_detail_file_num"],
                    $rs->fields["member_name"],
                    $rs->fields["title"],
                    $rs->fields["order_detail"],
                    $rs->fields["oper_sys"],
                    $amt,
                    $rs->fields["tot_amt"],
                    $rs->fields["amt_unit_dvs"]);
            $i++;
            $rs->moveNext();
        }
    }

    return $rs_html;
}

/**
 * @brief 조판대기 책장형 임시 리스트 HTML
 */
function makeBrochureTmpTypsetStandbyListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s' id=\"del_tr_flatt_n_%s\">";
    $html .= "\n    <td><input type=\"checkbox\" name=\"brochure_tmp_chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s/%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    if ($rs->EOF == 1) {
        $rs_html = "<tr><td colspan=\"11\">추가 된 내용이 없습니다.</td></tr>";
    } else {
        while ($rs && !$rs->EOF) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $rs_html .= sprintf($html, $class, 
                    $rs->fields["page_order_detail_brochure_seqno"],
                    $rs->fields["page_order_detail_brochure_seqno"],
                    $rs->fields["sell_site"],
                    substr($rs->fields["order_detail_dvs_num"], 1),
                    $rs->fields["member_name"],
                    $rs->fields["title"],
                    $rs->fields["cate_name"],
                    $rs->fields["page"],
                    $rs->fields["page_amt"]);
            $i++;
            $rs->moveNext();
        }
    }
    return $rs_html;
}

?>
