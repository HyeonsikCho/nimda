<?php

/**
 * @brief 생산공정 구분 정의 출력 리스트 HTML
 */
function makeStorageListHtml($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr>";
    $html .= "\n    <td>%s</td>"; // NO
    $html .= "\n    <td>%s</td>"; // 주문명
    $html .= "\n    <td>%s</td>"; // 회원명
    $html .= "\n    <td>%s</td>"; // 인쇄물제목
    $html .= "\n    <td>%s(%s X %s)</td>"; // 상품정보
    $html .= "\n    <td>%s</td>"; // 배송
    $html .= "\n    <td>%s</td>"; // 상태
    $html .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"changeState('%s');\">웹로그인</button>"; // 관리
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
        } else if($rs->fields["state"] == "3120") {
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
            $rs->fields["order_regi_date"],
            $rs->fields["office_nick"],
            $rs->fields["title"],
            $rs->fields["order_detail"],
            $rs->fields["amt"],
            $rs->fields["count"],
            $dlvr_way,
            $state,
            $rs->fields["member_seqno"]); // 업체번호);
        $i--;
        $rs->moveNext();
    }

    if ($rs_html == "") {
        return "<tr><td colspan=\"10\">검색된 내용이 없습니다.</td></tr>";
    }

    return $rs_html;
}

?>