/*
 *
 * Copyright (c) 2016 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *============================================================================
 * 2016/02/15 왕초롱 생성
 *============================================================================
 *
 */

$(document).ready(function() {
    dateSet('0');
    loadIncomeList(1);
});

var page = "1";
var list_num = "30";

//입출금경로 상세 불러오기
var loadPathDetail = function() {

    //입출금경로 미선택시
    if ($("#depo_path").val() == "") {

        $("#depo_path_detail").html("<option value=\"\">선택</option>");
        return false;

    }

    $.ajax({

        type: "POST",
        data: {
		    "depo_path" : $("#depo_path").val()
        },
        url: "/ajax/calcul_mng/income_data/load_path_detail.php",
        success: function(result) {
	    
	    	$("#depo_path_detail").html(result);
        }, 
        error: getAjaxError
    });
}

//수입자료 리스트 불러오기
var loadIncomeList = function(pg) {

    //회원명 인풋창 비었을경우  회원일련번호 초기화
    if (checkBlank($("#office_nick").val())) {
        $("#member_seqno").val("");
    }

	if (checkBlank($("#member_seqno").val()) && !checkBlank($("#office_nick").val())) {
            alert("검색창 팝업을 이용하시고 검색해주세요.");
	    $("#office_nick").focus();
	    return false;
	}

    $("#buf_date_from").val($("#date_from").val());
    $("#buf_date_to").val($("#date_to").val());

    showMask();
    var formData = new FormData($("#income_form")[0]);
        formData.append("page", pg);
        formData.append("list_num", list_num);

    $.ajax({

        type: "POST",
        data: formData,
	    processData : false,
	    contentType : false,
        url: "/ajax/calcul_mng/income_data/load_income_list.php",
        success: function(result) {
		var list = result.split('♪♭@');
            if($.trim(list[0]) == "") {

                $("#income_list").html("<tr><td colspan='6'>"
                  +  "검색된 내용이 없습니다.</td></tr>"); 
                $("#cash_sum").html("0원");
                $("#bankbook_sum").html("0원");
                $("#card_sum").html("0원");
                $("#etc_sum").html("0원");

                $("#result_price").html("0개");
                $("#depo_sum").html("0원");
                $("#sales_sum").html("0원");
                $("#adjust_price").html("0원");
	        } else {

                $("#income_list").html(list[0]);
                $("#income_page").html(list[1]);
                $('select[name=list_set]').val(list_num);
                $("#cash_sum").html(list[2]);
                $("#bankbook_sum").html(list[3]);
                $("#card_sum").html(list[4]);
                $("#etc_sum").html(list[5]);

                $("#result_price").html(list[6]);
                $("#depo_sum").html(list[7]);
                $("#sales_sum").html(list[8]);
                $("#adjust_price").html(list[9]);

	        }
	    hideMask();
        }, 
        error: getAjaxError
    });
}

//입금내역 상세보기
var showWithdraw = function(member_seqno, deal_date) {
    var url = "/ajax/calcul_mng/income_data/load_withdraw_popup.php";
    var data = {
        "member_seqno" : member_seqno,
        "from_date" : $("#buf_date_from").val(),
        "to_date" : $("#buf_date_to").val()
    };

    var callback = function(result) {
        openRegiPopup(result, "1010");
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

//보여 주는 페이지 갯수 설정
var showPageSetting = function(val) {

    list_num = val;
    loadIncomeList(1);
} 

//선택 조건으로 검색(페이징 클릭)
var searchResult = function(pg) {

    page = pg;
    loadIncomeList(page);
}

