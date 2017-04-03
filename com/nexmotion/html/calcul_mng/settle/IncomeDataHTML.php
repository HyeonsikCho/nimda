<?
/* 
 * 금전출납부 이체자료 list  생성 
 * $result : $result->fields["regi_date"] = "등록일자" 
 * $result : $result->fields["evid_date"] = "증빙일자" 
 * $result : $result->fields["depo_withdraw_path"] = "입출금경로" 
 * $result : $result->fields["depo_withdraw_path_detail"] = "입출금경로상세" 
 * $result : $result->fields["income_price"] = "수입 금액" 
 * $result : $result->fields["trsf_income_price"] = "이체 수입 금액" 
 * 
 * return : list
 */
function makeIncomeList($result, $list_count) {

    $ret = "";
    $i = 1 * ($list_count+1);

    while ($result && !$result->EOF) {
        //sales_price, adjust_price, depo_price
        $office_nick = $result->fields["office_nick"];
        $update_date = $result->fields["update_date"];
        $member_seqno = $result->fields["member_seqno"];
        $sales_price = $result->fields["sales_price"];
        $adjust_price = $result->fields["adjust_price"];
        $depo_price = $result->fields["depo_price"];


        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }

        $list .= "\n    <td>%s</td>"; // 회원명
        $list .= "\n    <td>%s</td>"; // 매출총액
        $list .= "\n    <td>%s</td>"; // 조정총액
        $list .= "\n    <td>%s</td>"; // 순매출총액
        $list .= "\n    <td>%s</td>"; // 입금총액
        $list .= "\n    <td><button type=\"button\" class=\"bgreen btn_pu btn fix_height20 fix_width60\" onclick=\"showWithdraw('%s', '%s');\">보기</button></td>"; // 입금내역보기
        $list .= "\n  </tr>";


        $sum = $sales_price - $adjust_price;
        $ret .= sprintf($list, $office_nick, number_format($sales_price), number_format($adjust_price), number_format($sum),
            number_format($depo_price), $member_seqno, $update_date);

        $result->moveNext();
        $i++;
    }

    return $ret;
}
?>
