<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/doc/mkt/mkt_mng/EventListDOC.php");
/* 
 * 오특이이벤트 list  생성 
 * $result : $result->fields["name"] = "오특이 이벤트명" 
 * $result : $result->fields["start_hour"] = "시작 시간" 
 * $result : $result->fields["end_hour"] = "종료 시간" 
 * $result : $result->fields["dsply_yn"] = "진열 여부"
 * $result : $result->fields["oevent_event_seqno"] 
                    = "오특이 이벤트 일련번호" 
 * $result : $result->fields["cate_name"] = "카테고리 이름" 
 * $result : $result->fields["sell_site"] = "판매채널" 
 * 
 * return : list
 */
function makeOeventList($result) {

    $ret = "";

    $i = 1;
    
    while ($result && !$result->EOF) {

        $name = $result->fields["name"];
        $event_date = $result->fields["event_date"];
        $start_hour = $result->fields["start_hour"];
        $start_hour = substr($start_hour, 11, 5);
        $end_hour = $result->fields["end_hour"];
        $end_hour = substr($end_hour, 11,5);
        $dsply_yn = $result->fields["dsply_yn"];



        if ($dsply_yn == "Y") {

            $dsply_str = "진열";

        } else {

            $dsply_str = "미진열";

        }

        $cate_name = $result->fields["cate_name"];
        $sell_site = $result->fields["sell_site"];
        $oevent_seqno = $result->fields["oevent_event_seqno"];

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
		$list .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"popOeventDetailLayer(%d)\">수정</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $sell_site, $name, $cate_name
                       ,$event_date . "&nbsp; &nbsp;" . $start_hour . " ~ " . $end_hour
                       ,$dsply_str, $oevent_seqno); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}

/* 
 * 요즘바빠요 이벤트 list  생성 
 * $result : $result->fields["name"] = "오특이 이벤트명" 
 * $result : $result->fields["dsply_yn"] = "진열 여부"
 * $result : $result->fields["nowdays_busy_event_seqno"] 
                    = "요즘 바빠요 일련번호"
 * $result : $result->fields["cate_name"] = "카테고리 이름" 
 * $result : $result->fields["sell_site"] = "판매채널" 
 * 
 * return : list
 */
function makeNowadaysList($result) {

    $ret = "";

    $i = 1;

    while ($result && !$result->EOF) {

        $name = $result->fields["name"];
        $dsply_yn = $result->fields["dsply_yn"];

        if ($dsply_yn == "Y") {

            $dsply_str = "진열";

        } else {

            $dsply_str = "미진열";

        }

        $cate_name = $result->fields["cate_name"];
        $sell_site = $result->fields["sell_site"];
        $nowadays_seqno = $result->fields["nowadays_busy_event_seqno"];

        if ($i%2 == 1) {
            $list  = "\n  <tr>";
        } else {
            $list  = "\n  <tr class=\"cellbg\">";
        }
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
        $list .= "\n    <td>%s</td>";
		$list .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"popNowadaysDetailLayer(%d)\">수정</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $sell_site, $name, $cate_name
                       ,$dsply_str, $nowadays_seqno); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}

/* 
 * 골라담기 이벤트 list  생성 
 * $result : $result->fields["name"] = "골라담기 이벤트명" 
 * $result : $result->fields["use_yn"] = "사용 여부"
 * $result : $result->fields["tot_order_price"] = "총 주문 금액"
 * $result : $result->fields["sale_rate"] = "할인 요율"
 * $result : $result->fields["overto_event_seqno"] 
                    = "골라담기 일련번호"
 * $result : $result->fields["cate_name"] = "카테고리 이름" 
 * $result : $result->fields["sell_site"] = "판매채널" 
 * 
 * return : list
 */
function makeOvertoList($result) {

    $ret = "";

    $i = 1;

    while ($result && !$result->EOF) {

        $name = $result->fields["name"];
        $use_yn = $result->fields["use_yn"];

        if ($use_yn == "Y") {

            $use_str = "사용";

        } else {

            $use_str = "미사용";

        }

        $tot_order_price = $result->fields["tot_order_price"];
        $sale_rate = $result->fields["sale_rate"];
        $cate_name = $result->fields["cate_name"];
        $sell_site = $result->fields["sell_site"];
        $overto_seqno = $result->fields["overto_event_seqno"];

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
		$list .= "\n    <td><button type=\"button\" class=\"orge btn_pu btn fix_height20 fix_width40\" onclick=\"popOvertoLayer(%d)\">수정</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $sell_site, $name, $use_str
                       ,$tot_order_price, $sale_rate, $overto_seqno); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}

/**
 * @brief 인쇄 검색조건 낱장형 html 반환
 *
 * @param $conn  = connection identifier
 * @param $dao   = 쿼리를 수행할 dao
 * @param $param = 검색조건 파라미터
 *
 * @return 생성된 html
 */
function makePrintTmptHtmlSheet($conn, $dao, $param) {
    $param["side_dvs"] = "단면";

    $print  = "<option value=\"\">전체</option>";
    $print .= $dao->selectCateTmptHtml($conn, $param);

    $param["side_dvs"] = "양면";
    $print .= $dao->selectCateTmptHtml($conn, $param);

    return $print;
};


/**
 * @brief 수량 select 박스 option 생성
 *
 * @param $arr
 *
 */
function makeOptionNumHtml($arr) {

    $html = "";

    $html = "\n  <option value=\"\">전체</option>";

    foreach($arr as $key=> $value) {

        $html .= "\n  <option value=\"" . $value . "\">" . $value . "</option>";

    }

    return $html;
}


/**
 * @brief 선택된 값으로 셋팅되는  콤보박스  html 생성
 *
 */
function makeSelectedOptionHtml($rs, $selected, $key, $val) {

    $html = "";

    while ($rs && !$rs->EOF) {

        $fields = $rs->fields[$key]; 
        $value = $rs->fields[$val]; 

        if ($selected == $value) {


            $html .= "\n  <option value=\"" . $value . "\" selected=\"selected\">" . $fields. "</option>";

        } else {

            $html .= "\n  <option value=\"" . $value . "\">" . $fields. "</option>";
        }

        $rs->MoveNext();
    }

    return $html;
}

/* 
 * 골라담기 이벤트 상품 list  생성 
 * $result : $result->fields["paper_name"] = "종이 이름" 
 * $result : $result->fields["paper_dvs"] = "종이 구분" 
 * $result : $result->fields["paper_color"] = "종이 색상" 
 * $result : $result->fields["paper_basisweigth"] = "종이 평량"
 * $result : $result->fields["print_name"] = "출력 도수"
 * $result : $result->fields["cate_name"] = "카테고리 이름" 
 * $result : $result->fields["output_size"] = "출력 사이즈" 
 * $result : $result->fields["overto_event_detail_seqno"] = "골라담기 상세 일련번호" 
 * 
 * return : list
 */
function makeOvertoDetailList($result) {

    $ret = "";

    $i = 1;

    while ($result && !$result->EOF) {

        $cate_name = $result->fields["cate_name"];
        $paper_name = $result->fields["paper_name"];
        $dvs = $result->fields["paper_dvs"];
        $color = $result->fields["paper_color"];
        $basisweight = $result->fields["paper_basisweight"];
        $output_size = $result->fields["output_size"];
        $print_name = $result->fields["print_name"];
        $overto_detail_seqno = $result->fields["overto_event_detail_seqno"];


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
        $list .= "\n    <td><button type=\"button\" onclick=\"editOvertoPrdt(%d); return false;\" class=\"orge btn_pu btn fix_height20 fix_width40\">수정</button>
					        		    <button type=\"button\" onclick=\"delOvertoPrdt(%d); return false;\" class=\"bred btn_pu btn fix_height20 fix_width40\">삭제</button></td>";
        $list .= "\n  </tr>";

        $ret .= sprintf($list, $i, $cate_name,
                        $paper_name . "/" . $dvs . "/" . 
                        $color . "/" . $basisweight,
                        $output_size, $print_name,
                        $overto_detail_seqno, $overto_detail_seqno); 

        $result->moveNext();
        $i++; 
    }

    return $ret;
}

function makeOeventHtml($rs) {

    $i = 0;
    $html = "";
    $ret  = "\n<li class=\"%s\">";
    $ret .= "\n   <a href=\"#none\" target=\"_self\">";
    $ret .= "\n       <dl>";
    $ret .= "\n           <dd class=\"figure\">";
    $ret .= "\n               <div class=\"label\">";
                                //퍼센트 이미지
    $ret .= "\n                 %s ";
//    $ret .= "\n                   <img src=\"[TPH_Vdesign_dir]/images/main/event_number_%s.png\">";
//    $ret .= "\n                   <img src=\"[TPH_Vdesign_dir]/images/main/event_number_%s.png\">";
    $ret .= "\n               </div>";
                            //상품사진
    $ret .= "\n               <img src=\"%s\" style=\"width:300px;height:300px;\">";
    $ret .= "\n           </dd>";
    $ret .= "\n           <dt>";
    $ret .= "\n               <ul>";
                                //이벤트이름
    $ret .= "\n                   <li>%s</li>";
                                //종이카테고리
    $ret .= "\n                   <li>%s</li>";
                                //인쇄물카테고리
    $ret .= "\n                   <li>%s</li>";
                                //상품규격
    //$ret .= "                   <li>%s</li>";
                                //수량
    $ret .= "\n                   <li>%s</li>";
    $ret .= "\n               </ul>";
    $ret .= "\n           </dt>";
    $ret .= "\n           <dd class=\"price\">";
    $ret .= "\n               %s<span class=\"unit\">원</span>";
    $ret .= "\n           </dd>";
    $ret .= "\n       </dl>";
    $ret .= "\n   </a>";
    $ret .= "\n</li>";

    while ($rs && !$rs->EOF) {

        if ($i == 0)
            $class = "on";
        else
            $class = "";

        $sale_price = (int)$rs->fields["sale_price"];
        $sum_price = (int)$rs->fields["sum_price"];
        $basic_price = $sale_price + $sum_price;
       
        $percent = round($sale_price / $basic_price * 100); 

        $img = "";
        if (strlen($percent) == 2) {
            $img  = "\n                   <img src=\"[TPH_Vdesign_dir]/images/main/event_number_".substr($percent, 0, 1).".png\">";
            $img .= "\n                   <img src=\"[TPH_Vdesign_dir]/images/main/event_number_".substr($percent, 1, 1).".png\">";
        } else if (strlen($percent) == 1) {
            $img  = "\n                   <img src=\"[TPH_Vdesign_dir]/images/main/event_number_".$percent.".png\">";
        } else {
            $img  = "\n                   <img src=\"[TPH_Vdesign_dir]/images/main/event_number_0.png\">";
        }

        $paper_detail = $rs->fields["paper_name"] . " "
                        . $rs->fields["paper_dvs"] . " "
                        . $rs->fields["paper_color"] . " "
                        . $rs->fields["paper_basisweight"];

        $file = explode(".", $rs->fields["save_file_name"]);

        $html .= sprintf($ret, $class
                        , $img
                        , $rs->fields["file_path"] . $file[0] . "_300_300." . $file[1]
                        //, $rs->fields["file_path"] . $rs->fields["save_file_name"]
                        , $rs->fields["name"]
                        , $paper_detail
                        , $rs->fields["print_tmpt"]
                        , number_format($rs->fields["amt"]) . " " . $rs->fields["amt_unit"]
                        , number_format($rs->fields["sum_price"]));
        $i++;
        $rs->moveNext();
    }

    return $html;
}

function makeNowADaysHtml($rs) {

    $i = 0;
    $html = "";
    $ret  = "\n<li class=\"%s\">";
    $ret .= "\n   <a href=\"#none\" target=\"_self\">";
    $ret .= "\n       <dl>";
    $ret .= "\n           <dd class=\"figure\">";
                              //상품사진
    $ret .= "\n               <img src=\"%s\" style=\"width:300px;height:300px;\">";
    $ret .= "\n           </dd>";
    $ret .= "\n           <dt>";
    $ret .= "\n               <ul>";
                                //이벤트이름
    $ret .= "\n                   <li>%s</li>";
                                //종이카테고리
    $ret .= "\n                   <li>%s</li>";
                                //인쇄물카테고리
    $ret .= "\n                   <li>%s</li>";
                                //상품규격
    //$ret .= "                   <li>%s</li>";
                                //수량
    $ret .= "\n                   <li>%s</li>";
    $ret .= "\n               </ul>";
    $ret .= "\n           </dt>";
    $ret .= "\n           <dd class=\"price\">";
    $ret .= "\n               %s<span class=\"unit\">원</span>";
    $ret .= "\n           </dd>";
    $ret .= "\n       </dl>";
    $ret .= "\n   </a>";
    $ret .= "\n</li>";

    while ($rs && !$rs->EOF) {

        if ($i == 0)
            $class = "on";
        else
            $class = "";

        $paper_detail = $rs->fields["paper_name"] . " "
                        . $rs->fields["paper_dvs"] . " "
                        . $rs->fields["paper_color"] . " "
                        . $rs->fields["paper_basisweight"];

        $file = explode(".", $rs->fields["save_file_name"]);

        $html .= sprintf($ret, $class
                        , $rs->fields["file_path"] . $file[0] . "_300_300." . $file[1]
                        //, $rs->fields["file_path"] . $rs->fields["save_file_name"]
                        , $rs->fields["name"]
                        , $paper_detail
                        , $rs->fields["print_tmpt"]
                        , number_format($rs->fields["amt"]) . " " . $rs->fields["amt_unit"]
                        , number_format($rs->fields["sum_price"]));
        $i++;
        $rs->moveNext();
    }

    return $html;
}

?>
