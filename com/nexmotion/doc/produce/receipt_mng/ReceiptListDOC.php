<?
//상태변경관리
function statusMng($list, $paging) {

    $html = <<<HTML

														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>상태변경관리</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideStatusMng();"></button>
														 	    </dt>
														 	</dl>
														 	 <div class="pop-base">
														 	  	  <div class="pop-content">
							                    	            <div class="form-group">				                                                
                             <div class="table-body" style="min-height:150px;">
                                   <ul class="table_top">
							      	                  <li class="sel">
					                      	        <select class="fix_width200" id="pop_order_state" onchange="changeStatusOption();">
                                                        <option value="">상태(전체)</option>
                                                        <option value="접수중">접수중</option>
                                                        <option value="시안요청중">시안요청중</option>
                                                        <option value="접수보류">접수보류</option>
					                      	        </select>
					                      		    </li>
					                      		    <li class="sel tar">
					                      	            <label for="pop_search_txt" class="con_label">Search(인쇄제목) : <input type="text" class="search_btn fix_width120" id="pop_search_txt" onkeyup="searchKey(event);"><button type="button" class="btn btn-sm btn-info fa fa-search" onclick="searchText();"></button></label>
					                      		    </li>
                                   </ul>
					                      		<table class="fix_width100f">
                                                   <thead>
					                      				<tr>
					                      					<th class="bm2px">주문번호</th>
					                      					<th class="bm2px">회원명</th>
					                      					<th class="bm2px">인쇄제목</th>
					                      					<th class="bm2px">소분류</th>
					                      					<th class="bm2px">접수자</th>
					                      					<th class="bm2px">상태</th>
					                      					<th class="bm2px">관리</th>
					                      				</tr>
					                      			 </thead>
					                      			 <tbody id="pop_list">	
                                                         $list
					                      			 </tbody>
					                      		</table>
					                      		<p class="fs12" style="position: relative;padding: 0px;height: 0px;">
					                      			  Showing 1 to 5 of 20 entries
					                                  <select name="list_set" class="fix_width55" onchange="showPageSetting(this.value, 'pop');">
					                      	            <option>5</option>
					                      	            <option>10</option>
					                      	            <option>20</option>
					                              	    <option selected="selected">30</option>
					                      	          </select>
					                      		</p>
										           <div class="tac clear" id="pop_page">
                                                     $paging
										           </div>
					                  </div>
                       	      </div>
							                    	            </div>				                                

														 	  	  </div>
														 	 </div>
														 	  	      <p class="tac">
														 	  	          <button type="button" class="btn btn-sm btn-primary" onclick="hideStatusMng();">닫기</button>
														 	  	      </p> 
                                                                      </ br>

														 	 </div>
														 	  <br />

HTML;

    return $html;
}

//접수창 팝업
function receiptPopup($param) {

    $html = <<<HTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>낱장형 접수창</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideReceiptPopup('$param[seqno]', '$param[state]');"></button>
														 	    </dt>
														 	</dl>
														 	 <div class="pop-base">
														 	  	  <div class="pop-content">
							                    	           <div class="form-group">			
							                    	            	<label class="control-label fix_width75 tar">주문번호</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[order_num]</label> 
							                    	            	<br />
							                    	            	<label class="control-label fix_width75 tar">회원이름</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[member_name]($param[office_nick])</label>
							                    	            	<br />

							                    	            	<label class="control-label fix_width75 tar">인쇄물제목</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[title]</label>
							                    	            	<br />							                    	            	
							                    	            	<label class="control-label fix_width75 tar">상품명</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[cate_name]</label>
							                    	            	<br />							                    	            	
							                    	            	<label class="control-label fix_width75 tar">수량</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[amt]$param[amt_unit_dvs]</label>
							                    	            	<br />							                    	            	
							                    	            	<label class="control-label fix_width75 tar">배송</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[dlvr_way] ($param[dlvr_sum_way])</label>
                       	                                <div class="table-body">
                                                              <div class="table_basic">
					                                                		<table class="fix_width100f">
                                                                             <thead>
					                                                				<tr>
					                                                					<th class="bm2px"></th>
					                                                					<th class="bm2px">주문내용</th>
					                                                					<th class="bm2px">공정관리</th>
					                                                				</tr>
					                                                			 </thead>
					                                                			 <tbody>	
					                                                				<tr>
					                                                					<td class="fwb">종이</td>
					                                                					<td>$param[paper_info]</td>
					                                                					<td></td>
					                                                				</tr>
					                                                				<tr>
					                                                					<td class="fwb">사이즈</td>
					                                                					<td>$param[stan_name]</td>
					                                                					<td></td>
					                                                				</tr>
					                                                				<tr>
					                                                					<td class="fwb">인쇄도수</td>
					                                                					<td>$param[print_tmpt_name]</td>
					                                                					<td></td>
					                                                				</tr>
                                                                                    <tbody id="after_info">
					                      				                            $param[after_html]
                                                                                    </tbody>
                                                                                    <!--
					                                                				<tr>
					                                                					<td class="fwb">입출고</td>
					                                                					<td class="fwb">
					                                                						<label class="form-radio form-normal"><input type="radio" name="de-blk" $param[stor_release_y]>사용</label>
					                                                						<label class="fix_width15"></label>   
					                                                						<label class="form-radio"><input type="radio" name="de-blk" $param[stor_release_n]>사용안함</label>
					                                                					</td>
					                                                					<td></td>
					                                                				</tr>	
                                                                                    <tr>       
                                                                                    <td class="fwb">배송</td>
					                                                					<td>택배</td>
					                                                					<td></td>
					                                                				</tr>
                                                                                    -->
					                                                			 </tbody>
					                                                		</table>
                                                              </div>
                       	                                </div>
                       	                                
                       	                                <label class="control-label fix_width75 tar">고객메모</label><label class="fix_width20 fs14 tac">:</label>
							                    	    <textarea class="bs_noti2" disabled style="width: 437px;">$param[memo]</textarea>
							                    	    <br />							                    	           	    							                    	           	    
                                                        $param[order_file_html]
							                    	    <label class="control-label fix_width75 tar">건수</label><label class="fix_width20 fs14 tac">:</label>	
							                    	    <label class="control-label">$param[count]건</label> 
							                    	    <br />
                                                        $param[upload_html]
											            <br />
											            <label class="control-label fix_width75 tar">주소</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[order_addr] $param[order_name]</label> 
                                                        <br />

                       	                                <label class="control-label fix_width75 tar">옵션</label><label class="fix_width20 fs14 tac">:</label> <label class="control-label">$param[opt_name]</label>
							                    	    </div>				                                
														</div>
														 	 </div>
														 	  	<div class="form-group">			
														 	  	      <p class="tac mt15">
                                                                          <!--
														 	  	          <button type="button" class="btn btn-sm btn-warning" onclick="changeStatus('$param[seqno]', '340', 'Y');" $param[disabled]>보류</button>
                                                                          -->
														 	  	          <label class="fix_width140"> </label>
														 	  	          <button type="button" class="btn btn-sm btn-success" onclick="getReceipt('$param[seqno]', '$param[count]', '$param[order_detail_dvs_num]', '$param[order_detail_seqno]');" $param[disabled]>접수</button>
														 	  	          <button type="button" class="btn btn-sm btn-primary" onclick="hideReceiptPopup('$param[seqno]', '$param[state]');">닫기</button>
														 	  	          <label class="fix_width140"> </label>
														 	  	          
														 	  	          <button type="button" class="btn btn-sm btn-danger" onclick="delOrder('$param[seqno]', '$param[order_detail_dvs_num]');">주문삭제</button>
														 	  	      </p> 
														 	 </div>
														 	  <br />
														 
HTML;

    return $html;
}

//접수창 후공정 팝업
function receiptAfterPopup($param) {

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
							                    	           	<!--
							                    	            	  <div style="margin-left:75px;width:410px;" class="process_view_box7">  
                                                        $param[opt_name]
                                  	  									</div>

							                    	            	<label class="control-label fix_width75 tar">주문번호</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[order_num]</label> 
							                    	            	<br />
							                    	            	<label class="control-label fix_width75 tar">회원이름</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[member_name]($param[office_nick])</label>
							                    	            	<hr class="hr_bd3_b">
							                    	            	<label class="control-label fix_width75 tar">주문요약</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width44 tar"> </label><label class="control-label fix_width75 tar">인쇄물제목</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[title]</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width20 tar"> </label><label class="fix_width20 fs14 tac"> </label>   <label class="control-label fix_width75 tar">상품명</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[cate_name]</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width20 tar"> </label><label class="fix_width20 fs14 tac"> </label>   <label class="control-label fix_width75 tar">종이</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[order_detail]</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width20 tar"> </label><label class="fix_width20 fs14 tac"> </label>   <label class="control-label fix_width75 tar">인쇄도수</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[print_tmpt_name]</label>
							                    	            	<br /><br />
											                 	     
											                 	     <label class="control-label fix_width75 tar">배송정보</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">택배</label> 
											                 	     <br />
											                 	     <label class="control-label fix_width75 tar"> </label><label class="fix_width20 fs14 tac"> </label>	<label class="control-label">서울 중구 을지로 15길 6-6 301호 영남자기 인사과</label> 
											                 	     <br /><br />
                                                                     -->
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
							                    	           	    <select class="fix_width140" id="op_typ" value="$param[op_typ]" onchange="selectOpTyp(this.value);">
							                    	           	        <option value="">선택</option>
							                    	           	        <option value="자동발주">자동발주</option>
							                    	           	        <option value="전화발주">전화발주</option>
							                    	           	        <option value="팩스발주">팩스발주</option>
							                    	           	        <option value="이메일발주">이메일발주</option>
							                    	           	    </select>
							                    	           	    <input id="op_typ_detail" type="text" class="input_co2 fix_width140" value="$param[op_typ_detail]">
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

//접수창 후공정 팝업
function receiptAfterViewPopup($param) {

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
							                    	           	<!--
							                    	            	  <div style="margin-left:75px;width:410px;" class="process_view_box7">  
                                                        $param[opt_name]
                                  	  									</div>

							                    	            	<label class="control-label fix_width75 tar">주문번호</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[order_num]</label> 
							                    	            	<br />
							                    	            	<label class="control-label fix_width75 tar">회원이름</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">$param[member_name]($param[office_nick])</label>
							                    	            	<hr class="hr_bd3_b">
							                    	            	<label class="control-label fix_width75 tar">주문요약</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width44 tar"> </label><label class="control-label fix_width75 tar">인쇄물제목</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[title]</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width20 tar"> </label><label class="fix_width20 fs14 tac"> </label>   <label class="control-label fix_width75 tar">상품명</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[cate_name]</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width20 tar"> </label><label class="fix_width20 fs14 tac"> </label>   <label class="control-label fix_width75 tar">종이</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[order_detail]</label>
							                    	            	<br />
							                    	            	<label class="control-label fix_width20 tar"> </label><label class="fix_width20 fs14 tac"> </label>   <label class="control-label fix_width75 tar">인쇄도수</label><label class="fix_width20 fs14 tac">:</label><label class="control-label">$param[print_tmpt_name]</label>
							                    	            	<br /><br />
											                 	     
											                 	     <label class="control-label fix_width75 tar">배송정보</label><label class="fix_width20 fs14 tac">:</label>	<label class="control-label">택배</label> 
											                 	     <br />
											                 	     <label class="control-label fix_width75 tar"> </label><label class="fix_width20 fs14 tac"> </label>	<label class="control-label">서울 중구 을지로 15길 6-6 301호 영남자기 인사과</label> 
											                 	     <br /><br />
                                                                     -->
											                 	    <label class="control-label fix_width75 tar">작업파일</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label"><a href="/common/after_work_file_down.php?seqno=$param[after_op_work_file_seqno]"> $param[origin_file_name]</a></label>
											                 	    <br />
											                 	    <label class="control-label fix_width75 tar">발주제목</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label">$param[title]</label>
							                    	           	    <br />
							                    	           	    <label class="control-label fix_width75 tar">후공정명</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label">$param[after_name]</label>
							                    	           	    <br />
							                    	           	    <label class="control-label fix_width75 tar">수주처</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label">$param[manu_name]</label>
							                    	           	    <br />
							                    	           	    <label class="control-label fix_width75 tar">수량</label><label class="fix_width20 fs14 tac">:</label>	
                                                                    <label class="control-label">$param[amt]$param[amt_unit_dvs]</label>
							                    	           	    <br />
                       	                                            <label class="control-label fix_width75 tar">메모</label><label class="fix_width20 fs14 tac">:</label>
							                    	           	    <textarea class="bs_noti2" style="width:269px;" disabled="disabled">$param[memo]</textarea>
							                    	           	    <br />	
							                    	           	    <label class="control-label fix_width75 tar">발주유형</label><label class="fix_width20 fs14 tac">:</label>							                    	           	    
                                                                    <label class="control-label">$param[op_typ]$param[op_typ_detail]</label>

							                    	            </div>				                                
														 	  	  </div>
														 	 </div>
	
														 	  	<div class="form-group">			
														 	  	      <p class="tac mt15">
														 	  	          <button type="button" class="btn btn-sm btn-primary" onclick="hideAddAfterPopup('$param[state]');">닫기</button>
														 	  	      </p> 
														 	 </div>
														 	  <br />
														 
HTML;

    return $html;
}
?>
