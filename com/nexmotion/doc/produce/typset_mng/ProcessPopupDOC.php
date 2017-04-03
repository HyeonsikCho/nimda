<?
//출력작업일지
function outputProcessAddPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>출력공정 - 작업일지</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base">
		<div class="pop-content ofa">
        <div class="table-body3">
        <ul class="table_top">
			<li style="float:left;list-style: none;padding-bottom: 10px;">
			</li>
            <li style="float:right;list-style: none;padding-bottom: 10px;">
                $param[btn_html]
                <label class="btn btn_md fix_width180" onclick="hideRegiPopup();">닫기</label>
            </li>
        </ul>
        <div class="table_basic none-hover">
            <table class="table" style="width:100%;">
                 <tbody>
                     <tr>
                         <th>출력명</th>
                         <td>$param[output_name] ($param[typset_num])
                             <input type="hidden" id="output_name" value="$param[output_name]">
                         </td>
                         <th>운영체제</th>
                         <td>$param[oper_sys]</td>
                     </tr>
                     <tr>
                         <th>발주자</th>
                         <td>$param[orderer]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th>종이</th>
                         <td>
                             $param[paper_name] $param[paper_dvs] $param[paper_color] $param[paper_basisweight]
                         </td>
                         <th>사이즈</th>
                         <td>
                             $param[affil] $param[subpaper] ($param[size])
                             <input type="hidden" id="size" value="$param[size]">
                         </td>
                     </tr>
                     <tr>
                         <th>인쇄 도수</th>
                         <td>
                             전면 기본 $param[beforeside_tmpt]도 별색 $param[beforeside_spc_tmpt]도<br />
                             후면 기본 $param[aftside_tmpt]도 별색 $param[aftside_spc_tmpt]도
                         </td>
                         <th>후공정</th>
                         <td>
                             $param[after_list]
                         </td>
                     </tr>
                     <tr>
                         <th>판구분</th>
                         <td>
                             <select class="fix_width150" id="board_dvs" onchange="getWorkPrice();">
                                 $param[board_dvs]
                             </select>
                         </td>
                         <th>판 수량</th>
                         <td>
                             <input type="text" class="input_co2" style="width:85%;" id="amt" value="$param[amt]" onkeyup="getWorkPrice();" $param[disabled]>판
                         </td>
                     </tr>
                     <tr>
                         <th>메모(특이사항)</th>
                         <td colspan="3">$param[memo]</td>
                     </tr>
                     <tr>
                         <th>작업자</th>
                         <td colspan="3">
                            <select class="fix_width200" onchange="changeManu('output', this.value);" id="extnl_etprs_seqno" $param[disabled]>
                                $param[manu_html]
				        	</select>
				        	<select class="fix_width200" onchange="getWorkPrice();" id="extnl_brand_seqno" $param[disabled]>
                                $param[brand_html]
				        	</select>
                            $param[worker]
                         </td>
                     </tr>
                     <tr>
                         <th>작업자 메모</th>
                         <td colspan="3">
                             $param[memo_select_html]
                             <textarea class="bs_noti2" style="width:95%;margin-left:1px;" id="worker_memo" disabled="disabled">$param[worker_memo]</textarea>
                         </td>
                     </tr>
                     <tr>
                         <th>작업시각</th>
                         <td colspan="3">$param[work_start_hour] $param[work_end_hour]</td>
                     </tr>
                     <tr>
                         <th>작업금액</th>
                         <td>
                             <input type="text" class="input_co2" style="width:85%;" id="work_price" value="$param[price]" disabled="disabled">원
                         </td>
                         <th>조정금액</th>
                         <td><input type="text" class="input_co2" style="width:85%;" id="adjust_price" value="$param[adjust_price]" $param[disabled] onkeyup="getNumberFormat(this.value, 'adjust_price');">원
</td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                 </tbody>
            </table>
         </div>
	     </div> <!-- pop-content -->

         <div class="table-body">
	            <ul class="table_top">
				 	  <li class="sel">
				     </li>
				     <li class="sel tar" style="width:100%;">
                        $param[finish_modi]
				     </li>
	            </ul>
                <table class="table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>작업시간</th>
                            <th>작업메모</th>
                            <th>유효여부</th>
                            <th>작업자</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        $param[work_list]
                    </tbody>
                </table>
         </div>
	</div>
HTML;

    return $html;
}

//출력 바코드  
function outputBarcode($param) {

    $html = <<<HTML
                     <tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                     <tr>
                         <th>출력명</th>
                         <td>$param[output_name]($param[typset_num])</td>
                         <th>운영체제</th>
                         <td>$param[oper_sys]</td>
                     </tr>
                     <tr>
                         <th>발주자</th>
                         <td>$param[orderer]</td>
                         <th>작업자</th>
                         <td>$param[worker]</td>
                     </tr>
                     <tr>
                         <th>종이</th>
                         <td>$param[paper_info]</td>
                         <th>사이즈</th>
                         <td>$param[affil] $param[subpaper] ($param[size])</td>
                     </tr>
                     <tr>
                         <th>인쇄 도수</th>
                         <td>
                             전면 : 기본 $param[beforeside_tmpt] 별색 $param[beforeside_spc_tmpt] <br />
                             후면 : 기본 $param[aftside_tmpt] 별색 $param[aftside_spc_tmpt]
                         </td>
                         <th>수량</th>
                         <td>$param[amt]$param[amt_unit]</td>
                     </tr>
                     <tr>
                         <th>작업시각</th>
                         <td>$param[work_start_hour]$param[work_end_hour]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th>메모(특이사항)</th>
                         <td>$param[memo]</td>
                         <th>발주방법</th>
                         <td>$param[typ] $param[typ_detail]</td>
                     </tr>
HTML;

    return $html;
}

//인쇄작업일지
function printProcessAddPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>인쇄공정 - 작업일지</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base">
		<div class="pop-content ofa">
        <div class="table-body3">
        <ul class="table_top">
			<li style="float:left;list-style: none;padding-bottom: 10px;">
			</li>
            <li style="float:right;list-style: none;padding-bottom: 10px;">
                $param[btn_html]
                <label class="btn btn_md fix_width180" onclick="hideRegiPopup();">닫기</label>
            </li>
        </ul>
        <div class="table_basic">
            <table class="table" style="width:100%;">
                 <tbody>
                     <tr>
                         <th>인쇄명</th>
                         <td>$param[print_name] ($param[typset_num])
                             <input type="hidden" id="print_name" value="$param[print_name]">
                         </td>
                         <th>운영체제</th>
                         <td>$param[oper_sys]</td>
                     </tr>
                     <tr>
                         <th>발주자</th>
                         <td>$param[orderer]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                      </tr>
                     <tr>
                         <th>종이</th>
                         <td>
                             $param[paper_name] $param[paper_dvs] $param[paper_color] $param[paper_basisweight]
                         </td>
                         <th>사이즈</th>
                         <td>
                             <select id="affil" onchange="getSize();" $param[disabled]>
                                 $param[affil]
                             </select>
                             <select id="subpaper" onchange="getSize();" $param[disabled]>
                                 $param[subpaper]
                             </select>
                             <span id="size">($param[size])</span>
                             <input type="hidden" id="size_val" value="$param[size]">
                         </td>
                     </tr>
                     <tr>
                         <th>인쇄 도수</th>
                         <td>
                             전면 기본 $param[beforeside_tmpt]도 별색 $param[beforeside_spc_tmpt]도<br />
                             후면 기본 $param[aftside_tmpt]도 별색 $param[aftside_spc_tmpt]도
                         </td>
                         <th>후공정</th>
                         <td>
                             $param[after_list]
                         </td>
                     </tr>
                     <tr>
                         <th>잉크사용량</th>
                         <td>
                             C : <input type="text" class="input_co2 fix_width20" id="ink_C" value="$param[ink_C]" $param[disabled]>
				           	 M : <input type="text" class="input_co2 fix_width20" id="ink_M" value="$param[ink_M]" $param[disabled]>
				             Y : <input type="text" class="input_co2 fix_width20" id="ink_Y" value="$param[ink_Y]" $param[disabled]>
				             K : <input type="text" class="input_co2 fix_width20" id="ink_K" value="$param[ink_K]" $param[disabled]>
                         </td>
                         <th>인쇄 수량</th>
                         <td>$param[amt]$param[amt_unit]
                             <input type="hidden" id="amt" value="$param[amt]">
                             <input type="hidden" id="amt_unit" value="$param[amt_unit]">
                         </td>
                     </tr>
                     <tr>
                         <th>메모(특이사항)</th>
                         <td colspan="3">$param[memo]</td>
                     </tr>
                     <tr>
                         <th>작업자</th>
                         <td colspan="3">
                            <select class="fix_width200" onchange="changeManu('output', this.value);" id="extnl_etprs_seqno" $param[disabled]>
                                $param[manu_html]
				        	</select>
				        	<select class="fix_width200" onchange="getWorkPrice();" id="extnl_brand_seqno" $param[disabled]>
                                $param[brand_html]
				        	</select>
                            $param[worker]
                         </td>
                     </tr>
                     <tr>
                         <th>작업자 메모</th>
                         <td colspan="3">
                             $param[memo_select_html]
                             <textarea class="bs_noti2" style="width:95%;margin-left:1px;" id="worker_memo" disabled="disabled">$param[worker_memo]</textarea>
                     </tr>
                     <tr>
                         <th>작업시각</th>
                         <td colspan="3">$param[work_start_hour] $param[work_end_hour]</td>
                     </tr>
                     <tr>
                         <th>작업금액</th>
                         <td>
                             <input type="text" class="input_co2" style="width:85%;" id="work_price" value="$param[price]" disabled="disabled">원
                         </td>
                         <th>조정금액</th>
                         <td><input type="text" class="input_co2" style="width:85%;" id="adjust_price" value="$param[adjust_price]" $param[disabled] onkeyup="getNumberFormat(this.value, 'adjust_price');">원
</td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                 </tbody>
            </table>
		</div>
		</div> <!-- pop-content -->

         <div class="table-body">
                <table class="table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>작업시간</th>
                            <th>작업메모</th>
                            <th>유효여부</th>
                            <th>작업자</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        $param[work_list]
                    </tbody>
                </table>
         </div>
	</div>
	<!-- pop-base -->
HTML;

    return $html;
}

//인쇄 바코드  
function printBarcode($param) {

    $html = <<<HTML
                     <tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                     <tr>
                         <th>인쇄명</th>
                         <td>$param[print_name]($param[typset_num])</td>
                         <th>운영체제</th>
                         <td>$param[oper_sys]</td>
                     </tr>
                     <tr>
                         <th>발주자</th>
                         <td>$param[orderer]</td>
                         <th>작업자</th>
                         <td>$param[worker]</td>
                     </tr>
                     <tr>
                         <th>종이</th>
                         <td>$param[paper_info]</td>
                         <th>사이즈</th>
                         <td>$param[affil] $param[subpaper] ($param[size])</td>
                     </tr>
                     <tr>
                         <th>인쇄 도수</th>
                         <td>
                             전면 : 기본 $param[beforeside_tmpt] 별색 $param[beforeside_spc_tmpt] <br />
                             후면 : 기본 $param[aftside_tmpt] 별색 $param[aftside_spc_tmpt]
                         </td>
                         <th>수량</th>
                         <td>$param[amt]$param[amt_unit]</td>
                     </tr>
                     <tr>
                         <th>작업시각</th>
                         <td>$param[work_start_hour]$param[work_end_hour]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">메모(특이사항)</th>
                         <td>$param[memo]</td>
                         <th>발주방법</th>
                         <td>$param[typ] $param[typ_detail]</td>
                     </tr>
HTML;

    return $html;
}

//조판 후공정작업일지
function BasicAfterProcessAddPopup($param) {

    $html = <<<HTML
     <dl>
          <dt class="tit">
               <h4>후공정 - 작업일지</h4>
          </dt>
          <dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
          </dt>
     </dl>  
														 	
     <div class="pop-base">        
      <div class="pop-content ofa">
      <div class="table-body3">
        <ul class="table_top">
			<li style="float:left;list-style: none;padding-bottom: 10px;">
			</li>
            <li style="float:right;list-style: none;padding-bottom: 10px;">
                $param[btn_html]
                <label class="btn btn_md fix_width180" onclick="hideRegiPopup();">닫기</label>
            </li>
        </ul>
        <div class="table_basic">
            <table class="table" style="width:100%;">
                 <tbody>
                     <tr>
                         <th style="background: #eee;">후공정명</th>
                         <td>$param[after_name]
                             <input type="hidden" id="after_name" value="$param[after_name]">
                         </td>
                         <th style="background: #eee;">수량</th>
                         <td>
                             $param[amt]$param[amt_unit]
                             <input type="hidden" id="amt" value="$param[amt]">
                             <input type="hidden" id="amt_unit" value="$param[amt_unit]">
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">발주자</th>
                         <td>$param[orderer]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">상품</th>
                         <td>
                             $param[cate_name]
                         </td>
                         <th style="background: #eee;">작업상세</th>
                         <td>
                             $param[after_name] $param[depth1] $param[depth2] $param[depth3]
                             <input type="hidden" id="depth1" value="$param[depth1]">
                             <input type="hidden" id="depth2" value="$param[depth2]">
                             <input type="hidden" id="depth3" value="$param[depth3]">
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">메모(특이사항)</th>
                         <td colspan="3">$param[memo]</td>
                     </tr>
                     <tr>
                         <th>작업자</th>
                         <td colspan="3">
                            <select class="fix_width200" onchange="changeManu('after', this.value);" id="extnl_etprs_seqno" $param[disabled]>
                                $param[manu_html]
				        	</select>
				        	<select class="fix_width200" id="extnl_brand_seqno" $param[disabled]>
                                $param[brand_html]
				        	</select>
                            $param[worker]
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">작업자 메모</th>
                         <td colspan="3">
                             $param[memo_select_html]
                             <textarea class="bs_noti2" style="width:95%;" id="worker_memo" disabled="disabled">$param[worker_memo]</textarea>
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">작업시각</th>
                         <td colspan="3">$param[work_start_hour] $param[work_end_hour]</td>
                     </tr>
                     <tr>
                         <th>작업금액</th>
                         <td>
                             <input type="text" class="input_co2" style="width:85%;" id="work_price" value="$param[price]" disabled="disabled">원
                         </td>
                         <th>조정금액</th>
                         <td><input type="text" class="input_co2" style="width:85%;" id="adjust_price" value="$param[adjust_price]" $param[disabled] onkeyup="getNumberFormat(this.value, 'adjust_price');">원
</td>
                     </tr>
                     <tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                 </tbody>
            </table>
          </div>
          </div> <!-- pop-content -->

         <div class="table-body">
                <table class="table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>작업시간</th>
                            <th>작업메모</th>
                            <th>유효여부</th>
                            <th>작업자</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        $param[work_list]
                    </tbody>
                </table>
         </div>
     </div>
     <!-- pop-base -->
HTML;

    return $html;
}

//후공정작업일지
function afterProcessAddPopup($param) {

    $html = <<<HTML
     <dl>
          <dt class="tit">
               <h4>후공정 - 작업일지</h4>
          </dt>
          <dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
          </dt>
     </dl>  
														 	
     <div class="pop-base">        
      <div class="pop-content ofa">
      <div class="table-body3">
        <ul class="table_top">
			<li style="float:left;list-style: none;padding-bottom: 10px;">
			</li>
            <li style="float:right;list-style: none;padding-bottom: 10px;">
                $param[btn_html]
                <label class="btn btn_md fix_width180" onclick="hideRegiPopup();">닫기</label>
            </li>
        </ul>
        <div class="table_basic">
            <table class="table" style="width:100%;">
                 <tbody>
                     <tr>
                         <th style="background: #eee;">후공정명</th>
                         <td>$param[after_name]
                             <input type="hidden" id="after_name" value="$param[after_name]">
                         </td>
                         <th style="background: #eee;">수량</th>
                         <td>
                             $param[amt]$param[amt_unit]
                             <input type="hidden" id="amt" value="$param[amt]">
                             <input type="hidden" id="amt_unit" value="$param[amt_unit]">
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">발주자</th>
                         <td>$param[orderer]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">상품</th>
                         <td>
                             $param[cate_name]
                         </td>
                         <th style="background: #eee;">작업상세</th>
                         <td>
                             $param[after_name] $param[depth1] $param[depth2] $param[depth3] $param[detail]
                             <input type="hidden" id="depth1" value="$param[depth1]">
                             <input type="hidden" id="depth2" value="$param[depth2]">
                             <input type="hidden" id="depth3" value="$param[depth3]">
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">메모(특이사항)</th>
                         <td colspan="3">$param[memo]</td>
                     </tr>
                     <tr>
                         <th>작업자</th>
                         <td colspan="3">
                            <select class="fix_width200" onchange="changeManu('after', this.value);" id="extnl_etprs_seqno" $param[disabled]>
                                $param[manu_html]
				        	</select>
				        	<select class="fix_width200" onchange="getWorkPrice();" id="extnl_brand_seqno" $param[disabled]>
                                $param[brand_html]
				        	</select>
                            $param[worker]
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">작업자 메모</th>
                         <td colspan="3">
                             $param[memo_select_html]
                             <textarea class="bs_noti2" style="width:95%;" id="worker_memo" disabled="disabled">$param[worker_memo]</textarea>
                         </td>
                     </tr>
                     <tr>
                         <th style="background: #eee;">작업시각</th>
                         <td colspan="3">$param[work_start_hour] $param[work_end_hour]</td>
                     </tr>
                     <tr>
                         <th>작업금액</th>
                         <td>
                             <input type="text" class="input_co2" style="width:85%;" id="work_price" value="$param[price]" disabled="disabled">원
                         </td>
                         <th>조정금액</th>
                         <td><input type="text" class="input_co2" style="width:85%;" id="adjust_price" value="$param[adjust_price]" $param[disabled] onkeyup="getNumberFormat(this.value, 'adjust_price');">원
</td>
                     </tr>
                     <tr>
                         <th>원본파일</th>
                         <td colspan="3" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                     <tr>
                         <th>후공정작업파일</th>
                         <td colspan="3" style="text-align: center;">
                             $param[after_pic]
                         </td>
                     </tr>
                 </tbody>
            </table>
          </div>
          </div> <!-- pop-content -->

         <div class="table-body">
                <table class="table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>작업시간</th>
                            <th>작업메모</th>
                            <th>유효여부</th>
                            <th>작업자</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        $param[work_list]
                    </tbody>
                </table>
         </div>
     </div>
     <!-- pop-base -->
HTML;

    return $html;
}

//조판후공정 바코드
function basicAfterBarcode($param) {

    $html = <<<HTML
                     <tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr>
                     <tr>
                         <th>후공정명</th>
                         <td>$param[after_name]</td>
                         <th>상품</th>
                         <td>
                             $param[cate]
                         </td>
                     </tr>
                     <tr>
                         <th>발주자</th>
                         <td>$param[orderer]</td>
                         <th>작업자</th>
                         <td>$param[worker]</td>
                     </tr>
                     <tr>
                         <th>작업상세</th>
                         <td>
                             $param[after_name] $param[depth1] $param[depth2] $param[depth3]
                         </td>
                         <th>수량</th>
                         <td>$param[amt]$param[amt_unit]</td>
                     </tr>
                     <tr>
                         <th>작업시각</th>
                         <td>$param[work_start_hour]$param[work_end_hour]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th>메모(특이사항)</th>
                         <td>$param[memo]</td>
                         <th>발주방법</th>
                         <td>$param[typ] $param[typ_detail]</td>
                     </tr>

HTML;

    return $html;
}
?>
