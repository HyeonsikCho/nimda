<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

//지시서 내용 리스트
function makeOrdList($conn, $dao, $param) {

    $rs = $dao->selectProduceOrdList($conn, $param);

    $html = "";
    $list_html  = "\n                                           <tr class=\"%s\">";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                               <td>%s</td>";
    $list_html .= "\n                                           </tr>";
    $i = 1;

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $amt = "";
        if ($rs->fields["amt"] < 1 && $rs->fields["amt"] > 0) {
            $amt = number_format($rs->fields["amt"], 1);
        } else {
            $amt = number_format($rs->fields["amt"]);
        }

        $html .= sprintf($list_html, $class, $i
                ,$rs->fields["typset_num"]
                ,$rs->fields["paper"]
                ,$rs->fields["size"]
                ,$rs->fields["print_tmpt"]
                ,$amt . "장"
                ,$rs->fields["specialty_items"]);
        $i++;
        $rs->moveNext();
    }

    return $html;
}

// 구분별 지시서 생성
function makeOrdDvs($conn, $dao, $param, $dvs_rs) {

    $html = "";
    while ($dvs_rs && !$dvs_rs->EOF) {

        $dvs = $dvs_rs->fields["dvs"];
        $param["dvs"] = $dvs;

        $tot_rs = $dao->selectProduceOrdDvs($conn, $param);
        $cnt = number_format($tot_rs->fields["cnt"]);
        $tot_amt = "";
        if ($tot_rs->fields["tot_amt"] < 1 && $tot_rs->fields["tot_amt"] > 0) {
            $tot_amt = number_format($tot_rs->fields["tot_amt"], 1);
        } else {
            $tot_amt = number_format($tot_rs->fields["tot_amt"]);
        }

        $html .= "\n                                 <table class=\"table fix_width100f\" style=\"margin:0;\">";
        $html .= "\n                                       <thead>";
        $html .= "\n                                           <tr>";
        $html .= "\n                                               <th colspan=\"7\" style=\"background-color: #ddd;\">" . $dvs . " (" . substr($param["date"], 0 ,10) . ")</th>";
        $html .= "\n                                           </tr>";
        $html .= "\n                                           <tr>";
        $html .= "\n                                               <th class=\"bm2px\">번호</th>";
        $html .= "\n                                               <th class=\"bm2px\">조판번호</th>";
        $html .= "\n                                               <th class=\"bm2px\">종이</th>";
        $html .= "\n                                               <th class=\"bm2px\">사이즈</th>";
        $html .= "\n                                               <th class=\"bm2px\">도수</th>";
        $html .= "\n                                               <th class=\"bm2px\">수량</th>";
        $html .= "\n                                               <th class=\"bm2px\">특기사항</th>";
        $html .= "\n                                           </tr>";
        $html .= "\n                                       </thead>";
        $html .= "\n                                       <tbody>";
        $html .= makeOrdList($conn, $dao, $param);
        $html .= "\n                                       </tbody>";
        $html .= "\n                                       <thead>";
        $html .= "\n                                           <tr>";
        $html .= "\n                                               <td colspan=\"5\"></td>";
        $html .= "\n                                               <th>판수량 합계 : " . $cnt . "</th>";
        $html .= "\n                                               <th>판매수 합계 : " . $tot_amt . "장</th>";
        $html .= "\n                                           </tr>";
        $html .= "\n                                       </thead>";
        $html .= "\n                                 </table>";

        $dvs_rs->moveNext();
    }

    return $html;
}

// 생산 지시서 생성
function makeProduceOrd($conn, $dao, $param) {

    $ord_dvs_rs = $dao->selectOrdDvs($conn, $param);

    $html = "";
    while ($ord_dvs_rs && !$ord_dvs_rs->EOF) {

       $ord_dvs = $ord_dvs_rs->fields["ord_dvs"];
       $param["ord_dvs"] = $ord_dvs;

       $dvs_rs = $dao->selectDvs($conn, $param);

       $html .= "\n                                 <table class=\"table fix_width100f\" style=\"margin:0;\">";
       $html .= "\n                                     <thead>";
       $html .= "\n                                         <tr><th style=\"background-color: #D4D4D4; border:0px;\">생산지시서 [" . $ord_dvs . "]</th></tr>";
       $html .= "\n                                     </thead>";
       $html .= "\n                                 </table>";
       $html .= makeOrdDvs($conn, $dao, $param, $dvs_rs);
       $html .= "\n                                 <br />";

       $ord_dvs_rs->moveNext();
   }

   return $html;
}

// 생산지시서 총합 리스트
function makeTotalList($conn, $dao, $param) {

    $dvs_arr = array();
    $dvs_arr[0] = "명함";
    $dvs_arr[1] = "스티커";
    $dvs_arr[2] = "전단";

    $td_html  = "";
    $td_html .= "<td>%s</td>";
    $td_html .= "<td>%s</td>";
    $td_html .= "<td>%s</td>";

    $i = 0;
    $th = "";
    $th2 = "";
    $board_arr = array();
    $page_arr = array();
    $b_amt = "";
    $p_amt = "";
    foreach ($dvs_arr as $key => $value) {

        $th .= "\n<th colspan=\"" . count($dvs_arr) . "\">" . $value . "</th>";
        $th2 .= "<th>당일</th><th>본</th><th>전체</th>";
        $param["dvs"] = $dvs_arr[$i];
        $rs = $dao->selectTotalList($conn, $param);

        while ($rs && !$rs->EOF) {

            $tmp_dvs = explode("_", $rs->fields["dvs"]);

            $board_arr[$tmp_dvs[1]] = (int)$board_arr[$tmp_dvs[1]] + 1;
            $page_arr[$tmp_dvs[1]] = (int)$page_arr[$tmp_dvs[1]] + (int)$rs->fields["amt"];

            $rs->moveNext();
        }

        $b_amt .= sprintf($td_html, number_format($board_arr["당일판"]), 
                number_format($board_arr["본판"]), 
                number_format((int)$board_arr["당일판"] + (int)$board_arr["본판"]));
        $p_amt .= sprintf($td_html, number_format($page_arr["당일판"]), 
                number_format($page_arr["본판"]), 
                number_format((int)$page_arr["당일판"] + (int)$page_arr["본판"]));
        $i++;
    }

//    $html = "<br />";
    $html  = "\n<table class=\"table fix_width100f\">";
    $html .= "\n  <thead>";
    $html .= "\n    <tr>";
    $html .= "\n      <th rowspan=\"2\"></th>";
    $html .= $th;
    $html .= "\n    </tr>";
    $html .= "\n    <tr>";
    $html .= $th2;
    $html .= "\n    </tr>";
    $html .= "\n  </thead>";
    $html .= "\n  <tbody>";
    $html .= "\n    <tr>";
    $html .= "\n      <th style=\"background: #eee;\">판수</th>";
    $html .= $b_amt;
    $html .= "\n    </tr>";
    $html .= "\n    <tr>";
    $html .= "\n      <th style=\"background: #eee;\">장수</th>";
    $html .= $p_amt;
    $html .= "\n    </tr>";
    $html .= "\n  </tbody>";
    $html .= "\n</table>";

    return $html;
}
?>
