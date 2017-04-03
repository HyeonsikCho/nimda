<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeOrderProcessViewListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n    <td %s>%s</td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    $util = new CommonUtil();

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $order_num = $rs->fields["order_num"];

        $state_arr = $_SESSION["state_arr"];

        $receipt_td_color = "";
        $receipt_state = "주문대기";
        $typset_td_color = "";
        $typset_state = "준비";
        $output_td_color = "";
        $output_state = "준비";
        $print_td_color = "";
        $print_state = "준비";
        $basic_after_td_color = "";
        $basic_after_state = "준비";
        $after_td_color = "";
        $after_state = "준비";
        $stor_td_color = "";
        $stor_state = "준비";
        $release_td_color = "";
        $release_state = "준비";
        $dlvr_td_color = "";
        $dlvr_state = "준비";

        $state = $rs->fields["order_state"];
        if ($state > 1300 && $state < 2000) {
            $receipt_state = $util->statusCode2status($state);
            if ($receipt_state == "접수대기") {
                $receipt_td_color = "";
            } else if ($receipt_state == "접수중") {
                $receipt_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($receipt_state == "QC대기" || 
                    $receipt_state == "시안요청중" || 
                    $receipt_state == "시안확인완료" || 
                    $receipt_state == "접수보류") {
                $receipt_td_color = "style=\"background-color:yellow;\"";
            }

        } else if ($state > 2100 && $state < 2200) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";

            $typset_state = $util->statusCode2status($state);
            if ($typset_state == "조판대기") {
                $typset_td_color = "";
            } else if ($typset_state == "조판중") {
                $typset_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($typset_state == "조판누락") {
                $typset_td_color = "style=\"background-color:yellow;\"";
            }

        } else if ($state > 2200 && $state < 2300) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";

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

        } else if ($state > 2300 && $state < 2400) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
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

        } else if ($state > 2400 && $state < 2500) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";

            $basic_after_state = $util->statusCode2status($state);
            if ($basic_after_state == "조판후공정대기") {
                $basic_after_td_color = "";
            } else if ($basic_after_state == "조판후공정중") {
                $basic_after_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($basic_after_state == "조판후공정보류") {
                $basic_after_td_color = "style=\"background-color:yellow;\"";
            } else if ($basic_after_state == "조판후공정취소") {
                $basic_after_td_color = "style=\"background-color:red; color:white;\"";
            }

        } else if ($state > 2500 && $state < 3000) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $basic_after_td_color = "style=\"background-color:blue; color:white;\"";
            $basic_after_state = "조판후공정완료";

            $after_state = $util->statusCode2status($state);
            if ($after_state == "주문후공정대기") {
                $after_td_color = "";
            } else if ($after_state == "주문후공정중") {
                $after_td_color = "style=\"background-color:orange; color:white;\"";
            } else if ($after_state == "주문후공정보류") {
                $after_td_color = "style=\"background-color:yellow;\"";
            } else if ($after_state == "주문후공정취소") {
                $after_td_color = "style=\"background-color:red; color:white;\"";
            }

        } else if ($state > 3000 && $state < 3200) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $basic_after_td_color = "style=\"background-color:blue; color:white;\"";
            $basic_after_state = "조판후공정완료";
            $after_td_color = "style=\"background-color:blue; color:white;\"";
            $after_state = "주문후공정완료";

            $stor_state = $util->statusCode2status($state);
            if ($stor_state == "입고대기") {
                $stor_td_color = "";
            } else if ($stor_state == "입고중") {
                $stor_td_color = "style=\"background-color:orange; color:white;\"";
            }

        } else if ($state > 3200 && $state < 3300) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $basic_after_td_color = "style=\"background-color:blue; color:white;\"";
            $basic_after_state = "조판후공정완료";
            $after_td_color = "style=\"background-color:blue; color:white;\"";
            $after_state = "주문후공정완료";
            $stor_td_color = "style=\"background-color:blue; color:white;\"";
            $stor_state = "입고완료";

            $release_state = $util->statusCode2status($state);
            if ($release_state == "출고대기") {
                $release_td_color = "";
            } else if ($release_state == "출고중") {
                $release_td_color = "style=\"background-color:orange; color:white;\"";
            }

        } else if ($state > 3300 && $state < 3400) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $basic_after_td_color = "style=\"background-color:blue; color:white;\"";
            $basic_after_state = "조판후공정완료";
            $after_td_color = "style=\"background-color:blue; color:white;\"";
            $after_state = "주문후공정완료";
            $stor_td_color = "style=\"background-color:blue; color:white;\"";
            $stor_state = "입고완료";
            $release_td_color = "style=\"background-color:blue; color:white;\"";
            $release_state = "출고완료";

            $dlvr_state = $util->statusCode2status($state);
            if ($dlvr_state == "배송대기") {
                $dlvr_td_color = "";
            } else if ($dlvr_state == "배송중") {
                $dlvr_td_color = "style=\"background-color:orange; color:white;\"";
            }
        } else if ($state > 3400) {
            $receipt_td_color = "style=\"background-color:blue; color:white;\"";
            $receipt_state = "접수완료";
            $typset_td_color = "style=\"background-color:blue; color:white;\"";
            $typset_state = "조판완료";
            $output_td_color = "style=\"background-color:blue; color:white;\"";
            $output_state = "출력완료";
            $print_td_color = "style=\"background-color:blue; color:white;\"";
            $print_state = "인쇄완료";
            $basic_after_td_color = "style=\"background-color:blue; color:white;\"";
            $basic_after_state = "조판후공정완료";
            $after_td_color = "style=\"background-color:blue; color:white;\"";
            $after_state = "주문후공정완료";
            $stor_td_color = "style=\"background-color:blue; color:white;\"";
            $stor_state = "입고완료";
            $release_td_color = "style=\"background-color:blue; color:white;\"";
            $release_state = "출고완료";
            $dlvr_td_color = "style=\"background-color:blue; color:white;\"";
            $dlvr_state = "배송완료";
        } 

        $rs_html .= sprintf($html, $class,
                $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>",
                $rs->fields["title"],
                $order_num,
                $receipt_td_color,
                $receipt_state,
                $typset_td_color,
                $typset_state,
                $output_td_color,
                $output_state,
                $print_td_color,
                $print_state,
                $basic_after_td_color,
                $basic_after_state,
                $after_td_color,
                $after_state,
                $stor_td_color,
                $stor_state,
                $release_td_color,
                $release_state,
                $dlvr_td_color,
                $dlvr_state);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
