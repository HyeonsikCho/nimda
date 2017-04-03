<?
//인쇄생산지시
function makePrintProduceOrd($rs) {

    $rs_html = "";
    $html  = "\n<tr>";
 //   $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n  <td>%s</td>";
    $html .= "\n</tr>";
    while ($rs && !$rs->EOF) {

        $rs_html .= sprintf($html, $rs->fields["manu_name"]
        //        , $rs->fields["theday_directions"]
                , $rs->fields["basic_seoul_directions"]
                , $rs->fields["basic_region_directions"]
                , $rs->fields["tot_directions"]);
        $rs->moveNext();
    }

    return $rs_html;
}

//인쇄생산지시 수정모드
function makePrintProduceOrdModiMode($conn, $dao, $rs) {

    $html = "";
    if ($rs && !$rs->EOF) {
        $html  = makeOrdPopup($conn, $dao, $rs);
        $html .= makeOrdPopup2($conn, $dao);
    } else {
        $html = makeOrdPopup2($conn, $dao);
    }

    return $html;
}

//지시 팝업 - 이전 저장 있는경우
function makeOrdPopup($conn, $dao, $rs) {

    $list = "";
    //제조사
    $optParam = array();
    $optParam["table"] = "extnl_etprs";
    $optParam["col"] = "extnl_etprs_seqno ,manu_name";
    $optParam["where"]["pur_prdt"] = "인쇄";

    while ($rs && !$rs->EOF) {

        $html  = "";
        $html .= "<tr>";
        $html .= "  <td>";
        $html .= "      <select class=\"fix_width150\" name=\"selManu[]\" disabled=\"true\">%s</select>";
        $html .= "  </td>";
//        $html .= "  <td>";
//        $html .= "      <input type=\"number\" class=\"input_co2\" name=\"theday[]\" min=\"0\" value=\"%s\" %s>";
//        $html .= "  </td>";
        $html .= "  <td>";
        $html .= "      <input type=\"number\" class=\"input_co2\" name=\"basic_seoul[]\" min=\"0\" value=\"%s\" %s>";
        $html .= "  </td>";
        $html .= "  <td>";
        $html .= "      <input type=\"number\" class=\"input_co2\" name=\"basic_region[]\" min=\"0\" value=\"%s\" %s>";
        $html .= "  </td>";
        $html .= "  <td style=\"text-align:center;\">";

        if ($i == $rs->_numOfRows) {
            $html .= "       <button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"plusLine(this); return false;\"><i class=\"fa fa-plus\"></i></button>";
            $html .= "       <button type=\"button\" style=\"display:none;\" class=\"btn btn-sm btn-warning\" onclick=\"minusLine(this); return false;\"><i class=\"fa fa-minus\"></i></button>";
        } else {
            $html .= "       <button type=\"button\" style=\"display:none;\" class=\"btn btn-sm btn-info\" onclick=\"plusLine(this); return false;\"><i class=\"fa fa-plus\"></i></button>";
            $html .= "       <button type=\"button\" class=\"btn btn-sm btn-warning\" onclick=\"minusLine(this); return false;\"><i class=\"fa fa-minus\"></i></button>";
        }

        $html .= "  </td>";
        $html .= "</tr>";

        $optRs = $dao->selectData($conn, $optParam);
        $option = "<option value=\"%s\" %s>%s</option>";
        $option_html = "";
        while ($optRs && !$optRs->EOF) {

            $selected = "";
            if ($rs->fields["extnl_etprs_seqno"] == $optRs->fields["extnl_etprs_seqno"]) {
                $selected = "selected";
            }

            $option_html .= sprintf($option
                    , $optRs->fields["extnl_etprs_seqno"]
                    , $selected
                    , $optRs->fields["manu_name"]);
            $optRs->moveNext();
        }

        $theday_readonly = "";
        if ($rs->fields["theday_exec"] != "0")
            $theday_readonly = "readonly";

        $basic_seoul_readonly = "";
        if ($rs->fields["basic_exec"] != "0")
            $basic_seoul_readonly = "readonly";

        $basic_region_readonly = "";
        if ($rs->fields["basic_exec"] != "0")
            $basic_region_readonly = "readonly";

        $list .= sprintf($html 
                , $option_html
                , $rs->fields["theday_directions"]
                , $theday_readonly
                , $rs->fields["basic_seoul_directions"]
                , $basic_seoul_readonly
                , $rs->fields["basic_region_directions"]
                , $basic_region_readonly);
        $rs->moveNext();
    }
    return $list;
}

//지시 팝업 - 이전 저장  없는경우
function makeOrdPopup2($conn, $dao) {

    //제조사
    $optParam = array();
    $optParam["table"] = "extnl_etprs";
    $optParam["col"] = "extnl_etprs_seqno ,manu_name";
    $optParam["where"]["pur_prdt"] = "인쇄";
    $optRs = $dao->selectData($conn, $optParam);
    $option = "<option value=\"%s\">%s</option>";
    $option_html = "";
    $option_html = "<option value=\"\" selected>인쇄업체(선택)</option>";
    
    while ($optRs && !$optRs->EOF) {
        $option_html .= sprintf($option
                                , $optRs->fields["extnl_etprs_seqno"]
                                , $optRs->fields["manu_name"]);
        $optRs->moveNext();
    }
 
    $html  = "";
    $html .= "<tr class=\"li selbg\">";
    $html .= "  <td>";
    $html .= "      <select class=\"fix_width150\" name=\"selManu[]\">%s</select>";
    $html .= "  </td>";
//    $html .= "  <td>";
//    $html .= "      <input type=\"number\" class=\"input_co2\" name=\"theday[]\" min=\"0\" value=\"0\">";
//    $html .= "  </td>";
    $html .= "  <td>";
    $html .= "      <input type=\"number\" class=\"input_co2\" name=\"basic_seoul[]\" min=\"0\" value=\"0\">";
    $html .= "  </td>";
    $html .= "  <td>";
    $html .= "      <input type=\"number\" class=\"input_co2\" name=\"basic_region[]\" min=\"0\" value=\"0\">";
    $html .= "  </td>";
    $html .= "  <td>";
    $html .= "      <button type=\"button\" onclick=\"plusLine(this); return false;\"class=\"btn btn-sm btn-info\"><i class=\"fa fa-plus\"></i></button>";
    $html .= "      <button type=\"button\" class=\"btn btn-sm btn-warning\" style=\"display:none;\" onclick=\"minusLine(this); return false;\"><i class=\"fa fa-minus\"></i></button>";
    $html .= "  </td>";
    $html .= "</tr>";

    return sprintf($html, $option_html);
}
?>
