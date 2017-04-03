<?
//출력작업일지
function outputProcessViewPopup($param) {

    $html = <<<HTML
     <dl>
          <dt class="tit">
               <h4>출력공정 - 작업일지</h4>
          </dt>
          <dt class="cls">
               <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="windowClose();"></button>
          </dt>
     </dl>  
														 	
     <div class="pop-base fl fix_width860">        
			  											 	 	  
          <div class="pop-content ofa fix_height590">

                             <ul class="form-group">
                                  <li class="fix_width440 fl">
                                  	  	  <label class="control-label fix_width79 tar">출력명</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width150">$param[output_name] ($param[typset_num])</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">발주자</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width120">$param[orderer]</label>
                                  	  	  <br />
                                  	  	  
		                         		  <label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width120">$param[manu]</label>
                                          <br />
 
                                          <label class="control-label fix_width79 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width120">$param[brand]</label>
                                          <br />

                                  	  	  <label class="control-label fix_width79 tar">작업자</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width120">$param[worker]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">발주방법</label><label class="fix_width20 fs14 tac">:</label>
	                           			  <label class="control-label fix_width300">$param[typ] $param[typ_detail]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">날짜</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width300">$param[work_start_hour] $param[work_end_hour]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">사이즈</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width110">$param[affil] ($param[size])</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width100">$param[amt]$param[amt_unit]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">판구분</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width40">$param[board]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">조판메모</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <textarea class="bs_noti2" disabled style="resize: none;">$param[memo]</textarea>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업자메모</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <textarea class="bs_noti2" disabled style="resize: none;">$param[worker_memo]</textarea>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업금액</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width50 tar">$param[work_price]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">조정금액</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <input type="text" class="input_co2 fix_width120" id="adjust_price" value="$param[adjust_price]" onkeyup="getNumberFormat(this.value, 'adjust_price');">
                                  	  	  <label class="btn btn_md fix_width120" onclick="getPriceAdjust('$param[output_work_report_seqno]');"> 금액조정</label>
                                  	  	  <br />                      	  	  
                                  </li>                        
                                  	  
                                  <li  class="fix_width350 fl">                               	
                                      <div class="process_view_box7">  
                                         $param[opt_list]
                                  	  </div>                                  	  
                                  	  <br />
                                  	  <div class="process_view_box8">  
					                     <img src="$param[pic]" style="max-width:340px;max-height:450px;">
                                  	  </div>                          
                                  </li>                                  
                             </ul>

          </div> <!-- pop-content -->
     </div>
     <!-- pop-base -->
     
     <div class="pop-base clear">     
     	<p class="tac mt5">
          <label class="btn btn_md fix_width120" onclick="windowClose();"> 닫기</label>          
      </p>
      <br /><br />
          
    </div>
HTML;

    return $html;
}

//인쇄작업일지
function printProcessViewPopup($param, $flag="") {

    $html = <<<HTML
     <dl>
          <dt class="tit">
               <h4>인쇄공정 - 작업일지</h4>
          </dt>
          <dt class="cls">
			   <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="windowClose();"></button>
          </dt>
     </dl>  
														 	
     <div class="pop-base fl fix_width860">        
			  											 	 	  
          <div class="pop-content ofa">

                             <ul class="form-group">
                                  <li class="fix_width440 fl">
                                  	  	  <label class="control-label fix_width79 tar">인쇄명</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width150">$param[print_name] ($param[typset_num])</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">발주자</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width120">$param[orderer]</label>
                                  	  	  <br />
                                  	  	  
		                         		  <label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width120">$param[manu]</label>
                                          <br />

                                          <label class="control-label fix_width79 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width120">$param[brand]</label>
                                          <br />

                                  	  	  <label class="control-label fix_width79 tar">작업자</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width120">$param[worker]</label>
                                  	  	  <br />
                                  	  	  <label class="control-label fix_width79 tar">발주방법</label><label class="fix_width20 fs14 tac">:</label>
	                           			  <label class="control-label fix_width300">$param[typ] $param[typ_detail]</label>
                                   	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">날짜</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width300">$param[work_start_hour] $param[work_end_hour]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">사이즈</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width110">$param[affil] ($param[size])</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width100">$param[amt]$param[amt_unit]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">인쇄도수</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width150 tar">전면 기본 4도 별색 1도</label>                                  	  	  
                                  	  	  <br />
                                  	  	  <label class="control-label fix_width81 tar"></label>
                                  	  	  <label class="control-label fix_width170 tar">후면 기본 1도 별색 1도</label>
                                  	  	  <br />                              	  	  
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">조판메모</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <textarea class="bs_noti2" disabled style="resize: none;">$param[memo]</textarea>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업자메모</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <textarea class="bs_noti2" disabled style="resize: none;">$param[worker_memo]</textarea>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업금액</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width50 tar">$param[work_price]</label>
                                  	  	  <br />
                                  
                                          $param[adjust_price_html]          
                                  </li>
                                  
                                  <li  class="fix_width350 fl">
                                  	
                                      <div class="process_view_box7">  
                                         $param[opt_list]
                                  	  </div>
                                  	  
                                  	  <br />                                         	                                  	
                                  	  <div class="process_view_box8">  
					                     <img src="$param[pic]" style="max-width:340px;max-height:450px;">
                                  	  </div>                                  	  	
                                  </li>
                             </ul>

          </div> <!-- pop-content -->
     </div>
     <!-- pop-base -->
     
     <div class="pop-base clear">     
     	<p class="tac mt5">
          <label class="btn btn_md fix_width120" onclick="windowClose();"> 닫기</label>          
      </p>
      <br /><br />    	    
          
    </div>
 
HTML;

    return $html;
}

//후공정작업일지
function afterProcessViewPopup($param) {

    $html = <<<HTML
     <dl>
          <dt class="tit">
               <h4>후공정 - 작업일지</h4>
          </dt>
          <dt class="cls">
               <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="windowClose();"></button>
          </dt>
     </dl>  
														 	
     <div class="pop-base fl fix_width860">        
			  											 	 	  
          <div class="pop-content ofa fix_height620">

                             <ul class="form-group">
                                  <li class="fix_width440 fl">
                                  	  	  <label class="control-label fix_width79 tar">후공정명</label><label class="fix_width20 fs14 tac">:</label>
	                				      <label class="control-label fix_width150">$param[after_name] ($param[after_op_seqno])</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">발주자</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width120">$param[orderer]</label>
                                  	  	  <br />
                                  	  	  
		                         		  <label class="control-label fix_width79 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width120">$param[manu]</label>
                                          <br />
 
                                          <label class="control-label fix_width79 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
		                         		  <label class="control-label fix_width120">$param[brand]</label>
                                          <br />

                                  	  	  <label class="control-label fix_width79 tar">작업자</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width120">$param[worker]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">발주방법</label><label class="fix_width20 fs14 tac">:</label>
	                           			  <label class="control-label fix_width300">$param[typ] $param[typ_detail]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">날짜</label><label class="fix_width20 fs14 tac">:</label>
					                      <label class="control-label fix_width300">$param[work_start_hour] $param[work_end_hour]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">소분류</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width55">$param[cate]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업상세</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width300">$param[after_name] $param[depth1] $param[depth2] $param[depth3]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width100">$param[amt]$param[amt_unit]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">접수자메모</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <textarea class="bs_noti2" disabled>$param[memo]</textarea>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업자메모</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <textarea class="bs_noti2" disabled>$param[worker_memo]</textarea>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">작업금액</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <label class="control-label fix_width50 tar">$param[work_price]</label>
                                  	  	  <br />
                                  	  	  
                                  	  	  <label class="control-label fix_width79 tar">조정금액</label><label class="fix_width20 fs14 tac">:</label>
                                  	  	  <input type="text" class="input_co2 fix_width120" id="adjust_price" value="$param[adjust_price]" onkeyup="getNumberFormat(this.value, 'adjust_price');">
                                  	  	  <label class="btn btn_md fix_width120" onclick="getPriceAdjust('$param[after_work_report_seqno]');"> 금액조정</label>
                                  	  	  <br />                      	  	  
                                  </li>                        
                                  	  
                                  <li  class="fix_width350 fl">
                                      <div class="process_view_box7">
                                         $param[opt_list]
                                  	  </div>
                                  	  <br />
                                  	  <div class="process_view_box9">
					                     <img src="$param[pic]" style="max-width:340px;max-height:450px;">
                                  	  </div>
                                  	  <br />
                                  	  <div class="process_view_box9">
					                    <img src="$param[after_pic]" style="max-width:340px;max-height:200px;">
                                  	  </div>
                                  </li>
                             </ul>

          </div> <!-- pop-content -->
     </div>
     <!-- pop-base -->
     
     <div class="pop-base clear">     
     	<p class="tac mt5">
          <label class="btn btn_md fix_width120" onclick="windowClose();"> 닫기</label>          
      </p>
      <br /><br />    	    
    </div>

HTML;

    return $html;
}
?>
