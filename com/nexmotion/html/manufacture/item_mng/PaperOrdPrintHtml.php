<?
//종이 발주 내역서
function makeTotalOrd($rs) {

    if (!$rs) {
        return false;
    }

    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = 0;
    $list = "";
    $tot_amt = 0;
    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
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

        $tmp_amt = "";
        //장->R = 장수 / 자리수(절수) / 500
        if ($rs->fields["amt_unit"] == "장") {
            $tmp_amt = (int)$rs->fields["amt"] / (int)$rs->fields["stor_subpaper"] / 500;
        } else {
            $tmp_amt = (int)$rs->fields["amt"];
        }

        $amt = "";
        if ($tmp_amt < 1 && $tmp_amt > 0) {
            $amt = number_format($tmp_amt, 1);
        } else {
            $amt = number_format($tmp_amt);
        }

        $paper_comp = $rs->fields["paper_comp"];
        $op_date = date("Y-m-d", strtotime($rs->fields["op_date"]));

        $tot_amt = $tot_amt + $tmp_amt;
        $list .= sprintf($html, $class, $i, $paper
                ,$rs->fields["op_affil"] . " " . $stor_subpaper . $rs->fields["stor_size"]
                ,$amt . "R"
                ,$rs->fields["grain"]
                ,$rs->fields["storplace"]
                ,"(". $rs->fields["op_degree"] . "차)" . $rs->fields["memo"]);
        $i++;
        $rs->moveNext();
    }

    $list .= "\n<tr><th style=\"background: #eee;\" colspan=\"2\">총계</td><td style=\"background: #eee;\" colspan=\"5\">" . number_format($tot_amt, 2) . " R</td></tr>";


    $html  = "\n<div class=\"tab-content\">";
    $html .= "\n  <div class=\"table-body\">";
    $html .= "\n    <div id=\"print_page_main\">";
    $html .= "\n      <label class=\"fs14\" style=\"float:left; margin-left:10px;\">지업사 : " . $paper_comp . "</label>";
    $html .= "\n      <label class=\"fs14\" style=\"float:right; margin-right:10px;\">조회일자 : " . $op_date . "</label>";
    $html .= "\n      <table class=\"table fix_width100f\">";
    $html .= "\n        <thead>";
    $html .= "\n          <tr>";
    $html .= "\n            <th class=\"bm2px\">번호</th>";
    $html .= "\n            <th class=\"bm2px\">종이명</th>";
    $html .= "\n            <th class=\"bm2px\">입고사이즈</th>";
    $html .= "\n            <th class=\"bm2px\">수량</th>";
    $html .= "\n            <th class=\"bm2px\">종이결</th>";
    $html .= "\n            <th class=\"bm2px\">입고처</th>";
    $html .= "\n            <th class=\"bm2px\">메모</th>";
    $html .= "\n          </tr>";
    $html .= "\n        </thead>";
    $html .= "\n        <tbody>";
    $html .= "\n" . $list;
    $html .= "\n        </tbody>";
    $html .= "\n      </table>";
    $html .= "\n    </div>";
    $html .= "\n    <label class=\"btn btn_md\" onclick=\"pagePrint(document.getElementById('print_page_main'), 'total'); return false;\" style=\"width:200px;\"><i class=\"fa fa-print\"></i> 종이 발주 내역서 인쇄</label>";
    $html .= "\n</div>";
    $html .= "\n<br />";

    return $html;
}

//종이 발주 지시서
function makePaperOrd($rs) {

    if (!$rs) {
        return false;
    }

    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = 0;
    $tb_arr = array();
    $tot_arr = array();
    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
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

        $tmp_amt = "";
        //장->R = 장수 / 자리수(절수) / 500
        if ($rs->fields["amt_unit"] == "장") {
            $tmp_amt = (int)$rs->fields["amt"] / (int)$rs->fields["stor_subpaper"] / 500;
        } else {
            $tmp_amt = (int)$rs->fields["amt"];
        }

        $amt = "";
        if ($tmp_amt < 1 && $tmp_amt > 0) {
            $amt = number_format($tmp_amt, 1);
        } else {
            $amt = number_format($tmp_amt);
        }

        $op_date = date("Y-m-d", strtotime($rs->fields["op_date"]));

        $tb_arr[$rs->fields["paper_comp"] . "::" . $op_date][$rs->fields["op_degree"]] .= sprintf($html
                ,$class
                ,$rs->fields["op_num"]
                ,$paper
                ,$rs->fields["op_affil"] . " " . $stor_subpaper . $rs->fields["stor_size"]
                ,$amt . "R"
                ,$rs->fields["grain"]
                ,$rs->fields["storplace"]
                ,$rs->fields["memo"]);

        if (!$tot_arr[$rs->fields["paper_comp"] . "::" . $op_date . $rs->fields["op_degree"]]) {
            $tot_arr[$rs->fields["paper_comp"] . "::" . $op_date . $rs->fields["op_degree"]] = 0;
        }

        $tot_arr[$rs->fields["paper_comp"] . "::" . $op_date . $rs->fields["op_degree"]] = (int)$tot_arr[$rs->fields["paper_comp"] . "::" . $op_date.$rs->fields["op_degree"]] + (int)$tmp_amt;

        $i++;
        $rs->moveNext();
    }

    $html  = "\n<div class=\"tab-content\">";
    $html .= "\n  <div class=\"table-body\">";
    $html .= "\n    <div id=\"print_page%s\">";
    $html .= "\n      <label class=\"fs14\"><%s></label>";
    $html .= "\n      <table class=\"table fix_width100f\">";
    $html .= "\n        <thead>";
    $html .= "\n          <tr>";
    $html .= "\n            <th class=\"bm2px\">발주번호</th>";
    $html .= "\n            <th class=\"bm2px\">종이명</th>";
    $html .= "\n            <th class=\"bm2px\">입고사이즈</th>";
    $html .= "\n            <th class=\"bm2px\">수량</th>";
    $html .= "\n            <th class=\"bm2px\">종이결</th>";
    $html .= "\n            <th class=\"bm2px\">입고처</th>";
    $html .= "\n            <th class=\"bm2px\">메모</th>";
    $html .= "\n          </tr>";
    $html .= "\n        </thead>";
    $html .= "\n        <tbody>";
    $html .= "\n          %s";
    $html .= "\n        </tbody>";
    $html .= "\n      </table>";
    $html .= "\n    </div>";
    $html .= "\n    <label class=\"btn btn_md\" onclick=\"pagePrint(document.getElementById('print_page%s')); return false;\"><i class=\"fa fa-print\"></i> 종이 발주서 인쇄</label>";
    $html .= "\n</div>";
    $html .= "\n<br />";

    $list_arr = array();
    $rs_html = "";
    foreach($tb_arr as $key => $value){
        foreach($value as $keys => $values){
            $tmp = explode("::", $key);
            $paper_comp = $tmp[0];
            $op_date = $tmp[1];
            $tot_list = "\n<tr><th style=\"background: #eee;\" colspan=\"2\">총계</td><td style=\"background: #eee;\" colspan=\"5\">" . number_format($tot_arr[$key.$keys], 2) . " R</td></tr>";
            $rs_html .= sprintf($html, $keys, $paper_comp 
                    . " (" . $op_date . ") " 
                    . $keys . "차 발주 (전절 기준 연수(R)", $values . $tot_list, $keys);
        }
    }

    return $rs_html;
}
?>
