<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
/**
 * @brief 조판 리스트 - 낱장 HTML
 */
function makeFlattYTypsetListHtml($rs, $param) {
  
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
    $html .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"openProducePop('flatt_y', '%s', '%s')\">조판</button>\n<button type=\"button\" class=\"bred btn_pu btn fix_height20 fix_width40\" onclick=\"delTypset('flatt_y', '%s')\">삭제</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $state = "";
        if ($rs->fields["state"] == "2130") {
            $state = "조판중";
        } else {
            $state = "조판완료";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["typset_num"],
                $rs->fields["print_title"],
                $rs->fields["regi_date"],
                $rs->fields["name"],
                $state,
                $rs->fields["sheet_typset_seqno"],
                $rs->fields["state"],
                $rs->fields["sheet_typset_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판 리스트 - 책자 HTML
 */
function makeFlattNTypsetListHtml($rs, $param) {
  
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
    $html .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"openProducePop('flatt_n', '%s', '%s')\">조판</button>\n<button type=\"button\" class=\"bred btn_pu btn fix_height20 fix_width40\" onclick=\"delTypset('flatt_n', '%s')\">삭제</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $state = "";
        if ($rs->fields["state"] == "2130") {
            $state = "조판중";
        } else {
            $state = "조판완료";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["typset_num"],
                $rs->fields["print_title"],
                $rs->fields["regi_date"],
                $rs->fields["name"],
                $state,
                $rs->fields["brochure_typset_seqno"],
                $rs->fields["state"],
                $rs->fields["brochure_typset_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판 대기 리스트 - 낱장 HTML
 */
function makeFlattYTypsetStandbyListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"order_common_seqno\" value=\"%s\" onclick=\"changeOrderNum();\" %s></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"showOrderContent('%s')\">보기</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $checked = "";
        if ($i == 1) {
            $checked = "checked=\"checked\"";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["order_common_seqno"],
                $checked,
                $rs->fields["sell_site"],
                $rs->fields["order_detail_file_num"],
                $rs->fields["member_name"],
                $rs->fields["title"],
                $rs->fields["order_detail"],
                $rs->fields["amt"],
                $rs->fields["amt_unit_dvs"],
                $rs->fields["req_cont"],
                $rs->fields["order_common_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}


/**
 * @brief 조판 대기 리스트 - 책자 HTML
 */
function makeFlattNTypsetStandbyListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"order_common_seqno\" value=\"%s\" onclick=\"changeOrderNum();\" %s></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"showOrderContent('%s')\">보기</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $checked = "";
        if ($i == 1) {
            $checked = "checked=\"checked\"";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["order_common_seqno"],
                $checked,
                $rs->fields["sell_site"],
                substr($rs->fields["order_detail_dvs_num"], 1),
                $rs->fields["member_name"],
                $rs->fields["title"],
                $rs->fields["order_detail"],
                $rs->fields["page"],
                $rs->fields["amt_unit_dvs"],
                $rs->fields["memo"],
                $rs->fields["order_common_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판 정보리스트 HTML
 */
function makeTypsetInfoListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $indd_down_html = "<a href=\"/common/typset_format_file_down.php?seqno=%s\"><img style=\"width:20px;margin-right:5px;\" src=\"/design_template/images/ai_icon.png\" alt=\"\" /></a>";
    $cdr_down_html = "<a href=\"/common/typset_format_file_down.php?seqno=%s\"><img style=\"width:20px;margin-right:5px;\" src=\"/design_template/images/cdr_icon.png\" alt=\"\" /></a>";
    $psd_down_html = "<a href=\"/common/typset_format_file_down.php?seqno=%s\"><img style=\"width:20px;margin-right:5px;\" src=\"/design_template/images/ps_icon.png\" alt=\"\" /></a>";

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"typset_info\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class=\"fwb\">%s*%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1;

    while ($rs && !$rs->EOF) {
        $down = "";

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        if (strlen(trim($param["indd_seqno"])) > 0) {
            $down .= sprintf($indd_down_html, $param["indd_seqno"]);
        }

        if (strlen(trim($param["cdr_seqno"])) > 0) {
            $down .= sprintf($cdr_down_html, $param["cdr_seqno"]);
        }

        if (strlen(trim($param["psd_seqno"])) > 0) {
            $down .= sprintf( $psd_down_html, $param["psd_seqno"]);
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["typset_format_seqno"],
                $rs->fields["name"],
                $rs->fields["affil"],
                $rs->fields["subpaper"],
                $rs->fields["wid_size"],
                $rs->fields["vert_size"],
                $rs->fields["dscr"],
                $down);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 출력 등록업체 리스트 HTML
 */
function makeOutputInfoListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"output_info\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class=\"fwb\">%s*%s</span></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["output_seqno"],
                $rs->fields["manu_name"],
                $rs->fields["brand_name"],
                $rs->fields["output_name"],
                $rs->fields["affil"],
                $rs->fields["wid_size"],
                $rs->fields["vert_size"],
                $rs->fields["board"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 인쇄 등록업체 리스트 HTML
 */
function makePrintInfoListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"print_info\" value=\"%s\"></td>";
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

        $rs_html .= sprintf($html, $class, 
                $rs->fields["print_seqno"],
                $rs->fields["manu_name"],
                $rs->fields["brand_name"],
                $rs->fields["print_name"],
                $rs->fields["affil"],
                $rs->fields["wid_size"],
                $rs->fields["vert_size"]);
        $i++;
        $rs->moveNext();
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

        $rs_html .= sprintf($html, $class, 
                $rs->fields["paper_seqno"],
                $rs->fields["manu_name"],
                $rs->fields["brand_name"],
                $rs->fields["paper_name"],
                $rs->fields["affil"],
                $rs->fields["wid_size"],
                $rs->fields["vert_size"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 종이 발주 리스트 HTML
 */
function makePaperOpListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();
    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"checkbox\" name=\"selPaper\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><span class='%s fwb'>%s</span></td>";
    $html .= "\n    %s";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";

        } else if ($i % 2 == 1) {
            $class = "";
        }

        $date = "";
        if ($rs->fields["op_date"]) {
            $date = $rs->fields["op_date"];
        }

        $state_btn = "<td><button  type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"modiPaperOpView('" . $rs->fields["paper_op_seqno"] .  "');\">보기</button> <button type=\"button\" class=\"bred btn_pu btn fix_height20 fix_width40\" onclick=\"delPaperOp('" . $rs->fields["paper_op_seqno"] .  "', '" . $rs->fields["state"] . "');\">취소</button></td>";

        if ($rs->fields["state"] == "510") {
            $state_color = "";
        } else if ($rs->fields["state"] == "520") {
            $state_color = "blue_text01";
        } else if ($rs->fields["state"] == "530") {
            $state_color = "red_text01";
            $state_btn = "<td></td>";
        }
 
        $state = $util->statusCode2status($rs->fields["state"]);
        $rs_html .= sprintf($html, $class, 
                $rs->fields["paper_op_seqno"],
                $rs->fields["paper_op_seqno"],
                $rs->fields["paper_name"],
                $rs->fields["op_affil"],
                $rs->fields["amt"],
                $rs->fields["amt_unit"],
                $rs->fields["manu_name"],
                $rs->fields["orderer"],
                $rs->fields["typ_detail"],
                $date,
                $state_color,
                $state,
                $state_btn);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 후공정 등록업체 리스트 HTML
 */
function makeAfterInfoListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"radio\" name=\"after_info\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["after_seqno"],
                $rs->fields["manu_name"],
                $rs->fields["brand_name"],
                $rs->fields["after_name"],
                $rs->fields["depth1"],
                $rs->fields["depth2"],
                $rs->fields["depth3"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 후공정지시서 리스트 HTML
 */
function makeAfterOpListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();
    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button  type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"modiAfterOpView('%s');\">보기</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $seq_btn = "";
        if ($rs->fields["basic_yn"] == "N") {
            $disabled_up = "";
            $disabled_down = "";

            if ($rs->fields["seq"] == 1) {
                $disabled_up = "disabled=\"disabled\"";
            }

            if ($param["maxseq"] == $rs->fields["seq"]) {
                $disabled_down = "disabled=\"disabled\"";
            }

            $seq_btn = "<button type=\"button\" class=\"btn btn-xs btn-info\" onclick=\"getSeqUp('" . $rs->fields["order_common_seqno"] . "' , '" . $rs->fields["seq"] . "');\" " . $disabled_up . ">▲</button> <button type=\"button\" class=\"btn btn-xs btn-info\" onclick=\"getSeqDown('" . $rs->fields["order_common_seqno"] . "', '" . $rs->fields["seq"] . "');\" " . $disabled_down . ">▼</button></td>";
        }

        $rs_html .= sprintf($html, $class, 
                $seq_btn,
                $rs->fields["after_op_seqno"],
                $rs->fields["after_name"],
                $rs->fields["amt"],
                $rs->fields["amt_unit"],
                $rs->fields["manu_name"],
                $rs->fields["op_typ_detail"],
                $rs->fields["after_op_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 옵션 등록업체 리스트 HTML
 */
function makeOptInfoListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"checkbox\" name=\"opt_chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["opt_seqno"],
                $rs->fields["name"],
                $rs->fields["depth1"],
                $rs->fields["depth2"],
                $rs->fields["depth3"],
                $rs->fields["amt"],
                $rs->fields["crtr_unit"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 옵션 발주 리스트 HTML
 */
function makeOptOpListHtml($rs, $param) {
  
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
    $html .= "\n    <td><button type=\"button\" class=\"bred btn_pu btn fix_height20 fix_width40\" onclick=\"delOptDirections('%s');\">삭제</button></td>";
    $html .= "\n  </tr>";

    $rs_html .= sprintf($html, $param["class"], 
            $rs->fields["name"],
            $rs->fields["depth1"],
            $rs->fields["depth2"],
            $rs->fields["depth3"],
            $rs->fields["amt"],
            $rs->fields["crtr_unit"],
            $param["order_opt_history_seqno"]);

    return $rs_html;
}
?>
