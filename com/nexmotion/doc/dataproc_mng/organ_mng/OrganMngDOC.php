<?
//부서 관리 html 팝업
function getDeparAdminHtml($param) {

    $html = <<<VIEWHTML

    <form name="depar_form" id="depar_form" method="post">

                        			    <dl>
                        			        <dt class="tit">
                        			       	    <h4>부서추가</h4>
                        			        </dt>
                        			        <dt class="cls">
                        			       	    <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-danger fa fa-times"></button>
                        			        </dt>
                        			    </dl>

                        			    <div class="pop-base">
                                            <div class="pop-content">
                                                <div class="form-group">
                                                    <label class="control-label fix_width79 tar">판매채널</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select onchange="changeHighDepar(this.value);" id="sell_site" name="sell_site" class="fix_width200" $param[dis_sel]>
                                                        $param[sell_site_html]
        		                                    </select>

                                                    <label class="control-label fix_width79 tar">상위부서</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select name="high_depar_code" id="high_depar_code" class="fix_width200" $param[dis_sel]>
                                                        $param[high_depar_html]
        		                                    </select>
                                                    <br />

                                        		    <label class="control-label fix_width79 tar">부서명</label><label class="fix_width20 fs14 tac">:</label>
                        			    		    <input type="text" class="input_co2 fix_width191" id="depar_name" name="depar_name" value="$param[depar_name]">
                        			    		    <br />

                                                </div>

                                                <hr class="hr_bd3">

                                                <div class="form-group">
                                                    <p class="tac mt15">
                                            		    <button type="button" onclick="saveDeparInfo('$param[depar_admin_seqno]'); return false;" class="btn btn-sm btn-success">저장</button>
                                                        <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-primary">닫기</button>
                                                    </p>
                                                </div>
                        			        </div>
                        			    </div>

    </form>
VIEWHTML;

    return $html;

}

//관리자 html 팝업
function getMngHtml($param) {

    $html = <<<VIEWHTML

    <form name="mng_form" id="mng_form" method="post">
                        			    <dl>
                        			        <dt class="tit">
                        			       	    <h4>관리자 관리</h4>
                        			        </dt>
                        			        <dt class="cls">
                        			       	    <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-danger fa fa-times"></button>
                        			        </dt>
                        			    </dl>
                        			    <div class="pop-base">
                                            <div class="pop-content">
                                                <div class="form-group">
                                                    <label class="control-label fix_width79 tar">판매채널</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select onchange="changeDepar(this.value);" id="sell_site" name="sell_site" class="fix_width200" $param[dis_sel]>
                                                        $param[sell_site_html]
        		                                    </select>
                        			    		    <br />
                                        		    <label class="control-label fix_width79 tar">성명</label><label class="fix_width20 fs14 tac">:</label>
                        			    		    <input type="text" class="input_co2 fix_width170" id="mng_name" name="mng_name" value="$param[mng_name]">
                        			    		    <br />
                                                    <label class="control-label fix_width79 tar">성별</label><label class="fix_width20 fs14 tac">:</label>
                                                    <input type="radio"> 남자 <input type="radio"> 여자
                                                    <br />

                        			    		    <label class="control-label fix_width79 tar">사원번호</label><label class="fix_width20 fs14 tac">:</label>
                        			    		    <input type="text" class="input_co2 fix_width170" name="empl_code" value="$param[empl_code]">
                        			    		    <br />
                                                    <label class="control-label fix_width79 tar">휴대폰번호</label><label class="fix_width20 fs14 tac">:</label>
                        			    		    <input type="text" class="input_co2 fix_width170" name="tel_num" value="$param[tel_num]">
                        			    		    <br />
                                                    <label class="control-label fix_width79 tar">내선</label><label class="fix_width20 fs14 tac">:</label>
                        			    		    <input type="text" class="input_co2 fix_width170" name="exten" value="$param[exten]">
                        			    		    <br />
                        			    		    <label class="control-label fix_width79 tar">ID</label><label class="fix_width20 fs14 tac">:</label>
                        			    		    <input type="text" class="input_co2 fix_width170" id="empl_id" name="empl_id" value="$param[empl_id]">
                        			    		    <button onclick="resetPasswd('$param[empl_seqno]'); return false;" class="btn btn_pu fix_width120 fix_height30 bgreen fs12">비밀번호초기화</button>
                        			    		    <br />

                        			    		    <label class="control-label fix_width79 tar">부서명</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select name="depar_code" id="depar_code" class="fix_width180">
                                                        $param[depar_name_html]
        		                                    </select>
                                                    <br />

                                                    <label class="control-label fix_width79 tar">보안등급</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select name="admin_auth" class="fix_width100">
                                                        $param[admin_auth_html]
        		                                    </select>
                                                    <br />

                                                    <label class="control-label fix_width79 tar">직책</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select name="job_code" class="fix_width100">
                                                        $param[job_name_html]
        		                                    </select>
                                                    <br />

                                                    <label class="control-label fix_width79 tar">직급</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select name="posi_code" class="fix_width100">
                                                        $param[posi_name_html]
        		                                    </select>
                                                    <br />

                                                    <label class="control-label fix_width79 tar">운영체제</label><label class="fix_width20 fs14 tac">:</label>
                                                    <select name="oper_sys" class="fix_width100">
                                                        $param[oper_sys_html]
        		                                    </select>
                                                    <br />

                                                    <label class="control-label fix_width79 tar">입사일</label><label class="fix_width20 fs14 tac">:</label>
                                                    <input placeholder="yyyy-MM-dd" class="input_co2 fix_width83 date" id="enter_date" name="enter_date" value="$param[enter_date]">
                                                    <br />
                                                </div>

                                                <hr class="hr_bd3_b">

                                                <div class="form-group">
                                                    <p class="tac mt15">
                                                        <label class="fix_width140"></label>
                                            		    <button type="button" onclick="saveMngInfo('$param[empl_seqno]'); return false;" class="btn btn-sm btn-success">저장</button>
                                                        <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-primary">닫기</button>
                                                        <label class="fix_width120"></label>
                                                        <button type="button" onclick="resignMng('$param[empl_seqno]'); return false;" class="btn btn-sm btn-danger">퇴사</button>
                                                    </p>
                                                </div>
                        			        </div>
                        			    </div>
    </form>

VIEWHTML;

    return $html;

}

//권한 관리 html 팝업
function getMngAuthHtml($param) {

    $html = <<<VIEWHTML

    <form name="auth_form" id="auth_form" method="post">
                        			    <dl>
                        			        <dt class="tit">
                        			       	    <h4>접근권한설정</h4>
                        			        </dt>
                        			        <dt class="cls">
                        			       	    <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-danger fa fa-times"></button>
                        			        </dt>
                        			    </dl>

                        			    <div class="pop-base">
                                            <div class="pop-content table-body">
                                                <div class="table_basic">
                                			        <table class="table fix_width100f">
                                                        <thead>
                                			                <tr>
                                			    	            <th class="bm2px">번호</th>
                                			    	            <th class="bm2px">분류</th>
                                			    	            <th class="bm2px">페이지</th>
                                			    	            <th class="bm2px">허용여부</th>
                                			                </tr>
                                			                <tr>
                                			                	<th colspan="3" class="bm2px">전체</th>
                                			                	<th class="bm2px">
                                                                    <label for="all_y" style="cursor: pointer;"><input type="radio" id="all_y" name="all_chk" class="radio_box" onclick="$('input[class=radio_box][value=Y]').prop('checked', true);">허용</label>
                                			                	    <label class="fix_width10"> </label>
                                			                	    <label for="all_n" style="cursor: pointer;"><input type="radio" id="all_n" name="all_chk" class="radio_box" onclick="$('input[class=radio_box][value=N]').prop('checked', true);">허용안함</label></th>
                                			                </tr>
                                                        </thead>
                                                        <tbody>
                                                            $param[tbody_html]
                                                        </tbody>
                                			        </table>
                                                </div>
                        			        </div>
                        			    </div>
                                                    <p class="tac mt15">
                                            		    <button type="button" onclick="saveMngAuth('$param[empl_seqno]'); return false;" class="btn btn-sm btn-success">저장</button>
                                                        <button type="button" onclick="hideRegiPopup(); return false;" class="btn btn-sm btn-primary">닫기</button>
                                                    </p>
                                                    <br /><br />
    </form>

VIEWHTML;

    return $html;

}

?>


