<?
//조판 파일 html
function getFileHtml($param) {

    $html = <<<VIEWHTML

                        <div id="file_area">
                        <label class="fix_width100"> </label>
                        <label class="control-label cp blue_text01"><a href="/common/typset_format_file_down.php?seqno=$param[seqno]">$param[file_name]</a></label>
                        <br /> 
                        </div>

VIEWHTML;

    return $html;

}


//생산 조판 html
function getPrdcTypsetView($param) {

    $html = <<<VIEWHTML

    <form name="typset_form" id="typset_form" method="post">
									  <dl>
										  <dt class="tit">
										  <h4>생산조판정보</h4>
										  </dt>
										  <dt class="cls">
											  <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-danger fa fa-times"></button>
										  </dt>
									  </dl>
									  <div class="pop-base">
										  <div class="pop-content">

											  <div class="form-group">
                                                  <label class="control-label fix_width105 tar">카테고리</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[preset_cate]
												  <br />

												  <label class="control-label fix_width105 tar">조판명</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[preset_name]
												  <br />
												  <label class="control-label fix_width105 tar">종이</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[paper]
												  <br />

												  <label class="control-label fix_width105 tar">사이즈</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[affil] $param[subpaper] ($param[wid_size]*$param[vert_size])
												  <br />
                   	                     	 	  <label class="control-label fix_width105 tar">홍각기/돈땡</label><label class="fix_width10 fs14 tac">:</label>
                                                  <label class="control-label cp"><input type="radio" class="radio_box" name="honggak_yn" value="Y" $param[honggak_y] disabled>홍각기</label>  
                                                  <label class=" fix_width05"></label>
                                                  <label class="control-label cp"><input type="radio" class="radio_box" name="honggak_yn" value="N" $param[honggak_n] disabled>돈땡</label>
                                                  <br />                                 
                       	                     	  <label class="control-label fix_width105 tar">용도</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[purp] 
                                                  <br />
												  <label class="control-label fix_width105 tar">등록일</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[regi_date]
												  <label class="control-label fix_width105 tar">작업자</label><label class="fix_width10 fs14 tac">:</label>
                                                  $param[worker_id]
                                                  <br />
												  <label class="control-label fix_width105 tar">판설명</label><label class="fix_width10 fs14 tac">:</label>
												  <textarea id="dscr" name="dscr" class="bs_noti2" style="width:400px; height=150px;">$param[dscr]</textarea>
											  </div>
											  <hr class="hr_bd3">

											  <p class="tac mt15">
												  <button type="button" id="save_typset" onclick="saveTypset('$param[add_yn]'); return false;" class="orge btn btn-sm">수정</button>
												  <label class="fix_width5"> </label>
												  <button type="button" id="del_typset" onclick="delPopTypset(); return false;" class="btn btn-sm btn-danger">삭제</button>
												  <label class="fix_width140"> </label>
												  <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-primary">닫기</button>
											  </p>
										  </div>
									  </div>
                                      </form>
VIEWHTML;

    return $html;
}
?>
