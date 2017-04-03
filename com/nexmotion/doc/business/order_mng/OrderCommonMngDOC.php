<?
//조판정보
function typsetInfo($param) {

    $html = <<<HTML
										<div class="pop-content ofa fix_height590">
											<ul class="form-group">
												<li class="fix_width440 fl">
													<label class="control-label fix_width79 tar">조판명</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[typset_name] ($param[typset_num])</label>
													<br />

													<label class="control-label fix_width79 tar">조판자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[empl_name]</label>
													<br />

													<label class="control-label fix_width79 tar">계열/절수</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[affil] / $param[subpaper]</label>
													<br />

													<label class="control-label fix_width79 tar">조판사이즈</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[size]</label>
													<br />

													<label class="control-label fix_width79 tar">조판일자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[regi_date]</label>
													<br />

													<label class="control-label fix_width79 tar">홍각기/돈떙</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[honggak_yn]</label>
													<br />

													<label class="control-label fix_width79 tar">원본파일</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300"><a href="/common/sheet_typset_file_down.php?seqno=$param[sheet_typset_file_seqno]">$param[origin_file_name]</a></label>
													<br />

													<label class="control-label fix_width79 tar">인쇄수량</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[print_amt]$param[print_amt_unit]</label>
													<br />

													<label class="control-label fix_width79 tar">조판메모</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[memo]</label>
													<br />

													<br />

												</li>

												<li  class="fix_width100 fl">
													<div class="process_view_box8">
														<img src="$param[pic]" style="max-width:340px;max-height:460px;">
													</div>
												</li>
											</ul>
										</div>
HTML;

    return $html;
}

//출력정보
function outputInfo($param) {

    $html = <<<HTML
										<div class="pop-content ofa fix_height590">
											<ul class="form-group">
												<li class="fix_width440 fl">
													<label class="control-label fix_width79 tar">출력명</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[output_name] ($param[typset_num])</label>
													<br />

													<label class="control-label fix_width79 tar">발주자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[orderer]</label>
													<br />

													<label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[manu_name]</label>
													<label class="fix_width15"></label>
													<label class="control-label fix_width79 tar">작업자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[worker]</label>
													<br />

													<label class="control-label fix_width79 tar">발주방법</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[typ] $param[typ_detail]</label>
													<br />

													<label class="control-label fix_width79 tar">날짜</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[work_start_hour]$param[work_end_hour]</label>
													<br />

													<label class="control-label fix_width79 tar">사이즈</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[affil] ($param[size])</label>
													<br />

													<label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[amt]$param[amt_unit]</label>
													<br />

													<label class="control-label fix_width79 tar">판구분</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[board]</label>
													<br />

													<label class="control-label fix_width79 tar">조판메모</label><label class="fix_width20 fs14 tac">:</label>
													<textarea class="bs_noti2" disabled>$param[memo]</textarea>
													<br />

													<label class="control-label fix_width79 tar">작업자메모</label><label class="fix_width20 fs14 tac">:</label>
													<textarea class="bs_noti2" disabled>$param[worker_memo]</textarea>
													<br />

													<label class="control-label fix_width79 tar">작업금액</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[work_price]</label>
													<br />

													<label class="control-label fix_width79 tar">조정금액</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[adjust_price]</label>
													<br />

												</li>

												<li  class="fix_width350 fl">
													<div class="process_view_box8">
														<img src="$param[pic]" style="max-width:340px;max-height:460px;">
													</div>
												</li>
											</ul>
										</div>

HTML;

    return $html;
}

//인쇄정보
function printInfo($param) {

    $html = <<<HTML
										<div class="pop-content ofa fix_height590">

											<ul class="form-group">
												<li class="fix_width440 fl">
													<label class="control-label fix_width79 tar">인쇄명</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[print_name] ($param[typset_num])</label>
													<br />

													<label class="control-label fix_width79 tar">발주자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[orderer]</label>
													<br />

													<label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[manu_name]</label>
													<label class="fix_width15"></label>
													<label class="control-label fix_width79 tar">작업자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[worker]</label>
													<br />

													<label class="control-label fix_width79 tar">발주방법</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[typ] $param[typ_detail]</label>
													<br />

													<label class="control-label fix_width79 tar">날짜</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[work_start_hour]$param[work_end_hour]</label>
													<br />

													<label class="control-label fix_width79 tar">사이즈</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[affil] ($param[size])</label>
													<br />

													<label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[amt]$param[amt_unit]</label>
													<br />

													<label class="control-label fix_width79 tar">인쇄도수</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">전면 기본 $param[beforeside_tmpt]도 별색 $param[beforeside_spc_tmpt]도</label>
													<br />
													<label class="control-label fix_width100 tar"></label>
													<label class="control-label fix_width300">후면 기본 $param[aftside_tmpt]도 별색 $param[aftside_spc_tmpt]도</label>
													<br />

													<label class="control-label fix_width79 tar">조판메모</label><label class="fix_width20 fs14 tac">:</label>
													<textarea class="bs_noti2" disabled>$param[memo]</textarea>
													<br />

													<label class="control-label fix_width79 tar">작업자메모</label><label class="fix_width20 fs14 tac">:</label>
													<textarea class="bs_noti2" disabled>$param[worker_memo]</textarea>
													<br />

													<label class="control-label fix_width79 tar">작업금액</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[work_price]</label>
													<br />

													<label class="control-label fix_width79 tar">조정금액</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[adjust_price]</label>
												</li>
												<li  class="fix_width350 fl">
													<div class="process_view_box8">
														<img src="$param[pic]" style="max-width:340px;max-height:460px;">
													</div>
												</li>

											</ul>
										</div>
HTML;

    return $html;
}

//후공정정보
function afterInfo($param) {

    $html = <<<HTML
										<div class="pop-content ofa fix_height590">
											<ul class="form-group">
												<li class="fix_width440 fl">
													<label class="control-label fix_width79 tar">후공정명</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[after_name] ($param[after_op_seqno])</label>
													<br />

													<label class="control-label fix_width79 tar">발주자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[orderer]</label>
													<br />

													<label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[manu_name]</label>
													<br />

													<label class="control-label fix_width79 tar">작업자</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[worker]</label>
													<br />

													<label class="control-label fix_width79 tar">발주방법</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[op_typ] $param[op_typ_detail]</label>
													<br />

													<label class="control-label fix_width79 tar">날짜</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[work_start_hour]$param[work_end_hour]</label>
													<br />

													<label class="control-label fix_width79 tar">소분류</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width55 tar">$param[cate_name]</label>
													<br />

													<label class="control-label fix_width79 tar">작업상세</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	            <label class="control-label fix_width300">$param[after_name] $param[depth1] $param[depth2] $param[depth3]</label>
													<br />

													<label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	            <label class="control-label fix_width300">$param[amt]$param[amt_unit]</label>
													<br />

													<label class="control-label fix_width79 tar">접수자메모</label><label class="fix_width20 fs14 tac">:</label>
													<textarea class="bs_noti2" disabled>$param[memo]</textarea>
													<br />

													<label class="control-label fix_width79 tar">작업자메모</label><label class="fix_width20 fs14 tac">:</label>
													<textarea class="bs_noti2" disabled>$param[worker_memo]</textarea>
													<br />

													<label class="control-label fix_width79 tar">작업금액</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[work_price]</label>
													<br />

													<label class="control-label fix_width79 tar">조정금액</label><label class="fix_width20 fs14 tac">:</label>
													<label class="control-label fix_width300">$param[adjust_price]</label>
												</li>
												<li  class="fix_width350 fl">
													<div class="process_view_box9">
														<img src="$param[pic]" style="max-width:340px;max-height:200px;">
													</div>
													<br />
													<div class="process_view_box9">
														<img src="$param[after_pic]" style="max-width:340px;max-height:200px;">
													</div>
												</li>
											</ul>
										</div>

HTML;

    return $html;
}
?>
