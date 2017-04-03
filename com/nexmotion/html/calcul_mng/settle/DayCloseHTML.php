<?
/**
 * @brief 일마감리스트
 */
function makeDayListHtml($param) {

    $rs_html = "";
    $html  = "\n  <tr class='%s'>";
    $html .= "\n    <td>%s</td>";
    $html .= "\n    <td><button type=\"button\" ";
    $html .= "onclick=\"changeDayClose('%s', '%s', %d);\" ";
    $html .= "class=\"btn btn-xs %s\" %s>%s</button></td>";
    $html .= "\n  </tr>";
    $j = "";
    $class = "";
    $btn_class = "";
    $btn_text = "";
    $btn_dis = "";
    $close_yn = "";

    for($i=$param["day"]; $i>=1; $i--) {

        if ($i % 2 == 0) {
            $class = "cellbg";
        } else if ($i % 2 == 1) {
            $class = "";
        }

        $btn_dis = "disabled=\"disabled\"";
        if ($i > $param["close_day"]) {
            $btn_class = "btn-success";
            $btn_text = "일마감하기";
            $close_yn = "Y";
            if (($param["close_day"] == "0" || $param["close_day"]) 
                    && $i == $param["close_day"] + 1) {
                $btn_dis = "";
            }
        } else {
            $btn_class = "btn-danger";
            $btn_text = "일마감풀기";
            $close_yn = "N";
            if ($i == (int)($param["close_day"])) {
                $btn_dis = $param["disabled"];
            }
        }

        if (strlen($i) == "1") {
            $j = "0" . $i;
        } else {
            $j = $i;
        }

        $rs_html .= sprintf($html, $class
                           ,$param["year"] . $param["mon"] . $j
                           ,$param["year"] . "-" . $param["mon"] . "-" . $j
                           ,$close_yn
                           ,$param["sell_site"]
                           ,$btn_class
                           ,$btn_dis
                           ,$btn_text);
    }
    return $rs_html;
}

?>
