<?
function makePaperInfoHtml($rs, $dvs) {
    $ret = "<option value=''>선택</option>";

    while ($rs && !$rs->EOF) {
        $fld = $rs->fields[$dvs];

        $ret .= option($fld, $fld);

        $rs->MoveNext();
    }

    return $ret;
}
?>
