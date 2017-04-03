<?
/**
 * @param 주문정보 팝업 html 생성
 *
 * @detail $param에는 주문 공통 테이블에 저장되어있는
 * 기본정보(basic_info)
 * 추가정보(add_info)
 * 가격정보(price_info)
 * 결제정보(pay_info)
 * html이 넘어온다
 *
 * @param $param = 정보 배열 
 * 
 * @return 팝업 html
 */
function makePaperOpPrintHtml($info) {

    $html = <<<html
            <dl>
                <dt class="tit">
                    <h4>종이 발주서</h4>
                </dt>
                <dt class="cls">
                    <button type="button" onclick="hideRegiPopup()" class="btn btn-sm btn-danger fa fa-times"></button>
                </dt>
            </dl>  

            <div class="pop-base">
                <div class="pop-content">
                    <div class="form-group">
                      $info
                    </div>  
                </div> <!-- pop-content -->
            </div>
            <!-- pop-base -->
	 	<div class="form-group">			
                <p class="tac mt15" onclick="hideRegiPopup()">
                    <label class="btn btn_md fix_width120"> 닫기</label>          
                </p>
            </div>
            <br />
html;

    return $html;
}

//접수창 후공정 팝업
function brochureReceiptAfterPopup($param) {

    $html = <<<HTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>생산지시서 - 후공정</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideAddAfterPopup('$param[state]');"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 
														 	 <div class="pop-base">
														 	  	  <div class="pop-content">
							                    	           <div class="form-group">			
											                 	     <label class="control-label fix_width75 tar blue_text01">작업파일</label><label class="fix_width20 fs14 tac">:</label>	
							                    	           	    <!-- 파일 업로드 / 스크립이 하단에 있어야 작동함 
											                 	     <input id="upload_path" class="disableInputField" placeholder="후공정작업파일" disabled="disabled" value="$param[origin_file_name]"/>
                                                                    -->
											                 	     <label class="fileUpload">
                                                                         <!-- 
											                 	         <input id="upload_file" type="file" class="upload" onchange="fileSearchBtn(this.value);"/>
											                 	         -->



											                 	         <button class="btn btn-sm btn-info fa fa-folder-open" id="op_work_file">찾아보기</button>
											                 	     </label>
											                 	     <br />
                                                                     <input type="hidden" id="op_uploaded_work_file_seqno" name="op_uploaded_work_file_seqno" value="$param[after_op_work_file_seqno]"/>
                                                                     <input type="hidden" id="op_uploaded_work_file_filename" name="op_uploaded_work_file_filename" value="$param[origin_file_name]"/>
                                                                     <input type="hidden" id="op_uploaded_work_file_filesize" name="op_wtork_file_filesize" value="$param[size]"/>
                                                                     <div id="op_uploaded_work_file_list"></div>
                                                                     <input type="hidden" id="op_work_file_seqno" name="op_work_file_seqno" />
                                                                     <div id="op_work_file_list"></div>




											                 	       <label class="control-label fix_width75 tar">발주제목</label><label class="fix_width20 fs14 tac">:</label>	
                                                                       <label class="control-label">$param[title]</label>
							                    	           	    <br />
							                    	           	    <label class="control-label fix_width75 tar">후공정명</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label">$param[tot_after_name]</label>
							                    	           	    <br />
							                    	           	    <label class="control-label fix_width75 tar blue_text01">수주처</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <select class="fix_width140" id="extnl_etprs_seqno" onchange="changeAfterManu(this.value);">
                                                                      <option value="">선택</option>
                                                                      $param[etprs_html]
							                    	           	    </select>
							                    	           	    <br />
							                    	           	    <label class="control-label fix_width75 tar blue_text01">브랜드</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <select class="fix_width140" id="extnl_brand_seqno">
                                                                      <option value="">선택</option>
                                                                      $param[brand_html]
							                    	           	    </select>
							                    	           	    <br />

							                    	           	    <label class="control-label fix_width75 tar">수량</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label">$param[amt]$param[amt_unit_dvs]</label>
							                    	           	    <br />
                       	                                <label class="control-label fix_width75 tar blue_text01">메모</label><label class="fix_width20 fs14 tac">:</label>
							                    	           	    <textarea class="bs_noti2" style="width:269px;" id="memo">$param[memo]</textarea>
							                    	           	    <br />	
							                    	           	    <label class="control-label fix_width75 tar blue_text01">발주유형</label><label class="fix_width20 fs14 tac">:</label>							                    	           	    
							                    	           	    <select class="fix_width140" id="op_typ" value="$param[op_typ]" onchange="selectOpTyp(this.value, 'receipt');">
							                    	           	        <option value="">선택</option>
							                    	           	        <option value="자동발주">자동발주</option>
							                    	           	        <option value="전화발주">전화발주</option>
							                    	           	        <option value="팩스발주">팩스발주</option>
							                    	           	        <option value="이메일발주">이메일발주</option>
							                    	           	    </select>
							                    	           	    <input id="receipt_typ_detail" type="text" class="input_co2 fix_width140" value="$param[op_typ_detail]">
							                    	           	    <input id="order_common_seqno" type="hidden" class="fix_width140" value="$param[order_common_seqno]">
							                    	           	    <input id="after_seqno" type="hidden" class="fix_width140" value="$param[after_seqno]">
							                    	           	    <input id="title" type="hidden" class="fix_width140" value="$param[title]">
							                    	           	    <input id="after_name" type="hidden" class="fix_width140" value="$param[after_name]">
							                    	           	    <input id="depth1" type="hidden" class="fix_width140" value="$param[depth1]">
							                    	           	    <input id="depth2" type="hidden" class="fix_width140" value="$param[depth2]">
							                    	           	    <input id="depth3" type="hidden" class="fix_width140" value="$param[depth3]">
							                    	           	    <input id="amt" type="hidden" class="fix_width140" value="$param[amt]">
							                    	           	    <input id="amt_unit_dvs" type="hidden" class="fix_width140" value="$param[amt_unit_dvs]">
							                    	           	    <input id="seq" type="hidden" class="fix_width140" value="$param[seq]">

							                    	            </div>				                                
														 	  	  </div>
														 	 </div>
														 
	
														 	  	<div class="form-group">			
														 	  	      <p class="tac mt15">
														 	  	          <button type="button" class="btn btn-sm btn-success" onclick="saveAfterOp('$param[order_detail_dvs_num]');">저장</button>
														 	  	          <button type="button" class="btn btn-sm btn-primary" onclick="hideAddAfterPopup('$param[state]');">닫기</button>
														 	  	      </p> 
														 	 </div>
														 	  <br />
														 
HTML;

    return $html;
}
?>
