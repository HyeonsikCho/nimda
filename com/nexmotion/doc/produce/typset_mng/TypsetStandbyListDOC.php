<?
//분판 팝업
function getDivideTypsetPopup($param) {

    $html = <<<HTML
                       	         <!-- View edit 팝업창 -->                       	         
                       	 <!--        <div class="pop_add_box fix_width350 position350"> -->
														     <dl>
														         <dt class="tit">
														         	  <h4>분판관리</h4>
														         </dt>
														         <dt class="cls">
														         	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideDivPopup();"></button>
														         </dt>
														     </dl>
														      
														      <div class="pop-base">
														       	  <div class="pop-content">
														       <div id="$param[type]_add_div">  	
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
                       	         <!-- View edit 팝업창 -->
HTML;

    return $html;
}

//추가 분판 인풋
function addDivide($param) {

    $html = <<<HTML
							                       	           <div class="form-group tac" id="$param[type]_del_div$param[num]">
							                       	               <input type="text" class="input_co2 fix_width55" id="$param[type]_div_val$param[num]" value="$param[val]"> 
                                                                   <!--
							                       	               <button type="button" class="btn btn-sm btn-info" onclick="addSheetDiv();"><i class="fa fa-plus"></i></button>
							                       	               <button type="button" class="btn btn-sm btn-warning" onclick="delSheetDiv($param[num]);"><i class="fa fa-minus"></i></button>
                                                                   -->
							                       	               <button type="button" class="btn btn-sm btn-danger" onclick="delDivide($param[num], '$param[type]');">삭제</button>
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
?>
