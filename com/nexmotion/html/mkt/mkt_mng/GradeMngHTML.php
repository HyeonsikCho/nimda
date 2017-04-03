<?
/* 
 * 회원 관리 list 생성 
 * $result : $result->fields["grade_name"] = "등급 이름" 
 * $result : $result->fields["grade_dscr"] = "등급 설명" 
 * $result : $result->fields["sales_start_price"] = "매출 최소 가격" 
 * $result : $result->fields["sales_end_price"] = "매출 최대 가격" 
 * $result : $result->fields["sales_sale_rate"] = "매출 할인 비율" 
 * $result : $result->fields["grade"] = "등급" 
 * $result : $result->fields["sales_give_point"] = "매출 혜택 포인트" 
 * $result : $result->fields["member_grade_policy_seqno"] 
 *                                          = "회원 등급 정책 일련번호" 
 * 
 * return : list
 */
function makeGradeMngList($result) {

    $ret = "";

    $i = 1;

    while ($result && !$result->EOF) {

        $name = $result->fields["grade_name"];
        $grade = $result->fields["grade"];
        $start_price = $result->fields["sales_start_price"];
        $end_price = $result->fields["sales_end_price"];
        $sale_rate = $result->fields["sales_sale_rate"];
        $give_point = $result->fields["sales_give_point"];
        $dsrc = $result->fields["grade_dscr"];
        $grade_seqno = $result->fields["member_grade_policy_seqno"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <input type=\"hidden\" name=\"grade_set%d\" value=\"%d\">";
        $list .= "\n    <td><input type=\"text\" name=\"grade_name%d\" class=\"input_co2 fix_width75\" value=\"%s\"></td>";
        $list .= "\n    <td><input type=\"text\" class=\"input_co2 fix_width20\" value=\"%s\" disabled></td>";
        $list .= "\n    <td><input type=\"text\" name=\"start_price%d\" class=\"input_co2 fix_width100\" value=\"%d\"> ~ ";
        $list .= "\n    <input type=\"text\" name=\"end_price%d\" class=\"input_co2 fix_width100\" value=\"%d\"></td>";
        $list .= "\n    <td><input type=\"text\" name=\"sale_rate%d\" class=\"input_co2 fix_width40\" value=\"%s\">%%</td>";
        $list .= "\n    <td><input type=\"text\" name=\"give_point%d\" class=\"input_co2 fix_width40\" value=\"%s\">P</td>";
        $list .= "\n    <td><input type=\"text\" name=\"dscr%d\" class=\"input_co2 fix_width400\" value=\"%s\"></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, 
                        $grade_seqno, $grade_seqno, $grade_seqno, $name, 
                        $grade, $grade_seqno, $start_price,
                        $grade_seqno, $end_price, $grade_seqno, $sale_rate, 
                        $grade_seqno, $give_point, $grade_seqno, $dsrc); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}

/* 
 * 회원 등급별 포인트 통계 list 생성 
 * $result : $result->fields["year"] = "년도" 
 * $result : $result->fields["dvs"]  = "구분" 
 * $result : $result->fields["member_grade"] = "회원 등급" 
 * $result : $result->fields["mon"] = "월" 
 * $result : $result->fields["point"] = "포인트" 
 * return : list
 */
function makeGradePointList($give_rs, $use_rs, $gradearr = "") {

    $ret = "";

    $i = 1;

    while ($give_rs && !$give_rs->EOF) {

        $year = $give_rs->fields["year"];
        $dvs = $give_rs->fields["dvs"];
        $grade = $give_rs->fields["member_grade"];
        $mon = $give_rs->fields["mon"];

        $give_point = "";
        $use_point = "";

        while ($use_rs && !$use_rs->EOF) {

            $use_year = $use_rs->fields["year"];
            $use_dvs = $use_rs->fields["dvs"];
            $use_grade = $use_rs->fields["member_grade"];
            $use_mon = $use_rs->fields["mon"];

            if ($year == $use_year && 
                    $mon == $use_mon && $grade == $use_grade) {

                $use_point = $use_rs->fields["point"];
            }

            $use_rs->moveNext();
        }

        $give_point = $give_rs->fields["point"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td>%d</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";

        $list .= "\n  </tr>";

        $grade_name = $grade;
        if ($gradearr != "") {
            $grade_name = $gradearr[$grade];
        }

        $ret .= sprintf($list, 
                        $i,
                        $grade_name,
                        $give_point,
                        $use_point); 

        $give_rs->moveNext();
        $use_rs->moveFirst();
        $i++; 
    }

    return $ret;
}


?>
