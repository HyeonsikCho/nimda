<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');

/**
 * @brief 회원 통합리스트 HTML
 */
function makeMemberCommonListHtml($conn, $dao, $rs, $param) {
  
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
//    $html .= "\n    <td title=\"%s\" style=\"overflow:hidden; white-space:nowrap; text-overflow:ellipsis; \">%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>";
    $html .= "\n        <button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"showDetail('%s');\">보기</button>";
    $html .= "\n        <button type=\"button\" onclick=\"webLogin('%s')\" class=\"orge btn_pu btn fix_height20 fix_width63\">웹로그인</button>";
    $html .= "\n    </td>";
    $html .= "\n  </tr>";

    $i = $param["cnt"] - $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $param = array();
        $param["table"] = "member_grade_policy";
        $param["col"] = "grade_name";
        $param["where"]["grade"] = $rs->fields["grade"];

        $sel_rs = $dao->selectData($conn, $param);

        $first_join_date = "";
        $final_order_date = "";
        $final_login_date = "";
        if ($rs->fields["first_join_date"]) {
            $first_join_date = date("Y-m-d", strtotime($rs->fields["first_join_date"]));
        }
        if ($rs->fields["final_order_date"]) {
            $final_order_date = date("Y-m-d", strtotime($rs->fields["final_order_date"]));
        }
        if ($rs->fields["final_login_date"]) {
            $final_login_date = date("Y-m-d", strtotime($rs->fields["final_login_date"]));
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                $rs->fields["member_name"] . " <span style=\"color:blue; font-weight: bold;\">[" . $rs->fields["office_nick"] . "]</span>",
                $rs->fields["tel_num"],
                $rs->fields["cell_num"],
                $rs->fields["member_typ"],
                $sel_rs->fields["grade_name"],
//                $rs->fields["office_eval"],
//                $rs->fields["office_eval"],
                $first_join_date,
                $final_order_date,
                $final_login_date,
                $rs->fields["member_seqno"],
                $rs->fields["member_seqno"]);
        $i--;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원명 검색 팝업 리스트 HTML
 */
function makePopMemberNameListHtml($rs) {
  
    if (!$rs) {
        return false;
    }

    $rs_html  = "\n  <ul style=\"ofh\">";
    $html .= "\n    <li onclick=\"selectResult('%s');\" style=\"cursor: pointer;\">%s(%s)</li>";

    while ($rs && !$rs->EOF) {

        $rs_html .= sprintf($html,
                $rs->fields["office_nick"],
                $rs->fields["office_nick"],
                $rs->fields["member_name"]);
        $rs->moveNext();
    }

    $rs_html .= "\n  </ul>";

    return $rs_html;
}

/**
 * @brief 회원 정보 담당자 리스트 HTML
 */
function makeMemberMngDetailHtml($rs) {
  
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
    $html .= "\n  </tr>";
    $i = 1;

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["member_name"],
                $rs->fields["member_id"],
                $rs->fields["tel_num"],
                $rs->fields["cell_num"],
                $rs->fields["mail"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 정보 회계 담당자 리스트 HTML
 */
function makeMemberAcctingMngDetailHtml($rs) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";
    $i = 1;

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["name"],
                $rs->fields["tel_num"],
                $rs->fields["cell_num"],
                $rs->fields["mail"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 정보 관리사업자 리스트 HTML
 */
function makeMemberidminLicenseeregiDetailHtml($rs) {
  
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
    $html .= "\n    <td>%s %s</td>";
    $html .= "\n  </tr>";
    $i = 1;

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                $rs->fields["crn"],
                $rs->fields["corp_name"],
                $rs->fields["repre_name"],
                $rs->fields["tel_num"],
                $rs->fields["cell_num"],
                $rs->fields["addr"],
                $rs->fields["addr_detail"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 배송관리 HTML
 */
function makeMemberDlvrHtml($rs) {
  
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
    $html .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"regiMyDlvr('%s', '%s');\">수정</button></td>";
    $html .= "\n  </tr>";
    $i = 1;

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                $rs->fields["dlvr_name"],
                $rs->fields["recei"],
                $rs->fields["addr"] . " " . $rs->fields["addr_detail"],
                $rs->fields["tel_num"],
                date("Y-m-d", strtotime($rs->fields["regi_date"])),
                $rs->fields["member_seqno"],
                $rs->fields["member_dlvr_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 등급 리스트 HTML
 */
function makeMemberGradeListHtml($rs, $grade_arr) {
  
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
 //   $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = 1;
    $state = "";
    $exist_grade = "";
    $new_grade = "";

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        if ($rs->fields["state"] == 1) {
            $state = "<span class=\"blue_text01\">[승인대기]</span>";
        } else if ($rs->fields["state"] == 2) {
            $state = "[승인완료]";
        } else if ($rs->fields["state"] == 3) {
            $state = "<span class=\"red_text01\">[승인거절]</span>";
        }

        $exist_grade = $grade_arr[$rs->fields["exist_grade"]];
        $new_grade = $grade_arr[$rs->fields["new_grade"]];

        $rs_html .= sprintf($html, $class, 
                $i,
                $exist_grade,
                $new_grade,
                $rs->fields["req_empl_name"],
                $rs->fields["aprvl_empl_name"],
                $rs->fields["reason"],
 //               date("Y-m-d", strtotime($rs->fields["regi_date"])),
                $state);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 매출정보리스트 HTML
 */
function makeMemberSalesListHtml($rs, $param) {

    $util = new CommonUtil();
  
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
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"showOrderDetail('%s');\">보기</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $order_state = $util->statusCode2status($rs->fields["order_state"]);

        if ($rs->fields["order_state"] == "325") {
            $order_state = "QC대기";
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                $rs->fields["order_num"],
                $rs->fields["title"],
                $rs->fields["order_detail"],
                $rs->fields["amt"] . "(" . $rs->fields["count"] . ")",
                number_format($rs->fields["pay_price"]) . "원",
                $rs->fields["order_regi_date"],
                $order_state,
                $rs->fields["order_common_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 포인트 지급내역 리스트 HTML
 */
function makeMemberPointReqListHtml($rs, $param) {
  
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
    $html .= "\n  </tr>";

    $i = 1 + $param["s_num"];
    $state = "";

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        if ($rs->fields["state"] == 1) {
            $state = "<span class=\"blue_text01\">[승인대기]</span>";
        } else if ($rs->fields["state"] == 2) {
            $state = "[승인완료]";
        } else if ($rs->fields["state"] == 3) {
            $state = "<span class=\"red_text01\">[승인거절]</span>";
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                $rs->fields["point_name"],
                number_format($rs->fields["point"]),
                $rs->fields["req_empl_name"],
                $rs->fields["aprvl_empl_name"],
                $rs->fields["reason"],
                $state);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원포인트정보리스트 HTML
 */
function makeMemberPointListHtml($rs, $param) {
  
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
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];
    $save = "";
    $use = "";

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        if ($rs->fields["dvs"] == "2") {
            $save = "";
            $use = $rs->fields["point"];
        } else if ($rs->fields["dvs"] == "1") {
            $save = $rs->fields["point"];
            $use = "";
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                date("Y-m-d", strtotime($rs->fields["regi_date"])),
                $rs->fields["point_name"],
                $save,
                $use,
                number_format($rs->fields["rest_point"]),
                number_format($rs->fields["order_price"]) . "원",
                $rs->fields["note"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원 쿠폰정보리스트 HTML
 */
function makeMemberCouponListHtml($rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><span class=\"%s\">%s</span></td>";
    $html .= "\n    <td><span class=\"%s\">%s</span></td>";
    $html .= "\n    <td><span class=\"%s\">%s</span></td>";
    $html .= "\n    <td><span class=\"%s\">%s %s %s</span></td>";
    $html .= "\n    <td><span class=\"%s\">%s</span></td>";
    $html .= "\n    <td><span class=\"%s\">%s</span></td>";
    $html .= "\n    <td><span class=\"%s\">%s</span></td>";
    $html .= "\n  </tr>";
   
    $i = 1 + $param["s_num"];
    
    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $cp_name = $rs->fields["cp_name"];
        $val = $rs->fields["val"];
        $unit = $rs->fields["unit"];
        $max_sale_price = $rs->fields["max_sale_price"];
        $min_order_price = $rs->fields["min_order_price"];
        $use_start_date = $rs->fields["use_able_start_date"];

        $use_cnd = "";
        $use_val = "";
        $use_dvs = "";

        //요율일때
        if ($max_sale_price != "") {

            $use_cnd = "최대 ";
            $use_val = "&#8361;" . number_format($max_sale_price);
            $use_dvs = " 할인";
        }

        //금액일때
        if ($min_order_price != "") {

            $use_cnd = "주문금액 ";
            $use_val =  "&#8361;" . number_format($min_order_price);
            $use_dvs =  " 이상";
        } 

        $use_deadline = $rs->fields["use_deadline"];
        $issue_date = $rs->fields["issue_date"];
        $use_yn = $rs->fields["use_yn"];
        $today = date("Y-m-d H:i:s", time());

        $state = "";
     
        //사용
        if ($use_yn == "N") {

            //사용기한이 현재 날짜보다 크고 현재날짜가 사용 가능 시작 일자보다 클때
            if ($today <= $use_deadline && $today >= $use_start_date) {
                $state = "사용가능";
                $state_class = "";

            } else if (!$use_deadline) {
                $state = "사용가능";
                $state_class = "";

            //현재 날짜가 사용기한보다 클때
            } else {
                $state = "기한만료";
                $state_class = "grey_text01";
            }
        } else {
            $state ="사용완료";
            $state_class = "grey_text01";
        }


        $rs_html .= sprintf($html, $class, 
                $state_class,
                $i,
                $state_class,
                $cp_name,
                $state_class,
                number_format($val) . $unit,
                $state_class,
                $use_cnd,
                $use_val,
                $use_dvs,
                $state_class,
                substr($use_deadline, 0, 10),
                $state_class,
                substr($issue_date, 0, 10),
                $state_class,
                $state);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}

/**
 * @brief 회원이벤트정보리스트 HTML
 */
function makeMemberEventListHtml($rs, $param) {
  
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
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $i,
                $rs->fields["event_typ"],
                $rs->fields["prdt_name"],
                $rs->fields["bnf"],
                date("Y-m-d", strtotime($rs->fields["regi_date"])));
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}
?>
