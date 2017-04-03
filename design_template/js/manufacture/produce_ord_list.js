$(document).ready(function() {
    $("#date").datepicker("setDate", new Date());
    getProduceOrdList();
});

//생산지시서 리스트
var getProduceOrdList = function() {

    var url = "/ajax/manufacture/process_ord_list/load_produce_ord_list.php";
    var data = { 
        "ord_dvs"     : $("#ord_dvs").val(),
        "date"        : $("#date").val()
    };

    var callback = function(result) {
        $("#list").html(result);
	if (!result) {
            $("#list").html("<table class=\"table fix_width100f\"><tr><td>데이터가 없습니다.</td></tr></table>");
	}
    };

    showMask();
    ajaxCall(url, "html", data, callback);
}

function pagePrint(Obj) { 

    var PrintPage = window.open("about:blank",Obj.id); 

    PrintPage.document.open(); 
    PrintPage.document.write("<html><head><title></title><style type='text/css'>table th{border:1px solid #333; width:100px; text-align:center; height:20px; font-size:14px;}table td{border:1px solid #333; width:100px; text-align:right; height:20px; font-size:12px; padding-right:5px;}table{border-collapse:collapse; width:100%;}</style>\n</head>\n<body>" + Obj.innerHTML + "\n</body></html>"); 
    PrintPage.document.close(); 

    PrintPage.document.title = "인쇄 생산 계획서"; 
    PrintPage.print(PrintPage.location.reload()); 
}
