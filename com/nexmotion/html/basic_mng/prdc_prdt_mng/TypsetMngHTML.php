<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/doc/basic_mng/prdc_prdt_mng/TypsetListDOC.php");
/* 
 * 조판 품목 list grid 생성 
 * $result : $result->fields["name"] = "조판 이름" 
 * $result : $result->fields["affil"] = "계열" 
 * $result : $result->fields["subpaper"] = "절수" 
 * $result : $result->fields["wid_size"] = "가로 사이즈" 
 * $result : $result->fields["vert_size"] = "세로 사이즈" 
 * $result : $result->fields["dscr"] = "조판 설명" 
 * $result : $result->fields["typset_format_seqno"] = "조판 일련번호" 
 * $result : $result->fields["file_path"] = "파일 경로" 
 * $result : $result->fields["origin_file_name"] = "원본 파일이름" 
 * $result : $result->fields["size"] = "사이즈" 
 * $result : $result->fields["ext"] = "확장자" 
 * $result : $result->fields["typset_format_file_seqno"] = "파일 일련번호" * 
 * return : list
 */
function makeTypsetList($rs, $param) {

    if (!$rs) {
        return false;
    }

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td><input type=\"checkbox\" name=\"typset_chk\" value=\"%s\"></td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" class=\"green btn_pu btn fix_height20 fix_width40\" onclick=\"loadTypsetInfo(%d)\">보기</button></td>";
    $html .= "\n  </tr>";
    $i = 1 + $param["s_num"];

    while ($rs && !$rs->EOF) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $rs_html .= sprintf($html, $class, 
                $rs->fields["typset_format_seqno"],
                $rs->fields["preset_cate"],
                $rs->fields["preset_name"],
                $rs->fields["affil"],
                $rs->fields["subpaper"],
                $rs->fields["wid_size"] . "*" . $rs->fields["vert_size"],
                $rs->fields["dscr"],
                $rs->fields["process_yn"],
                $rs->fields["typset_format_seqno"]);
        $i++;
        $rs->moveNext();
    }

    return $rs_html;
}
?>

