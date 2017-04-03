<?
/**
 * @brief 사용자 주문페이지에서 사용될
 * 템플릿 다운로드 팝업 html 생성
 *
 * @param $rs = 해당 카테고리의 템플릿 데이터 검색결과
 * @param $param = 검색결과 외적으로 필요한 부가정보
 *
 * @return 생성된 html
 */
function makeTemplatePopHtml($rs, $param) {
    $cate_name = $param["cate_name"];

    $down_btn = "<button onclick=\"downloadTemplate('%s', '%s');\">다운</button>";;

    $td_base .= "\n                 <td>%s</td>"; // uniq_num
    $td_base .= "\n                 <td>%s</td>"; // stan_name
    $td_base .= "\n                 <td>%s</td>"; // cut_size
    $td_base .= "\n                 <td>%s</td>"; // work_size
    $td_base .= "\n                 <td class=\"btn\">%s</td>";
    $td_base .= "\n                 <td class=\"btn\">%s</td>";
    $td_base .= "\n                 <td class=\"btn\">%s</td>";

    $tr_html  = '';
    while ($rs && !$rs->EOF) {
        $fields = $rs->fields;

        $ai_btn  = '';
        $eps_btn = '';
        $cdr_btn = '';

        if (!empty($fields["ai_origin_file_name"])) {
            $ai_btn  = sprintf($down_btn, "ai"
                                        , $fields["cate_template_seqno"]);
        }
        if (!empty($fields["eps_origin_file_name"])) {
            $eps_btn  = sprintf($down_btn, "eps"
                                         , $fields["cate_template_seqno"]);
        }
        if (!empty($fields["cdr_origin_file_name"])) {
            $cdr_btn  = sprintf($down_btn, "cdr"
                                         , $fields["cate_template_seqno"]);
        }

        $tr_html .= "\n             <tr>";
        $tr_html .= sprintf($td_base, $fields["uniq_num"]
                                    , $fields["stan_name"]
                                    , $fields["cut_size"]
                                    , $fields["work_size"]
                                    , $ai_btn
                                    , $eps_btn
                                    , $cdr_btn);
        $tr_html .= "\n             </tr>";

        $rs->MoveNext();
    }

    $html = <<<html
        <header>
            <nav class="_tab">
                <ul>
                    <li><button class="_templete"><h2>템플릿 다운로드</h2></button></li>
                </ul>
            </nav>
            <button class="close" title="닫기"><img src="/design_template/images/common/btn_circle_x_white.png" alt="X"></button>
        </header>
        <article>
            <article class="_tabContents _templete">
                <h3>{$cate_name}</h3>
                <table class="list thead">
                    <colgroup>
                        <col width="90">
                        <col width="90">
                        <col width="90">
                        <col width="90">
                        <col width="60">
                        <col width="60">
                        <col width="60">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>고유번호</th>
                            <th>사이즈</th>
                            <th>재단사이즈(mm)</th>
                            <th>작업사이즈(mm)</th>
                            <th>AI</th>
                            <th>EPS</th>
                            <th>CDR</th>
                        </tr>
                    </thead>
                </table>
                <div class="tableScroll">
                    <div class="wrap">
                        <table class="list">
                            <colgroup>
                                <col width="90">
                                <col width="90">
                                <col width="90">
                                <col width="90">
                                <col width="60">
                                <col width="60">
                                <col width="60">
                                <col>
                            </colgroup>
                            <tbody>
                                {$tr_html}
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </article>
        <script>
            tab();
        </script>
html;

    return $html;
}
?>
