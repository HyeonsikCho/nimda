<?
function getPrdcAfterView($param) {

    $html = <<<VIEWHTML

                                <form name="after_form" id="after_form" method="post">
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>후공정조회 / 수정</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-danger fa fa-times"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base2">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">업체 정보 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">
														 	  	  	
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">제조사</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" id="pop_manu_name" class="input_co2 fix_width180" value="$param[manu_name]" disabled>
							                    	               <label class="control-label fix_width120 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" id="pop_brand_name" class="input_co2 fix_width180" value="$param[brand]" disabled>
							                    	           </div>    
							                    	           
														 	  	  </div>
														 	 </div>
														 	 
														 	 <div class="pop-base2">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">후공정 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">				                                                
				                                                <label class="control-label fix_width120 tar">후공정명</label><label class="fix_width20 fs14 tac">:</label>
				                                                <select id="after_name" name="after_name" class="fix_width184">
                                                                $param[name_html]
				                                                </select>
				                                                <br />
				                                                <label class="control-label fix_width120 tar">Depth1</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" id="pop_depth1" name="pop_depth1" class="input_co2 fix_width180" value="$param[depth1]">
                                                                <br />
				                                                <label class="control-label fix_width120 tar">Depth2</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" id="pop_depth2" name="pop_depth2" class="input_co2 fix_width180" value="$param[depth2]">
				                                                <br />
				                                                <label class="control-label fix_width120 tar">Depth3</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" id="pop_depth3" name="pop_depth3" class="input_co2 fix_width180" value="$param[depth3]">

				                                                <br /><br />

				                                                <hr class="hr_bd2">
				                                                <label class="control-label fix_width120 tar">기준단위</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" id="crtr_unit" name="crtr_unit" value="$param[crtr_unit]">
                                                                <label class="control-label fix_width120 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" id="amt" name="amt" class="input_co2 fix_width180" maxlength="10" value="$param[amt]">
							                    	            </div>				                                

														 	  	  </div>
														 	 </div>
														 
														 	 <div class="form-group">			
														 	  	      <p class="tac mt15">
														 	  	          <button type="button" onclick="saveAfter(); return false;" class="btn btn-sm btn-success">저장</button>
														 	  	          <label class="fix_width5"> </label>
														 	  	          <button type="button" onclick="delPopAfter(); return false;" class="btn btn-sm btn-danger">삭제</button>
														 	  	          <label class="fix_width140"> </label>
														 	  	          <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-primary">닫기</button>
														 	  	      </p> 
														 	 </div>

												
														 	  
														 	  <br />

                                                              </form>
VIEWHTML;

    return $html;


}
?>
