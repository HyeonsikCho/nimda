<?
function getPublicPopup($param) {

    $html = <<<VIEWHTML
			<form name="publicForm" id="publicForm" method="post">
				<dl>
					<dt class="tit">
					<h4>증빙유형 등록/수정</h4>
					</dt>
					<dt class="cls">
						<button type="button" onclick="hideRegiPopup();" class="btn btn-sm btn-danger fa fa-times"></button>
					</dt>
				</dl>
				<div class="pop-base">
					<div class="pop-content">
						<div class="form-group">
							<label class="control-label fix_width79 tar">회원명</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" class="input_co2 fix_width200" name="member_name" value="$param[member_name]"><br />
							<label class="control-label fix_width75 tar">증빙유형</label>
							<label class="fix_width20 fs14 tac">:</label><br />
							<label class="form-radio">
                                <input type="radio" name="public_dvs" class="radio_box" value="세금계산서" onchange="changePublicDvs(this.value);" $param[tax_invoice]> 세금계산서 
                            </label>
							<label class="form-radio">
                                <input type="radio" name="public_dvs" class="radio_box" value="미발행" onchange="changePublicDvs(this.value)" $param[unissued]> 미발행 
                            </label>
							<label class="form-radio">
                                <input type="radio" value="현금영수증" name="public_dvs" class="radio_box" onchange="changePublicDvs(this.value)" $param[cashreceipt]> 현금영수증 
                            </label>( 
							<label class="form-radio">
                                <input type="radio" id="income_tax" checked="checked" value="소득공제" name="evid_dvs" class="radio_box" $param[income_tax]>소득공제
                            </label>
							<label class="form-radio">
                                <input type="radio" id="expense" name="evid_dvs" value="지출증빙" class="radio_box" $param[expense]>지출증빙
                            </label> - <input type="text" name="cashreceipt_num" id="cashreceipt_num" name="cashreceipt_num" class="input_co2 fix_width120" value="$param[cashreceipt_num]" $param[cashreceipt_style]> )</label>
						</div>
						<hr class="hr_bd3">
                        <div id="info_area" $param[info_style]>
                        <div class="form-group">
                        <label class="control-label fix_width79 tar">관리사업자</label><label class="fix_width20 fs14 tac">:</label>
						<select onchange="changeLicensee(this.value);" class="fix_width180">
							<option value="">선택하세요</option>
                            $param[licensee_html]
						</select>
                        </div>
                        <div class="form-group">
                        <label class="control-label fix_width79 tar">공급받는자</label><label class="fix_width20 fs14 tac">:</label>
                        <input type="text" class="input_co2 fix_width170" id="supply_corp" name="supply_corp" value="$param[supply_corp]">
                        <label class="control-label fix_width79 tar">사업자번호</label><label class="fix_width20 fs14 tac">:</label>
                        <input type="text" class="input_co2 fix_width170" id="crn" name="crn" value="$param[crn]">
                        </div>
						<div class="form-group">
							<label class="control-label fix_width79 tar">대표자</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" class="input_co2 fix_width170" id="repre_name" name="repre_name" value="$param[repre_name]">
							<label class="control-label fix_width79 tar">주소</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" class="input_co2 fix_width170" id="addr" name="addr" value="$param[addr]">
						</div>
						<div class="form-group">
							<label class="control-label fix_width79 tar">업태</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" class="input_co2 fix_width170" id="bc" name="bc" value="$param[bc]">
							<label class="control-label fix_width79 tar">종목</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" class="input_co2 fix_width170" id="tob" name="tob" value="$param[tob]">
						</div>
						<hr id="hr" class="hr_bd3">
                        </div>
						<div class="form-group">
                            <div id="date_area" style="float:left;">
							    <label class="control-label fix_width79 tar">발행일자</label><label class="fix_width20 fs14 tac">:</label>
							    <input type="text" id="date" name="public_date" class="input_co2 fix_width170 date" value="$param[public_date]" placeholder="yyyy-MM-dd">
                            </div>
                            <div id="item_area" style="float:left; margin-left:4px; $param[item_style]">
							    <label class="control-label fix_width79 tar">품목</label><label class="fix_width20 fs14 tac">:</label>
							    <input type="text" id="item" name="item" class="input_co2 fix_width170" value="$param[item]">
                            </div>
						</div>
                        <div style="clear:both"></div>
						<div class="form-group" id="tax_area">
							<label class="control-label fix_width79 tar">공급가액</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" name="supply_price" id="supply_price" class="input_co2 fix_width170" value="$param[supply_price]">
							<label class="control-label fix_width79 tar">세액</label><label class="fix_width20 fs14 tac">:</label>
							<input type="text" id="vat" name="vat" class="input_co2 fix_width170" value="$param[vat]">
						</div>
						<div class='clear mt15 tac'>
							<label onclick="updatePublic($param[public_seqno]);" class="btn btn_md fix_width80"><i class="fa fa-plus blue"></i>발행</label>
							<label class="fix_width50"></label>
							<label onclick="hideRegiPopup();" class="btn btn_md fix_width80"><i class="fa fa-plus blue"></i>취소</label>
						</div>
					</div>
				</div>
			</form>


VIEWHTML;

    return $html;
}

function getPublicDivPopup($param) {

    $html = <<<VIEWHTML
			<dl>
				<dt class="tit">
				<h4>증빙분할관리</h4>
				</dt>
				<dt class="cls">
					<button type="button" onclick="hideRegiPopup();" class="btn btn-sm btn-danger fa fa-times"></button>
				</dt>
			</dl>

			<div class="pop-base">
				<div class="pop-content">
					<div>
						<div id="add_div">
                            <div class="form-group tac" id="del_div1">
							    <input onkeydown="loadNowTotal();" type="text" id="div_val1" class="input_co2 fix_width55" value="">
							    <button type="button" class="btn btn-sm btn-danger" onclick="delDiv(1);">삭제</button>
                            </div>
						</div>
						<div class="form-group tac">
							<input type="text" class="input_co2 fix_width55" value="0" style="margin-right: 54px;" id="nowtotal" disabled>
						</div>
						<input type="hidden" id="sheetIdx" value="1">
					</div>
					<div class="pop-base tac">
						<button type="button" class="btn  btn-info fwb nanum" onclick="addDiv();">증빙 추가</button>
					</div>

					<hr class="hr_bd2">
					<br />
					<div class="form-group tac" style="margin-top: -20px;margin-bottom: -10px;">
						<label class="control-label fix_width">합계</label><label class="fix_width20 fs14 tac">:</label>
						<label class="control-label fix_width">$param[total]</label>
					</div>

				</div>
			</div>

			<div class="pop-base tac">
				<button type="button" onclick="savePublicDiv('$param[seqno]', '$param[total]')" class="btn  btn-primary fwb nanum"> 저장</button>
			</div>


VIEWHTML;

    return $html;
}

//추가 인풋
function addDiv($param) {

    $html = <<<HTML
							                       	           <div class="form-group tac" id="del_div$param[num]">
							                       	               <input type="text" class="input_co2 fix_width55" onkeydown="loadNowTotal()" id="div_val$param[num]"> 
							                       	               <button type="button" class="btn btn-sm btn-danger" onclick="delDiv($param[num]);">삭제</button>
							                       	           </div> 

HTML;

    return $html;
}
?>

