/*
 *
 * Copyright (c) 2015 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *=============================================================================
 * 2015/01/06 임종건 생성
 * 2016/04/27 전민재 수정 (파일업로더 추가)
 *=============================================================================
 *
 */
// 작업 파일 업로더 객체
var uploaderObj = "";
// 작업 파일 업로더 확인여부
var fileFlag = 0;

$(document).ready(function() {
    dateSet('0');
    // 팀 별 검색에서 팀구분 값 로드
    loadDeparInfo();
    cndSearch.exec(30, 1);
    
    if ($("#agree_yn").val() == "Y") {
        $("#agree_btn").hide();   
        $("#occur_price").attr("readonly", true);   
        $("#refund_prepay").attr("readonly", true);   
        $("#refund_money").attr("readonly", true);   
        $("#refund_money").attr("readonly", true);   
        $("#cust_burden_price").attr("readonly", true);   
        $("#extnl_etprs").attr("disabled", true);   
        $("#outsource_burden_price").attr("readonly", true);   
    }
    if ($("#order_yn").val() == "Y") {
        $("#order_btn").hide();   
        $("#count").attr("readonly", true);   
        $("#work_file").attr("disabled", true);   
        $("#work_file_upload").attr("disabled", true);   
        $("#file_uplode_btn").hide();
    } else {
        fileUpload();
    }

});

//보여줄 페이지 수
var showPage = "";

/**
 * @brief 선택조건으로 검색 클릭시
 */
var cndSearch = {
    "exec"       : function(listSize, page, pop="") {
        
        var url = "/ajax/business/claim_list/load_claim_list.php";
        var blank = "<tr><td colspan=\"9\">검색 된 내용이 없습니다.</td></tr>";
        var data = {
    	    "search_cnd"  : $("#search_cnd").val(),
       	    "date_from"   : $("#date_from").val(),
    	    "date_to"     : $("#date_to").val(),
    	    "time_from"   : $("#time_from").val(),
    	    "time_to"     : $("#time_to").val()
	};
        var callback = function(result) {
            var rs = result.split("♪");
            if (pop) {
                if (rs[0].trim() == "") {
                    $("#list", opener.document).html(blank);
                    return false;
                }
                $("#list", opener.document).html(rs[0]);
                $("#page", opener.document).html(rs[1]);
                window.close();
            } else {
                if (rs[0].trim() == "") {
                    $("#list").html(blank);
                    return false;
                }
                $("#list").html(rs[0]);
                $("#page").html(rs[1]);
            }
        };

        if (checkBlank($("#office_nick").val())) {
            $("#member_seqno").val("");
        }

        if (checkBlank($("#member_seqno").val()) && !checkBlank($("#office_nick").val())) {
            alert("검색창 팝업을 이용하시고 검색해주세요.");
            $("#office_nick").focus();
            return false;
        }

        data.sell_site     = $("#sell_site").val();
        data.depar_code    = $("#depar_code").val();
        data.member_seqno  = $("#member_seqno").val();
        data.status        = $("#status").val();
        if (pop) {
            data.claim_dvs     = $("#dvs", opener.document).val();
        } else {
            data.claim_dvs     = $("#dvs").val();
        }
        data.listSize      = listSize;
        data.page          = page;

        showMask();
        ajaxCall(url, "html", data, callback);
    }
};

/**
* @brief 검색
*/
var searchClaim = function() {
    cndSearch.exec(30, 1);
}

/**
* @brief 보여줄 페이지 수 설정
*/
var showPageSetting = function(val) {
    showPage = val;
    cndSearch.exec(val, 1);
}

/**
* @brief 페이지 이동
*/
var movePage = function(val) {
    cndSearch.exec(showPage, val);
}

var closePop = function(seqno) {
    var url = "/proc/business/claim_list/modi_claim_order_state.php";
    var data = {
    	"seqno" : seqno
    };
    var callback = function(result) {
        cndSearch.exec(30, 1, "pop");
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

/**
* @brief 클레임관리
*/
var getClaim = {
    "exec"       : function(seqno) {
        $("#seqno").val(seqno);	
        var f = document.frm;
        window.open("", "POP")
        f.action = "/business/pop_claim_info.html";
        f.target = "POP";
        f.method = "POST";
        f.submit();
        return false; 
    }
}

//클레임 처리
var procOrderClaim = {
     "save"       : function(seqno) {
        
        var url = "/proc/business/claim_list/regi_claim_save_info.php";
        var data = {"seqno":seqno};
        var callback = function(result) {
            hideMask();
            if (result == 1) {
                alert("클레임 처리정보를 저장하였습니다.");
            } else {
                alert("클레임 처리정보를 저장실패하였습니다.");
            }
        };

        data.dvs = $("#claim_dvs").val();
        data.dvs_detail = $("#dvs_detail").val();
        data.mng_cont = $("#mng_cont").val();
        showMask();
        ajaxCall(url, "html", data, callback);
    },
     "agree"       : function(seqno) {
        
        var url = "/proc/business/claim_list/regi_claim_agree_info.php";
        var data = {"seqno":seqno};
        var callback = function(result) {
            hideMask();
            if (result == 1) {
                $("#agree_btn").hide();   
                $("#occur_price").attr("readonly", true);   
                $("#refund_prepay").attr("readonly", true);   
                $("#refund_money").attr("readonly", true);   
                $("#refund_money").attr("readonly", true);   
                $("#cust_burden_price").attr("readonly", true);   
                $("#extnl_etprs").attr("disabled", true);   
                $("#outsource_burden_price").attr("readonly", true);   
                alert("합의 하였습니다.");
            } else {
                alert("합의를 실패하였습니다.");
            }
        };
        if (checkBlank($("#occur_price").val())) {
            alert("발생비용이 빈값입니다.");
            $("#occur_price").focus();
            return false;
        }
        if (checkBlank($("#refund_prepay").val())) {
            alert("환불금액 선입금이 빈값입니다.");
            $("#refund_prepay").focus();
            return false;
        }
        if (checkBlank($("#refund_money").val())) {
            alert("환불금액 현금이 빈값입니다.");
            $("#refund_money").focus();
            return false;
        }
        if (checkBlank($("#cust_burden_price").val())) {
            alert("고객부담금이 빈값입니다.");
            $("#cust_burden_price").focus();
            return false;
        }
        if (checkBlank($("#extnl_etprs").val())) {
            alert("클레임처를 선택 해주세요.");
            $("#extnl_etprs").focus();
            return false;
        }
        if (checkBlank($("#outsource_burden_price").val())) {
            alert("클레임처 금액이 빈값입니다.");
            $("#outsource_burden_price").focus();
            return false;
        }

        data.occur_price = $("#occur_price").val();
        data.refund_prepay = $("#refund_prepay").val();
        data.refund_money = $("#refund_money").val();
        data.cust_burden_price = $("#cust_burden_price").val();
        data.extnl_etprs = $("#extnl_etprs").val();
        data.outsource_burden_price = $("#outsource_burden_price").val();

        showMask();
        ajaxCall(url, "html", data, callback);
    },
     "order"       : function(seqno) {
        
        var url = "/proc/business/claim_list/regi_claim_order_info.php";
        var count = $("#count").val();
        var order_file_seqno = $("#work_file_seqno").val();
        /**
         * fileFlag = 0 , 업로드를 전혀 하지 않은 상황 or 업로드된 파일이 삭제된 
         *                상황으로 견적문의 클릭시 OK
         * fileFlag = 1 , 업로드 진행중 상황으로 견적문의 클릭시 NO
         * fileFlag = 2 , 업로드 완료 후 상황으로 견적문의 클릭시 OK 
         */
        if (fileFlag == 1) {
            alert("파일 업로드 진행 중입니다.");
            return;
        }
        
        if (checkBlank(order_file_seqno)) {
            alert("첨부된 파일이 없습니다.");
            return false;
        }
        
        if (checkBlank(count)) {
            alert("건수가 빈값입니다.");
            $("#count").focus();
            return false;
        }
        
        var formData = new FormData();
        formData.append("seqno", seqno);
        formData.append("count", count);
        formData.append("order_file_seqno", order_file_seqno);

        showMask();
        $.ajax({
            type: "POST",
            data: formData,
            url: url,
            dataType : "html",
            processData : false,
            contentType : false,
            success: function(result) {
                hideMask();
                if (result == 1) {
                    $("#order_btn").hide();   
                    $("#count").attr("readonly", true);   
                    alert("주문을 하였습니다.");
                } else {
                    alert("주문을 실패하였습니다.");
                }
            },
            error    : getAjaxError   
        });
    }
}

/**
 * @brief 파일업로드
 */
var fileUpload = function() {
    var runtimes = "html5,flash,silverlight,html4";
    var mimeTypes = [
        {title : "Zip files", extensions: "zip"} 
    ];

    var btnId    = "work_file";
    var listId   = "work_file_list";
    var uploadId = "work_file_upload";
    var delId    = "work_file_del";

    var uploader = new plupload.Uploader({
        url                 : "/proc/business/claim_list/upload_file.php",
        runtimes            : runtimes,
        browse_button       : btnId, // you can pass in id...
        flash_swf_url       : "/design_template/js/uploader/Moxie.swf",
        silverlight_xap_url : "/design_template/js/uploader/Moxie.xap",
        multi_selection     : false,

        filters : {
                max_file_size : "4096mb",
                mime_types    : mimeTypes 
        },
        init : {
            PostInit : function() {
                document.getElementById(listId).innerHTML = '';
            },
            FilesAdded : function(up, files) {
                // 파일을 새로 추가할 경우
                if (up.files.length > 1) {
                    var fileSeqno = $("#" + delId).attr("file_seqno");

                    // 파일이 업로드 된 상태(fileSeqno !== empty)에서
                    // 다른 파일을 새로 업로드 할 경우
                    if (checkBlank(fileSeqno) === false &&
                            confirm("기존 파일은 삭제합니다." + 
                                    "\n계속 하시겠습니까?") === false) {
                        return false;
                    }
                    up.removeFile(up.files[0]);
                    
                    if (checkBlank(fileSeqno) === false) {
                        removeFile(fileSeqno, false);
                    }
                }

                plupload.each(files, function(file) {
                    document.getElementById(listId).innerHTML =
		                    "<div id=\"" + file.id + "\">" +
		                    file.name + " (" +
		                    plupload.formatSize(file.size) +
		                    ")<b></b>" +
                            "&nbsp;" +
                            "<img src=\"/design_template/images/btn_circle_x_red.png\"" +
                            "     id=\"work_file_del\"" +
                            "     file_seqno=\"\"" +
                            "     alt=\"X\"" +
                            "     onclick=\"removeFile('', true);\"" +
                            "     style=\"cursor:pointer;\" /></div>";
                });
            },
            FilesRemoved : function(up, files) {
                document.getElementById(listId).innerHTML = '';
                fileFlag = 0;
                $("#work_file_seqno").val('');
            },
            UploadProgress : function(up, file) {
                fileFlag = 1;
                fileId = file.id;

                document.getElementById(file.id)
                        .getElementsByTagName("b")[0]
                        .innerHTML = "<span>" + file.percent + "%</span>";
            },
            FileUploaded : function(up, file, response) {
                var jsonObj = JSON.parse(response.response);
                var fileSeqno = jsonObj.file_seqno;
                
                $("#" + delId).attr(
                    {"onclick"    : "removeFile('" + fileSeqno + "', true);",
                     "file_seqno" : fileSeqno}
                );

                fileFlag = 2;

                $("#work_file_seqno").val(fileSeqno);
            },
            Error : function(up, err) {
                document.getElementById(listId).innerHTML +=
                        "\nError #" + err.code + ": " + err.message;
            }
        }
    });

    uploader.init();
    uploaderObj = uploader;
}

/**
 * @brief 작업파일 부분 삭제
 *
 * @param seqno = 주문 파일 일련번호
 * @param flag  = uploader.removeFile 여부
 */
var removeFile = function(seqno, flag) {

    if (checkBlank(seqno) === true) {
        var uploader = uploaderObj;
        var files = uploader.files;
        uploader.removeFile(files[0]);

        return false;
    }

    if (flag === true) {
        if (confirm("작업파일을 삭제하시겠습니까?" +
                    "\n삭제된 파일은 복구되지 않습니다.") === false) {
            return false;
        }
    }

    var url = "/proc/business/claim_list/del_claim_file.php";
    var data = {
        "order_file_seqno"  : seqno
    };
    var callback = function(result) {

        if (result == "F") {
            alert("파일 정보 삭제에 실패했습니다.");
            return false;
        }

        if (flag === true) {
            var uploader = uploaderObj;
            var files = uploader.files;
            uploader.removeFile(files[0]);
        }
    };

    showMask();
    ajaxCall(url, "text", data, callback);
};

/**
 * @brief 작업파일 업로드
 */
var uploadFile = function() {
    var uploader = uploaderObj;
    var url = "/proc/business/claim_list/upload_file.php";
    uploader.settings.url = url;
    uploader.start();
};
