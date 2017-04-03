<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeOutputOpProcessListHtml($rs, $param) {

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
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"getAdd('%s', 'output');\">작업일지</button>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["출력준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);

            if ($state == "인쇄대기" || $state == "후공정대기") {
                $state = "출력완료";
            }
            $rs_html .= sprintf($html, $class,
                    $i,
                    $rs->fields["typset_num"],
                    $rs->fields["name"],
                    $rs->fields["affil"],
                    $rs->fields["size"],
                    $rs->fields["board"],
                    $rs->fields["amt"] . $rs->fields["amt_unit"],
                    $rs->fields["manu_name"],
                    $state,
                    $rs->fields["output_op_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeWaitInListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' name='cb_order' val='%s'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s<span onclick=\"lookdetail('%s')\" onmouseover=\"\" style=\"cursor: pointer;\">[상세보기]</span></td>"; // 재질 및 규격
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"changeState('%s');\">입고완료</button>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {
        $state = "";
        $rs->fields['order_regi_date'] = substr($rs->fields['order_regi_date'],0,10);
        $rs->fields['receipt_regi_date'] = substr($rs->fields['receipt_regi_date'],0,10);
        if ($rs->fields["state"] == "110") {
            $state = "생산중";
        } else if($rs->fields["state"] == "210") {
            $state = "생산중";
        } else if($rs->fields["state"] == "310") {
            $state = "생산중";
        } else if($rs->fields["state"] == "410") {
            $state = "생산중";
        } else if($rs->fields["state"] == "910") {
            $state = "입고대기";
        } else if($rs->fields["state"] == "950") {
            $state = "출고대기";
        } else if($rs->fields["state"] == "010") {
            $state = "배송중";
        } else if($rs->fields["state"] == "011") {
            $state = "배송완료";
        }

        $dlvr_way = "";
        if ($rs->fields["dlvr_way"] == "01") {
            $dlvr_way = "택배";
        } else if ($rs->fields["dlvr_way"] == "02"){
            $dlvr_way = "직배";
        } else if ($rs->fields["dlvr_way"] == "03"){
            $dlvr_way = "화물";
        } else if ($rs->fields["dlvr_way"] == "04"){
            $dlvr_way = "퀵(오토바이)";
        } else if ($rs->fields["dlvr_way"] == "05"){
            $dlvr_way = "퀵(지하철)";
        } else if ($rs->fields["dlvr_way"] == "06"){
            $dlvr_way = "방문(인현동)";
        } else if ($rs->fields["dlvr_way"] == "07"){
            $dlvr_way = "방문(필동)";
        }

        $rs_html .= sprintf($html,
            $rs->fields["order_detail_dvs_num"],
            $i,
            $rs->fields["office_nick"],
            $rs->fields["order_regi_date"],
            $rs->fields["receipt_regi_date"],
            $rs->fields["title"],
            $rs->fields["order_detail"],
            $rs->fields["member_seqno"], // 업체번호
            $rs->fields["amt"],
            $rs->fields["count"],
            $dlvr_way,
            $state,
            $rs->fields["order_detail_dvs_num"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeWaitOutListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' name='cb_order' val='%s'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s<span onclick=\"lookdetail('%s')\" onmouseover=\"\" style=\"cursor: pointer;\">[상세보기]</span></td>"; // 재질 및 규격
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"changeState('%s');\">출고완료</button>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {
        $state = "";
        $rs->fields['order_regi_date'] = substr($rs->fields['order_regi_date'],0,10);
        $rs->fields['receipt_regi_date'] = substr($rs->fields['receipt_regi_date'],0,10);
        if ($rs->fields["state"] == "110") {
            $state = "생산중";
        } else if($rs->fields["state"] == "210") {
            $state = "생산중";
        } else if($rs->fields["state"] == "310") {
            $state = "생산중";
        } else if($rs->fields["state"] == "410") {
            $state = "생산중";
        } else if($rs->fields["state"] == "910") {
            $state = "입고대기";
        } else if($rs->fields["state"] == "950") {
            $state = "출고대기";
        } else if($rs->fields["state"] == "010") {
            $state = "배송중";
        } else if($rs->fields["state"] == "011") {
            $state = "배송완료";
        }

        $dlvr_way = "";
        if ($rs->fields["dlvr_way"] == "01") {
            $dlvr_way = "택배";
        } else if ($rs->fields["dlvr_way"] == "02"){
            $dlvr_way = "직배";
        } else if ($rs->fields["dlvr_way"] == "03"){
            $dlvr_way = "화물";
        } else if ($rs->fields["dlvr_way"] == "04"){
            $dlvr_way = "퀵(오토바이)";
        } else if ($rs->fields["dlvr_way"] == "05"){
            $dlvr_way = "퀵(지하철)";
        } else if ($rs->fields["dlvr_way"] == "06"){
            $dlvr_way = "방문(인현동)";
        } else if ($rs->fields["dlvr_way"] == "07"){
            $dlvr_way = "방문(필동)";
        }

        $rs_html .= sprintf($html,
            $rs->fields["order_detail_dvs_num"],
            $i,
            $rs->fields["office_nick"],
            $rs->fields["order_regi_date"],
            $rs->fields["receipt_regi_date"],
            $rs->fields["title"],
            $rs->fields["order_detail"],
            $rs->fields["member_seqno"], // 업체번호
            $rs->fields["amt"],
            $rs->fields["count"],
            $dlvr_way,
            $state,
            $rs->fields["order_detail_dvs_num"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

function makeDeliveryIngListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td><input type='checkbox' name='cb_order' val='%s'></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s<span onclick=\"lookdetail('%s')\" onmouseover=\"\" style=\"cursor: pointer;\">[상세보기]</span></td>"; // 재질 및 규격
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"changeState('%s');\">배송완료</button>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {
        $state = "";
        $rs->fields['order_regi_date'] = substr($rs->fields['order_regi_date'],0,10);
        $rs->fields['receipt_regi_date'] = substr($rs->fields['receipt_regi_date'],0,10);
        if ($rs->fields["state"] == "110") {
            $state = "생산중";
        } else if($rs->fields["state"] == "210") {
            $state = "생산중";
        } else if($rs->fields["state"] == "310") {
            $state = "생산중";
        } else if($rs->fields["state"] == "410") {
            $state = "생산중";
        } else if($rs->fields["state"] == "910") {
            $state = "입고대기";
        } else if($rs->fields["state"] == "950") {
            $state = "출고대기";
        } else if($rs->fields["state"] == "010") {
            $state = "배송중";
        } else if($rs->fields["state"] == "011") {
            $state = "배송완료";
        }

        $dlvr_way = "";
        if ($rs->fields["dlvr_way"] == "01") {
            $dlvr_way = "택배";
        } else if ($rs->fields["dlvr_way"] == "02"){
            $dlvr_way = "직배";
        } else if ($rs->fields["dlvr_way"] == "03"){
            $dlvr_way = "화물";
        } else if ($rs->fields["dlvr_way"] == "04"){
            $dlvr_way = "퀵(오토바이)";
        } else if ($rs->fields["dlvr_way"] == "05"){
            $dlvr_way = "퀵(지하철)";
        } else if ($rs->fields["dlvr_way"] == "06"){
            $dlvr_way = "방문(인현동)";
        } else if ($rs->fields["dlvr_way"] == "07"){
            $dlvr_way = "방문(필동)";
        }

        $rs_html .= sprintf($html,
            $rs->fields["order_detail_dvs_num"],
            $i,
            $rs->fields["office_nick"],
            $rs->fields["order_regi_date"],
            $rs->fields["receipt_regi_date"],
            $rs->fields["title"],
            $rs->fields["order_detail"],
            $rs->fields["member_seqno"], // 업체번호
            $rs->fields["amt"],
            $rs->fields["count"],
            $dlvr_way,
            $state,
            $rs->fields["order_detail_dvs_num"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

function makeDeliveryCompleteListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s<span onclick=\"lookdetail('%s')\" onmouseover=\"\" style=\"cursor: pointer;\">[상세보기]</span></td>"; // 재질 및 규격
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {
        $state = "";
        $rs->fields['order_regi_date'] = substr($rs->fields['order_regi_date'], 0, 10);
        $rs->fields['receipt_regi_date'] = substr($rs->fields['receipt_regi_date'], 0, 10);
        if ($rs->fields["state"] == "110") {
            $state = "생산중";
        } else if($rs->fields["state"] == "210") {
            $state = "생산중";
        } else if($rs->fields["state"] == "310") {
            $state = "생산중";
        } else if($rs->fields["state"] == "410") {
            $state = "생산중";
        } else if($rs->fields["state"] == "910") {
            $state = "입고대기";
        } else if($rs->fields["state"] == "950") {
            $state = "출고대기";
        } else if($rs->fields["state"] == "010") {
            $state = "배송중";
        } else if($rs->fields["state"] == "011") {
            $state = "배송완료";
        }

        $dlvr_way = "";
        if ($rs->fields["dlvr_way"] == "01") {
            $dlvr_way = "택배";
        } else if ($rs->fields["dlvr_way"] == "02"){
            $dlvr_way = "직배";
        } else if ($rs->fields["dlvr_way"] == "03"){
            $dlvr_way = "화물";
        } else if ($rs->fields["dlvr_way"] == "04"){
            $dlvr_way = "퀵(오토바이)";
        } else if ($rs->fields["dlvr_way"] == "05"){
            $dlvr_way = "퀵(지하철)";
        } else if ($rs->fields["dlvr_way"] == "06"){
            $dlvr_way = "방문(인현동)";
        } else if ($rs->fields["dlvr_way"] == "07"){
            $dlvr_way = "방문(필동)";
        }

        $rs_html .= sprintf($html,
            $i,
            $rs->fields["office_nick"],
            $rs->fields["order_regi_date"],
            $rs->fields["receipt_regi_date"],
            $rs->fields["title"],
            $rs->fields["order_detail"],
            $rs->fields["member_seqno"], // 업체번호
            $rs->fields["amt"],
            $rs->fields["count"],
            $dlvr_way,
            $state);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 생산공정 구분 정의 인쇄 리스트 HTML
 */
function makePrintOpProcessListHtml($rs, $param) {

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
    $html .= "\n    <td>%s%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"getAdd('%s', 'print');\">작업일지</button>";
    $html .= "\n  </tr>";
    
    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["인쇄준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);

            if ($state == "조판후공정대기") {
                $state = "인쇄완료";
            }
            $rs_html .= sprintf($html, $class,
                    $i,
                    $rs->fields["typset_num"],
                    $rs->fields["name"],
                    $rs->fields["beforeside_tmpt"],
                    $rs->fields["beforeside_spc_tmpt"],
                    $rs->fields["aftside_tmpt"],
                    $rs->fields["aftside_spc_tmpt"],
                    $rs->fields["tot_tmpt"],
                    $rs->fields["size"],
                    $rs->fields["amt"],
                    $rs->fields["amt_unit"],
                    $rs->fields["manu_name"],
                    $state,
                    $rs->fields["print_op_seqno"],
                    $rs->fields["print_op_seqno"],
                    $rs->fields["print_op_seqno"],
                    $rs->fields["print_op_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 생산공정 구분 정의 조판별 후공정 리스트 HTML
 */
function makeBasicAfterOpProcessListHtml($rs, $param) {

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
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"getAdd('%s', 'basic_after');\">작업일지</button>";
    $html .= "\n  </tr>";
    
    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        $state_arr = $_SESSION["state_arr"];
        if ($rs->fields["state"] != $state_arr["조판후공정준비"]) {

            if ($i % 2 == 0) {
                $class = "cellbg";
            } else if ($i % 2 == 1) {
                $class = "";
            }

            $state = $util->statusCode2status($rs->fields["state"]);
            if ($state == "주문후공정대기") {
                $state = "조판후공정완료";
            }

            $name = $rs->fields["after_name"];
            $depth1 = $rs->fields["depth1"];
            $depth2 = $rs->fields["depth2"];
            $depth3 = $rs->fields["depth3"];

            $rs_html .= sprintf($html, $class,
                    $i,
                    $rs->fields["typset_num"],
                    $name,
                    $depth1,
                    $depth2,
                    $depth3,
                    $rs->fields["amt"] . $rs->fields["amt_unit"],
                    $rs->fields["orderer"],
                    $rs->fields["manu_name"],
                    $state,
                    $rs->fields["basic_after_op_seqno"],
                    $rs->fields["basic_after_op_seqno"],
                    $rs->fields["basic_after_op_seqno"],
                    $rs->fields["basic_after_op_seqno"]);
            $i--;
        }
        $rs->moveNext();
    }

    return $rs_html;
}

/**
* @brief 생산공정 구분 정의 주문별 후공정 리스트 HTML
*/
function makeAfterOpProcessListHtml($rs, $param) {

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
   $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"getAdd('%s', 'after');\">작업일지</button>";
   $html .= "\n  </tr>";
   
   $i = $param["cnt"] - $param["s_num"];

   $util = new CommonUtil();

   while ($rs && !$rs->EOF) {

       $state_arr = $_SESSION["state_arr"];
       if ($rs->fields["state"] != $state_arr["주문후공정준비"]) {

           if ($i % 2 == 0) {
               $class = "cellbg";
           } else if ($i % 2 == 1) {
               $class = "";
           }

           $state = $util->statusCode2status($rs->fields["state"]);
           if ($state == "입고대기") {
               $state = "주문후공정완료";
           }

           $name = $rs->fields["after_name"];
           $depth1 = $rs->fields["depth1"];
           $depth2 = $rs->fields["depth2"];
           $depth3 = $rs->fields["depth3"];

           $rs_html .= sprintf($html, $class,
                   $i,
                   $rs->fields["order_num"],
                   $name,
                   $depth1,
                   $depth2,
                   $depth3,
                   $rs->fields["amt"] . $rs->fields["amt_unit"],
                   $rs->fields["orderer"],
                   $rs->fields["manu_name"],
                   $state,
                   $rs->fields["after_op_seqno"],
                   $rs->fields["after_op_seqno"],
                   $rs->fields["after_op_seqno"],
                   $rs->fields["after_op_seqno"]);
           $i--;
       }
       $rs->moveNext();
   }

   return $rs_html;
}

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

        $html .= sprintf($list_html, $class, $i
                ,$rs->fields["typset_num"]
                ,$rs->fields["paper"]
                ,$rs->fields["size"]
                ,$rs->fields["print_tmpt"]
                ,$rs->fields["amt"] . $rs->fields["amt_unit"]
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
        $cnt = $tot_rs->fields["cnt"];
        $tot_amt = $tot_rs->fields["tot_amt"];

        $html .= "\n                                 <table class=\"table fix_width100f\">";
        $html .= "\n                                       <thead>";
        $html .= "\n                                           <tr>";
        $html .= "\n                                               <th colspan=\"7\">" . $dvs . " (" . substr($param["date"], 0 ,10) . ")</th>";
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
        $html .= "\n                                               <th>판매수 합계 : " . $tot_amt . "</th>";
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

       $html .= "\n                                 <table class=\"table fix_width100f\">";
       $html .= "\n                                     <thead>";
       $html .= "\n                                         <tr><th>생산지시서 [" . $ord_dvs . "]</th></tr>";
       $html .= "\n                                     </thead>";
       $html .= "\n                                 </table>";
       $html .= makeOrdDvs($conn, $dao, $param, $dvs_rs);

       $ord_dvs_rs->moveNext();
   }

   return $html;
}
?>
