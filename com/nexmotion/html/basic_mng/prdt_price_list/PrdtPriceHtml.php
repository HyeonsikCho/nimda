<?
/**
 * @brief 카테고리 수량 option html 생성
 *
 * @param $rs       = 검색결과
 * @param $amt_unit = 수량단위
 * @param $default  = default selected 값
 * @param $price_info_arr = 가격검색용 정보저장 배열
 *
 * @return option html
 */
function makeCateAmtOption($rs, $amt_unit) {
    $ret = '';

    while($rs && !$rs->EOF) {
        $amt = $rs->fields["amt"];
        $amt = doubleval($amt);

        $selected = "";

        $ciphers = 0;
        if ($amt < 1) {
            $ciphers = 1;
        }

        $amt_format = number_format($amt, $ciphers);
        $amt_format .= ' ' . $amt_unit;

        $ret .= option($amt_format, $amt, $selected);

        $rs->MoveNext();
    }

    return $ret;
}
?>
