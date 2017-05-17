<?
//콤보박스 option 생성
function option($str, $val="", $attr="") {
    return sprintf("\n  <option value=\"%s\" %s>%s</option>", $val
                                                            , $attr
                                                            , $str);
}

/**
 * @brief 옵션 html 생성
 *
 * @param $rs   = 검색결과
 * @param $val  = option value에 들어갈 필드값
 * @param $dvs  = option에 표시할 필드값
 * @param $base = 기본으로 추가할 option 값
 * @param $flag = $base를 사용할지 flag
 *
 */
function makeOptionHtml($rs, $val, $dvs, $base="전체", $flag="Y") {

    if ($flag == "Y") {
        $html = "\n" . option($base);   

    } else {
        $html = "";
    }

    while ($rs && !$rs->EOF) {

        $fields = $rs->fields[$dvs]; 

        //만약 $val 빈값이면
        if ($val === "") {
            $value = $fields;
        } else {
            $value = $rs->fields[$val];
        }

        $html .= "\n" . option($fields, $value);   

        $rs->MoveNext();
    }

    return $html;
}

/**
 * @brief 규격 옵션 html 생성
 *
 * @param $rs   = 검색결과
 * @param $val  = option value에 들어갈 필드값
 * @param $dvs  = option에 표시할 필드값
 * @param $pos_num_arr = 자리수 배열
 * @param $base = 기본으로 추가할 option 값
 * @param $flag = $base를 사용할지 flag
 *
 * @return option html
 */
function makeStanOptionHtml($rs,
                            $val,
                            $dvs,
                            $pos_num_arr,
                            $base="전체",
                            $flag="Y") {

    if ($flag == "Y") {
        $html = "\n" . option($base);   
    } else {
        $html = "";
    }

    while ($rs && !$rs->EOF) {

        $fields = $rs->fields[$dvs]; 

        //만약 $val 빈값이면
        if ($val === "") {
            $value = $fields;
        } else {
            $value = $rs->fields[$val];
        }

        $attr  = "affil=\"" . $rs->fields["affil"] . "\"";

        if (is_array($pos_num_arr)) {
            $attr .= " pos_num=\"" . $pos_num_arr[$fields] . "\"";
        }

        $html .= "\n" . option($fields, $value, $attr);   

        $rs->MoveNext();
    }

    return $html;
}

/**
 * @brief 옵션 html 생성
 *
 * @param $rs   = 검색결과
 * @param $arr["flag"]  = "기본 값 존재여부"
 * @param $arr["def"]  = "기본 값(ex:전체)"
 * @param $arr["def_val"]  = "기본 값의 option value"
 * @param $arr["val"]  = "option value에 들어갈 필드 값"
 * @param $arr["dvs"]  = "option에 표시할 필드값"
 * @param $arr["dvs_tail"]  = "option 값 뒤에 붙일 단어"
 *
 */
function makeSelectOptionHtml($rs, $arr) {

    if ($arr["flag"] == "Y") {
        $html = "\n" . option($arr["def"], $arr["def_val"]);   

    } else {
        $html = "";
    }

    while ($rs && !$rs->EOF) {

        $fields = $rs->fields[$arr["dvs"]]; 

        //필드 값 뒤에 붙일 단어
        if ($arr["dvs_tail"]) {

            $fields = $fields . $arr["dvs_tail"];

        }

        //만약 $val 빈값이면
        if ($arr["val"] === "" || $arr["val"] === NULL) {
            $value = $fields;
        } else {
            $value = $rs->fields[$arr["val"]];
        }

        $html .= "\n" . option($fields, $value);   

        $rs->MoveNext();
    }

    return $html;
}

//일자별 검색 버튼 생성
function makeDateSetBtn($param, $str, $fn="dateSet", $wid="40") {

    $html  = "<a style=\"cursor: pointer;\" onclick=\"" . $fn;
    $html .= "('" . $param . "')\" class=\"btn btn_md fix_width";
    $html .= $wid . "\">" . $str . "</a>";

    return $html;
}

//일자별 검색
function makeDateInfo($id) {
 
    $html .= "\n<input type=\"text\" id=\"date_" . $id . "\"";
    $html .= " class=\"input_co2 fix_width83 date\" placeholder=\"yyyy-MM-dd\">";

    $html .= "<select id=\"time_" . $id . "\" class=\"fix_width55\" style=\"margin-left:5px;\">";

    for ($i=0; $i<24; $i++) {
        $html .= option($i, $i);
    }

    $html .= "</select>";
    return $html;
}

//일자검색
function makeDatePickerOne($func="") {

    $html = "";

    $html .= "\n<input type=\"text\" onchange=\"".$func."\" id=\"date\"";
    $html .= " class=\"input_co2 fix_width83 date\" placeholder=\"yyyy-MM-dd\">";

    return $html;
}

/**
 * @brief 날짜일 검색 html 생성
 *
 * @param $param["value"]  = option value에 들어갈 필드값 - 배열
 * @param $param["fields"]  = option에 표시할 필드값 - 배열
 * @param $param["id"] = id 값
 * @param $param["flag"] = 검색조건여부
 *
 */
function makeDatePickerHtml($param) {

    $html = "";

    if ($param["flag"] == true) {
        $html .= "\n<select id=\"" . $param["id"] . "\" class=\"fix_width150\">";

        for ($i=0; $i < count($param["fields"]); $i++) {
            $html .= "\n" . option($param["fields"][$i], $param["value"][$i]);
        }
        $html .= "\n</select>";
    }

    if (!$param["func"]) {
        $param["func"] = "dateSet";
    }

    $html .= "\n" . makeDateInfo($param["from_id"]) . " ~ ";
    $html .= "\n" . makeDateInfo($param["to_id"]);
    $html .= "\n" . makeDateSetBtn(1, "어제", $param["func"], 40);
    $html .= "\n" . makeDateSetBtn(0, "오늘", $param["func"], 40);
    $html .= "\n" . makeDateSetBtn(7, "일주일", $param["func"], 55);
    $html .= "\n" . makeDateSetBtn(30, "한달", $param["func"], 40);
    $html .= "\n" . makeDateSetBtn("last", "작년동기", $param["func"], 63);
    $html .= "\n" . makeDateSetBtn("all", "전체", $param["func"], 40);
    //$html .= "<br /><label style=\"margin-left:178px;\">";
    //$html .= "</label>";

    return $html;
}

/*
 * 검색 list 생성
 *
 * @param $arr["col"]  = "컬럼 값"
 * @param $arr["val"]  = "컬럼에 해당하는 value 값"
 * @param $arr["type"]  = "검색 한 필드(함수명에 들어갈 이름)"
 *
 * return : list
 */
function makeSearchList($result, $arr) {

    $buff = "";

    while ($result && !$result->EOF) {

        $buff .= "<a href=\"#\" onclick=\"" . $arr["type"];
        $buff .= "Click('" . $result->fields[$arr["col"]];
        $buff .= "')\"><li>" . $result->fields[$arr["val"]] . "</li></a>";

        $result->moveNext();
    }

    return $buff;
}

/*
 * 검색 list 생성
 *
 * @param $arr["opt"]  = "옵션값"
 * @param $arr["opt_val"]  = "옵션 value 값"
 * @param $arr["func"]  = "함수명에 들어갈 이름"
 *
 * return : list
 */
function makeSearchListSub($result, $arr) {

    $buff = "";

    while ($result && !$result->EOF) {

        $buff .= "<a href=\"#\" onclick=\"";
        $buff .= "nameClick('" . $result->fields[$arr["opt_val"]];
        $buff .= "', '" . $result->fields[$arr["opt"]];
        $buff .= "', '" . $result->fields["cpn_admin_seqno"] . "')\"><li>";
        $buff .= $result->fields[$arr["opt"]] . "</li></a>";

        $result->moveNext();
    }

    return $buff;
}

/*
 * 검색 list 생성(concat)
 *
 * @param $arr["opt"]  = "옵션값"
 * @param $arr["opt_val"]  = "옵션 value 값"
 * @param $arr["func"]  = "함수명에 들어갈 이름"
 *
 * return : list
 */
function makeSearchListConcat($result, $arr) {

    $buff = "";

    while ($result && !$result->EOF) {

        $buff .= "<a href=\"#\" onclick=\"" .  $arr["func"];
        $buff .= "Click('" . $result->fields[$arr["opt_val"]];
        $buff .= "', '" . $result->fields[$arr["opt"]] . "')\"><li>" . $result->fields[$arr["view_name"]] . "</li></a>";

        $result->moveNext();
    }

    return $buff;
}

/**
 * @brief 숫자 select 박스 option 생성
 *
 * @param $start_num   = 처음 숫자
 * @param $end_num   = 마지막 숫자
 *
 */
function makeOptionTimeHtml($start_num, $end_num) {

    $html = "";
    for($i=$start_num; $i<$end_num+1; $i++) {

        if (strlen($i) == "1") $i = "0" . $i;

        $html .= "\n  <option value=\"" . $i . "\">" . $i . "</option>";

    }

    return $html;
}

/**
 * @brief 숫자 select 박스 option 생성
 *
 * @param $start_num   = 처음 숫자
 * @param $end_num   = 마지막 숫자
 *
 */
function makeOptionTimeSelectHtml($start_num, $end_num, $select_num) {

    $html = "";
    for($i=$start_num; $i<$end_num+1; $i++) {

        $selected = "";
        if ($select_num == $i) {

            $selected = "selected=\"selected\"";
        }

        if (strlen($i) == "1") $i = "0" . $i;

        $html .= "\n  <option value=\"" . $i . "\"";
        $html .= $selected . ">" . $i . "</option>";

    }
    return $html;
}
?>
