<?
/*
 * 검색 list 생성
 *
 * return : list
 */
function makeSearchManuList($result) {

    $buff = "";

    while ($result && !$result->EOF) {

        $buff .= "<a href=\"#\" onclick=\"nameClick('";
        $buff .= $result->fields["extnl_etprs_seqno"];
        $buff .= "','" .$result->fields["manu_name"];
        $buff .= "','" . $result->fields["crn"];
        $buff .= "','" . $result->fields["repre_name"];
        $buff .= "','". $result->fields["addr"];
        if ($result->fields["addr_detail"]) {
            $buff .= " " . $result->fields["addr_detail"];
        }
        $buff .= "','" . $result->fields["bc"];
        $buff .= "','" . $result->fields["tob"] . "')\">";
        $buff .= "<li>" . $result->fields["manu_name"];
        $buff .= "</li></a>";

        $result->moveNext();
    }
    return $buff;
}

/**
 * @brief 매입리스트 HTML
 */
function makePurTabListHtml($conn, $dao, $rs, $param) {
  
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
    $html .= "\n   <td><button onclick=\"editPurTab('%s');\" class=\"bgreen btn_pu btn fix_height20 fix_width40\">보기</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class,
                $rs->fields["write_date"],
                $rs->fields["pur_cpn"],
                $rs->fields["crn"],
                $rs->fields["item"],
                number_format($rs->fields["supply_price"]),
                number_format($rs->fields["vat"]),
                number_format($rs->fields["tot_price"]),
                $rs->fields["pur_tab_seqno"]);

        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

?>
