<?
function getPaperStockMngPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>재고조정</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base2">
		<div class="pop-content">
			<div class="form-group">
				<label><span class="fwb red_text01">* 재고조정은 수정이 불가능합니다. 등록을 통해만 재고량을 조정할 수 있습니다. 신중하게 작성하시길 바랍니다.</span></label>
				<br />
				<label class="control-label fix_width85 tar">등록일자</label><label class="fix_width20 fs14 tac">:</label>
				  $param[today]
				<br />
				<label class="control-label fix_width85 tar">종이제조사</label><label class="fix_width20 fs14 tac">:</label>
                <select class="fix_width150" id="manu" onchange="selectPaperManu(''); return false;">
                    $param[manu]
				</select>
				<br />
				<label class="control-label fix_width85 tar">종이</label><label class="fix_width20 fs14 tac">:</label>
                <select class="fix_width110" id="name" name="name" onchange="selectPaperName(''); return false;" disabled>
				 	<option value="">종이(전체)</option>
	            </select>
		        <select class="fix_width110" id="dvs" name="dvs" onchange="selectPaperDvs(''); return false;" disabled>
				 	<option value="">구분(전체)</option>
	            </select>
		        <select class="fix_width110" id="color" name="color" onchange="selectPaperColor(''); return false;" disabled>
				    <option value="">색상(전체)</option>
	            </select>
		        <select class="fix_width110" id="basisweight" name="basisweight" onchange="selectPaperBasisweight(); return false;" disabled>
				    <option value="">평량(전체)</option>
                </select>
				<br />

				<label class="control-label fix_width85 tar">현재 재고량</label><label class="fix_width20 fs14 tac">:</label>
                <span id="now_stock_amt">종이를 선택해주세요.</span>
				<br />
				<label class="control-label fix_width85 tar">실재고량</label><label class="fix_width20 fs14 tac">:</label>
				<input type="number" min="0" id="realstock_amt" class="input_co5 fix_width130" value=""> <label class="control-label">R</label>
                <br />
				<label class="control-label fix_width85 tar">재고조정</label><label class="fix_width20 fs14 tac">:</label>
				<label class="form-radio form-normal">
					<input type="radio" checked="" value="Y" name="stor_yn">입고
				</label>
				<label class="fix_width15"></label>
				<label class="form-radio">
					<input type="radio" value="N" name="stor_yn">사용
				</label>
				<input type="number" min="0" id="amt" class="input_co5 fix_width130" value=""> <label class="control-label">R</label>
				<br />
				<label class="control-label fix_width85 tar">작성자</label><label class="fix_width20 fs14 tac">:</label>
                    $param[worker]  
                  <input type="hidden" value="$param[worker]"> 
				<br />
				<label class="control-label fix_width85 tar">조정이유</label><label class="fix_width20 fs14 tac">:</label>
				<textarea class="bs_noti2" id="adjust_reason" style="width:800px;"></textarea>
				<br />
			</div>
            <hr class="hr_bd3">
			<div class="form-group">
				<p class="tac mt15">
					<button type="button" id="btn" onclick="regiStockCtrl(); return false;" class="btn btn-sm btn-success">등록</button>
					<button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-primary">닫기</button>
				</p>
			</div>
		</div>
	</div>
	<!-- pop-base -->
HTML;

    return $html;
}
?>
