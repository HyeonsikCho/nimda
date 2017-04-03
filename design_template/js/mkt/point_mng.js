/*
 *
 * Copyright (c) 2015 Nexmotion, Inc.
 * All rights reserved.
 * 
 * REVISION HISTORY (reverse chronological order)
 *============================================================================
 * 2015/12/14 왕초롱 생성
 *============================================================================
 *
 */
$(document).ready(function() {

    loadPointStats();

});

//포인트 정책 저장
var savePointPolicy = function() {

    var formData = $("#point_form").serialize();

    $.ajax({

        type: "POST",
        data: formData,
        url: "/proc/mkt/point_mng/proc_point_policy.php",
        success: function(result) {
            if(result == "1") {

                alert("수정했습니다.");

            } else {

                alert("실패했습니다.");
            }
        }, 
        error: getAjaxError
    });
}

//포인트 정책 불러오기
var loadPointPolicy = function() {

    showMask();

    $.ajax({

        type: "POST",
        data: {},
        url: "/ajax/mkt/point_mng/load_point_policy.php",
        success: function(result) {
		var tmp = result.split('♪♭@');

        $("#give_join_point").val(tmp[0]);
        $("#order_rate").val(tmp[1]);
        hideMask();

        }, 
        error: getAjaxError
    });

}

//등급별 포인트 통계
var loadPointStats = function() {

    showMask();

    $.ajax({

        type: "POST",
        data: {
                "sell_site"  : $("#sell_site").val(),
                "year"       : $("#year").val(),
                "mon"        : $("#mon").val()
        },
        url: "/ajax/mkt/point_mng/load_point_stats.php",
        success: function(result) {
		    var point = result.split('♪♭‡');

            $("#join_point").val(point[0]);
            $("#order_point").val(point[1]);
            $("#admin_point").val(point[2]);
            $("#grade_point").val(point[3]);
            $("#tot_point").val(point[4]);
            $("#recoup_point").val(point[5]);

	        hideMask();
        }, 
        error: getAjaxError
    });
}

