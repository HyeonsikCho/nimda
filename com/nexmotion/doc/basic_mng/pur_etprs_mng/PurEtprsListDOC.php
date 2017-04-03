<?
function getPurEtprsRegi() {

    $html = <<<HTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>매입업체 등록</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
							                    	           <div class="pop-content">
                                                               <div class="tab-content">
                                                               <form name="regi_form" id="regi_form">
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">매입업체명</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="manu_name" maxlength="30" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">매입품</label><label class="fix_width20 fs14 tac">:</label>
                                                                    <select name="edit_pur_prdt" id="edit_pur_prdt" class="fix_width120">
							                    						<option value="종이">종이</option>
							                    						<option value="출력">출력</option>
							                    						<option value="인쇄">인쇄</option>
							                    						<option value="후공정">후공정</option>
							                    						<option value="카드명함">카드명함</option>
							                    						<option value="자석">자석</option>
							                    						<option value="메뉴판">메뉴판</option>
							                    						<option value="마스터">마스터</option>
							                    						<option value="그린백">그린백</option>
							                    						<option value="기타">기타</option>
				                                                    </select>  
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width140 tar">거래상태</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <label class="form-radio"><input type="radio" name="deal_type" value="Y" class="radio_box" checked> 거래중</label>
							                    	               <label class="fix_width15"></label>   
							                    	               <label class="form-radio"><input type="radio" name="deal_type" value="N" class="radio_box"> 거래중지</label>			                    	               
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">전화번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="tel_num" maxlength="30" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">팩스번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="fax" maxlength="30" value="">
							                    	               <label class="control-label fix_width105 tar">홈페이지</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width200" name="hp" maxlength="255" value="">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">회사명</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="cpn_name" maxlength="30" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">E-Mail</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width250" name="mail" maxlength="255" value="">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">주소</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width40" maxlength="5" name="zipcode" id="zipcode" value="" readonly>
							                    	               <label class="btn btn_pu fix_width75 bgreen fs13" onclick="getPostcode('');">우편번호</label>
							                    	               <br />
							                    	               <label class="fix_width140"></label>
							                    	               <input type="text" class="input_co2 fix_width350" id="addr" maxlength="255" name="addr" value="" readonly>
							                    	               <input type="text" class="input_co2 fix_width350" id="addr_detail" maxlength="255" name="addr_detail" value="">
                                                                   <hr class="hr_bd3">
							                    	               <label class="control-label fix_width120 tar">매입업체 담당자</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="etprs_mng_name" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">부서</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="etprs_depar" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">직책</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="etprs_job" value="">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">전화번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="etprs_tel_num" value="">
							                    	                <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">내선</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width75" name="etprs_exten_num" value="">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">핸드폰번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="etprs_cell_num" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">E-Mail</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width200" name="etprs_email" value="">

                                                                   <hr class="hr_bd3">
							                    	               <label class="control-label fix_width120 tar">회계 담당자</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="accting_mng_name" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">부서</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="accting_depar" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">직책</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="accting_job" value="">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">전화번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="accting_tel_num" value="">
							                    	                <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">내선</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width75" name="accting_exten" value="">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">핸드폰번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="accting_cell_num" value="">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">E-Mail</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width200" name="accting_email" value="">
                                                                   </div>

                                                               <hr class="hr_bd3">
                                                               <div style="text-align:right;">
                                                                   <label class="btn btn_md fix_width140 fr" onclick="regiEtprsInfo();"><i class="fa fa-check-square"></i> 저장</label>
							                    	           </div>
                                                               </form>
                                                               </div>
                                                        </div>
													</div>

                       	      </div>  
                       	      <!-- Regi 팝업창 -->
	
HTML;

    return $html;
}

//매입품목 수정 테이블
function getPurEtprsEdit($param) {

    $html = <<<EDITHTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>매입업체 관리</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideManuRegiPopup();"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
							                    	           <div class="pop-content">

							                    	           <!-- TAB UI -->
							                    	           <ul class="nav nav-tabs">
							                    	               <li class="active">
							                    	                   <a href="#tab1" data-toggle="tab"> 매입업체 정보 </a> 
							                    	               </li>
                                                                   <li>
							                    	                   <a href="#tab2" data-toggle="tab"> 매입업체 사업자등록증 정보 </a> 
                                                                   </li>
                                                   	               <li>
							                    	                   <a href="#tab3" data-toggle="tab"> 매입업체 로그인 관리 </a> 
                                                                   </li>
							                    	               <li>
							                    	                   <a href="#tab4" data-toggle="tab">브랜드 관리 </a> 
							                    	               </li>
                                                                   $param[pur_prdt_html]                                               	                                         </ul>
							                    	           <!-- TAB UI -->
							                    	           
                                                               <div class="tab-content">
                                                               <div class="tab-pane active" id="tab1" style="height:368px;">  
                                                               <form name="edit_form" id="edit_form">
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">매입업체명</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="manu_name" maxlength="30" value="$param[manu_name]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">매입품</label><label class="fix_width20 fs14 tac">:</label>
                                                                    <!--select name="edit_pur_prdt" id="edit_pur_prdt" class="fix_width120" disabled="disabled"-->
                                                                    <select name="edit_pur_prdt" id="edit_pur_prdt" class="fix_width120">
							                    						<option value="종이">종이</option>
							                    						<option value="출력">출력</option>
							                    						<option value="인쇄">인쇄</option>
							                    						<option value="후공정">후공정</option>
							                    						<option value="카드명함">카드명함</option>
							                    						<option value="자석">자석</option>
							                    						<option value="메뉴판">메뉴판</option>
							                    						<option value="마스터">마스터</option>
							                    						<option value="그린백">그린백</option>
							                    						<option value="기타">기타</option>
				                                                    </select>  
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width140 tar">거래상태</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <label class="form-radio"><input type="radio" name="deal_type" value="Y" class="radio_box" $param[deal_y]> 거래중</label>
							                    	               <label class="fix_width15"></label>   
							                    	               <label class="form-radio"><input type="radio" name="deal_type" value="N" class="radio_box" $param[deal_n]> 거래중지</label>			                    	               
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">전화번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="tel_num" maxlength="30" value="$param[tel_num]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">팩스번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="fax" maxlength="30" value="$param[fax]">
							                    	               <label class="control-label fix_width105 tar">홈페이지</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width200" name="hp" maxlength="255" value="$param[hp]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">회사명</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="cpn_name" maxlength="30" value="$param[cpn_name]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">E-Mail</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width250" name="mail" maxlength="255" value="$param[mail]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">주소</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width40" maxlength="5" name="zipcode" id="zipcode" value="$param[zipcode]" readonly>
							                    	               <label class="btn btn_pu fix_width75 bgreen fs13" onclick="getPostcode('');">우편번호</label>
							                    	               <br />
							                    	               <label class="fix_width140"></label>
							                    	               <input type="text" class="input_co2 fix_width350" id="addr" maxlength="255" name="addr" value="$param[addr]" readonly>
							                    	               <input type="text" class="input_co2 fix_width350" id="addr_detail" maxlength="255" name="addr_detail" value="$param[addr_detail]">
                                                                   <hr class="hr_bd3">
							                    	               <label class="control-label fix_width120 tar">매입업체 담당자</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="etprs_mng_name" value="$param[mng_name]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">부서</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="etprs_depar" value="$param[mng_depar]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">직책</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="etprs_job" value="$param[mng_job]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">전화번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="etprs_tel_num" value="$param[mng_tel_num]">
							                    	                <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">내선</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width75" name="etprs_exten_num" value="$param[mng_exten_num]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">핸드폰번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="etprs_cell_num" value="$param[mng_cell_num]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">E-Mail</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width200" name="etprs_email" value="$param[mng_mail]">

                                                                   <hr class="hr_bd3">
							                    	               <label class="control-label fix_width120 tar">회계 담당자</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="accting_mng_name" value="$param[acct_name]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">부서</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="accting_depar" value="$param[acct_depar]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">직책</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width120" name="accting_job" value="$param[acct_job]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">전화번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width100" name="accting_tel_num" value="$param[acct_tel_num]">
							                    	                <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width30 tar">내선</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width75" name="accting_exten" value="$param[acct_exten_num]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">핸드폰번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="accting_cell_num" value="$param[acct_cell_num]">
							                    	               <label class="fix_width28"> </label>
							                    	               <label class="control-label fix_width100 tar">E-Mail</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width200" name="accting_email" value="$param[acct_mail]">
                                                                   </div>

                                                               <hr class="hr_bd3">
                                                               <div style="text-align:right;">
                                                                   <label class="btn btn_md fix_width140 fr" onclick="editEtprsInfo('$param[etprs_seqno]');"><i class="fa fa-check-square"></i> 저장</label>
							                    	           </div>
                                                               </form>
                                                               </div>
							                    	           
                                                               <div class="tab-pane" id="tab2" style="height:368px;">
                                                               <form name="edit_bls_form" id="edit_bls_form">
							                    	           <div class="form-group"> 
							                    	               <label class="control-label fix_width120 tar">회사명</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="bls_cpn_name" value="$param[bls_cpn_name]" maxlength="30">
							                    	               <label class="fix_width40"> </label>
							                    	               <label class="control-label fix_width100 tar">대표자명</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="repre_name" value="$param[repre_name]" maxlength="30">
							                    	               <label class="fix_width40"> </label>
                                                                   <br />
							                    	               <label class="control-label fix_width120 tar">사업자등록번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="crn" value="$param[crn]">
							                    	               <!--input type="text" class="input_co2 fix_width55" name="crn_first" maxlength="3" value="$param[crn_first]"> -
                                                                   <input type="text" class="input_co2 fix_width30" maxlength="2" name="crn_scd" value="$param[crn_scd]"> -
                                                                   <input type="text" class="input_co2 fix_width55" maxlength="5" name="crn_thd" value="$param[crn_thd]"-->
							                    	               <label class="fix_width20"> </label>
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">업태</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="tob" value="$param[tob]" maxlength="30">
							                    	               <label class="fix_width40"> </label>
							                    	               <label class="control-label fix_width100 tar">종목</label><label class="fix_width20 fs14 tac" maxlength="30">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" name="bc" value="$param[bc]">
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">주소</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" maxlength="5" class="input_co2 fix_width40" name="bls_zipcode" id="bls_zipcode" value="$param[bls_zipcode]" readonly>
							                    	               <label class="btn btn_pu fix_width75 bgreen fs13" onclick="getPostcode('bls_');">우편번호</label>
							                    	                <br />
							                    	               <label class="fix_width140"></label>
							                    	               <input type="text" class="input_co2 fix_width350" name="bls_addr" id="bls_addr" value="$param[bls_addr]" readonly>
							                    	               <input type="text" class="input_co2 fix_width350" name="bls_addr_detail" id="bls_addr_detail" maxlength="255" value="$param[bls_addr_detail]">
							                    	           </div>
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar"> 거래은행</label><label class="fix_width20 fs14 tac">:</label>
                                                                    <select id="bls_bank_name" name="bls_bank_name" class="fix_width120">
                                                                      $param[bank_html]
							                    	               </select>
							                    	               <label class="fix_width10"></label>
							                    	               <label class="control-label tar"> 계좌번호</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width150" name="ba_num" maxlength="30" value="$param[ba_num]">
                                                                   <br />
							                    	           	   <label class="control-label fix_width120 tar">참고사항</label><label class="fix_width20 fs14 tac">:</label>
							                    	           	    <textarea class="bs_noti" maxlength="255" name="add_items">$param[add_items]</textarea>
							                    	           </div>
                                                               <br />
                                                               <br />
                                                               <hr class="hr_bd3">
                                                               <div style="text-align:right;">
                                                                   <label class="btn btn_md fix_width140 fr" onclick="editEtprsBlsInfo('$param[etprs_seqno]');"><i class="fa fa-check-square"></i> 저장</label>
							                    	           </div>

							                    	           </div>


                                                               <div class="tab-pane" id="tab3" style="height:368px;">
                                                                <div class="form-group"> 
							                    	               <div class="table-body">
                                                                        <ul class="table_top">
                                    							      	               <li class="sel">
                                    					                      	            <label class="fs14 fwb">담당자</label>
                                    							      	               </li>
                                    							      	               <li class="sel tar">
                                    					                      	             <label class="btn btn_pu fix_width102 bgreen fs13" onclick="openMngAddPopup('mng', ''); return false;">추가</label>
                                    							      	               </li>

                                                                        </ul>
                                                                        <div class="table_basic" style="overflow:auto;">
                                    					                      		<table class="fix_width100f">
                                                                                <thead>
                                    					                      				<tr>
                                    					                      					<th class="bm2px">담당자</th>
                                    					                      					<th class="bm2px">아이디</th>
                                    					                      					<th class="bm2px">접속코드</th>
                                    					                      					<th class="bm2px">전화번호</th>
                                    					                      					<th class="bm2px">핸드폰</th>
                                    					                      					<th class="bm2px">E-mail</th>
                                    					                      					<th class="bm2px">관리</th>
                                    					                      				</tr>
                                    					                      			 </thead>
                                    					                      			 <tbody id="extnl_member">	
                                                                                         $param[extnl_member]
                                    					                      			 </tbody>
                                    					                      		</table>
                                                        
                                                                        </div>
                       	      </div>
							                    	           </div>
							                    	           </div>

                                                               <div class="tab-pane" id="tab4" style="height:368px;">
                                                               <div class="form-group"> 
							                    	               <div class="table-body">
                                                                        <ul class="table_top">
                                    							      	               <li class="sel">
                                    					                      	            <label class="fs14 fwb">브랜드</label>
                                    							      	               </li>
                                    							      	               <li class="sel tar">
                                    					                      	             <label class="btn btn_pu fix_width102 bgreen fs13" onclick="openMngAddPopup('brand', '');">추가</label>
                                    							      	               </li>

                                                                        </ul>
                                                                        <div class="table_basic" style="overflow:auto;">
                                    					                      		<table class="fix_width100f">
                                                                                <thead>
                                    					                      				<tr>
                                    					                      					<th class="bm2px" style="width:100px;">번호</th>
                                    					                      					<th class="bm2px">브랜드</th>
                                    					                      					<th class="bm2px" style="width:100px;">관리</th>
                                    					                      				</tr>
                                    					                      			 </thead>
                                    					                      			 <tbody id="brand_list">	
                                                                                         $param[brand_list]
                                    					                      			 </tbody>
                                    					                      		</table>
                                                                        </div>
                       	      </div>
							                    	           </div>

														 	</div>

                                                        <div class="tab-pane" id="tab5" style="height:368px;">
                                                   	    
                       	                                  <div class="table-body">
                                                   	      <div id="prdt_list" class="tab-content">
                                                          $param[prdt_list]
                                                          </div>
			  											  </div>
														</div>
                                                        </div>
													</div>

                       	      </div>  
                       	      <!-- View 팝업창 -->
				                   

EDITHTML;

    return $html;
}

//매입업체 로그인 관리 팝업
function extnlLoginPopup($param) {

    $html = <<<HTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>사업자정보 - 담당자</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button onclick="hidePopPopup(); return false;" type="button" class="btn btn-sm btn-danger fa fa-times"></button>
														 	      </dt>
														 	</dl>  

														 	<div class="pop-base">
														 	  	  <div class="pop-content">
														 	  	    <div class="form-group">
														 	  	        <label class="control-label fix_width75 tar">아이디</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input id="mem_id" name="mem_id" type="text" class="input_co2 fix_width100" maxlength="50" value="$param[id]" $param[readonly_id]>
                                                                        $param[check_id]

    														 	        <label class="control-label fix_width91 tar">접속코드</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input type="text" class="input_co2 fix_width100" maxlength="255" name="mem_passwd" id="mem_passwd" value="$param[access_code]">    														 	        
    														 	        <br />
    														 	        <label class="control-label fix_width75 tar">이름</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input type="text" class="input_co2 fix_width100" name="mem_name" id="mem_name" maxlength="30" value="$param[name]">
    														 	        <label class="control-label fix_width91 tar">직책</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input type="text" class="input_co2 fix_width75" name="mem_job" id="mem_job" maxlength="30" value="$param[job]">
    														 	        <label class="control-label fix_width100 tar">담당업무</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input type="text" class="input_co2 fix_width75" name="mem_task" id="mem_task" maxlength="20" value="$param[resp_task]">
    														 	        <br />
    														 	        
    														 	        <label class="control-label fix_width75 tar">E-mail</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input type="text" class="input_co2 fix_width100" name="mem_mail_top" id="mem_mail_top" maxlength="50" value="$param[mail_top]"> @ <input type="text" name="mem_mail_btm" id="mem_mail_btm" class="input_co2 fix_width100" maxlength="50" value="$param[mail_btm]">
    														 	        <select onchange="changeMail(this.value)" id="mail_type" class="fix_width110">
    														 	            <option value="">직접입력</option>
                                                                            $param[email_html]
    														 	        </select>
    														 	        <br />
    														 	        <label class="control-label fix_width75 tar">전화번호</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <select id="mem_tel_top" name="mem_tel_top" class="fix_width75">
    														 	            <option value="">선택</option>
                                                                            $param[tel_html]
    														 	        </select>-<input id="mem_tel_mid" name="mem_tel_mid" type="text" class="input_co2 fix_width75" maxlength="4" value="$param[tel_mid]">-<input id="mem_tel_btm" name="mem_tel_btm" type="text" class="input_co2 fix_width75" maxlength="4" value="$param[tel_btm]">
    														 	        <br />
    														 	         <label class="control-label fix_width75 tar">핸드폰</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <select name="mem_cel_top" id="mem_cel_top" name="mem_cel_top" class="fix_width75">
    														 	            <option value="">선택</option>
                                                                            $param[cel_html]
    														 	        </select> -  <input name="mem_cel_mid" id="mem_cel_mid" type="text" class="input_co2 fix_width75" maxlength="4" value="$param[cel_mid]">-<input name="mem_cel_btm" id="mem_cel_btm" type="text" class="input_co2 fix_width75" maxlength="4" value="$param[cel_btm]">
    														 	        <hr class="hr_bd2">
    														 	        <p class="tac mt15">		
                                                                          	<button onclick="saveExtnlMember('$param[chk_flag]');" type="button" class="btn btn-sm btn-success">저장</button>
                                                                            $param[del_btn]
                                                                            <label class="fix_width5"> </label>
                                                                            <button onclick="hidePopPopup(); return false;" type="button" class="btn btn-sm btn-primary">닫기</button>
                                                                        </p>
														 	  	    </div>		
														 	  	  </div>
                                   </div>
HTML;

    return $html;
}

//브랜드 추가
function extnlBrandPopup($param) {

    $html = <<<HTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>사업자정보 - 브랜드</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button onclick="hidePopPopup(); return false;" type="button" class="btn btn-sm btn-danger fa fa-times"></button>
														 	      </dt>
														 	</dl>  

														 	<div class="pop-base">
														 	  	  <div class="pop-content">
														 	  	    <div class="form-group">
    														 	        <label class="control-label fix_width75 tar">브랜드</label><label class="fix_width10 fs14 tac">:</label>
    														 	        <input type="text" class="input_co2 fix_width200" name="brand_name" id="brand_name" maxlength="30" value="$param[name]">
    														 	        <p class="tac mt15">
                                                                          	<button onclick="saveBrand('$param[seqno]');" type="button" class="btn btn-sm btn-success">저장</button>
 $param[del_btn]
                                                                            <label class="fix_width5"> </label>
                                                                            <button onclick="hidePopPopup(); return false;" type="button" class="btn btn-sm btn-primary">닫기</button>
                                                                        </p>
														 	  	    </div>		
														 	  	  </div>
														 	</div>
HTML;

    return $html;
}


//매입 품목 리스트 
function getPurPrdtList($param) {

    $html = <<<PAPERHTML
                                                                                <div class="tab-pane active" id="buy_tab1"> <!-- 종이 tab 01 -->
														 	    	      	          <table class="table fix_width100f">
					                      			                              <thead id="supply_thead">
                                                                                    $param[prdt_thead]
					                      			                              </thead>
					                      			                              <tbody id="supply_tbody">	
                                                                                     $param[prdt_tbody]
					                      			                              </tbody>
														 	    	      	      </table>
    <p class="p_num_p fs12">
Showing 1 to 5 of 30 entries
        <select name="list_set" class="fix_width55" onchange="showPageSetting(this.value);">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
        </select>
    </p>
                                                                                <div class="tac clear" id="supply_page">
                                                                                </div>


                                                   	    	    </div>
PAPERHTML;

    return $html;
}

//매입 품목 후공정 리스트
function getAfterList($param) {

    $html = <<<AFTERHTML
                                                                <div class="tab-pane" id="buy_tab4"> <!-- 후공정 tab 04 -->
                                                   	    	    	
                                                   	    	       	<table class="table fix_width100f">
                                                   	    	       	    <thead>
                                                   	    	       	        <tr>
                                                   	    	       	            <th class="bm2px"><a href="#" class="blue_text01">브랜드 <i class="fa fa-sort"></i></a></th>
                                                   	    	       	            <th class="bm2px">후공정대분류</th>
                                                   	    	       	            <th class="bm2px">계열</th> 
                                                   	    	       	            <th class="bm2px"><a href="#" class="blue_text01">후공정명  <i class="fa fa-sort"></i></a></th>
                                                   	    	       	            <th class="bm2px">용도</th>
                                                   	    	       	            <th class="bm2px">상세</th>					                      					
                                                   	    	       	            <th class="bm2px">가로사이즈</th>
                                                   	    	       	            <th class="bm2px">세로사이즈</th>
                                                   	    	       	            <th class="bm2px">기준단위</th>
                                                   	    	       	            <th class="bm2px">조회</th>
                                                   	    	       	   </tr>
                                                   	    	       	    </thead>
                                                   	    	       	    <tbody>	
                                                   	    	       	        <tr>
					                                        					<td>건식R1000</td>
					                                        					<td>코팅</td>
					                                        					<td>46</td>
					                                        					<td>46 건식코팅</td>
					                                        					<td>건식기</td>
					                                        					<td>옵셋기 후공정</td>					                                        					
					                                        					<td>1,000</td>
					                                        					<td>788</td>					                      					
					                                        					<td>판</td>
					                                        					<td><button type="button" class="bgreen btn_pu btn fix_height20 fix_width75" onclick="location='매입업체리스트-view-후공정-edit.html'">view</button></td>
					                                        				</tr>
					                                        				<tr class="cellbg">
					                                        					<td>아발론</td>
					                                        					<td>CTP</td>
					                                        					<td>46</td>
					                                        					<td>46 건식코팅</td>
					                                        					<td>건식기</td>
					                                        					<td>옵셋기 후공정</td>					                                        					
					                                        					<td>1,000</td>
					                                        					<td>788</td>					                      					
					                                        					<td>판</td>
					                                        					<td><button type="button" class="bgreen btn_pu btn fix_height20 fix_width75" onclick="location='매입업체리스트-view-후공정-edit.html'">view</button></td>
					                                        				</tr>
					                                        				<tr>
					                                        					<td>아발론</td>
					                                        					<td>CTP</td>
					                                        					<td>46</td>
					                                        					<td>46 건식코팅</td>
					                                        					<td>건식기</td>
					                                        					<td>옵셋기 후공정</td>					                                        					
					                                        					<td>1,000</td>
					                                        					<td>788</td>					                      					
					                                        					<td>판</td>
					                                        					<td><button type="button" class="bgreen btn_pu btn fix_height20 fix_width75" onclick="location='매입업체리스트-view-후공정-edit.html'">view</button></td>
					                                        				</tr>
					                                        				<tr class="cellbg">
					                                        					<td>아발론</td>
					                                        					<td>CTP</td>
					                                        					<td>46</td>
					                                        					<td>46 건식코팅</td>
					                                        					<td>건식기</td>
					                                        					<td>옵셋기 후공정</td>					                                        					
					                                        					<td>1,000</td>
					                                        					<td>788</td>					                      					
					                                        					<td>판</td>
					                                        					<td><button type="button" class="bgreen btn_pu btn fix_height20 fix_width75" onclick="location='매입업체리스트-view-후공정-edit.html'">view</button></td>
					                                        				</tr>
					                                        				<tr>
					                                        					<td>아발론</td>
					                                        					<td>CTP</td>
					                                        					<td>46</td>
					                                        					<td>46 건식코팅</td>
					                                        					<td>건식기</td>
					                                        					<td>옵셋기 후공정</td>					                                        					
					                                        					<td>1,000</td>
					                                        					<td>788</td>					                      					
					                                        					<td>판</td>
					                                        					<td><button type="button" class="bgreen btn_pu btn fix_height20 fix_width75" onclick="location='매입업체리스트-view-후공정-edit.html'">view</button></td>
					                                        				</tr>
					                                        				</tbody>
					                                        		</table>
                                                   	    	       	
                                                   	    	    </div>
                                                   	    	    



AFTERHTML;

    return $html;
}

//매입업체 - 종이
function getPaperView($param) {

    $html = <<<PAPERVIEWHTML
    
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>종이 조회</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button onclick="hidePopPopup();" type="button" class="btn btn-sm btn-danger fa fa-times"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">업체 정보 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">
														 	  	  	
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">제조사</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[manu]" disabled>
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[brand]" disabled>
							                    	           </div>    
							                    	           
														 	  	  </div>
														 	 </div>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">종이 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">							                    	            		 
							                    	            	   <label class="control-label fix_width150 tar">종이 대분류</label><label class="fix_width20 fs14 tac">:</label>
							                    	            	   <input type="text" class="input_co2 fix_width180" value="$param[sort]" disabled>							                    	            	   


				                                                        <label class="control-label fix_width120 tar">기존가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            	   <input type="text" class="input_co2 fix_width180" value="$param[basic_price]" disabled>							                    	            	   
							                    	            	   <br />							                    	            	   
				                                             <label class="control-label fix_width150 tar">종이명</label><label class="fix_width20 fs14 tac">:</label>
				                                             <input type="text" class="input_co2 fix_width180" value="$param[name]" disabled>

				                                                        <label class="control-label fix_width120 tar">요율</label><label class="fix_width20 fs14 tac">:</label>
							                    	            	   <input type="text" class="input_co2 fix_width180" value="$param[pur_rate]" disabled>	
							                    	            	   <br />
							                    	            	   <label class="control-label fix_width150 tar">구분</label><label class="fix_width20 fs14 tac">:</label>
				                                             <input type="text" class="input_co2 fix_width180" value="$param[dvs]" disabled>

				                                                        <label class="control-label fix_width120 tar">적용금액</label><label class="fix_width20 fs14 tac">:</label>
							                    	            	   <input type="text" class="input_co2 fix_width180" value="$param[pur_aplc_price]" disabled>	
							                    	            	   <br />
							                    	            	   <label class="control-label fix_width150 tar">색상</label><label class="fix_width20 fs14 tac">:</label>
				                                             <input type="text" class="input_co2 fix_width180" value="$param[color]" disabled>

				                                                        <label class="control-label fix_width120 tar">매입가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            	   <input type="text" class="input_co2 fix_width180" value="$param[pur_price]" disabled>	
							                    	            	   <br />
							                    	            	   <label class="control-label fix_width150 tar">평량</label><label class="fix_width20 fs14 tac">:</label>
				                                             <input type="text" class="input_co2 fix_width180" value="$param[basisweight]" disabled>
							                    	            	   <br />
							                    	                 <label class="control-label fix_width150 tar">계열</label><label class="fix_width20 fs14 tac"> : </label>
				                                             <input type="text" class="input_co2 fix_width180" value="$param[affil]" disabled>
				                                             <br />							                    	            	   				                                                  
								                                	     <label class="fix_width170"></label>
                                                       <label class="con_label">가로사이즈</label><label class="fix_width20 fs14 tac">:</label>
                                                       <input type="text" class="input_co2 fix_width100" value="$param[wid_size]" disabled> <span class="con_label">mm</span>
                                                       <label class="fix_width20"></label>   
                                                       <label class="con_label">세로사이즈</label><label class="fix_width20 fs14 tac">:</label>
                                                       <input type="text" class="input_co2 fix_width100" value="$param[vert_size]" disabled> <span class="con_label">mm</span>
								                                </div>
								                                
								                                <hr class="hr_bd2">
								                                
								                                <div class="form-group">
				                                                 <label class="control-label fix_width150 tar">기준단위</label><label class="fix_width20 fs14 tac"> : </label>
				                                                 <input type="text" class="input_co2 fix_width150" value="$param[crtr_unit]" disabled>
								                                </div>				                                

														 	  	  </div>
														 	 </div>
														 	 
														 	  <div class="pop-base tac">														 	  	   
														 	  	   <button onclick="hidePopPopup();" type="button" class="btn  btn-primary fwb nanum"> 닫기</button>
														 	  </div>
														 	  
														 	  <br />
														 

                       	      </div>  
PAPERVIEWHTML;

    return $html;
}

//매입업체 - 출력
function getOutputView($param) {

    $html = <<<OUTPUTVIEWHTML
    
	<dl>
														 	    <dt class="tit">
														 	    	  <h4>출력 조회</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" onclick="hidePopPopup();" class="btn btn-sm btn-danger fa fa-times"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">업체 정보 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">
														 	  	  	
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">제조사</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[manu]" disabled>
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[brand]" disabled>
							                    	           </div>    
							                    	           
														 	  	  </div>
														 	 </div>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">출력 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">
				                                                <label class="control-label fix_width150 tar">출력 대분류</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[top]" disabled>
                                                                <label class="control-label fix_width120 tar">기존가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[basic_price]" disabled>							    
				                                                <br />				                                                
				                                                <label class="control-label fix_width150 tar">출력명</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[name]" disabled>
                                                                <label class="control-label fix_width120 tar">요율</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_rate]" disabled>	
				                                              	<br />
				                                                <label class="control-label fix_width150 tar">출력판</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[board]" disabled>
                                                                <label class="control-label fix_width120 tar">적용금액</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_aplc_price]" disabled>	
				                                                <br />
				                                                <label class="control-label fix_width150 tar">계열</label><label class="fix_width20 fs14 tac"> : </label>
				                                             	<input type="text" class="input_co2 fix_width180" value="$param[affil]" disabled>
                                                                <label class="control-label fix_width120 tar">판매가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_price]" disabled>	
				                                             		<br />							                    	            	   				                                                  
								                                	     		<label class="fix_width170"></label>
                                                       		<label class="con_label">가로사이즈</label><label class="fix_width20 fs14 tac">:</label>
                                                       		<input type="text" class="input_co2 fix_width100" value="$param[wid_size]" disabled> <span class="con_label">mm</span>
                                                       		<label class="fix_width20"></label>   
                                                       		<label class="con_label">세로사이즈</label><label class="fix_width20 fs14 tac">:</label>
                                                       		<input type="text" class="input_co2 fix_width100" value="$param[vert_size]" disabled> <span class="con_label">mm</span>				                                              	
							                    	            </div>				                                
								                                
								                                <hr class="hr_bd2">
								                                
								                                <div class="form-group">
				                                                 <br />
				                                                 <label class="control-label fix_width150 tar">기준단위</label><label class="fix_width20 fs14 tac"> : </label>
				                                                 <input type="text" class="input_co2 fix_width150" value="$param[crtr_unit]" disabled>
								                                </div>                      								                                

														 	  	  </div>
														 	 </div>
														 	 
														 	  <div class="pop-base tac">
														 	  	   <button onclick="hidePopPopup();" type="button" class="btn  btn-primary fwb nanum"> 닫기</button>
														 	  </div>
														 	  
														 	  <br />
														 

OUTPUTVIEWHTML;

    return $html;

}

//매입업체 - 인쇄
function getPrintView($param) {

    $html = <<<PRINTVIEWHTML
														 	<dl>
														 	    <dt class="tit">
														 	    	  <h4>인쇄 조회</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button onclick="hidePopPopup();" type="button" class="btn btn-sm btn-danger fa fa-times"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">업체 정보 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">
														 	  	  	
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">제조사</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[manu]" disabled>
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[brand]" disabled>
							                    	           </div>    
							                    	           
														 	  	  </div>
														 	 </div>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">인쇄 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">
				                                                <label class="control-label fix_width150 tar">인쇄 대분류</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[top]" disabled>
                                                                <label class="control-label fix_width120 tar">기존가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[basic_price]" disabled>	
				                                                <br />				                                                
				                                                <label class="control-label fix_width150 tar">인쇄명</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[name]" disabled>				                                                
                                                                <label class="control-label fix_width120 tar">요율</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_rate]" disabled>
				                                                <br />
				                                                <label class="control-label fix_width150 tar">인쇄색도</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[tmpt]" disabled>
                                                                <label class="control-label fix_width120 tar">적용금액</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_aplc_price]" disabled>
				                                                <br />
							                    	            <label class="control-label fix_width150 tar">계열</label><label class="fix_width20 fs14 tac"> : </label>
				                                             	<input type="text" class="input_co2 fix_width180" value="$param[affil]" disabled>
                                                                <label class="control-label fix_width120 tar">판매가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_price]" disabled>
				                                             		<br />							                    	            	   				                                                  
								                                	     		<label class="fix_width170"></label>
                                                       		<label class="con_label">가로사이즈</label><label class="fix_width20 fs14 tac">:</label>
                                                       		<input type="text" class="input_co2 fix_width100" value="$param[wid_size]" disabled> <span class="con_label">mm</span>
                                                       		<label class="fix_width20"></label>   
                                                       		<label class="con_label">세로사이즈</label><label class="fix_width20 fs14 tac">:</label>
                                                       		<input type="text" class="input_co2 fix_width100" value="$param[vert_size]" disabled> <span class="con_label">mm</span>
								                                </div>
								                                
								                                <hr class="hr_bd2">
								                                
								                                <div class="form-group">
				                                                 <label class="control-label fix_width150 tar">기준단위</label><label class="fix_width20 fs14 tac"> : </label>
				                                                 <input type="text" class="input_co2 fix_width150" value="$param[crtr_unit]" disabled>
								                                </div>							                                

														 	  	  </div>
														 	 </div>
														 	 
														 	  <div class="pop-base tac">
														 	  	   <button type="button" onclick="hidePopPopup();" class="btn  btn-primary fwb nanum"> 닫기</button>
														 	  </div>
														 	  
														 	  <br />
	
    
PRINTVIEWHTML;

    return $html;

}

//매입업체 - 후공정
function getAfterView($param) {

    $html = <<<AFTERVIEWHTML

	<dl>
														 	    <dt class="tit">
														 	    	  <h4>후공정 조회</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button onclick="hidePopPopup();" type="button" class="btn btn-sm btn-danger fa fa-times"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">업체 정보 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">
														 	  	  	
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">제조사</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[manu]" disabled>
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[brand]" disabled>
							                    	           </div>    
							                    	           
														 	  	  </div>
														 	 </div>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">후공정 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">
				                                                <label class="control-label fix_width150 tar">후공정명</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[name]" disabled>
                                                                <label class="control-label fix_width120 tar">기존가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[basic_price]" disabled>	
				                                                <br />				                                                
				                                                <label class="control-label fix_width150 tar">Depth1</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[depth1]" disabled>
                                                                <label class="control-label fix_width120 tar">요율</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_rate]" disabled>
				                                                <br />
				                                                <label class="control-label fix_width150 tar">Depth2</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[depth2]" disabled>
                                                                <label class="control-label fix_width120 tar">적용금액</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_aplc_price]" disabled>
				                                                <br />
				                                                <label class="control-label fix_width150 tar">Depth3</label><label class="fix_width20 fs14 tac"> : </label>
				                                             	<input type="text" class="input_co2 fix_width180" value="$param[depth3]" disabled>
                                                                <label class="control-label fix_width120 tar">판매가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_price]" disabled>
								                                </div>				                                
								                                <hr class="hr_bd2">
								                                <div class="form-group">
				                                                 <label class="control-label fix_width150 tar">단위</label><label class="fix_width20 fs14 tac"> : </label>
				                                                 <input type="text" class="input_co2 fix_width180" value="$param[crtr_unit]" disabled>
                                                                </div>
														 	  	  </div>
														 	 </div>
														 	 
														 	  <div class="pop-base tac">
														 	  	   <button type="button" onclick="hidePopPopup();" class="btn  btn-primary fwb nanum"> 닫기</button>
														 	  </div>
														 	  
														 	  <br />
AFTERVIEWHTML;

    return $html;

}

//매입업체 - 옵션
function getOptView($param) {

    $html = <<<OPTVIEWHTML
    
	<dl>
														 	    <dt class="tit">
														 	    	  <h4>옵션 조회</h4>
														 	    </dt>
														 	    <dt class="cls">
														 	    	  <button type="button" onclick="hidePopPopup();" class="btn btn-sm btn-danger fa fa-times"></button>
														 	    </dt>
														 	</dl>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">업체 정보 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">
														 	  	  	
							                    	           <div class="form-group">
							                    	               <label class="control-label fix_width120 tar">제조사</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[manu]" disabled>
							                    	               <br />
							                    	               <label class="control-label fix_width120 tar">브랜드</label><label class="fix_width20 fs14 tac">:</label>
							                    	               <input type="text" class="input_co2 fix_width180" value="$param[brand]" disabled>
							                    	           </div>    
							                    	           
														 	  	  </div>
														 	 </div>
														 	 
														 	 <div class="pop-base">
			  											 	 	  <ul class="tab_box">
						                                  <li>
						       	                              <a class="box">옵션 사양 등록 </a> 
						                                  </li>
			  											 	 	  </ul>
														 	  	  <div class="pop-content">

							                    	            <div class="form-group">
				                                                <label class="control-label fix_width150 tar">옵션명</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[name]" disabled>
                                                                <label class="control-label fix_width120 tar">기존가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[basic_price]" disabled>	
				                                                <br />				                                                
				                                                <label class="control-label fix_width150 tar">Depth1</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[depth1]" disabled>
                                                                <label class="control-label fix_width120 tar">요율</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_rate]" disabled>
				                                                <br />
				                                                <label class="control-label fix_width150 tar">Depth2</label><label class="fix_width20 fs14 tac">:</label>
				                                                <input type="text" class="input_co2 fix_width180" value="$param[depth2]" disabled>
                                                                <label class="control-label fix_width120 tar">적용금액</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_aplc_price]" disabled>
				                                                <br />
				                                                <label class="control-label fix_width150 tar">Depth3</label><label class="fix_width20 fs14 tac"> : </label>
				                                             	<input type="text" class="input_co2 fix_width180" value="$param[depth3]" disabled>
                                                                <label class="control-label fix_width120 tar">판매가격</label><label class="fix_width20 fs14 tac">:</label>
							                    	            <input type="text" class="input_co2 fix_width180" value="$param[pur_price]" disabled>
                                                                </div>
                                                                <hr class="hr_bd2">
								                                <div class="form-group">
                                                                <label class="control-label fix_width150 tar">단위</label><label class="fix_width20 fs14 tac"> : </label>
				                                                 <input type="text" class="input_co2 fix_width150" value="$param[crtr_unit]" disabled>
								                                </div>	
														 	  	  </div>
														 	 </div>
														 	 
														 	  <div class="pop-base tac">
														 	  	   <button type="button" onclick="hidePopPopup();" class="btn  btn-primary fwb nanum"> 닫기</button>
														 	  </div>
														 	  
														 	  <br />
														 

OPTVIEWHTML;

    return $html;

}
?>
