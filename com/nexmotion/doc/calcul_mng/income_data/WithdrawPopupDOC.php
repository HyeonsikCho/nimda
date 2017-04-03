<?
//입금내역 상세보기
function getWithdrawPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>입금내역 - 상세</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>
	<div class="pop-base">
	    <div class="pop-content ofa" style="padding:0;">
        <div class="table-body3" style="padding-bottom:0;">
        <div class="table_basic none-hover">
        <table class="table" style="width:100%;">
        <tbody>
            <tr>
                <th>입금유형</th>
                <th>입금액</th>
                <th>입출금 경로상세</th>
                <th>등록일자</th>
            </tr>
HTML;

    $html .= $param;

    $html .= <<<HTML
            </tr>
        </tbody>
        </table>
        </div>
        </div>
        </div>
        </div>
HTML;
    return $html;
}
?>
