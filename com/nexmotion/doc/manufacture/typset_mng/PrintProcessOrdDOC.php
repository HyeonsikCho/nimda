<?
//지시 팝업
function getDirectionPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>인쇄생산계획 지시관리</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base">
		<div class="pop-content ofa" style="padding:0;">
        	<div class="table-body">
        		<div class="table_basic">
        			<ul class="table_top">
        				<li class="sel"></li>
        				<li class="sel tar"></li>
        			</ul>
                    <form name="frm" id="frm">
                    <input type="hidden" id="date_from" name="date_from">
        			<table class="table fix_width920">
        				<thead>
        				<tr>
        					<th>인쇄업체</th>
        					<th>당일판</th>
        					<th>본판</th>
        					<th>계획추가</th>
        				</tr>
        				</thead>
        				<tbody id="directionList">
                            $param[direction]
        				</tbody>
        			</table>
                    </form>
        			<div class="tac clear fr">
        				<button class="btn btn_pu fix_width120 fix_height30 bgreen fs12" onclick="regiDirection(); return false;">저장하기</button>
        			</div>
        
        		</div>
        	</div>
	</div>
HTML;

    return $html;
}


?>
