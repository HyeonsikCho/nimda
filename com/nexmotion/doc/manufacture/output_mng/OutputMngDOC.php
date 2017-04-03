<?
//출력 상세보기
function getOutputDetailPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>출력공정 - 상세</h4>
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
        <div class="table_basic none-hover">
            <table class="table" style="width:100%;">
                 <tbody>
                     <tr>
                         <th>조판번호</th>
                         <td>$param[typset_num]</td>
                         <th>출력명</th>
                         <td>
                           $param[output_name]
                           <input type="hidden" id="output_name" value="$param[output_name]">
                         </td>
                     </tr>
                     <tr>
                         <th>운영체제</th>
                         <td>$param[oper_sys]</td>
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
                             후면 기본 $param[aftside_tmpt]도 별색 $param[aftside_spc_tmpt]도 $param[honggak]
                         </td>
                         <th>후공정</th>
                         <td>
                             $param[after_list]
                         </td>
                     </tr>
                     <tr>
                         <th>조판자</th>
                         <td>$param[orderer]</td>
                         <th>마감구분</th>
                         <td>$param[dlvrboard]</td>
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
                         <td colspan="3"><span style="color:red;">$param[specialty_items]</span></td>
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
                         </td>
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

         <!--div class="table-body3" style="padding-top:0;">
	            <ul class="table_top">
				 	 <li class="sel">
                          전체사양
				     </li>
				     <li class="sel tar" style="width:100%;">
				     </li>
	            </ul>
                <table class="table" style="width:100%;">
                    <tbody>
                        <tr>
                            <th>종이</th>
                            <td>$param[paper_name] $param[paper_dvs] $param[paper_color] $param[paper_basisweight]</td>
                            <th>발주처</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>인쇄</th>
                            <td>
                                전면 기본 $param[beforeside_tmpt]도 별색 $param[beforeside_spc_tmpt]도 / 후면 기본 $param[aftside_tmpt]도 별색 $param[aftside_spc_tmpt]도
                            </td>
                            <th>발주처</th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
         </div-->

         <!--div class="table-body" style="padding-top:0;">
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
         </div-->
	</div>
HTML;

    return $html;
}

//출력 이미지보기
function getOutputImgPopup($param) {

    $html = <<<HTML
	<dl>
		<dt class="tit">
		<h4>출력공정 - 조판이미지</h4>
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
?>
