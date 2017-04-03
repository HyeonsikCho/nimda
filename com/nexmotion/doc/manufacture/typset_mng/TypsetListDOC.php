<?
function getTypsetView($param) {

    $html = <<<HTML
          <!-- 조판 tab 02 -->
          <div class="table-body">
            <ul class="ofh">
              <li class="fix_width440 fl">

              <!--
              <label class="control-label fix_width79 tar blue_text01">조판명</label>
              <label class="fix_width20 fs14 tac">:</label>
              <input type="text" class="input_co5 fix_width265" id="typset_name" value="$param[typset_name]">
              <br />
              -->

              <label class="control-label fix_width79 tar">조판자</label>
              <label class="fix_width20 fs14 tac">:</label>
              <label class="fs14 tac">$param[empl_name]</label>
              <br />

              <label class="control-label fix_width79 tar blue_text01">계열</label>
              <label class="fix_width20 fs14 tac">:</label>
              <select class="fix_width100" id="typset_affil" onchange="typsetFn.size(); return false;">
                $param[affil]
              </select>
              <br />

              <label class="control-label fix_width79 tar blue_text01">절수</label>
              <label class="fix_width20 fs14 tac">:</label>
              <select class="fix_width100" id="typset_subpaper" onchange="typsetFn.size(); return false;">
                $param[subpaper]
              </select>
              <br />

              <label class="control-label fix_width79 tar blue_text01">사이즈</label>
              <label class="fix_width20 fs14 tac">:</label>
              <input type="text" class="input_co5 fix_width100" id="typset_wid_size" value="$param[wid_size]"> *
              <input type="text" class="input_co5 fix_width100" id="typset_vert_size" value="$param[vert_size]">
              <br />

              <label class="control-label fix_width79 tar blue_text01">홍각기/돈땡</label>
              <label class="fix_width20 fs14 tac">:</label>
              <label class="control-label cp">
              <input type="radio" class="radio_box" name="typset_honggak_yn" id="typset_honggak_y" value="Y" $param[honggak_y]>홍각기
              </label>
              <label class=" fix_width05"></label>
              <label class="control-label cp">
              <input type="radio" class="radio_box" name="typset_honggak_yn" id="typset_honggak_n" value="N" $param[honggak_n]>돈땡
              </label>
              <br />

              <label class="control-label fix_width79 tar blue_text01">도수</label>
              <label class="fix_width20 fs14 tac blue_text01">:</label>
              <label class="control-label fix_width75 tac">전면 : 기본 </label>
              <input type="text" class="input_co5 fix_width30" id="typset_beforeside_tmpt" value="$param[beforeside_tmpt]">
              <label class="control-label">도</label>
              <label class="control-label fix_width55 tac"> 별색 </label>
              <input type="text" class="input_co5 fix_width30" id="typset_beforeside_spc_tmpt" value="$param[beforeside_spc_tmpt]">
              <label class="control-label">도</label>
              <br />

              <label class="control-label fix_width79 tar"> </label>
              <label class="fix_width20 fs14 tac"> </label>
              <label class="control-label fix_width75 tac">후면 : 기본 </label>
              <input type="text" class="input_co5 fix_width30" id="typset_aftside_tmpt" value="$param[aftside_tmpt]">
              <label class="control-label">도</label>
              <label class="control-label fix_width55 tac"> 별색 </label>
              <input type="text" class="input_co5 fix_width30" id="typset_aftside_spc_tmpt" value="$param[aftside_spc_tmpt]">
              <label class="control-label">도</label>
              <br />

              <label class="control-label fix_width79 tar blue_text01">인쇄수량</label>
              <label class="fix_width20 fs14 tac">:</label>
              <input type="text" class="input_co5 fix_width110" id="typset_print_amt" value="$param[print_amt]">
              <label class="fs14 tac">장</label>
              <br />

              <!--
              <label class="control-label fix_width79 tar">상품페이지</label>
              <label class="fix_width20 fs14 tac">:</label>
              <label class="fs14 tac">$param[prdt_page]$param[prdt_page_dvs]</label>
              <input type="hidden" id="prdt_page" value="$param[prdt_page]">
              <input type="hidden" id="prdt_page_dvs" value="$param[prdt_page_dvs]">
              <br />
              -->

              <label class="control-label fix_width79 tar blue_text01">배송판구분</label>
              <label class="fix_width20 fs14 tac">:</label>
              <select class="fix_width120" id="typset_dlvrboard">
                  $param[dlvrboard]
              </select>
              <br />

              <label class="control-label fix_width79 tar">후공정</label>
              <label class="fix_width20 fs14 tac">:</label>
              <label class="fs14 tac">$param[after_list]</label>
              <input type="hidden" id="after_list" value="$param[after_list]">
              <br />

              <label class="control-label fix_width79 tar">옵션</label>
              <label class="fix_width20 fs14 tac">:</label>
              <span class="fs14 tac">$param[opt_list]</span>
              <input type="hidden" id="opt_list" value="$param[opt_list]">
              <br />

              <label class="control-label fix_width79 tar blue_text01">특이사항</label>
              <label class="fix_width20 fs14 tac">:</label>	
              $param[specialty_html]
              <br />

              <label class="control-label fix_width79 tar blue_text01">조판메모</label>
              <label class="fix_width20 fs14 tac">:</label>
              <textarea class="bs_noti2" id="typset_memo">$param[memo]</textarea>
              <br /><br />
              </li>
              <li class="fix_width620 fl">
                <div class="ofa mb15">
                    <div class="demo">
                        <div class="item">            
                            <div class="clearfix">
                                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                                    $param[pic]
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
              </li>
            </ul>
            <hr class="hr_bd3_b">
            <p class="btn-lg red_btn clear ctrl_btn" style="display:none;">
            <a style="cursor: pointer;" onclick="typsetFn.modi();">등록 및 수정</a>
            </p>
            <br /><br /><br />
          </div>
          <!-- table-body -->

HTML;

    return $html;
}

//분판 팝업
function getDivideTypsetPopup($param) {

    $html = <<<HTML
		 <dl>
		     <dt class="tit">
		     	  <h4>분판관리</h4>
		     </dt>
		     <dt class="cls">
		     	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		     </dt>
		 </dl>
		  
		  <div class="pop-base">
		   	  <div class="pop-content">
		   <div id="add_div">  	
              $param[html]
              <input type="hidden" id="$param[idx]" value="$param[num]">
           </div> 
           $param[add_sheet_btn_html]
		   <hr class="hr_bd2">
           <br />
	       <div class="form-group tac" style="margin-top: -20px;margin-bottom: -10px;">
           <label class="control-label fix_width">합계</label><label class="fix_width20 fs14 tac">:</label>
		       <label class="control-label fix_width">$param[total]</label>
          $param[notice_html]
		   </div>  
		   	  </div>
		  </div>
          $param[save_btn_html]
        </div>                       	         
HTML;

    return $html;
}

//추가 분판 인풋
function addDivide($param) {

    $html = <<<HTML
							                       	           <div class="form-group tac" id="del_div$param[num]">
							                       	               <input type="text" class="input_co2 fix_width55" id="div_val$param[num]" value="$param[val]"> 
							                       	               <button type="button" class="btn btn-sm btn-danger" onclick="orderFn.sub($param[num]);">삭제</button>
							                       	           </div> 

HTML;

    return $html;
}

//추가 분판 인풋  disabled
function addDivideDisabled($param) {

    $html = <<<HTML
							                       	           <div class="form-group tac">
							                       	               <input type="text" class="input_co2 fix_width55" id="$param[type]_div_val$param[num]" value="$param[val]" disabled> 
							                       	           </div> 

HTML;

    return $html;
}

//출력 보기
function getOutputView($param) {

    $html = <<<HTML
        <li class="fix_width440 fl">
        <label class="control-label fix_width79 tar blue_text01">공정 </label>
        <label class="fix_width20 fs14 tac">:</label>

        <label class="control-label cp">
        <input type="radio" class="radio_box" name="output_porcess_use_yn" value="Y" onchange="changePorcessUseYn('output', this.value);" $param[output_y]>사용
        </label>
        <label class=" fix_width15"></label>
        <label class="control-label cp">
        <input type="radio" class="radio_box" name="output_porcess_use_yn" value="N" onchange="changePorcessUseYn('output', this.value);" $param[output_n]>사용안함
        </label>
        <br />
        <label class="control-label fix_width79 tar">출력명</label>
        <label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width145" value="$param[name]" id="output_name" disabled>
        <br />
        <label class="control-label fix_width79 tar">출력업체</label>
        <label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width145" id="output_manu_name" value="$param[manu_name]" disabled>
        <input type="hidden" id="output_brand_seqno" value="$param[extnl_brand_seqno]" disabled>
        <br />

        <label class="control-label fix_width79 tar">사이즈</label>
        <label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width110" value="$param[affil]" id="output_affil" disabled>
        <input type="text" class="input_co5 fix_width55" value="$param[wid_size]" id="output_wid_size" disabled>
        <label class="fix_width20 fs14 tac">*</label>
        <input type="text" class="input_co5 fix_width55" value="$param[vert_size]" id="output_vert_size" disabled>
        <br />

        <label class="control-label fix_width79 tar">판</label>
        <label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width110" value="$param[board]" id="output_board" disabled>
        <br />
        <label class="control-label fix_width79 tar blue_text01">수량</label>
        <label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width110" value="$param[amt]" id="output_amt"><label class="fs14 tac">판</label>
        <input type="hidden" value="$param[amt_unit]" id="output_amt_unit">
        <br />

        <label class="control-label fix_width79 tar blue_text01">출력메모</label>
        <label class="fix_width20 fs14 tac">:</label>
        <textarea class="bs_noti2" id="output_memo">$param[memo]</textarea>
        <br />

        </li>

HTML;

    return $html;
}

//인쇄 보기
function getPrintView($param) {

    $html = <<<HTML
        <label class="control-label fix_width79 tar blue_text01">공정 </label>
        <label class="fix_width20 fs14 tac">:</label>
        <label class="control-label cp">
        <input type="radio" class="radio_box" name="print_porcess_use_yn" value="Y" onchange="changePorcessUseYn('print', this.value);" $param[print_y]>사용
        </label>
        <label class=" fix_width15"></label>
        <label class="control-label cp">
        <input type="radio" class="radio_box" name="print_porcess_use_yn" value="N" onchange="changePorcessUseYn('print', this.value);" $param[print_n]>사용안함
        </label>
        <br />

        <label class="control-label fix_width79 tar blue_text01">인쇄도수</label>
        <label class="fix_width20 fs14 tac">:</label>
        <label class="control-label fix_width75 tac">전면 : 기본 </label>
        <input type="text" class="input_co5 fix_width30" id="print_beforeside_tmpt" value="$param[beforeside_tmpt]">
        <label class="control-label">도</label>
        <label class="control-label fix_width55 tac"> 별색 </label>
        <input type="text" class="input_co5 fix_width30" id="print_beforeside_spc_tmpt" value="$param[beforeside_spc_tmpt]">
        <label class="control-label">도</label>
        <br />

        <label class="control-label fix_width79 tar"> </label><label class="fix_width25 fs14 tac"></label>
        <label class="control-label fix_width75 tac">후면 : 기본 </label>
        <input type="text" class="input_co5 fix_width30" value="$param[aftside_tmpt]"> 
        <label class="control-label">도</label>
        <label class="control-label fix_width55 tac"> 별색 </label>
        <input type="text" class="input_co5 fix_width30" value="$param[aftside_spc_tmpt]"> 
        <label class="control-label">도</label>
        <br />

        <label class="control-label fix_width79 tar">인쇄명</label><label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width150" id="print_name" value="$param[name]" disabled>
        <br />

        <label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width150" id="print_manu_name" value="$param[manu_name]" disabled>
        <input type="hidden" id="print_brand_seqno" disabled>
        <br />

        <label class="control-label fix_width79 tar">사이즈</label><label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width110" id="print_affil" value="$param[affil]" disabled>
        <input type="text" class="input_co5 fix_width55" id="print_wid_size" value="$param[wid_size]" disabled>
        <label class="fix_width20 fs14 tac">*</label>
        <input type="text" class="input_co5 fix_width55" id="print_vert_size" value="$param[vert_size]" disabled>
        <br />

        <label class="control-label fix_width79 tar blue_text01">총인쇄도수</label><label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width110" value="$param[tot_tmpt]" id="print_tot_tmpt">
        <label class="fs14">도</label>
        <br />

        <label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
        <label class="fs14">$param[amt]</label>
        <label class="fs14">$param[amt_unit]</label>
        <br />

        <label class="control-label fix_width79 tar blue_text01">인쇄메모</label>
        <label class="fix_width20 fs14 tac">:</label>
        <textarea class="bs_noti2" id="print_memo">$param[memo]</textarea>
        <br />
HTML;

    return $html;
}

//후공정 보기
function getAfterView($param) {

    $html = <<<HTML
        <label class="control-label fix_width79 tar">후공정</label>
        <label class="fix_width20 fs14 tac">:</label>
        <label class="fs14">$param[after_name] $param[depth1] $param[depth2] $param[depth3]</label>
        <input type="hidden" value="$param[after_name]" id="after_name">
        <input type="hidden" value="$param[depth1]" id="depth1">
        <input type="hidden" value="$param[depth2]" id="depth2">
        <input type="hidden" value="$param[depth3]" id="depth3">
        <br />

        <label class="control-label fix_width79 tar">후공정 상세</label>
        <label class="fix_width20 fs14 tac">:</label>
        <label class="fs14">$param[detail]</label>
        <input type="hidden" value="$param[detail]" id="detail">
        <br />

        <label class="control-label fix_width79 tar blue_text01">후공정 업체</label>
        <label class="fix_width20 fs14 tac">:</label>	
        <select class="fix_width140" id="extnl_etprs_seqno" onchange="receiptFn.manu(this.value);">
          $param[manu_html]
        </select>
        <br />

        <label class="control-label fix_width79 tar blue_text01">브랜드</label>
        <label class="fix_width20 fs14 tac">:</label>	
        <select class="fix_width140" id="extnl_brand_seqno">
          $param[brand_html]
        </select>
        <br />

        <label class="control-label fix_width79 tar blue_text01">수량</label>
        <label class="fix_width20 fs14 tac">:</label>
        <input type="text" class="input_co5 fix_width120" value="$param[amt]" id="after_amt"> 
        <label class="fs14">장</label>
        <br />

        <label class="control-label fix_width79 tar blue_text01">메모</label>
        <label class="fix_width20 fs14 tac">:</label>
        <textarea class="bs_noti2" style="width:269px;" id="after_memo">$param[memo]</textarea>
        <br />

        <input type="hidden" value="$param[seq]" id="seq">
        <input type="hidden" value="$param[dlvrboard]" id="dlvrboard">
        <input type="hidden" value="$param[order_common_seqno]" id="order_common_seqno">
        <input type="hidden" value="$param[order_after_history_seqno]" id="order_after_history_seqno">
        <p class="btn-lg red_btn clear"><a style="cursor:pointer" onclick="receiptFn.op('$param[after_op_seqno]'); return false;">등록 및 수정</a></p>
        <br />
        <br />
        <br />

HTML;

    return $html;
}
?>
