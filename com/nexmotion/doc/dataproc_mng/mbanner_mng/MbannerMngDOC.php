<?
function setContentHtml($param) {

    $html = <<<HTML
            <form name="banner_form$param[count]" id="banner_form$param[count]" method="post">
            <!--  메인배너 컨텐츠 시작 -->
            <div class="tb_group mt25">
                <div class="tab_box_con">
                    <div class="tabbable">
                        <div class="tab-content">
				            <div class="form-group">
				                <label class="control-label fix_width150 tar">사용유무</label><label class="fix_width20 fs14 tac"> : </label>
				                <label class="form-radio form-normal"><input type="radio" name="use_yn$param[count]" value="Y" class="radio_box" $param[use_y]>사용</label>
				                <label class="fix_width15"></label>
				                <label class="form-radio"><input type="radio" name="use_yn$param[count]" value="N" class="radio_box" $param[use_n]>사용안함</label>
				                <br />
							</div>

				            <div class="pop-base fl fix_width1150">
				                <div class="pop-content fix_height315">
                                    <ul class="form-group">
                                        <!-- 미리보기 시작-->
                                        <li class="fix_width550 fl">
                                            <div class="process_view_box16" style="width:500px;height:255px;" id="img_div$param[count]">
                                                $param[img_html]
                                            </div>
                                        </li>
                                        <!-- 미리보기 종료-->

                                        <!-- 업로드 설정 시작-->
                                        <li class="fix_width500 fl">

                                            <label class="control-label fix_width85 tar">구분</label><label class="fix_width20 fs14 tac"> : </label>
                                            <label class="form-radio form-normal"><input type="radio" name="banner_dvs$param[count]" value="공지" class="radio_box" $param[dvs1]>공지</label>
                                            <label class="fix_width15"></label>
                                            <label class="form-radio"><input type="radio" name="banner_dvs$param[count]" value="상품소개" class="radio_box" $param[dvs2]>상품소개</label>
                                            <label class="fix_width15"></label>
                                            <label class="form-radio"><input type="radio" name="banner_dvs$param[count]" value="이벤트" class="radio_box" $param[dvs3]>이벤트</label><br />

                                            <label class="control-label fix_width85 tar">이미지</label><label class="fix_width20 fs14 tac">:</label>
                                            <!-- 파일 업로드 / 스크립이 하단에 있어야 작동함 -->
                                            <input id="upload_file$param[count]" name="upload_file$param[count]" class="disableInputField" placeholder="" disabled="disabled" />

                                            <label class="fileUpload">
                                                <input name="upload_btn$param[count]" id="upload_btn$param[count]" type="file" class="upload" />
                                                <span class="btn btn-sm btn-info fa">찾아보기</span>
                                            </label>
                                            <span class="fs13" style="margin-left: 102px;color:red;">* 사이즈 : 1000 x 510 / 사용가능 확장자 : jpg, gif, png</span>
                                            <script type="text/javascript">
                                                document.getElementById("upload_btn$param[count]").onchange = function () {
                                                document.getElementById("upload_file$param[count]").value = this.value;
                                                     };
                                            </script>
                                            <div id="file_area$param[count]">
                                                <label class="fix_width105"> </label>
                                                <label class="control-label cp blue_text01" id="file_name$param[count]">
                                                    $param[origin_file_name]
                                                </label>
                                            </div>

                                            <label class="control-label fix_width85 tar">링크 URL</label><label class="fix_width20 fs14 tac">:</label>
                                            <input type="text" name="url_addr$param[count]" class="input_co2 fix_width270" value="$param[url_addr]">

                                            <input type="hidden" id="banner_seqno$param[count]" value="$param[seqno]">
                                            <div>
                                                <p class="btn-lg red_btn">
                                                    <a onclick="savePopupSet('$param[count]'); return false;" href="#">저장</a>
                                                </p>
                                            </div>

                                        </li>
                                        <!-- 업로드 설정 종료-->
                                    </ul>
                                </div>
				            </div>
                        </div>
                    </div>
            	</div>
            </div>
            </form>
            <!--  메인배너 컨텐츠 종료-->

HTML;

    return $html;
}
?>
