/*
 *
 * Copyright (c) 2015 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *=============================================================================
 * 2016/06/02 왕초롱 생성
 *=============================================================================
 *
 */
$(document).ready(function() {
    tabCtrl("대기");
});

var list_page = "1";
var list_num = "30";
var public_state = "";
var selectTab = "";
var public_seqno = "";
var tab_name = "대기";
var complete_data = {};
var standby_data = {};
var cashreceipt_data = {};
var except_data = {};

var publicLayer = function(dvs,
			   public_seq,
			   public_date,
			   sell_site,
			   req_date,
			   pay_price,
			   card_price,
			   cash_price,
			   etc_price,
			   oa,
			   before_oa,
			   object_price,
			   name,
			   repre,
			   crn,
			   bc,
			   tob,
			   addr,
			   zipcode,
			   req_mon,
			   day,
			   unit_price,
			   supply_price,
			   vat,
			   public_dvs){

    public_seqno = public_seq;
    $("#new_member").hide();
    $("#new_save").hide();
    $("#except_save").show();
    $("#except_del").show();

    if (dvs == "basic") {
    	openRegiPopup($("#basic_popup").html(), 800);
	$("#" + dvs + "_pay_price").html(pay_price);
	$("#" + dvs + "_card_price").html(card_price);
	$("#" + dvs + "_cash_price").html(cash_price);
	$("#" + dvs + "_etc_price").html(etc_price);
	$("#" + dvs + "_object_price").html(object_price);
	$("#" + dvs + "_corp_name").html(name);
	$("#" + dvs + "_repre_name").html(repre);
	$("#" + dvs + "_crn").html(crn);
	$("#" + dvs + "_bc").html(bc);
	$("#" + dvs + "_tob").html(tob);
	$("#" + dvs + "_addr").html(addr);
	$("#" + dvs + "_zipcode").html(zipcode);
    } else {
    	openRegiPopup($("#edit_popup").html(), 800);
	$("#" + dvs + "_pay_price").val(pay_price);
	$("#" + dvs + "_card_price").val(card_price);
	$("#" + dvs + "_cash_price").val(cash_price);
	$("#" + dvs + "_etc_price").val(etc_price);
	$("#" + dvs + "_object_price").val(object_price);
	$("#" + dvs + "_corp_name").val(name);
	$("#" + dvs + "_repre_name").val(repre);
	$("#" + dvs + "_crn").val(crn);
	$("#" + dvs + "_bc").val(bc);
	$("#" + dvs + "_tob").val(tob);
	$("#" + dvs + "_addr").val(addr);
	$("#" + dvs + "_zipcode").val(zipcode);
	$("input[name=" + dvs + "_unitprice]").attr('value', unit_price);
	$("input[name=" + dvs + "_supply_price]").attr('value', unit_price);
	$("input[name=" + dvs + "_vat]").attr('value', vat);
	$("input[name=" + dvs + "_tot_price]").attr('value', object_price);

	$("input:radio[name='edit_public_dvs']:radio[value='" 
			+ public_dvs + "']").attr("checked",true);
    }

    $("#" + dvs + "_sell_site").val(sell_site);
    $("#" + dvs + "_req_date").html(req_date);
    $("#" + dvs + "_oa").val(oa);
    $("#" + dvs + "_before_oa").val(before_oa);
    $("#" + dvs + "_mon").html(req_mon);
    $("#" + dvs + "_day").html(day);
    $("#" + dvs + "_unitprice").html(unit_price);
    $("#" + dvs + "_supply_price").html(supply_price);
    $("#" + dvs + "_vat").html(vat);
    $("#" + dvs + "_sum_price").html(cash_price);
    $("#" + dvs + "_tot_price").html(cash_price);

    //datepicker 기본 셋팅
    $("#" + dvs + "_public_date").datepicker({
        autoclose:true,
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        todayHighlight: true,
    });

    if (public_date) {
    	$("#" + dvs + "_public_date").val(public_date);
    } else {
    	$("#" + dvs + "_public_date").datepicker("setDate", new Date());
    }
}

//회원명 가져오기
var loadOfficeNick = function(event, val, dvs) {
    if (event.keyCode != 13) {
        return false;
    }

    if (val.length < 2) {
        alert("두글자 이상 입력하세요.");
	    return false;
    }

    var url = "/ajax/calcul_mng/sales_tab/load_office_nick.php";
    var data = {
        "sell_site"  : $("#sell_site").val(),
        "search_val" : val
    };
    var callback = function(result) {
        if (dvs !== "select") {
            searchPopShow(event, "loadOfficeNick", "loadOfficeNick");
        } else {
            showBgMask();
        }

        $("#search_list").html(result);
    };

    ajaxCall(url, "html", data, callback);

}

//클릭시에 회원 발행 가능 금액 가져오기
var loadIssueClick = function(seqno, name) {

    var url = "/ajax/calcul_mng/sales_tab/load_member_issue_price.php";
    var data = {
        "member_seqno" : seqno,
       	"year"         : $("#year").val(),
    	"mon"          : $("#mon").val(),

    };
    var callback = function(result) {
        $("#office_nick").val(name);
        $("#able_price").val(result.trim());
        hideRegiPopup();
    };

    ajaxCall(url, "html", data, callback);

}

//매출계산서 대기 리스트가져오기
var loadPublicStandByList = {
    "exec"       : function(listSize, page) {
	if(listSize){
	    list_num = listSize;
	} 
	if(page) {
	   list_page = page;
	}

        var url = "/ajax/calcul_mng/sales_tab/load_public_standby_list.php";
        var blank = "<tr><td colspan=\"14\">검색 된 내용이 없습니다.</td></tr>";
        standby_data = {
       	    	"year"         : $("#year").val(),
    	    	"mon"          : $("#mon").val(),
    	    	"member_dvs"   : $("#member_dvs").val(),
    	    	"sell_site"    : $("#sell_site").val()
	    };

        var callback = function(result) {
            var rs = result.split("♪♭@");

            if (rs[0].trim() == "") {
                $("#standby_list").html(blank);
                $("#standby_member_cnt").html(rs[2]);
                $("#standby_pay_price").html(rs[3] + "원");
            	$("#standby_total_price").html(rs[3] + "원");
                $("#standby_card_price").html(rs[4] + "원");

                return false;
            }

            $("#standby_list").html(rs[0]);
            $("#page").html(rs[1]);
            $("#standby_member_cnt").html(rs[2]);
            $("#standby_pay_price").html(rs[3] + "원");
            $("#standby_total_price").html(rs[3] + "원");
            $("#standby_card_price").html(rs[4] + "원");
        };

        standby_data.corp_name   = $("#standby_corp_name").val();
        standby_data.listSize      = list_num;
        standby_data.page          = list_page;

        showMask();
        ajaxCall(url, "html", standby_data, callback);
    }
}

//매출계산서 현금영수증 리스트가져오기
var loadCashreceiptList = {
    "exec"       : function(listSize, page, state) {
	if(listSize){
	    list_num = listSize;
	} 
	if(page) {
	   list_page = page;
	}

        var url = "/ajax/calcul_mng/sales_tab/load_cashreceipt_list.php";
        var blank = "<tr><td colspan=\"12\">검색 된 내용이 없습니다.</td></tr>";
        cashreceipt_data = {
       	    	"year"         : $("#year").val(),
    	    	"mon"          : $("#mon").val(),
    	    	"member_dvs"   : $("#member_dvs").val(),
    	    	"sell_site"    : $("#sell_site").val()
	    };

        var callback = function(result) {
            var rs = result.split("♪♭@");

            if (rs[0].trim() == "") {
                $("#cashreceipt_list").html(blank);
                $("#cashreceipt_member_cnt").html(rs[2]);
                $("#cashreceipt_pay_total").html(rs[3] + "원");
            	$("#cashreceipt_total_price").html(rs[3] + "원");
            	$("#cashreceipt_card_total").html(rs[4] + "원");
                return false;
            }

            $("#cashreceipt_list").html(rs[0]);
            $("#page").html(rs[1]);
            $("#cashreceipt_member_cnt").html(rs[2]);
            $("#cashreceipt_pay_total").html(rs[3] + "원");
            $("#cashreceipt_total_price").html(rs[3] + "원");
            $("#cashreceipt_card_total").html(rs[4] + "원");
        };

        cashreceipt_data.corp_name   = $("#cashreceipt_corp_name").val();
        cashreceipt_data.listSize      = list_num;
        cashreceipt_data.page          = list_page;

        showMask();
        ajaxCall(url, "html", cashreceipt_data, callback);
    }
}

//매출계산서 미발행(현금영수증) 리스트가져오기
var loadUnissuedList = {
    "exec"       : function(listSize, page, state) {
	if(listSize){
	    list_num = listSize;
	} 
	if(page) {
	   list_page = page;
	}

        var url = "/ajax/calcul_mng/sales_tab/load_unissued_list.php";
        var blank = "<tr><td colspan=\"11\">검색 된 내용이 없습니다.</td></tr>";
        var data = {
       	    	"year"         : $("#year").val(),
    	    	"mon"          : $("#mon").val(),
    	    	"member_dvs"   : $("#member_dvs").val(),
    	    	"sell_site"    : $("#sell_site").val()
	    };

        var callback = function(result) {
            var rs = result.split("♪♭@");

            if (rs[0].trim() == "") {
                $("#unissued_list").html(blank);
                $("#unissued_member_cnt").html(rs[2]);
                $("#unissued_pay_total").html(rs[3] + "원");
            	$("#unissued_card_total").html(rs[4] + "원");
            	$("#unissued_cash_price").html(rs[5] + "원");
                return false;
            }

            $("#unissued_list").html(rs[0]);
            $("#page").html(rs[1]);
            $("#unissued_member_cnt").html(rs[2]);
            $("#unissued_pay_total").html(rs[3] + "원");
            $("#unissued_card_total").html(rs[4] + "원");
            $("#unissued_cash_price").html(rs[5] + "원");
        };

        data.search        = $("#unissued_search").val();
        data.search_dvs    = $("#unissued_search_dvs").val();
        data.listSize      = list_num;
        data.page          = list_page;

        showMask();
        ajaxCall(url, "html", data, callback);
    }
}

//매출계산서 완료 리스트가져오기
var loadPublicCompleteList = {
    "exec"       : function(listSize, page, dvs) {
	if(listSize){
	    list_num = listSize;
	} 
	if(page) {
	   list_page = page;
	}

        var url = "/ajax/calcul_mng/sales_tab/load_public_complete_list.php";
        var blank = "<tr><td colspan=\"14\">검색 된 내용이 없습니다.</td></tr>";
        complete_data = {
       	        "year"         : $("#year").val(),
    	        "mon"          : $("#mon").val(),
    	        "member_dvs"   : $("#member_dvs").val(),
    	        "sell_site"    : $("#sell_site").val(),
    	        "tab_public"   : $("#tab_public").val()
	    };

        var callback = function(result) {
            var rs = result.split("♪♭@");

            if (rs[0].trim() == "") {
                $("#complete_list").html(blank);
                $("#complete_member_cnt").html(rs[2]);
                $("#complete_pay_total").html(rs[3] + "원");
                $("#complete_total_price").html(rs[3] + "원");
                $("#complete_card_total").html(rs[4] + "원");
                return false;
            }

            $("#complete_list").html(rs[0]);
            $("#complete_page").html(rs[1]);
            $("#complete_member_cnt").html(rs[2]);
            $("#complete_pay_total").html(rs[3] + "원");
            $("#complete_total_price").html(rs[3] + "원");
            $("#complete_card_total").html(rs[4] + "원");
        };

        complete_data.search   = $("#complete_search").val();
        complete_data.search_dvs   = $("#complete_search_dvs").val();
        complete_data.listSize      = list_num;
        complete_data.page          = list_page;

        showMask();
        ajaxCall(url, "html", complete_data, callback);
    }
}

//예외처리  리스트가져오기
var loadPublicExceptList = {
    "exec"       : function(listSize, page) {
	if(listSize){
	    list_num = listSize;
	} 
	if(page) {
	   list_page = page;
	}

        var url = "/ajax/calcul_mng/sales_tab/load_public_except_list.php";
        var blank = "<tr><td colspan=\"14\">검색 된 내용이 없습니다.</td></tr>";
        except_data = {
       	    	"year"         : $("#year").val(),
    	    	"mon"          : $("#mon").val(),
    	    	"member_dvs"   : $("#member_dvs").val(),
    	    	"sell_site"    : $("#sell_site").val()
	    };

        var callback = function(result) {
            var rs = result.split("♪♭@");

            if (rs[0].trim() == "") {
                $("#except_list").html(blank);
                $("#except_member_cnt").html(rs[2]);
                $("#except_pay_price").html(rs[3] + "원");
            	$("#except_card_total").html(rs[4] + "원");
            	$("#except_cash_price").html(rs[5] + "원");
                return false;
            }

            $("#except_list").html(rs[0]);
            $("#page").html(rs[1]);
            $("#except_member_cnt").html(rs[2]);
            $("#except_pay_price").html(rs[3] + "원");
            $("#except_card_total").html(rs[4] + "원");
            $("#except_cash_price").html(rs[5] + "원");
        };

        except_data.corp_name   = $("#except_corp_name").val();
        except_data.listSize      = list_num;
        except_data.page          = list_page;

        showMask();
        ajaxCall(url, "html", except_data, callback);
    }
}



//보여줄 페이지 수 설정
var showPageSetting = function(val, state) {
 
    if (state == "대기") {
        loadPublicStandByList.exec(val, 1);
    } else if (state == "현금영수증") {
        loadCashreceiptList.exec(val, 1);
    } else if (state == "미발행") {
        loadUnissuedList.exec(val, 1);
    } else if (state == "완료") {
        loadPublicCompleteList.exec(val, 1);
    } else {
        loadPublicExceptList.exec(val, 1);
    }
}

//세금계산서(대기) 페이지 이동
var moveStandbyPage = function(val) {
    loadPublicStandByList.exec(list_num, val);
}

//현금영수증 페이지 이동
var moveCashreceiptPage = function(val) {
    loadCashreceiptList.exec(list_num, val);
}

//미발행(현금순매출) 페이지 이동
var moveUnissuedPage = function(val) {
    loadUnissuedList.exec(list_num, val);
}

//세금계산서(발급완료) 페이지 이동
var moveCompletePage = function(val) {
    loadPublicCompleteList.exec(list_num, val);
}

//예외처리 페이지 이동
var moveExceptPage = function(val) {
    loadPublicExceptList.exec(list_num, val);
}

/**
 * @brief 밸행 구분 탭
 */
var tabCtrl = function(state) {

    if (state) {
    	tab_name = state;
    }
    resetSearchInput();
    
    if (tab_name == "대기") {
	loadPublicStandByList.exec('30', '1');
    } else if (tab_name == "현금영수증") {
	loadCashreceiptList.exec('30', '1');
    } else if (tab_name == "미발행") {
	loadUnissuedList.exec('30', '1');
    } else if (tab_name == "완료") {
	loadPublicCompleteList.exec('30', '1');
    } else {
	loadPublicExceptList.exec('30', '1');
    }
}

var resetSearchInput = function() {
    $("#standby_corp_name").val('');
    $("#cashreceipt_corp_name").val('');
    $("#unissued_search").val('');
    $("#complete_search_name").val('');
    $("#except_corp_name").val('');
}

var savePublicSeq = function(seqno) {
    public_seqno = seqno;
    savePublic("예외");
}

var saveStateSeq = function(seqno, state) {
    public_seqno = seqno;
    savePublicState(state);
}

var savePublicState = function(state) {

    var url = "/proc/calcul_mng/sales_tab/proc_public_only_state.php";
    var data = {
	"state" : state,
    	"seqno" : public_seqno
    };

    var callback = function(result) {
        if (result == 1) {
	    if (state == "예외") {
		alert("예외처리로 이동하였습니다.");
	    } else {
		alert("대기로 이동하였습니다.");
	    }
	    hideRegiPopup();

	    if (tab_name == "대기") {
            	loadPublicStandByList.exec();
	    } else {
            	loadPublicExceptList.exec();
	    }

        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);

}

var savePublicDetail = function() {

    var url = "/proc/calcul_mng/sales_tab/proc_public_detail.php";
    var data = {
    	"seqno"        : public_seqno,
	"oa"           : $("#basic_oa").val(),
	"before_oa"    : $("#basic_before_oa").val(),
	"public_date"  : $("#basic_public_date").val(),
    };

    var callback = function(result) {
        if (result == 1) {
	    hideRegiPopup();
	    if (tab_name == "대기") {
	        alert("저장에 성공하였습니다.  발급완료로 이동하였습니다.");
                loadPublicStandByList.exec();
	    } else {
		alert("저장에 성공하였습니다.");
                loadPublicCompleteList.exec();
	    }
        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

//세금계산서 상태 변경
var savePublic = function(state) {

    var url = "/proc/calcul_mng/sales_tab/proc_public_state.php";
    var data = {
	"state" : state,
    	"seqno" : public_seqno,
	"tab_name" : tab_name
    };

    var callback = function(result) {
        if (result == 1) {
	    if (state == "완료") {
            	alert("발급완료로 이동했습니다.");
	    } else {
		if (tab_name == "완료") {
		    alert("미발행(현금순매출)으로 이동하였습니다.");

		} else {
		    alert("예외처리로 이동하였습니다.");
		}
	    }
	    hideRegiPopup();

	    if (tab_name == "대기") {
            	loadPublicStandByList.exec();
	    } else {
            	loadPublicCompleteList.exec();
	    }

        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);

}

//현금영수증 미발행 현금순매출 변경
var saveCashreceipt = function(seqno) {

    var url = "/proc/calcul_mng/sales_tab/proc_cashreceipt_state.php";
    var data = {
    	"seqno" : seqno
    };

    var callback = function(result) {
        if (result == 1) {
	    alert("미발행(현금순매출)으로 이동하였습니다.");
            loadCashreceiptList.exec();
        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

//현금영수증 미발행 현금순매출 삭제
var removeCashreceipt = function(seqno) {
    if (confirm("삭제하시겠습니까?") == false){
	return;
    }

    var url = "/proc/calcul_mng/sales_tab/del_cashreceipt.php";
    var data = {
    	"seqno" : seqno
    };

    var callback = function(result) {
        if (result == 1) {
	    alert("삭제하였습니다.");
            loadUnissuedList.exec();
        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

//체크박스 선택시 value값 가져오는 함수
var getselectedNo = function(el) {

    var selectedValue = ""; 
    
    $("#" + el + "_list input[name=" + el + "_chk]:checked").each(function() {
        selectedValue += ","+ $(this).val();		    
    });

    if (selectedValue != "" && el != "flatt_y") {
        selectedValue = selectedValue.substring(1);
    }

    return selectedValue;
}

//전체 선택
var allCheck = function(type) {
    //만약 전체선택 체크박스가 체크 된 상태일 경우
    if ($("#allCheck_" + type).prop("checked")) {
        $("#" + type + "_list input[type=checkbox]").prop("checked", true);
    } else {
        $("#" + type + "_list input[type=checkbox]").prop("checked", false);
    }
}

/**
* @brief 대기리스트 발행상태 변경
*/
var saveStandbyChkPublic = function(state) {

    var seqno = getselectedNo('standby');
    if (seqno.length == 0) {
	alert("체크박스를 선택해주세요");
	return false;
    }

    var url = "/proc/calcul_mng/sales_tab/proc_tab_public.php";
    var data = {
	"state" : state,
    	"seqno" : seqno
    };

    var callback = function(result) {
        if (result == 1) {
            loadPublicStandByList.exec();
            $(".check_box").prop("checked", false);
	    if (state == "완료") {
            alert("발급을 완료하였습니다.");
	    } else {
		    alert("보류하였습니다.");
	    }
        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

/**
* @brief 발행상태 변경
*/
var saveCashreceiptPublic = function() {
    cashreceipt_data.seqno = '';

    var url = "/proc/calcul_mng/sales_tab/proc_public_cashreceipt.php";
    var callback = function(result) {
        if (result == 0) {
            return alertReturnFalse("실패하였습니다.");
        }

        var downUrl  = "/common/excel_file_down.php?file_dvs=" + result + "&name=" + result;
        $("#file_ifr").attr("src", downUrl);

        alert("발행하였습니다.");
        loadCashreceiptList.exec();
    };

    showMask();
    ajaxCall(url, "text", cashreceipt_data, callback);

}

/**
* @brief 대기리스트 발행상태 변경
*/
var saveStandbyPublic = function() {

    var url = "/proc/calcul_mng/sales_tab/proc_public_standby.php";
    var data = standby_data;

    var callback = function(result) {
        if (result == 1) {
	    alert("발급을 완료하였습니다.");
            loadPublicStandByList.exec();

        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);

}

/**
* @brief 완료리스트 발행상태 변경
*/
var saveCompletePublic = function() {
    complete_data.seqno = '';

    var url = "/proc/calcul_mng/sales_tab/proc_public_complete.php";
    var callback = function(result) {
        if (result == 0) {
            return alertReturnFalse("실패하였습니다.");
        }

        var downUrl  = "/common/excel_file_down.php?file_dvs=" + result + "&name=" + result;
        $("#file_ifr").attr("src", downUrl);

        loadPublicCompleteList.exec();
    };

    showMask();
    ajaxCall(url, "text", complete_data, callback);
}

/**
* @brief 예외리스트 일괄 발행상태 변경
*/
var saveExceptBatchPublic = function() {

    //TODO 성공시에  엑셀다운로드 만들어야함
    var url = "/proc/calcul_mng/sales_tab/proc_public_except.php";
    var data = except_data;

    var callback = function(result) {

        if (result == 1) {
	    alert("수정하였습니다.");
            loadPublicExceptList.exec();

        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);

}

/**
* @brief 발행여부 및 보류
*/
var procStandby = function(state, dvs) {

    var seqno = getselectedNo(dvs);
    if (seqno.length == 0) {
	    alert("체크박스를 선택해주세요");
	    return false;
    }

    var url = "/proc/calcul_mng/sales_tab/proc_tab_public.php";
    var data = {
	"state" : state,
	"origin": dvs,
    	"seqno" : seqno
    };

    var callback = function(result) {
        if (result == 1) {
            if (dvs == "unissued") {
                loadPublicUnissuedList.exec();
            } else {
                loadPublicCompleteList.exec();
            }
            $(".check_box").prop("checked", false);
	    alert("발행을 대기하였습니다.");
        } else {
            alert("실패하였습니다.");
        }
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

/**
* @brief 예외처리  발행 수정
*/
var saveExceptPublic = function() {
    var formData = $("#editForm").serialize() + "&seqno=" + public_seqno;

    var url = "/proc/calcul_mng/sales_tab/proc_except_public.php";
    var data = formData;
    var callback = function(result) {
        if (result == 1) {
            hideRegiPopup();
            alert("성공하였습니다.");
            loadPublicExceptList.exec();
        } else {
            alert("실패하였습니다.");
        }
    }

    showMask();
    ajaxCall(url, "html", data, callback);
}

var enterCheck = function(event, dvs) {
    if(event.keyCode == 13) {
	if (dvs == "standby") {
	    loadPublicStandByList.exec();
	} else if (dvs == "cashreceipt") {
	    loadCashreceiptList.exec();
	} else if (dvs == "unissued") {
	    loadUnissuedList.exec();
	} else if (dvs == "complete") {
	    loadPublicCompleteList.exec();
	} else {
	    loadPublicExceptList.exec();
	}
    }
}

//매출, 대상금액, 단가, 공급가액, 세액 구하기
var applySumPrice = function(event) {

    removeChar(event);

    //매출 금액 구하기
    var sales = Number($("#edit_card_price").val()) + Number($("#edit_cash_price").val()) + Number($("#edit_etc_price").val());
    $("#edit_pay_price").val(sales);

    //대상금액 구하기
    var object_sum = Number($("#edit_pay_price").val()) - Number($("#edit_card_price").val());
    $("#edit_object_price").val(object_sum);
    $("input[name=edit_tot_price]").attr('value', object_sum);

    //단가, 공급가액 구하기
    var unitprice = Math.ceil(Number($("#edit_object_price").val()) / 1.1);
    $("#edit_unitprice").html(unitprice); 
    $("#edit_supply_price").html(unitprice); 
    $("input[name=edit_unitprice]").attr('value', unitprice);
    $("input[name=edit_supply_price]").attr('value', unitprice);

    //세액 구하기
    var vat = Number($("#edit_object_price").val()) - unitprice;
    $("#edit_vat").html(vat); 
    $("input[name=edit_vat]").attr('value', vat);

    //합계 구하기
    $("#edit_tot_price").html(object_sum);

}

/**
* @brief 세금계산서 발행
*/
var saveNewPublic = function() {
    if (!$("#regi_member_seqno").val()) {
	alert("회원명검색 후 클릭한 다음 저장해주세요.");
	return false;
    }

    var year = $("#edit_req_date").html().split("년");
    var mon = year[1].trim().split("월");

    var formData = $("#editForm").serialize() + "&year=" + year[0] + "&mon=" + mon[0];

    var url = "/proc/calcul_mng/sales_tab/proc_new_public.php";

    var data = formData;
    var callback = function(result) {
        if (result == 1) {
            hideRegiPopup();
            alert("성공하였습니다.");
	    tabCtrl();
        } else {
            alert("실패하였습니다.");
        }
    }

    showMask();
    ajaxCall(url, "html", data, callback);
}

//세금계산서 새로 만들기
var insertNewPublic = function() {
    public_seqno = "";
    openRegiPopup($("#edit_popup").html(), 800);
    $("#new_member").show();
    $("#new_save").show();
    $("#except_save").hide();
    $("#except_del").hide();

    //datepicker 기본 셋팅
    $("#edit_public_date").datepicker({
        autoclose:true,
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        todayHighlight: true,
    });

    $("#edit_public_date").datepicker("setDate", new Date());
    $("#edit_req_date").html($("#year").val() + "년 " + $("#mon").val() + "월");

}

/**
* @brief 새로생성한 발행저장
*/
var saveNewPublic = function() {
    if (!$("#regi_member_seqno").val()) {
	alert("회원명검색 후 클릭한 다음 저장해주세요.");
	return false;
    }

    var year = $("#edit_req_date").html().split("년");
    var mon = year[1].trim().split("월");

    var formData = $("#editForm").serialize() + "&year=" + year[0] + "&mon=" + mon[0];

    var url = "/proc/calcul_mng/sales_tab/proc_new_public.php";

    var data = formData;
    var callback = function(result) {
        if (result == 1) {
            hideRegiPopup();
            alert("성공하였습니다.");
	    tabCtrl();
        } else {
            alert("실패하였습니다.");
        }
    }

    showMask();
    ajaxCall(url, "html", data, callback);
}

/**
* @brief 새로생성할 회원 일련번호 가져옴
*/
var loadMemberSeqClick = function(seqno, name) {

    var url = "/ajax/calcul_mng/sales_tab/load_member_info.php";
    var data = {
        "member_seqno" : seqno
    };
    var callback = function(result) {
        var rs = result.split("♪♭♬");
	
	$("#edit_corp_name").val(rs[1]);
	$("#edit_repre_name").val(rs[2]);
	$("#edit_crn").val(rs[3]);
	$("#edit_bc").val(rs[4]);
	$("#edit_tob").val(rs[5]);
	$("#edit_addr").val(rs[6]);
	$("#edit_zipcode").val(rs[7]);

    	$("#regi_office_nick").val(name);
    	$("#regi_member_seqno").val(seqno);

    	hidePopPopup();
    };

    ajaxCall(url, "html", data, callback);
}

//새로 발급할 회원 조회
var loadRegiOfficeNick = function(event, val, dvs) {
    if (event.keyCode != 13) {
        return false;
    }

    if (val.length < 2) {
        alert("두글자 이상 입력하세요.");
	    return false;
    }

    var url = "/ajax/calcul_mng/sales_tab/load_public_office_nick.php";
    var data = {
        "sell_site"  : $("#sell_site").val(),
        "search_val" : val
    };
    var callback = function(result) {
        if (dvs !== "select") {
            searchPopPopShow(event, "loadRegiOfficeNick", "loadRegiOfficeNick");
        } else {
            showBgMask();
        }

        $("#search_list").html(result);
    };

    ajaxCall(url, "html", data, callback);
}

//검색창 팝업 show
var searchPopPopShow = function(event, fn1, fn2) {

    var html = "";
    html += "\n  <dl>";
    html += "\n    <dt class=\"tit\">";
    html += "\n      <h4>검색창 팝업</h4>";
    html += "\n    </dt>";
    html += "\n    <dt class=\"cls\">";
    html += "\n      <button type=\"button\" onclick=\"hidePopPopup();\" class=\"btn btn-sm btn-danger fa fa-times\">";
    html += "\n      </button>";
    html += "\n    </dt>";
    html += "\n  </dl>";
    html += "\n  <div class=\"pop-base\">";
    html += "\n    <div class=\"pop-content\">";
    html += "\n      <label for=\"search_pop\" class=\"con_label\">";
    html += "\n        Search : ";
    html += "\n        <input id=\"search_pop\" type=\"text\" class=\"search_btn fix_width180\" onkeydown=\"" + fn1 + "(event, this.value, 'select');\">";
    html += "\n        <button type=\"button\" class=\"btn btn-sm btn-info fa fa-search\" onclick=\"" + fn2 + "(event, this.value, 'select');\">";
    html += "\n        </button>";
    html += "\n      </label>";
    html += "\n      <hr class=\"hr_bd3\">";
    html += "\n      <div class=\"list_scroll fix_height120\" id=\"search_list\">";
    html += "\n      </div>";
    html += "\n    </div>";
    html += "\n  </div>";
    html += "\n</div>";

    openPopPopup(html, 440);
    $("#search_pop").focus();
}

//엑셀 다운로드
var excelDownload = function(dvs) {
    var seqno = '';
    var data  = null;
    var callback = null;

    $("input[name='" + dvs + "_chk']:checked").each(function() {
        seqno += $(this).val();
        seqno += ',';
    });
    seqno = seqno.substr(0, seqno.length - 1);

    var url = "/proc/calcul_mng/sales_tab/";
    if (dvs === "cashreceipt") {
        url += "proc_public_cashreceipt.php";

        cashreceipt_data.seqno = seqno;
        data = cashreceipt_data;

        callback = function(result) {
            var downUrl  = "/common/excel_file_down.php?file_dvs=" + result + "&name=" + result;
            $("#file_ifr").attr("src", downUrl);
            loadCashreceiptList.exec();
        };
    } else if (dvs === "complete") {
        url += "proc_public_complete.php";

        complete_data.seqno = seqno;
        data = complete_data;

        callback = function(result) {
            var downUrl  = "/common/excel_file_down.php?file_dvs=" + result + "&name=" + result;
            $("#file_ifr").attr("src", downUrl);
            loadPublicCompleteList.exec();
        };
    } else {
        url += "proc_public_.php";

        except_data.seqno = seqno;
        data = cashreceipt_data;
    }

    console.log(data);

    ajaxCall(url, "text", data, callback);
}

//발행구분 변경
var changePublicDvs = function(val) {
    //세금계산서일때
    if (val == "세금계산서") {
	$("#tax_invoice").show();
	$("#cashreceipt").hide();
    //현금영수증일때
    } else if (val == "현금영수증") {
	$("#tax_invoice").hide();
	$("#cashreceipt").show();
    //미발행일때
    } else {
	$("#tax_invoice").hide();
	$("#cashreceipt").hide();
    }
}

