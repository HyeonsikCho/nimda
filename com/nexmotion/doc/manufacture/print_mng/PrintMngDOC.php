<?
//인쇄작업일지
function getPrintDatilPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>인쇄공정 - 상세</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base">
		<div class="pop-content ofa" style="padding:0;">
        <div class="table-body3" style="padding-bottom:0;">
        <ul class="table_top">
			<li style="float:left;list-style: none;padding-bottom: 10px;">
			</li>
            <li style="float:right;list-style: none;padding-bottom: 10px;">
            $param[btn_html]
            </li>
        </ul>
        <div class="table_basic">
            <table class="table" style="width:100%;">
                 <tbody>
                     <tr>
                         <th>조판번호</th>
                         <td>$param[typset_num]</td>
                         <th>인쇄명</th>
                         <td>
                           $param[print_name]
                           <input type="hidden" id="print_name" value="$param[print_name]">
                         </td>
                     </tr>
                     <tr>
                         <th>운영체제</th>
                         <td>$param[oper_sys]</td>
                         <th>사이즈</th>
                         <td>
                           <span id="size_info">$param[size_info]</span>
                           <input type="hidden" id="size_val" value="$param[size]">
                         </td>
                         <!--td>
                             <select id="affil" onchange="getSize();" $param[disabled]>
                                 $param[affil]
                             </select>
                             <select id="subpaper" onchange="getSize();" $param[disabled]>
                                 $param[subpaper]
                             </select>
                             <span id="size">($param[size])</span>
                             <input type="hidden" id="size_val" value="$param[size]">
                         </td-->
                     </tr>
                     <tr>
                         <th>조판자</th>
                         <td>$param[orderer]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
                     </tr>
                     <tr>
                         <th>종이</th>
                         <td>
                             $param[paper_name] $param[paper_dvs] $param[paper_color] $param[paper_basisweight]
                         </td>
                         <th>인쇄 수량</th>
                         <td>$param[amt]$param[amt_unit]
                             <input type="hidden" id="amt" value="$param[amt]">
                             <input type="hidden" id="amt_unit" value="$param[amt_unit]">
                             <input type="hidden" id="paper_stor_yn" value="$param[paper_stor_yn]">
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
                         <th>메모(특이사항)</th>
                         <td><span style="color:red;">$param[specialty_items]</span></td>
                         <th>잉크사용량</th>
                         <td>
                             C : <input type="text" class="input_co2 fix_width20" id="ink_C" value="$param[ink_C]" $param[disabled]>
				           	 M : <input type="text" class="input_co2 fix_width20" id="ink_M" value="$param[ink_M]" $param[disabled]>
				             Y : <input type="text" class="input_co2 fix_width20" id="ink_Y" value="$param[ink_Y]" $param[disabled]>
				             K : <input type="text" class="input_co2 fix_width20" id="ink_K" value="$param[ink_K]" $param[disabled]>
                         </td>
                     </tr>
                     <tr>
                         <th>작업자</th>
                         <td colspan="3">
                            <select class="fix_width200" onchange="changeManu(this.value);" id="extnl_etprs_seqno" $param[disabled]>
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
                     </tr>
                     <tr>
                         <th>작업금액</th>
                         <td>
                             <span id="work_price_val">$param[price]원</span>
                             <input type="hidden" value="$param[price]" id="work_price">
                         </td>
                         <th>조정금액</th>
                         <td><input type="text" class="input_co2" style="width:85%;" id="adjust_price" value="$param[adjust_price]" $param[disabled] onkeyup="getNumberFormat(this.value, 'adjust_price');">원
</td>
                     </tr>
                     <tr>
                         <th>작업시각</th>
                         <td colspan="3">$param[work_start_hour] $param[work_end_hour]</td>
                     </tr>

                     <!--tr>
                         <td colspan="4" style="text-align: center;">
                             $param[pic]
                         </td>
                     </tr-->
                 </tbody>
            </table>
		</div>
		</div> <!-- pop-content -->

         <!--div class="table-body">
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
         </div-->
	</div>
	<!-- pop-base -->
HTML;

    return $html;
}

//인쇄 이미지보기
function getPrintImgPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>인쇄공정 - 조판이미지</h4>
		</dt>
		<dt class="cls">
			<button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideRegiPopup();"></button>
		</dt>
	</dl>

	<div class="pop-base">
		<div class="pop-content ofa" style="padding:0;">
        <div class="table_basic none-hover">
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
	</div>
HTML;

    return $html;
}

//인쇄 바코드  
function printBarcode($param) {

    $html = <<<HTML
                     <tr>
                         $param[pic]
                     </tr>
                     <tr>
                         <th>인쇄명</th>
                         <td width="713">$param[print_name]($param[typset_num])</td>
                         <th>운영체제</th>
                         <td width="713">$param[oper_sys]</td>
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
                         <td colspan="3">$param[specialty_items]</td>
                     </tr>
HTML;

    return $html;
}
?>
