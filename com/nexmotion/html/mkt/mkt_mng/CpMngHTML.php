<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/doc/mkt/mkt_mng/CpListDOC.php");
/* 
 * 쿠폰 list  생성 
 * $result : $result->fields["cp_name"] = "쿠폰명"
 * $result : $result->fields["val"] = "할인 되는 값"
 * $result : $result->fields["max_sale_price"] = "최대 할인 금액"
 * $result : $result->fields["min_order_price"] = "최소 주문 금액"
 * $result : $result->fields["unit"] = "할인 단위"
 * $result : $result->fields["object_appoint_way"] = "대상 지정 방법"
 * $result : $result->fields["cp_expo_yn"] = "노출여부"
 * $result : $result->fields["public_amt"] = "발행 수량"
 * $result : $result->fields["cp_seqno"] = "쿠폰 일련번호"
 * $result : $result->fields["public_period_start_date"] = "발행일 시작일자"
 * $result : $result->fields["public_period_end_date"] = "발행일 종료일자"
 * $result : $result->fields["usehour_yn"] = "사용시간 여부"
 * $result : $result->fields["usehour_start_hour"] = "사용시간 시작 시간"
 * $result : $result->fields["usehour_end_hour"] = "사용시간 종료 시간"
 * $result : $result->fields["expire_dvs"] = "만료일 여부"
 * $result : $result->fields["expire_public_day"] = "만료 발행 일"
 * $result : $result->fields["expire_extinct_date"] = "만료 소멸일자"
 * $result : $result->fields["cp_extinct_date"] = "쿠폰 소멸일자"
 * $result : $result->fields["cp_expo_yn"] = "쿠폰 노출여부"
 * $result : $result->fields["sell_site"] = "판매 사이트"
 * $result : $result->fields["cpn_admin_seqno"] = "판매 사이트 일련번호"
 * return : list
 */

function makeCpList($result) {

    $ret = "";

    $i = 1;

    while ($result && !$result->EOF) {

        $name = $result->fields["cp_name"];
        $unit = $result->fields["unit"];

        $cpn_seqno = $result->fields["cpn_admin_seqno"];

        $sale_cont = "";
        if ($unit == "%") {
            //할인요율 할인일때
            $val = $result->fields["val"];
            $max_sale_price = $result->fields["max_sale_price"];

            $sale_cont = "할인요율 " . $val .
                "%/최대" . $max_sale_price . "원 할인";
        } else {
            //할익금액 할인일때
            $val = $result->fields["val"];
            $min_order_price = $result->fields["min_order_price"];

            $sale_cont = "할인금액 " . $val . "원" .
                "/최소" . $min_order_price . "원 이상 구매시";
        }

        //발행일
        $public_period_date = date("Y-m-d", strtotime($result->fields["public_period_start_date"]));
        $public_period_date .= " ~ ";
        $public_period_date .= date("Y-m-d", strtotime($result->fields["public_period_end_date"]));

        //소멸일  
        $cp_extinct_date = $result->fields["cp_extinct_date"];
        //만료일 없음일경우 소멸일자를 빈값으로 처리함
        if ($cp_extinct_date == "0000-00-00") {
            $cp_extinct_date = "";
        }
        $object = $result->fields["object_appoint_way"];
        $object_style = "";

        if ($object == "Y") {
            $object_desc = "지정";
            $object_style = "style=\"margin-left:5px;\"";
        } else {
            $object_desc = "미지정";
            $object_style = "style=\"display:none;\"";
        }

        $cp_expo_yn = $result->fields["cp_expo_yn"];
        if ($cp_expo_yn == "Y") {
            $cp_expo_yn = "노출";
        } else {

            $cp_expo_yn = "미노출";
        }
        $cp_seqno = $result->fields["cp_seqno"];
        $sell_site = $result->fields["sell_site"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td>%d</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>"; 
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n   <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"loadCpDetail(%d)\">발행</button><button type=\"button\" %s class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"loadObjectAppoint(%d, %d)\">대상</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $i, $sell_site, $name, 
                $sale_cont, $public_period_date, $cp_extinct_date,
                $object_desc, $cp_expo_yn, $cp_seqno,
                $object_style, $cp_seqno, $cpn_seqno); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}

/* 
 * 카테고리 중분류 list  생성 
 * $result : $result->fields["cate_name"] = "카테고리명" 
 * $result : $result->fields["sortcode"] = "카테고리 분류코드" 
 * 
 * return : list
 */
function makeCateMidList($result) {

    $ret = "";
    $i = 0;

    while ($result && !$result->EOF) {

        $name = $result->fields["cate_name"];
        $sortcode = $result->fields["sortcode"];

        if ($i == 0 ){

            $list  = "\n   <label class=\"control-label cp\"><input type=\"checkbox\" class=\"radio_box\" value=\"%s\" name=\"cate_sortcode[]\">%s</label>
                        <br />";

        } else {

            $list = "\n  <label class=\"fix_width100\"> </label>
                        <label class=\"control-label cp\"><input type=\"checkbox\" class=\"radio_box\" value=\"%s\" name=\"cate_sortcode[]\">%s</label>
                        <br />";
        }

        $ret .= sprintf($list, $sortcode, $name); 

        $result->moveNext();
        $i++;
    }

    return $ret;
}

/* 
 * 멤버 list  생성 
 * $result : $result->fields["cp_name"] = "쿠폰명" 
 * $result : $result->fields["unit"] = "할인 단위" 
 * $result : $result->fields["val"] = "할인 되는 값" 
 * $result : $result->fields["max_sale_price"] = "최대 할인 금액" 
 * $result : $result->fields["min_order_price"] = "최소 주문 금액" 
 * 
 * return : list
 */
function makeMemberInfoList($result) {

    $ret = "";

    $i = 1;

    while ($result && !$result->EOF) {

        $office_nick = $result->fields["office_nick"];
        $grade = $result->fields["grade"];
        $member_typ = $result->fields["member_typ"];
        $cell_num = $result->fields["cell_num"];
        $member_seqno = $result->fields["member_seqno"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td><input name=\"member_chk\" value=\"%d@%s\" type=\"checkbox\" class=\"check_box\"></td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $member_seqno, $office_nick, $office_nick, $grade, 
                        $member_typ, $member_typ, $cell_num); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}


/* 
 * 대상 지정 회원 list  생성 
 * $result : $result->fields["nick"] = "사내 닉네임" 
 * $result : $result->fields["seqno"] = "할인 되는 값" 
 * 
 * return : list
 */
function makeAppointMemberList($result) {

    $ret = "";
    $i = 0;

    while ($result && !$result->EOF) {

        $seqno = $result->fields["member_seqno"];
        $nick = $result->fields["office_nick"];
        $cp_num = $result->fields["cp_num"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td><input name=\"appoint_chk\" value=\"%d@%s\" type=\"checkbox\" class=\"check_box\"></td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $seqno, $nick, $nick, $cp_num);
        
        $result->moveNext();
        $i++; 

    }

    return $ret;
}
/*
 * $result : $result->fields["cp_seqno"] = "쿠폰 일련번호"
 * $result : $result->fields["cpn_admin_seqno"] = "회사 관리 일련번호"
 * $result : $result->fields["issue_count"] = "발급된 쿠폰 수"
 * $result : $result->fields["use_count"] = "사용된 쿠폰 수"
 * $result : $result->fields["use_price"] = "사용된 쿠폰 값"
 */
function makeCpStatsList($result, $conn, $dao) {
    $ret = "";
    $i = 1;

    $param = array();
    while ($result && !$result->EOF) {

        $cp_seqno = $result->fields["cp_seqno"];
        $cpn_admin_seqno = $result->fields["cpn_admin_seqno"];

        $param["table"] = "cpn_admin";
        $param["col"] = "sell_site";
        $param["where"]["cpn_admin_seqno"] = $cpn_admin_seqno;
        $rs_cpn_admin = $dao->selectData($conn, $param);
        //채널
        while ($rs_cpn_admin && !$rs_cpn_admin->EOF) {
            $sell_site = $rs_cpn_admin->fields["sell_site"];
            $rs_cpn_admin->moveNext();
        }
           
        $param["table"] = "cp";
        $param["col"] = "cp_name, val, unit, max_sale_price, min_order_price";
        $param["where"]["cp_seqno"] = $cp_seqno;
        $rs_cp = $dao->selectData($conn, $param);
        //쿠폰명
        $cp_name = $rs_cp->fields["cp_name"];
        
        //내역
        $sale_cont = "";
        $val = $rs_cp->fields["val"];
        $unit = $rs_cp->fields["unit"];
        $max_sale_price = $rs_cp->fields["max_price_sale"];
        $min_order_price = $rs_cp->fields["min_order_price"];

        if ($unit == "%") {
            //할인요율 할인일때
            $sale_cont = "할인요율 " . $val .
                "%/최대" . $max_sale_price . "원 할인";

        } else {
            //할익금액 할인일때
            $sale_cont = "할인금액 " . $val . "원" .
                "/최소" . $min_order_price . "원 이상 구매시";
        }
            
        //카테고리 
        $cate = "";
        $rs_cate = $dao->selectCpStatsCate($conn, $cp_seqno);
        while ($rs_cate && !$rs_cate->EOF) {
            $cate .= ", ". $rs_cate->fields["cate_name"];
            $rs_cate->moveNext(); 
        }
        $cate = substr($cate, 2);
        
        //발급된 쿠폰 수
        $issue_count = $result->fields["issue_count"];
        
        //사용된 쿠폰 수
        $use_count = $result->fields["use_count"];
        
        //사용된 쿠폰 값
        $use_price = $result->fields["use_price"];
        
        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td>%d</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>"; 
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $i, $sell_site, $cp_name, $cate, 
                        $sale_cont, $issue_count, $use_count, $use_price); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}
?>
