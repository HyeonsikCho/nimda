<?
/*
 * 배송 친구 list html 생성
 * $result : SQL Result 
 *
 * return : html list
 */
function makeDlvrFriend($result) {

    $ret = "";
    $i=1;

    while ($result && !$result->EOF) {

        $regi_date = $result->fields["regi_date"];
        $addr = $result->fields["addr"];
        $addr_detail = $result->fields["addr_detail"];
        $tel_num = $result->fields["tel_num"];
        $name = $result->fields["member_name"];
        $nick = $result->fields["office_nick"];
        $main_yn = $result->fields["dlvr_friend_main"];
        $member_seqno = $result->fields["member_seqno"];

        //메인 여부
        if ($main_yn == "Y") {

            $type="Main";

        } else {

            $type="Sub";

        }

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td><button onclick=\"showDetail(%d);\" class=\"green btn_pu btn fix_height20 fix_width40\">보기</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $regi_date, $type, $name . "(" . $nick . ")"
                       ,$addr . " " . $addr_detail, $tel_num
                       ,$member_seqno);

        $result->moveNext();
        $i++;
    }

    return $ret; 
}

/*
 * 배송 친구 메인 요청 list html 생성
 * $result : SQL Result 
 *
 * return : html list
 */
function makeMainReqList($result) {

    $ret = "";
    $i=1;

    while ($result && !$result->EOF) {

        $seqno = $result->fields["member_seqno"];
        $main_seqno = $result->fields["dlvr_friend_main_seqno"];
        $regi_date = $result->fields["regi_date"];
        $addr = $result->fields["addr"];
        $addr_detail = $result->fields["addr_detail"];
        $tel_num = $result->fields["tel_num"];
        $name = $result->fields["member_name"];
        $nick = $result->fields["office_nick"];
        $main_yn = $result->fields["dlvr_friend_main"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }

        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td><button onclick=\"showDetail(%d);\" class=\"green btn_pu btn fix_height20 fix_width40\">보기</button></td>";
        $list .= "\n      <td><button onclick=\"mainReqList(%d, %d)\" class=\"orge btn_pu btn fix_height20 fix_width40\">수정</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $regi_date,  $name . "(" . $nick . ")"
                       ,$addr . " " . $addr_detail, $tel_num
                       ,$seqno, $main_seqno, $seqno);

        $result->moveNext();
        $i++;
    }

    return $ret; 
}

/*
 * 배송 친구 서브 요청 list html 생성
 * $result : SQL Result 
 *
 * return : html list
 */
function makeSubReqList($result) {

    $ret = "";
    $i=1;

    while ($result && !$result->EOF) {

        $sub_member_seqno = $result->fields["sub_member_seqno"];
        $sub_date = $result->fields["sub_date"];
        $sub_seqno = $result->fields["dlvr_friend_sub_seqno"];
        $sub_name = $result->fields["sub_name"];
        $sub_nick = $result->fields["sub_nick"];
        $sub_addr = $result->fields["sub_addr"];
        $sub_detail = $result->fields["sub_detail"];
        $sub_tel = $result->fields["sub_tel"];
        $main_seqno = $result->fields["dlvr_friend_main_seqno"];
        $main_member_seqno = $result->fields["main_member_seqno"];
        $main_name = $result->fields["main_name"];
        $main_nick = $result->fields["main_nick"];
        $main_addr = $result->fields["main_addr"];
        $main_detail = $result->fields["main_detail"];
        $main_tel = $result->fields["main_tel"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td><button onclick=\"showDetail(%d);\" class=\"green btn_pu btn fix_height20 fix_width40\">보기</button></td>";
        $list .= "\n      <td><button onclick=\"subReqList(%d, %d, %d)\" class=\"orge btn_pu btn fix_height20 fix_width40\">수정</button></button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $sub_date, $sub_name . " <span style=\"color:blue; font-weight: bold;\">[" . $sub_nick . "]</span>"
                       ,$sub_addr . " " . $sub_detail, $sub_tel
                       ,$main_name . "(" . $main_nick . ")",$main_addr . " " . $main_detail
                       ,$main_tel, $sub_member_seqno, $main_member_seqno
                       ,$main_seqno, $sub_seqno);

        $result->moveNext();
        $i++;
    }

    return $ret; 
}

/*
 * 배송 친구 메인 요청 list html 생성
 * $result : SQL Result 
 *
 * return : html list
 */
function makeDlvrMainList($result) {

    $ret = "";
    $i=1;

    while ($result && !$result->EOF) {

        $member_name = $result->fields["member_name"];
        $office_nick = $result->fields["office_nick"];
        $addr = $result->fields["addr"];
        $addr_detail = $result->fields["addr_detail"];
        $tel_num = $result->fields["tel_num"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $member_name . " <span style=\"color:blue; font-weight: bold;\">[" . $office_nick . "]</span>", $addr . " " .  $addr_detail
                       ,$tel_num);

        $result->moveNext();
        $i++;
    }

    return $ret; 
}

/*
 * 배송 친구 메인 list html 생성
 * $result : SQL Result 
 *
 * return : html list
 */
function makeDlvrMainSelectList($result, $main_seqno) {

    $ret = "";
    $i=1;

    while ($result && !$result->EOF) {

        $office_nick = $result->fields["office_nick"];
        $member_seqno = $result->fields["member_seqno"];
        $addr = $result->fields["addr"];
        $addr_detail = $result->fields["addr_detail"];
        $tel_num = $result->fields["tel_num"];

        $checked = "";
        if ($main_seqno == $member_seqno) {

            $checked = "checked=\"checked\"";

        } 

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n      <td><input name=\"main_radio\" value=\"%d\" type=\"radio\" %s/></td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n      <td>%s</td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $member_seqno, $checked, $office_nick
                       ,$addr . " " .  $addr_detail, $tel_num);

        $result->moveNext();
        $i++;
    }

    return $ret; 
}
?>
