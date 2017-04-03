<?
function getSheetTopTree($one_level, $two_level, $thr_level) {

    $i = 1;
    $html = "\n<ol class=\"tree\">";
    foreach ($one_level as $key=>$value) {
        $html .= "\n<li class=\"ptb05\">"; 
        $html .= "\n  <label for=\"folder". $i ."\">";
        $html .= "\n    <span class=\"one\" id=\"". $key ."\">". $value ."</span>";
        $html .= "\n  </label>";
        $html .= "\n<input class=\"bt2\" type=\"checkbox\" id=\"folder". $i ."\" /> ";
        $html .= getSheetMidTree($key, $two_level, $thr_level, $i);
        $html .= "\n</li>";
        $i++;
    }
    $html .= "\n</ol>";

    return $html;
}

function getSheetMidTree($key, $two_level, $thr_level, $i) {

    $html = "\n<ol>";
    $j = $i * 1000;
    if ($two_level[$key]) {
        foreach ($two_level[$key] as $arr_key=>$value) { 
            $html .= "\n<li>";
            $html .= "\n  <label for=\"subfolder". $j ."\">";
            $html .= "\n    <span class=\"two\" id=\"" . $arr_key . "\">";
            $html .=                                   $value . "</span>"; 
            $html .= "\n  </label>";
            $html .= "\n<input class=\"bt2\" type=\"checkbox\" id=\"subfolder" . $j . "\" /> "; 
            $html .= getSheetBtmTree($arr_key, $thr_level);
            $html .= "\n</li>";
            $j++;
        }
    }
    $html .= "\n</ol>";

    return $html;
}

function getSheetBtmTree($key, $thr_level) {

    $html = "\n<ol>";

    if ($thr_level[$key]) { 
        foreach ($thr_level[$key] as $arr_key=>$value) {

            $html .= "\n<li class=\"file\">";
            $html .= "\n  <a style=\"cursor:pointer;\" class=\"thr\" id=\"" . $arr_key ."\" onclick=\"showTyp('". $arr_key . "');\">" . $value . "</a>";
            $html .= "\n</li>";
        }
    }

    $html .= "\n</ol>";

    return $html;

}

function getBrochureTopTree($one_level, $two_level) {

    $i = 1;
    $html = "\n<ol class=\"tree\">";
    foreach ($one_level as $key=>$value) {
        $html .= "\n<li class=\"ptb05\">"; 
        $html .= "\n  <label for=\"folder". $i ."\">";
        $html .= "\n    <span class=\"one\" id=\"". $key ."\">". $value ."</span>";
        $html .= "\n  </label>";
        $html .= "\n<input class=\"bt2\" type=\"checkbox\" id=\"folder". $i ."\" /> ";
        $html .= getBrochureBtmTree($key, $two_level);
        $html .= "\n</li>";
        $i++;
    }
    $html .= "\n</ol>";

    return $html;
}

function getBrochureBtmTree ($key, $two_level) {
    $html = "\n<ol>";

    if ($two_level[$key]) { 
        foreach ($two_level[$key] as $arr_key=>$value) {

            $html .= "\n<li class=\"file\">";
            $html .= "\n  <a style=\"cursor:pointer;\" class=\"thr\" id=\"" . $arr_key ."\" onclick=\"showTyp('". $arr_key . "');\">" . $value . "</a>";
            $html .= "\n</li>";
        }
    }

    $html .= "\n</ol>";

    return $html;

}

?>
