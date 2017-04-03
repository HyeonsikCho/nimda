//작업자 메모 선택
var changeMemo = function(val) {

    if (val == "기타") {
        $("#worker_memo").prop("disabled", false);
        $("#worker_memo").val("");
        $("#worker_memo").focus();
    } else {
        $("#worker_memo").prop("disabled", true);
        $("#worker_memo").val(val);
    }
}

//전체 선택
var allCheck = function() {

    //만약 전체선택 체크박스가 체크 된 상태일 경우
    if ($("#allCheck").prop("checked")) {
        $("#list input[type=checkbox]").prop("checked", true);
    } else {
        $("#list input[type=checkbox]").prop("checked", false);
    }
}

//체크박스 선택시 value값 가져오는 함수
var getselectedNo = function() {

    var selectedValue = ""; 
    
    $("#list input[name=chk]:checked").each(function() {
        selectedValue += ","+ $(this).val();		    
    });

    if (selectedValue != "") {
        selectedValue = selectedValue.substring(1);
    }

    return selectedValue;
}

//수주처 변경시
var changeManu = function(el, val) {
 
    var url = "/ajax/produce/process_mng/load_brand_option.php";
    var data = { 
        "el"    : el,
        "seqno" : val
    };

    var callback = function(result) {
        $("#extnl_brand_seqno").html(result);
	    showBgMask();
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

//제조사 변경시
var changeManu = function(val) {
 
    var url = "/ajax/manufacture/common/load_output_brand_option.php";
    var data = { 
        "seqno" : val
    };

    var callback = function(result) {
        $("#extnl_brand_seqno").html(result);
	    showBgMask();
        getWorkPrice();
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

//출력 값 적용 버튼
var getOutput = function() {

    var seqno = $("input[type=radio][name=output_info]:checked").val();
 
    if (checkBlank(seqno)) {
        alert("선택 된 라디오 버튼이 없습니다.");
        return false;
    }

    showMask();
    var url = "/ajax/manufacture/common/load_output_info.php";
    var data = {
        "seqno"       : seqno
    };
    var callback = function(result) {
        var rs = result.split("♪");
        $("#output_name").val(rs[0]);
        $("#output_manu_name").val(rs[1]);
        $("#output_affil").val(rs[2]);
        $("#output_wid_size").val(rs[3]);
        $("#output_vert_size").val(rs[4]);
        $("#output_board").val(rs[5]);
        $("#output_brand_seqno").val(rs[6]);
        $("#output_seqno").val(rs[7]);
    }

    ajaxCall(url, "html", data, callback);
}

//인쇄 값 적용 버튼
var getPrint = function() {

    var seqno = $("input[type=radio][name=print_info]:checked").val();
 
    if (checkBlank(seqno)) {
        alert("선택 된 라디오 버튼이 없습니다.");
        return false;
    }

    showMask();
    var url = "/ajax/manufacture/common/load_print_info.php";
    var data = {
        "seqno"       : seqno
    };
    var callback = function(result) {
        var rs = result.split("♪");
        $("#print_name").val(rs[0]);
        $("#print_manu_name").val(rs[1]);
        $("#print_affil").val(rs[2]);
        $("#print_wid_size").val(rs[3]);
        $("#print_vert_size").val(rs[4]);
        $("#print_brand_seqno").val(rs[5]);
        $("#print_seqno").val(rs[6]);
    }

    ajaxCall(url, "html", data, callback);
}

//종이값 적용
var getPaper = function() {

    var seqno = $("input[type=radio][name=paper_info]:checked").val();

    if (checkBlank(seqno)) {
        alert("선택 된 라디오 버튼이 없습니다.");
        return false;
    }

    showMask();
    var url = "/ajax/manufacture/paper_op_mng/load_paper_info.php";
    var data = {
        "seqno"       : seqno
    };
    var callback = function(result) {
        var rs = result.split("♪");
        $("#paper_name").val(rs[0]);
        $("#paper_dvs").val(rs[1]);
        $("#paper_color").val(rs[2]);
        $("#paper_basisweight").val(rs[3] + rs[4]);
        $("#paper_manu_name").val(rs[5]);
        $("#paper_affil").val(rs[6]);
        $("#paper_op_wid_size").val(rs[7]);
        $("#paper_op_vert_size").val(rs[8]);
        $("#paper_brand_seqno").val(rs[9]);
        $("#paper_seqno").val(rs[10]);
    }

    ajaxCall(url, "html", data, callback);
}

//후공정 값 적용 버튼
var getAfter = function() {

    var seqno = $("input[type=radio][name=after_info]:checked").val();
 
    if (checkBlank(seqno)) {
        alert("선택 된 라디오 버튼이 없습니다.");
        return false;
    }

    showMask();
    var url = "/ajax/produce/typset_list/load_after_info.php";
    var data = {
        "seqno"       : seqno
    };
    var callback = function(result) {
        var rs = result.split("♪");
        $("#after_name").val(rs[0]);
        $("#after_manu_name").val(rs[1]);
        $("#after_extnl_brand_seqno").val(rs[2]);
        $("#after_depth1").val(rs[3]);
        $("#after_depth2").val(rs[4]);
        $("#after_depth3").val(rs[5]);
        $("#after_seqno").val(rs[6]);
    }

    ajaxCall(url, "html", data, callback);
}

/**
 * @brief 종이 절수별 사이즈 변경 
 */
var changeSubpaper = function(val) {

    if (checkBlank($("#paper_affil").val())) {
        $("#paper_subpaper").val("");
        alert("매입업체 종이가 입력 되어야 됩니다.");
        return false;
    }
    showMask();
    var url = "/ajax/produce/typset_list/load_subpaper_size_info.php";
    var data = {
        "affil"    : $("#paper_affil").val(),
        "subpaper" : val
    };
    var callback = function(result) {
        var rs = result.split("♪");
        $("#paper_stor_wid_size").val(rs[0]);
        $("#paper_stor_vert_size").val(rs[1]);
    }

    ajaxCall(url, "html", data, callback);
}

//종이발주 추가
var regiPaperOp = function(el) {

    if (checkBlank($("#paper_op_vert_size").val()) || checkBlank($("#paper_stor_vert_size").val())) {
        alert("사이즈를 입력해주세요");
	    return false;
    }

    if (checkBlank($("#paper_amt").val())) {
        alert("수량을 입력해주세요.");
	    return false;
    }

    showMask();
    var url = "/proc/manufacture/paper_op_mng/regi_paper_op_wait.php";
    var data = {
        "name"                : $("#paper_name").val(),
        "dvs"                 : $("#paper_dvs").val(),
        "color"               : $("#paper_color").val(),
        "basisweight"         : $("#paper_basisweight").val(),
        "op_affil"            : $("#paper_affil").val(),
        "op_size"             : $("#paper_op_wid_size").val() + "*" + $("#paper_op_vert_size").val(),
        "storplace"           : $("#storplace").val(),
        "stor_subpaper"       : $("#paper_subpaper").val(),
        "stor_size"           : $("#paper_stor_wid_size").val() + "*" + $("#paper_stor_vert_size").val(),
        "amt"                 : $("#paper_amt").val(),
        "amt_unit"            : $("#paper_amt_unit").val(),
        "memo"                : $("#paper_memo").val(),
        "brand_seqno"         : $("#paper_brand_seqno").val(),
        "grain"               : $("input[type=radio][name=paper_grain]:checked").val(),
        "paper_op_seqno"      : $("#paper_op_seqno").val(),
        "typset_num"          : $("#typset_num").val() 
    };

    var callback = function(result) {
        if (result == 1) {

            if (el == "pop") {

            } else {
                cndSearch.exec(showPage, 1);
                getPaperOpViewInit();
                tabView("list");
                $(".li2").removeClass("active");
    	        $(".li1").addClass("active");
            }

            alert("종이발주서를 추가 및 수정을 하였습니다.");
        } else {
            alert("종이발주서 추가 및 수정을 실패 하였습니다.");
        }
    }

    ajaxCall(url, "html", data, callback);
}
