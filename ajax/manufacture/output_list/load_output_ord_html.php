<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/manufacture/output_mng/OutputListDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new OutputListDAO();

$typset_num = $fb->form("typset_num");

$param = array();
$param["table"] = "sheet_typset";
$param["col"] = "regi_date, oper_sys, typset_format_seqno,
    print_amt, print_amt_unit, dlvrboard, specialty_items,
    sheet_typset_seqno, after_list";
$param["where"]["typset_num"] = $typset_num;

$rs = $dao->selectData($conn, $param);

$regi_date = $rs->fields["regi_date"];
$oper_sys = $rs->fields["oper_sys"];
$typset_format_seqno = $rs->fields["typset_format_seqno"];
$sheet_typset_seqno = $rs->fields["sheet_typset_seqno"];
$print_amt = $rs->fields["print_amt"] . $rs->fields["print_amt_unit"];
$dlvrboard = $rs->fields["dlvrboard"];
$specialty_items = $rs->fields["specialty_items"];
$after_list = $rs->fields["after_list"];

$param = array();
$param["table"] = "typset_format";
$param["col"] = "paper, preset_name";
$param["where"]["typset_format_seqno"] = $typset_format_seqno;

$rs = $dao->selectData($conn, $param);

$paper = $rs->fields["paper"];
$preset_arr = explode("_", $rs->fields["preset_name"]);

$param = array();
$param["table"] = "amt_order_detail_sheet";
$param["col"] = "order_detail_count_file_seqno";
$param["where"]["sheet_typset_seqno"] =  $sheet_typset_seqno;

$sel_rs = $dao->selectData($conn, $param);

$tr_html  = "\n<tr>";
$tr_html .= "\n  <td headers=\"text1\">%s</td>";
$tr_html .= "\n  <td headers=\"text2\">%s</td>";
$tr_html .= "\n  <td headers=\"text3\">%s</td>";
$tr_html .= "\n  <td headers=\"text4\">%s</td>";
$tr_html .= "\n  <td headers=\"text5\">%s</td>";
$tr_html .= "\n  <td headers=\"text6\">%s*%s</td>";
$tr_html .= "\n  <td headers=\"text7\">%s%s(%s장)</td>";
$tr_html .= "\n  <td headers=\"text8\">%s도</td>";
$tr_html .= "\n  <td headers=\"text9\">%s</td>";
$tr_html .= "\n  <td headers=\"text10\">%s</td>";
$tr_html .= "\n</tr>";

$i = 1;
$tb_html = "";
while ($sel_rs && !$sel_rs->EOF) {

    $order_detail_count_file_seqno = $sel_rs->fields["order_detail_count_file_seqno"];

    $param = array();
    $param["table"] = "order_detail_count_file";
    $param["col"] = "order_detail_seqno";
    $param["where"]["order_detail_count_file_seqno"] = $order_detail_count_file_seqno;

    $order_detail_seqno = $dao->selectData($conn, $param)->fields["order_detail_seqno"];

    $param = array();
    $param["table"] = "order_detail";
    $param["col"] = "order_common_seqno";
    $param["where"]["order_detail_seqno"] = $order_detail_seqno;

    $order_common_seqno = $dao->selectData($conn, $param)->fields["order_common_seqno"];

    $param = array();
    $param["order_common_seqno"] = $order_common_seqno;

    $rs = $dao->selectProduceOrdPrint($conn, $param);

    while ($rs && !$rs->EOF) {

        $tb_html .= sprintf($tr_html, $i,
                $rs->fields["nick"],
                $rs->fields["receipt_mng"],
                $rs->fields["office_nick"],
                $rs->fields["title"],
                $rs->fields["work_size_wid"],
                $rs->fields["work_size_vert"],
                $rs->fields["amt"],
                $rs->fields["amt_unit_dvs"],
                $rs->fields["page_cnt"],
                $rs->fields["tot_tmpt"],
                $rs->fields["produce_memo"],
                $rs->fields["invo_cpn"]);
        $rs->moveNext();
    }
    $i++;
    $sel_rs->moveNext();
}

$html  = "\n<!DOCTYPE HTML>";
$html .= "\n<html lang=\"ko\">";
$html .= "\n<head>";
$html .= "\n<meta charset=\"UTF-8\">";
$html .= "\n<title>생산지시서</title>";
$html .= "\n<style>";
$html .= "\n@charset \"utf-8\";";                                                                                                                                                       
$html .= "\n.test_tt{width:100%; margin:0 auto;}";                                                                                                                   
$html .= "\n.test_tt h1{float:left;}";
$html .= "\n.test_tt p{float:right; font-size:22px; font-weight:bold; height:30px; line-height:30px; margin-top:30px;}";
$html .= "\n.cc p{clear:both;}";                                                                                                                                
$html .= "\n#type1 caption{display:none;}";                                                                                     
$html .= "\n#type1{clear:both; border-collapse:collapse;}";                                                                                                     
$html .= "\n#type1 th{font-weight:bold; text-align:left; background-color:#D7ECFC; color:#333; text-align:center; width:100px; height:30px; border:1px solid #333;}";
$html .= "\n#type1 td{font-weight:normal; width:250px; padding-left:5px; background-color:#fff; border:1px solid #333;}";                                           
$html .= "\n#type2 th{font-size:12px; border:2px solid #333;}";
$html .= "\n#type2 th#text1{width:30px;}";
$html .= "\n#type2 td{font-size:13px; border:1px solid #333;}";
$html .= "\n#type2{border-collapse:collapse; margin-top:10px;}";
$html .= "\n#text2,#text8{width:30px;}";
$html .= "\n#text3, #text9{width:50px;}";
$html .= "\n#text4, #text5, #text9{width:110px;}";
$html .= "\n#text7{width:70px;}";
$html .= "\n</style>";
$html .= "\n</head>";
$html .= "\n<body>";
$html .= "\n<div class=\"test_tt\">";
$html .= "\n  <h1>디지탈 프린팅</h1>";
$html .= "\n  <p>판번호 :" . $typset_num . "</p>";
$html .= "\n</div>";
$html .= "\n<!-- test_tt -->";
$html .= "\n<div class=\"cc\">";
$html .= "\n  <p>작업  일자 : <strong>" . $regi_date . "</strong></p>";
$html .= "\n</div>";
$html .= "\n<!-- cc -->";
$html .= "\n<table id=\"type1\">";
$html .= "\n<caption></caption>";
$html .= "\n<tr>";
$html .= "\n  <th scope=\"row\" id=\"txt1\">판정보</th>";
$html .= "\n  <td headers=\"txt1\">" . $preset_arr[0].$preset_arr[1]. "</td>";
$html .= "\n  <th scope=\"row\" id=\"txt2\">IBM/MAC</th>";
$html .= "\n  <td headers=\"txt2\">" . $oper_sys . "</td>";
$html .= "\n</tr>";
$html .= "\n<tr>";
$html .= "\n  <th scope=\"row\" id=\"txt3\">종이</th>";
$html .= "\n  <td headers=\"txt3\">" . $paper . "</td>";
$html .= "\n  <th scope=\"row\" id=\"txt4\">출력실</th>";
$html .= "\n  <td headers=\"txt4\">자사출력실</td>";
$html .= "\n</tr>";
$html .= "\n<tr>";
$html .= "\n  <th scope=\"row\" id=\"txt5\">인쇄도수</th>";
$html .= "\n  <td headers=\"txt5\">" . $preset_arr[2] . "</td>";
$html .= "\n  <th scope=\"row\" id=\"txt6\">인쇄소</th>";
$html .= "\n  <td headers=\"txt6\">" . $preset_arr[3] . "</td>";
$html .= "\n</tr>";
$html .= "\n<tr>";
$html .= "\n  <th scope=\"row\" id=\"txt7\">인쇄수량</th>";
$html .= "\n  <td headers=\"txt7\">" . $print_amt . "</td>";
$html .= "\n  <th scope=\"row\" id=\"txt8\">판구분</th>";
$html .= "\n  <td headers=\"txt8\">" . $dlvrboard . "</td>";
$html .= "\n</tr>";
$html .= "\n<tr>";
$html .= "\n  <th scope=\"row\" id=\"txt9\">특이사항</th>";
$html .= "\n  <td headers=\"txt9\" class=\"txt9\">" . $specialty_items . "</td>";
$html .= "\n  <th scope=\"row\" id=\"txt10\">후공정</th>";
$html .= "\n  <td headers=\"txt10\" class=\"txt10\">" . $after_list . "</td>";
$html .= "\n</tr>";
$html .= "\n</table>";
$html .= "\n<!--type1-->";
$html .= "\n<table id=\"type2\">";
$html .= "\n<caption></caption>";
$html .= "\n<tr>";
$html .= "\n  <th scope=\"row\" id=\"text1\">NO</th>";
$html .= "\n  <th scope=\"row\" id=\"text2\">판매채널</th>";
$html .= "\n  <th scope=\"row\" id=\"text3\">접수자</th>";
$html .= "\n  <th scope=\"row\" id=\"text4\">회원명</th>";
$html .= "\n  <th scope=\"row\" id=\"text5\">인쇄제목</th>";
$html .= "\n  <th scope=\"row\" id=\"text6\">규격</th>";
$html .= "\n  <th scope=\"row\" id=\"text7\">수량</th>";
$html .= "\n  <th scope=\"row\" id=\"text8\">도수</th>";
$html .= "\n  <th scope=\"row\" id=\"text9\">비고</th>";
$html .= "\n  <th scope=\"row\" id=\"text10\">배송</th>";
$html .= "\n</tr>";
$html .= $tb_html;
$html .= "\n</table>";
$html .= "\n</body>";
$html .= "\n</html>";

echo $html;
$conn->close();
?>
