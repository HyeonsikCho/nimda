<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');
/**
 * @brief 원자재관리 리스트 HTML
 */
function makeRawMaterialsListHtml($conn, $dao, $rs, $param) {
  
    if (!$rs) {
        return false;
    }

    $util = new CommonUtil();

    $rs_html = "";
    $html  = "\n  <tr class=\"%s\">";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n  </tr>";

    $i = 0;
    while ($rs && !$rs->EOF) {

        if ($i%2)
            $class = "cellbg";
        else
            $class = "";

        $buttons = "<button type='button' class='green btn_pu btn fix_height22 fix_width40' onclick='viewRawMaterials(%s)'>보기</button>";
        $buttons = sprintf($buttons, 
                            $rs->fields["dealspec_seqno"],
                            $rs->fields["dealspec_seqno"]);

        $vat = "미포함";
        if ($rs->fields["vat_yn"] == "Y")
            $vat = "포함";

        $rs_html .= sprintf($html, 
                $class,
                date("Y.m.d", strtotime($rs->fields["regi_date"])),
                $rs->fields["name"],
                $rs->fields["manu_name"],
                $rs->fields["stan"],
                $rs->fields["amt"],
                $rs->fields["unitprice"],
                $rs->fields["price"],
                $vat, 
                $buttons);

        $rs->moveNext();
        $i++;
    }

    return $rs_html;
}
?>
