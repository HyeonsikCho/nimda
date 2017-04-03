<?
//배송정보 입력
function getDeliveryInfoRegiPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>배송처리</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base">
	  <div class="pop-content">
        <label class="control-label fix_width140"> <주문자정보></label>
        <div class="form-group" style="border: 1px solid #ddd;">
          <label class="control-label fix_width75 tar">이름</label><label class="fix_width10 fs14 tac">:</label>
          $param[name]
          <br />
          <label class="control-label fix_width75 tar">전화번호</label><label class="fix_width10 fs14 tac">:</label>
          $param[tel_num]
          <label class="control-label fix_width140 tar">휴대폰번호</label><label class="fix_width10 fs14 tac">:</label>
          $param[cell_num]
          <br />
          <label class="control-label fix_width75 tar">우편번호</label><label class="fix_width10 fs14 tac">:</label>
          $param[zipcode]
          <br />
          <label class="control-label fix_width75 tar">주소</label><label class="fix_width10 fs14 tac">:</label>
          $param[addr]
          <br />
          <label class="control-label fix_width75 tar"></label><label class="fix_width10 fs14 tac"> </label>
          $param[addr_detail]
        </div>
        <br />
        <div class="form-group">
          <label class="control-label fix_width75 tar">배송회사</label><label class="fix_width10 fs14 tac">:</label>
          <input type="text" class="input_co2 fix_width140" id="invo_cpn" value="$param[invo_cpn]">
          <label class="control-label fix_width83 tar">송장번호</label><label class="fix_width10 fs14 tac">:</label>
          <input type="text" class="input_co2 fix_width140" id="invo_num" value="$param[invo_num]">
          <input type="hidden" id="order_common_seqno" value="$param[order_common_seqno]">
          <input type="hidden" id="order_detail_seqno" value="$param[order_detail_seqno]">
        </div>
        <hr class="hr_bd3">
        <p class="tac mt15">
        $param[btn]
        <label class="fix_width5"> </label>
        <button type="button" class="btn btn-sm btn-primary" onclick="hideRegiPopup();">닫기</button>
        </p>
      </div> <!-- pop-content -->
    </div>
HTML;

    return $html;
}


?>
