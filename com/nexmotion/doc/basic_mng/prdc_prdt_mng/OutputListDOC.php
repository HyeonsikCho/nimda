<?
function getPrdcOutputView($param) {

    $html = <<<VIEWHTML

                                <form name="output_form" id="output_form" method="post">
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>출력조회 / 수정</h4>
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
						       	                              <a class="box">출력 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">				                                                
				                                                <label class="control-label fix_width120 tar">출력대분류</label><label class="fix_width20 fs14 tac">:</label>
				                                                <select id="output_top" name="output_top" class="fix_width184">
                                                                $param[top_html]
				                                                </select>
				                                                <br />
				                                                <label class="control-label fix_width120 tar">출력명</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" id="pop_output_name" name="pop_output_name" class="input_co2 fix_width180" value="$param[name]"> 
				                                                <br />
				                                                <label class="control-label fix_width120 tar">출력판</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" id="board" name="board" class="input_co2 fix_width180" value="$param[board]">
				                                                <br />
				                                                <label class="control-label fix_width120 tar"></label><label class="fix_width20 fs14 tac"></label>

				                                                <br />
				                                                <label class="control-label fix_width120 tar">계열</label><label class="fix_width20 fs14 tac">:</label>
				                                                <select id="affil" name="affil" class="fix_width180">
				                                                    <option value="46">46계열</option>
				                                                    <option value="국">국계열</option>
				                                                    <option value="별">별규격계열</option>
				                                                </select>
				                                                <br />
				                                                <label class="control-label fix_width120 tar">가로사이즈</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width100"  id="wid_size" name="wid_size" onkeydown='return checkNumber(event)' onkeyup='removeChar(event)' style='ime-mode:disabled;' maxlength="9" value="$param[wid_size]">  mm
				                                                <label class="control-label fix_width100  tar">세로사이즈</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width100" id="vert_size" name="vert_size" onkeydown='return checkNumber(event)' onkeyup='removeChar(event)' style='ime-mode:disabled;' maxlength="9" value="$param[vert_size]">mm
				                                                <hr class="hr_bd2">

				                                                
				                                                <label class="control-label fix_width120 tar">기준단위</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" id="crtr_unit" name="crtr_unit" value="$param[crtr_unit]">
				                                                
							                    	            </div>				                                

														 	  	  </div>
														 	 </div>
														 
														 	 <div class="form-group">			
														 	  	      <p class="tac mt15">
														 	  	          <button onclick="saveOutput(); return false;" type="button" class="btn btn-sm btn-success">저장</button>
														 	  	          <label class="fix_width5"> </label>
														 	  	          <button onclick="delPopOutput(); return false;" type="button" class="btn btn-sm btn-danger">삭제</button>
														 	  	          <label class="fix_width140"> </label>
														 	  	          <button onclick="hideRegiPopup(); return false;" type="button" class="btn btn-sm btn-primary">닫기</button>
														 	  	      </p> 
														 	 </div>

												
														 	  
														 	  <br />
                                                              </form>

VIEWHTML;

    return $html;

}
?>
