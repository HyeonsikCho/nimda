<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 수동조판 주문 리스트 HTML - 낱장형
 */
function makeOrderListHtml($rs, $param) {

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
//    $html .= "\n    <td>%s</td>";
//    $html .= "\n    <td width=\"150px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openTypsetPop('%s');\">분판</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $state = $util->statusCode2status($rs->fields["order_state"]);

        $rs_html .= sprintf($html, $class,
                $rs->fields["amt_order_detail_sheet_seqno"],
                $rs->fields["sell_site"],
                $rs->fields["order_num"],
                $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>",
                $rs->fields["title"],
                $rs->fields["order_detail"],
                number_format($rs->fields["page_cnt"]) . "장 (" . number_format($rs->fields["amt"]) . $rs->fields["amt_unit_dvs"] . ")",
                number_format($rs->fields["count"]) . "건",
                $state);
               // $rs->fields["order_common_seqno"]
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판 리스트 HTML - 낱장형
 */
function makeSheetTypsetListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"200px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openTypsetPop('%s');\">조판</button> <button type=\"button\" class=\"bred btn_pu btn fix_height20 fix_width60\" onclick=\"cancelTypset('%s'); return false;\">조판취소</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $state = $util->statusCode2status($rs->fields["state"]);

        $rs_html .= sprintf($html, $class,
                $i,
                $rs->fields["typset_num"],
                $rs->fields["specialty_items"],
                $state,
                $rs->fields["typset_num"],
                $rs->fields["typset_num"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 수동조판 주문 리스트 HTML - 낱장형
 */
function makeSheetTypsetOrderListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class=\"list_ctrl%s b_list %s\">";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
//    $html .= "\n    <td width=\"50px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"orderFn.pop('%s'); return false;\">분판</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $i, $class,
                $rs->fields["sell_site"],
                $rs->fields["order_num"],
                $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>",
                $rs->fields["title"],
                $rs->fields["order_detail"],
                $rs->fields["dlvr_produce_dvs"],
                number_format($rs->fields["page_cnt"]) . "장 (" . number_format($rs->fields["amt"]) . $rs->fields["amt_unit_dvs"] . ")",
//                number_format($rs->fields["count"]) . "건",
//                $rs->fields["amt_order_detail_sheet_seqno"]);
                number_format($rs->fields["count"]) . "건");
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 조판 리스트 HTML - 책자형
 */
function makeBrochureTypsetListHtml($rs, $param) {

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
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"150px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"openTypsetPop('%s');\">조판</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $state = $util->statusCode2status($rs->fields["order_state"]);

        $rs_html .= sprintf($html, $class,
                $rs->fields["sell_site"],
                $rs->fields["order_num"],
                $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>",
                $rs->fields["title"],
                $rs->fields["order_detail"],
                $state,
                $rs->fields["order_common_seqno"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 주문상세책자 리스트 HTML
 */
function makeOrderDetailBrochureListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class=\"list_ctrl%s b_list %s\">";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td width=\"125px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"orderFn.view('%s', %d, '%s', '%s'); return false;\">선택</button> <button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"orderFn.pop('%s'); return false;\">분판</button></td>";
    $html .= "\n  </tr>";

    $i = 0;

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $i, $class,
                $rs->fields["order_detail_dvs_num"],
                $rs->fields["typset_num"],
                $rs->fields["typ"],
                $rs->fields["order_detail"],
                $param["dlvr_produce_dvs"],
                $rs->fields["page"] . "P",
                $rs->fields["amt"] . $rs->fields["amt_unit_dvs"],
                $rs->fields["order_detail_brochure_seqno"],
                $i,
                $rs->fields["typset_num"],
                $rs->fields["page_order_detail_brochure_seqno"],
                $rs->fields["order_detail_dvs_num"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 종이발주 리스트 HTML
 */
function makeTypsetPaperOpMngListHtml($rs, $param) {
  
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
        if ($rs->fields["amt"] < 1) {
            $amt = number_format($rs->fields["amt"], 1);
        } else {
            $amt = number_format($rs->fields["amt"]);
        }

        $rs_html .= sprintf($html, 
                $class,
                $rs->fields["paper_op_seqno"], $rs->fields["op_num"],
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
function makeAfterOpListHtml($conn, $dao, $rs) {
  
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
    $html .= "\n    <td><button  type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"afterFn.view('%s');\">보기</button></td>";
    $html .= "\n  </tr>";

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $param = array();
        $param["table"] = "extnl_brand";
        $param["col"] = "extnl_etprs_seqno";
        $param["where"]["extnl_brand_seqno"] = $rs->fields["extnl_brand_seqno"];

        $extnl_etprs_seqno = $dao->selectData($conn, $param)->fields["extnl_etprs_seqno"];

        $param = array();
        $param["table"] = "extnl_etprs";
        $param["col"] = "manu_name";
        $param["where"]["extnl_etprs_seqno"] = $extnl_etprs_seqno;

        $manu_name = $dao->selectData($conn, $param)->fields["manu_name"];

        $rs_html .= sprintf($html, $class, 
                $rs->fields["after_name"],
                $rs->fields["depth1"],
                $rs->fields["depth2"],
                $rs->fields["depth3"],
                $rs->fields["amt"] . $rs->fields["amt_unit"],
                $manu_name,
                $rs->fields["basic_after_op_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
