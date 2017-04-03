<?
//재고조정 등록팝업
function makeStockMngPop($param) {

    $html = <<<HTML
            <dl>
              <dt class="tit">
                <h4>종이재고 조정</h4>
              </dt>
              <dt class="cls">
                <button type="button" onclick="hideDealspecReg();" class="btn btn-sm btn-danger fa fa-times"></button>
              </dt>
            </dl>
            <div class="pop-base">
              <div class="pop-content">
                <div class="form-group">
                  <label class="control-label fix_width55 tar">등록일자</label><label class="fix_width20 fs14 tac">:</label>
                  <label class="control-label fix_width55">$param[today]</label>
                  <br />
                  <label class="control-label fix_width55 tar">종이</label><label class="fix_width20 fs14 tac">:</label>
                  <label class="control-label fix_width120">$param[paper_name] $param[paper_color] $param[paper_basisweight]</label>
                  <br />
                  <label class="control-label fix_width55 tar">조정</label><label class="fix_width20 fs14 tac">:</label>
                  <label class="form-radio form-normal">
                  <input type="radio" name="adjust" id="adjust" value="stor" checked>입고량</label>
                  <label class="fix_width15"></label>
                  <label class="form-radio">
                  <input type="radio" name="adjust" id="adjust" value="use" >사용량</label>
                  <label class="fix_width30"></label>
                  <input type="text" name="amt" id="amt" class="search_btn fix_width100" placeholder="1" onkeyup="onlyNumber2(event)" value="">
                  <label class="control-label">R</label>
                  <br />
                  <label class="control-label fix_width55 tar">조정사유</label><label class="fix_width20 fs14 tac">:</label>
                  <textarea name="adjust_reason" id="adjust_reason" class="bs_noti4"></textarea>
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
            </div><!-- pop-base -->
HTML;
 
    return $html;
}

//실재고파악 팝업
function makeRealStockMngPop($param) {

    $html = <<<HTML
            <dl>
              <dt class="tit">
              <h4>실제고 파악</h4>
              </dt>
              <dt class="cls">
                <button type="button" class="btn btn-sm btn-danger fa fa-times" onclick="hideDealspecReg();"></button>
              </dt>
            </dl>
          <div class="pop-base">
            <div class="pop-content">
              <div class="form-group">
                <label class="control-label fix_width55 tar">등록일자</label><label class="fix_width20 fs14 tac">:</label>
                <label class="control-label fix_width55 tar">2015.10.22</label>
                <label class="fix_width310"> </label>
                <label class="control-label fix_width55 tar">수정일자</label><label class="fix_width20 fs14 tac">:</label>
                <label class="control-label fix_width55 tar">2015.10.22</label>
                <br />
                <label class="control-label fix_width55 tar">종이</label><label class="fix_width20 fs14 tac">:</label>
                <label class="control-label fix_width120 tar">아트지 백색 250g</label>
                <br />
                <label class="control-label fix_width55 tar">실 재 고</label><label class="fix_width20 fs14 tac">:</label>
                <input id="search" type="text" class="search_btn fix_width100" placeholder="1" value="">R
                <label class="fix_width15"></label>
                <label class="form-radio">
                <br />
                <label class="control-label fix_width55 tar">고객메모</label><label class="fix_width20 fs14 tac">:</label>
                <textarea class="bs_noti4">컴퍼니 이정은</textarea>
                <br />
              </div>
              <div class="form-group">
                <p class="tac mt15">
                  <label class="fix_width180"> </label>
                  <button type="button" class="btn btn-sm btn-success">저장</button>
                  <button type="button" class="btn btn-sm btn-danger">삭제</button>
                  <label class="fix_width200"> </label>
                  <button type="button" class="btn btn-sm btn-primary" onclick="hideDealspecReg();">닫기</button>
                </p>
              </div>
            </div><!-- pop-content -->
          </div><!-- pop-base -->
HTML;
 
    return $html;
}


?>
