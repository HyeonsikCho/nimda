<?
//거래명세서 등록팝업
function makeDealspecPop($param) {

    $html = <<<HTML
              <dl>
                <dt class="tit">
                  <h4>거래명세서 등록</h4>
                </dt>
                <dt class="cls">
                  <button type="button" onclick="hideDealspecReg();" class="btn btn-sm btn-danger fa fa-times"></button>
                </dt>
              </dl>
              <div class="pop-base">
                <div class="pop-content">
                  <ul class="ofh">
                    <li class="fix_width310 fl">
                      <div class="form-group">
                      <label class="control-label fix_width85 tar">공급처</label><label class="fix_width20 fs14 tac">:</label>
                        <!--<select class="fix_width100" name="pur_prdt_pop" id="pur_prdt_pop" onchange="loadExtnlEtprs(this.value, 'pur_manu_pop')">-->
                        <select class="fix_width150" id="pur_manu_pop" name="pur_manu_pop">
                            $param[opt]
                        </select> 
                        <br />	                               
                      <label class="control-label fix_width85 tar">품목</label><label class="fix_width20 fs14 tac">:</label>
                        <select class="fix_width150" name="pur_prdt_pop" id="pur_prdt_pop">
                          <option value="잉크-C">잉크-C</option>
                          <option value="잉크-M">잉크-M</option>
                          <option value="잉크-Y">잉크-Y</option>
                          <option value="잉크-K">잉크-K</option>
                          <option value="출력판">출력판</option>
                        </select> 
                        <br />
	                                
                      <label class="control-label fix_width85 tar">규격</label><label class="fix_width20 fs14 tac">:</label>
                        <input id="stan_pop" name="stan_pop" type="text" class="search_btn fix_width75" placeholder="" $param[stan]></input>
                        <br />
	                                
                      <label class="control-label fix_width85 tar">수량</label><label class="fix_width20 fs14 tac">:</label>
                        <input id="amt_pop" name="amt_pop" type="text" class="search_btn fix_width75" onkeyup="onlyNumber2(event)" $param[amt]>
                        <select id="amt_unit_pop" name="amt_unit_pop" class="fix_width85">
                          <option>장</option>
                          <option>하드코딩</option>
                        </select> 
                        <br />
	                                
                      <label class="control-label fix_width85 tar">단가</label><label class="fix_width20 fs14 tac">:</label>
                        <input id="unitprice_pop" name="unitprice_pop" type="text" class="search_btn fix_width180" onkeyup="onlyNumber2(event)" placeholder="" $param[unitprice]>
                        <br />                               
                      </div>
                    </li>
                            
                    <li class="fix_width400 fl">
                      <div class="form-group">
                      <label class="control-label fix_width55 tar">메모</label><label class="fix_width20 fs14 tac">:</label>
                      <textarea id="memo_pop" name="memo_pop" class="bs_noti2">$param[memo]</textarea>
                      <br />
                      </div>
                    </li>
                  </ul>
                        
                  <div class="form-group">
                    <label class="control-label fix_width85 tar">금액</label><label class="fix_width20 fs14 tac">:</label>
                    <input id="price_pop" name="price_pop" type="text" class="search_btn fix_width180" onkeyup="onlyNumber2(event)" $param[price]>
                    <label class="fix_width15"></label>
                      ( 
                    <label class="fix_width15"></label>
                      VAT
                    <label class="fix_width20 fs14 tac">:</label>
                    <input type="radio" name="vat_yn" value="Y" $param[vat_y]>포함</label>
                    <label class="fix_width15"></label>   
                    <label class="form-radio">
                    <input type="radio" name="vat_yn" value="N" $param[vat_n]>미포함</label>
                    <label class="fix_width15"></label>
                      )
                    <br />
                   </div>
                        
                   <div class="form-group">			
                     <p class="tac mt15">
                       <label class="fix_width180"> </label>
                       $param[save]
                       $param[edit]
                       $param[del]
                       <label class="fix_width200"> </label>
                       <button type="button" class="btn btn-sm btn-primary" onclick="hideDealspecReg();">닫기</button>
                     </p> 
                   </div>	                                                                      
                   <input type="hidden" name="seq" id="seq" $param[dealspec_seqno]>
                 </div> <!-- pop-content -->								
               </div> <!-- pop-base -->      
HTML;
 
    return $html;
}

?>
