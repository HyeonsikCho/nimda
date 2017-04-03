<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");

/**
 * @brief 주문 리스트 html 생성
 *
 * @param $conn = connection identifier
 * @param $dao  = 후공정, 옵션 가격 검색을 위한 dao
 * @param $rs   = 주문 리스트 검색결과
 *
 * @return 결과 배열(마지막 seqno랑 주문리스트 html 반환)
 */
function makeOrderListHtml($conn, $dao, $rs) {
    $html_base  = "<tr %s>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td><button type=\"button\" onclick=\"showOrderDetail('%s');\" class=\"green btn_pu btn fix_height20 fix_width60\" %s>%s</button></td>";
    $html_base .= "  <td>";
    $html_base .= "    <button type=\"button\" onclick=\"showOrderContent('%s');\" class=\"green btn_pu btn fix_height20 fix_width40\" %s>보기</button>";
    $html_base .= "    <button type=\"button\" onclick=\"webLogin('%s')\" class=\"orge btn_pu btn fix_height20 fix_width63\">웹로그인</button>";
    $html_base .= "  </td>";
    $html_base .= "</tr>";

    $util = new CommonUtil();

    $html = "";
    $first_seq = "";
    $last_seq = "";
    $i = 0;
    while ($rs && !$rs->EOF) {
        $seqno  = $rs->fields["seqno"];

        $state_arr = $_SESSION["state_arr"];
        $disabled = "disabled=\"disabled\"";
        $disabled2 = "disabled=\"disabled\"";
        $state = $util->statusCode2status($rs->fields["order_state"]);

        if ($rs->fields["order_state"] > $state_arr["접수대기"]) {
            $disabled = "";
        } else if ($rs->fields["order_state"] > $state_arr["조판대기"]) {
            $disabled = "";
            $disabled2 = "";
        }

        $sell_price = intval($rs->fields["sell_price"]);
        //$after_price = intval($dao->selectOrderAfterPrice($conn, $seqno));
        //$opt_price   = intval($dao->selectOrderOptPrice($conn, $seqno));
        $after_price = intval($rs->fields["add_after_price"]);
        $opt_price   = intval($rs->fields["add_opt_price"]);

        $order_price = $sell_price + $after_price + $opt_price;

        $tr_class = (($i++ % 2) === 0) ? "class=\"cellbg\"" : '';

        $html .= sprintf($html_base, $tr_class
                                   , $seqno
                                   , $rs->fields["sell_site"]
                                   , $rs->fields["order_num"]
                                   , date("Y-m-d", strtotime($rs->fields["order_regi_date"]))
                                   , $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>"
                                   , $rs->fields["title"]
                                   , $rs->fields["order_detail"]
                                   , number_format($order_price)
                                   , $seqno
                                   , $disabled2
                                   , $state
                                   , $seqno
                                   , $disabled
                                   , $rs->fields["member_seqno"]);
        $rs->MoveNext();
    }

    if ($i == 0) {
        return "<tr><td colspan=\"10\">검색된 내용이 없습니다.</td></tr>";
    }
    return $html;
}

/**
 * @brief 후공정 발주 리스트 html 생성
 *
 * @param $conn  = connection identifier
 * @param $dao   = 수주처 검색을 위한 dao
 * @param $rs    = 주문 리스트 검색결과
 * @param $param = 카테고리
 *
 * @return 결과 배열(마지막 seqno랑 주문리스트 html 반환)
 */
function makeAfterListHtml($conn, $dao, $rs, $cate_name) {
    $html_base  = "<tr %s>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td>%s</td>";
    $html_base .= "  <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"getAfterInfo('%s', '%s');\">보기</button></td>";
    $html_base .= "</tr>";

    $html = "";
    $i = 1;
    while ($rs && !$rs->EOF) {

        $tr_class = (($i % 2) === 0) ? "class=\"cellbg\"" : '';

        $param = array();
        $param["table"] = "extnl_brand";
        $param["col"] = "extnl_etprs_seqno";
        $param["where"]["extnl_brand_seqno"] = $rs->fields["extnl_brand_seqno"];

        $sel_rs = $dao->selectData($conn, $param);
  
        $param = array();
        $param["table"] = "extnl_etprs";
        $param["col"] = "manu_name";
        $param["where"]["extnl_etprs_seqno"] = $sel_rs->fields["extnl_etprs_seqno"];

        $sel_rs = $dao->selectData($conn, $param);

        $html .= sprintf($html_base, $tr_class
                                   , $i
                                   , $cate_name
                                   , $rs->fields["after_name"]
                                   , $rs->fields["depth1"]
                                   , $rs->fields["depth2"]
                                   , $rs->fields["depth3"]
                                   , $rs->fields["amt"]
                                   , $rs->fields["amt_unit"]
                                   , $rs->fields["orderer"]
                                   , $sel_rs->fields["manu_name"]
                                   , $rs->fields["after_op_seqno"]
                                   , $cate_name);
        $i++;
        $rs->MoveNext();
    }
    return $html;
}
?>
