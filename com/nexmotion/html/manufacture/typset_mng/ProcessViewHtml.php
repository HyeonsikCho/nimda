<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeProcessViewListHtml($conn, $dao, $rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td>%s</td>";
//    $html .= "\n    <td width=\"200px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"goNextState('%s', '%s'); return false;\" %s>공정진행</button> <button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width79\" onclick=\"goBeforeState('%s', '%s'); return false;\" %s>공정되돌리기</button></td>";
    $html .= "\n    <td width=\"80px;\"><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"goNextState('%s', '%s'); return false;\" %s>공정진행</button></td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $n_dis = "";
        $p_dis = "";
        $typset_num = $rs->fields["typset_num"];

        $param = array();
        $param["table"] = "paper_op";
        $param["col"] = "state";
        $param["where"]["typset_num"] = $typset_num;

        $paper_state = $dao->selectData($conn, $param)->fields["state"];

        $state_arr = $_SESSION["state_arr"];

        $paper_state = $util->statusCode2status($paper_state);

        if ($paper_state == "종이발주대기") {
            $paper_td_color = "";
        } else if ($paper_state == "종이발주취소") {
            $paper_td_color = "style=\"background-color:red; color:white;\"";
        } else if ($paper_state == "종이발주완료") {
            $paper_td_color = "style=\"background-color:skyblue; color:white;\"";
        } else if ($paper_state == "종이입고완료") {
            $paper_td_color = "style=\"background-color:blue; color:white;\"";
        } else {
            $paper_state = "재고";
            $paper_td_color = "style=\"background-color:blue; color:white;\"";
        } 

        $output_td_color = "";
        $output_state = "";
        $print_td_color = "";
        $print_state = "";
        $after_td_color = "";
        $after_state = "";

        $state = $rs->fields["state"];
        if ($state > 2000 && $state < 2300) {
            $output_state = $util->statusCode2status($state);
            if ($output_state == "출력대기") {
                $output_td_color = "";
            } else if ($output_state == "출력중") {
                $output_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($output_state == "출력보류") {
                $output_td_color = "style=\"background-color:yellow;\"";
            } else if ($output_state == "출력취소") {
                $output_td_color = "style=\"background-color:red; color:white;\"";
            }
            $print_td_color = "";
            $print_state = "준비";
            $after_td_color = "";
            $after_state = "준비";
            $p_dis = "disabled=\"disabled\"";
        } else if ($state > 2300 && $state < 2400) {
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_state = $util->statusCode2status($state);
            if ($print_state == "인쇄대기") {
                $print_td_color = "";
            } else if ($print_state == "인쇄중") {
                $print_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($print_state == "인쇄보류") {
                $print_td_color = "style=\"background-color:yellow;\"";
            } else if ($print_state == "인쇄취소") {
                $print_td_color = "style=\"background-color:red; color:white;\"";
            }
            $after_td_color = "";
            $after_state = "준비";
        } else if ($state > 2400 && $state < 2520) {
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $after_state = $util->statusCode2status($state);
            if ($after_state == "조판후공정대기") {
                $after_td_color = "";
            } else if ($after_state == "조판후공정중") {
                $after_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($after_state == "조판후공정보류") {
                $after_td_color = "style=\"background-color:yellow;\"";
            } else if ($after_state == "조판후공정취소") {
                $after_td_color = "style=\"background-color:red; color:white;\"";
            } else if ($after_state == "주문후공정대기") {
                $after_td_color = "style=\"background-color:blue; color:white;\"";
                $after_state = "조판후공정완료";
            } else {
                $after_td_color = "";
                $after_state = "준비";
            }
        } else {
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $after_td_color = "style=\"background-color:blue; color:white;\"";
            $after_state = "조판후공정완료";
            $n_dis = "disabled=\"disabled\"";
        }

        $rs_html .= sprintf($html, $class,
                $i,
                $typset_num,
                $paper_td_color,
                $paper_state,
                $output_td_color,
                $output_state,
                $print_td_color,
                $print_state,
                $after_td_color,
                $after_state,
                $rs->fields["specialty_items"],
                $rs->fields["typset_num"],
                $rs->fields["state"],
                $n_dis);
//                $n_dis,
//                $rs->fields["typset_num"],
//                $rs->fields["state"],
//                $p_dis);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
