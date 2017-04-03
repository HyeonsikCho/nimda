<?
/* 
 * 조정 list  생성 
 * $result : $result->fields["cont"] = "내용" 
 * $result : $result->fields["deal_date"] = "거래일자" 
 * $result : $result->fields["price"] = "금액" 
 * $result : $result->fields["dvs"] = "입력구분" 
 * $result : $result->fields["dvs_detail"] = "입력구분상세" 
 * $result : $result->fields["adjust_seqno"] = "조정 일련번호" 
 * 
 * return : list
 */
function makeSalesList($result, $list_count) {

    $ret = "";
    $i = 1 * ($list_count+1);

    while ($result && !$result->EOF) {

        $regi_date = $result->fields["order_regi_date"];
        $depar_name = $result->fields["depar_name"];
        $oper_sys = $result->fields["oper_sys"];
        $cate_name = $result->fields["cate_name"];
        $price = $result->fields["pay_price"];
        $point_price = $result->fields["use_point_price"];
        $grade_price = $result->fields["grade_sale_price"];
        $event_price = $result->fields["event_price"];
        $cp_price = $result->fields["cp_price"];
        
        $discount = $point_price + $grade_price 
                    + $event_price + $cp_price;

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $regi_date, $depar_name, $oper_sys,
                $cate_name, number_format($price), number_format($discount));

        $result->moveNext();
        $i++;
    }

    return $ret;
}







?>

