<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');
/**
 * @brief 매출 대기리스트 HTML
 */
function makePublicStandByListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' class='check_box' name='standby_chk' value='%d'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><a href=\"#\"";
    $html .= " onclick=\"publicLayer('basic',";
    $html .= " %d,'%s','%s','%s','%s','%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '')\">";
    $html .= "%s</a></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" onclick=\"saveStateSeq(%d, '예외')\" class=\"bred btn_pu btn fix_height20 fix_width40\">삭제</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        $rs_html .= sprintf($html,
                $rs->fields["public_admin_seqno"],
                $rs->fields["req_date"],
                $rs->fields["crn"],
                $rs->fields["public_admin_seqno"],
                $rs->fields["public_date"],
                $rs->fields["cpn_admin_seqno"],
                $rs->fields["req_year"] . "년 " .
                $rs->fields["req_mon"] . "월",
                number_format($rs->fields["pay_price"]),
                number_format($rs->fields["card_price"]),
                number_format($rs->fields["money_price"]),
                number_format($rs->fields["etc_price"]),
                $rs->fields["oa"],
                $rs->fields["before_oa"],
                number_format($rs->fields["object_price"]),
                $rs->fields["corp_name"],
                $rs->fields["repre_name"],
                $rs->fields["crn"],
                $rs->fields["bc"],
                $rs->fields["tob"],
                $rs->fields["addr"],
                $rs->fields["zipcode"],
                $rs->fields["req_mon"],
                $param["day"],
                number_format($rs->fields["unitprice"]),
                number_format($rs->fields["supply_price"]),
                number_format($rs->fields["vat"]),
                $rs->fields["corp_name"],
                $rs->fields["tel_num"],
                $rs->fields["req_mon"],
                number_format($rs->fields["pay_price"]),
                number_format($rs->fields["money_price"]),
                number_format($rs->fields["card_price"]),
                number_format($rs->fields["etc_price"]),
                number_format($rs->fields["pay_price"]),
                number_format($rs->fields["object_price"]),
                $rs->fields["tab_public"],
                $rs->fields["public_admin_seqno"]
                );

        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 매출 현금영수증리스트 HTML
 */
function makeCashreceiptListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' class='check_box' name='cashreceipt_chk' value='%d'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" onclick=\"saveCashreceipt(%d, '대기')\" class=\"bred btn_pu btn fix_height20 fix_width40\">삭제</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        $evid_dvs = "0";
        if ($rs->fields["evid_dvs"] == "지출증빙용") {
            $evid_dvs = "1";
        }

        $rs_html .= sprintf($html,
                $rs->fields["public_admin_seqno"],
                $rs->fields["req_date"],
                $rs->fields["print_title"],
                $rs->fields["supply_price"],
                $rs->fields["vat"],
                $rs->fields["pay_price"],
                $evid_dvs,
                $rs->fields["cashreceipt_num"],
                $rs->fields["tel_num"],
                $rs->fields["tab_public"],
                $rs->fields["public_admin_seqno"]
                );

        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 매출 미발행(현금순매출)리스트 HTML
 */
function makeUnissuedListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' class='check_box' name='unissued_chk' value='%d'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" onclick=\"removeCashreceipt(%d)\" class=\"bred btn_pu btn fix_height20 fix_width40\">삭제</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        $evid_dvs = "0";
        if ($rs->fields["evid_dvs"] == "지출증빙용") {
            $evid_dvs = "1";
        }

        $rs_html .= sprintf($html,
                $rs->fields["public_admin_seqno"],
                $rs->fields["req_date"],
                $rs->fields["print_title"],
                $rs->fields["supply_price"],
                $rs->fields["vat"],
                $rs->fields["pay_price"],
                $evid_dvs,
                $rs->fields["cashreceipt_num"],
                $rs->fields["tel_num"],
                $rs->fields["public_admin_seqno"]
                );

        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 매출 세금계산서(발행완료)리스트 HTML
 */
function makePublicCompleteListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' class='check_box' name='complete_chk' value='%d'></td>";
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
    $html .= "\n    <td><a href=\"#\"";
    $html .= " onclick=\"publicLayer('basic',";
    $html .= " %d,'%s','%s','%s','%s','%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '')\">";
    $html .= "%s</a></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        $rs_html .= sprintf($html,
                $rs->fields["public_admin_seqno"],
                $i,
                $rs->fields["req_year"],
                $rs->fields["req_mon"],
                $param["day"],
                "1",
                "11",
                "",
                "",
                "",
                "",
                $rs->fields["public_admin_seqno"],
                $rs->fields["public_date"],
                $rs->fields["cpn_admin_seqno"],
                $rs->fields["req_year"] . "년 " .
                $rs->fields["req_mon"] . "월",
                number_format($rs->fields["pay_price"]),
                number_format($rs->fields["card_price"]),
                number_format($rs->fields["money_price"]),
                number_format($rs->fields["etc_price"]),
                number_format($rs->fields["oa"]),
                number_format($rs->fields["before_oa"]),
                number_format($rs->fields["object_price"]),
                $rs->fields["corp_name"],
                $rs->fields["repre_name"],
                $rs->fields["crn"],
                $rs->fields["bc"],
                $rs->fields["tob"],
                $rs->fields["addr"],
                $rs->fields["zipcode"],
                $rs->fields["req_mon"],
                $param["day"],
                number_format($rs->fields["unitprice"]),
                number_format($rs->fields["supply_price"]),
                number_format($rs->fields["vat"]),
                $rs->fields["corp_name"],
                $rs->fields["crn"],
                $rs->fields["tab_public"]
                );

        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 매출 예외리스트 HTML
 */
function makePublicExceptListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' class='check_box' name='standby_chk' value='%d'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><a href=\"#\"";
    $html .= " onclick=\"publicLayer('edit',";
    $html .= " %d,'%s','%s','%s','%s','%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '%s', '%s',";
    $html .= " '%s', '%s', '%s', '%s', '%s', '%s',";
    $html .= " '%s', '%s','%s', '%s', '%s')\">";
    $html .= "%s</a></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" onclick=\"saveStateSeq(%d, '대기')\" class=\"bred btn_pu btn fix_height20 fix_width40\">삭제</button></td>"; $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        $rs_html .= sprintf($html,
                $rs->fields["public_admin_seqno"],
                $rs->fields["req_date"],
                $rs->fields["crn"],
                $rs->fields["public_admin_seqno"],
                $rs->fields["public_date"],
                $rs->fields["cpn_admin_seqno"],
                $rs->fields["req_year"] . "년 " .
                $rs->fields["req_mon"] . "월",
                $rs->fields["pay_price"],
                $rs->fields["card_price"],
                $rs->fields["money_price"],
                $rs->fields["etc_price"],
                $rs->fields["oa"],
                $rs->fields["before_oa"],
                $rs->fields["object_price"],
                $rs->fields["corp_name"],
                $rs->fields["repre_name"],
                $rs->fields["crn"],
                $rs->fields["bc"],
                $rs->fields["tob"],
                $rs->fields["addr"],
                $rs->fields["zipcode"],
                $rs->fields["req_mon"],
                $param["day"],
                $rs->fields["unitprice"],
                $rs->fields["supply_price"],
                $rs->fields["vat"],
                $rs->fields["public_dvs"],
                $rs->fields["corp_name"],
                $rs->fields["tel_num"],
                $rs->fields["req_mon"],
                number_format($rs->fields["pay_price"]),
                number_format($rs->fields["pay_price"]),
                0,
                0,
                number_format($rs->fields["pay_price"]),
                number_format($rs->fields["object_price"]),
                $rs->fields["tab_public"],
                $rs->fields["public_admin_seqno"]
                );

        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}


?>
