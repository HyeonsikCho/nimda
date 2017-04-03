<?
/**NAMING ARGUEMENT**/

//LIST일 경우 이름 뒤에 _list를 붙인다.
//등록일 경우 이름 뒤에 _regi를 붙인다.
//상세수정일 경우 이름 뒤에 _modi를 붙인다.
//검색일 경우 이름 뒤에 _search를 붙인다.

//상단 메뉴
const TOP_MENU_ARR = array(
        "business" => "영업",
        "manufacture" => "생산",
        "member" => "회원",
        "mkt" => "마케팅",
        "calcul_mng" => "정산관리",
        "basic_mng" => "기초관리",
        "dataproc_mng" => "전산관리");

/*
                "mbo_mng"           => "MBO관리",
                "business_list"     => "영업리스트"1),

            // MBO관리
            "mbo_mng" => array(
                "mbo_common_report" => "MBO통합보고서",
                "mbo_mng"           => "MBO관리",
                "sales_mbo"         => "매출MBO",
                "oa_mbo"            => "미수금MBO"),
            // 영업리스트
            "business_list" => array(
                "sales_prog_list"   => "매출추이리스트")),

        "mbo_mng"        => "fa fa-money", // MBO관리
        "business_list"  => "fa fa-money", // 영업리스트
*/

//사이드 메뉴
const LEFT_MENU_ARR =  array(
        //영업
        "business" => array(
            //중메뉴
            "sub" => array(
                "order_mng"         => "주문관리",
                "esti_mng"          => "견적관리",
                "claim_mng"         => "클레임관리",
                "oto_inq_mng"       => "1:1문의관리"),
            // 주문관리
            "order_mng" => array(
                "order_common_mng"   => "주문통합관리",
                "order_process_view" => "공정확인리스트(주문별)",
                "order_hand_regi"    => "주문수기등록"),
            // 견적관리
            "esti_mng" => array(
                "esti_list"         => "견적리스트"),
            // 클레임관리
            "claim_mng" => array(
                "claim_list"        => "클레임리스트"),
            // 1:1문의관리
            "oto_inq_mng" => array(
                "oto_inq_list"      => "1:1문의리스트")),

        //new 생산
        "manufacture" => array(
            //중메뉴
            "sub" => array(
                //"prdc_valid"       => "생산확인",
                "typset_mng"       => "조판관리",
                "output_mng"       => "출력관리",
                "print_mng"        => "인쇄관리",
                "after_mng"        => "후공정관리",
                /*
                "stor_mng"         => "입고관리",
                "release_mng"      => "출고관리",
				"delivery_mng"     => "배송관리",
                */
				"cooperator_mng"   => "협력업체품목관리",
                "item_mng"         => "자재관리"),
            /*
            //생산확인
            "prdc_valid" => array(
                "order_process_view" => "공정확인리스트(주문별)"),
            */
            //조판관리
            "typset_mng" => array(
                 /*
                 "manual_sheet_typset"    => "수동조판(낱장형)",
                 "manual_brochure_typset" => "수동조판(책자형)",
                 */
                 "typset_format"          => "자동판형설정",
                 "print_produce_ord"      => "인쇄생산지시",
                 "process_view"           => "공정확인리스트(조판별)",
                 "produce_ord_list"       => "생산지시서리스트"),
            //출력관리
            "output_mng" => array(
                "output_list"    => "출력리스트"),
            //인쇄관리
            "print_mng" => array(
                "paper_stor"         => "종이입고",
                "print_produce_exc"  => "인쇄생산이행",
                "print_list"         => "인쇄리스트"),
            //후공정관리
            "after_mng" => array(
                "basic_after_list" => "조판별-후공정리스트",
                "after_list"       => "주문별-후공정리스트"),
            /*
            //입고관리
            "stor_mng" => array(
                "stor_list"       => "입고리스트",
				"stor_process"       => "입고작업"),
            //출고관리
            "release_mng" => array(
                "release_list"    => "출고리스트",
				"release_process"    => "출고작업"),
            //배송관리
            "delivery_mng" => array(
                "delivery_list"   => "배송리스트",
				"delivery_work"   => "배송작업"),
            */
            //협력업체품목관리
            "cooperator_mng" => array(
                "cooperator_list" => "협력품목생산리스트"),
            //자재관리
            "item_mng" => array(
                "paper_op_mng"      => "종이발주관리",
                "paper_ord_print"   => "종이발주서인쇄",
                "paper_stock_mng"   => "종이재고관리")),
//                "paper_stock_print" => "종이재고인쇄")),

        //회원
        "member" => array(
            //중메뉴
            "sub" => array(
                "member_mng"         => "회원관리"),
            //회원관리
            "member_mng" => array(
                "member_common_list" => "회원통합리스트",
                "quiescence_list"    => "휴면대상회원리스트",
                "reduce_list"        => "정리회원리스트")),
//                "dlvr_friend_list"   => "배송친구리스트",

        //마케팅
        "mkt" => array(
            //중메뉴
            "sub" => array(
                "mkt_mng"            => "마케팅관리"),
            //등급관리
            "mkt_mng" => array(
                "grade_mng"          => "등급관리",
                "point_mng"          => "포인트관리",
                "cp_mng"             => "쿠폰관리",
                "event_mng"          => "이벤트관리",
                "mkt_aprvl_mng"      => "마케팅승인관리")),

        //정산관리
        "calcul_mng" => array(
            //중메뉴
            "sub" => array(
                "cashbook"           => "금전출납부",
                "settle"             => "결산",
                "tab"                => "계산서",
                "virt_ba_mng"        => "가상계좌관리",
                "set"                => "설정"),
            //금전출납부
            "cashbook" => array(
                "cashbook_regi"      => "금전출납등록",
                "cashbook_list"      => "금전출납리스트"),
            //결산
            "settle" => array(
                "income_data"        => "결산자료",
                "sales_data"         => "매출자료",
                "adjust_data"        => "조정자료",
                "adjust_regi"        => "조정등록",
                "day_close"          => "일마감"),
            //계산서
            "tab" => array(
                "sales_tab"          => "매출계산서",
                "pur_tab"            => "매입계산서"),
            //가상계좌관리
            "virt_ba_mng" => array(
                "virt_ba_list"       => "가상계좌리스트"),
            //설정
            "set" => array(
                "basic_data"         => "기초데이터")),

        //기초관리
        "basic_mng" => array(
            //중메뉴
            "sub" => array(
                "prdt_price_mng"     => "상품가격관리",
                "cate_mng"           => "카테고리관리",
                "prdt_mng"           => "상품관리",
                "pur_etprs_mng"      => "협력업체관리",
                "prdc_prdt_mng"      => "생산품목관리"),
            //상품가격관리
            "prdt_price_mng" => array(
                "prdt_price_list"    => "상품가격리스트",
                "after_price_list"   => "후공정가격리스트",
                "opt_price_list"     => "옵션가격리스트",
                "calcul_price_list"  => "계산형가격리스트"),
            //카테고리관리
            "cate_mng" => array(
                "cate_list"          => "카테고리리스트"),
//              "basic_pro_bus_mng"  => "기본생산업체관리"),
            //상품관리
            "prdt_mng" => array(
                "prdt_basic_regi"    => "상품기초등록",
                "prdt_item_regi"     => "상품구성아이템등록"),
            //협력업체관리
            "pur_etprs_mng" => array(
                "pur_etprs_list"     => "협력업체리스트"),
            //생산품목관리
            "prdc_prdt_mng" => array(
                "typset_mng"         => "조판관리",
                "paper_mng"          => "종이관리",
                "output_mng"         => "출력관리",
                "print_mng"          => "인쇄관리",
                "after_mng"          => "후공정관리",
                "opt_mng"            => "옵션관리")),

        //전산관리
        "dataproc_mng" => array(
            //중메뉴
            "sub" =>        array(
                "set"                => "설정",
                "organ_mng"          => "조직관리",
                "bulletin_mng"       => "게시판관리"),
            //설정
            "set" =>         array(
                "prdt_info_mng"      => "상품정보관리",
                "template_mng"       => "상품템플릿관리",
                "paper_info_mng"     => "재질보기관리",
                "state_mng"          => "상태관리",
                "mtra_dscr_mng"      => "자재설명관리"),
            //조직관리
            "organ_mng" =>   array(
                "auth_mng"           => "권한관리",
                "organ_mng"          => "조직관리",
                "passwd_change"      => "패스워드변경"),
            //게시판관리
            "bulletin_mng" => array(
                "popup_mng"          => "팝업관리",
                "notice_mng"         => "공지관리",
				"mbanner_mng"        => "메인배너관리")));

//===================================================================================================//
//==========================================메뉴 이미지 class========================================//
//===================================================================================================//

//참조 : http://fontawesome.io/icons/

//1DEPTH의 이미지
const LEFT_MENU_CLASS_ARR = array(

        //영업
        "order_mng"        => "fa fa-shopping-cart",  // 주문관리
        "esti_mng"         => "fa fa-calculator",     // 견적관리
        "claim_mng"        => "fa fa-phone",          // 클레임관리
        "oto_inq_mng"      => "fa fa-user",           // 1:1문의관리

        //생산
        //"receipt_mng"      => "fa fa-user",         //접수관리
        "prdc_valid"       => "fa fa-user",           //접수관리
        "typset_mng"       => "fa fa-pencil-square-o",//조판관리
        "output_mng"       => "fa fa-photo",          //출력관리
        "print_mng"        => "fa fa-print",          //인쇄관리
        "after_mng"        => "fa fa-edit",           //후공정관리
        "stor_mng"         => "fa fa-inbox",          //입고관리
        "release_mng"      => "fa fa-inbox",          //출고관리
		"delivery_mng"     => "fa fa-truck",          //배송관리
		"cooperator_mng"   => "fa fa-building-o",     //협력업체품목관리
        "produce_plan"     => "fa fa-cog",            //생산계획
        "item_mng"         => "fa fa-inbox",          //자재관리
        "produce_result"   => "fa fa-tags",           //생산결과

        //회원
        "member_mng"       => "fa fa-users",          //회원관리

        //마케팅
        "mkt_mng"          => "fa fa-search-plus",    //마케팅관리

        //정산관리
        "cashbook"         => "fa fa-money",          //금전출납부
        "settle"           => "fa fa-bank",           //결산
        "tab"              => "fa fa-tags",           //계산서
        "virt_ba_mng"      => "fa fa-inbox",          //가상계좌관리
        "set"              => "fa fa-cog",            //설정

        //기초관리
        "prdt_price_mng"   => "fa fa-krw",            //상품가격관리
        "cate_mng"         => "fa fa-gear",           //카테고리관리
        "prdt_mng"         => "fa fa-inbox",          //상품관리
        "pur_etprs_mng"    => "fa fa-building-o",     //협력업체관리
        "prdc_prdt_mng"    => "fa fa-tags",           //생산품목관리

        //전산관리
        "set"              => "fa fa-cog",            //설정
        "organ_mng"        => "fa fa-sitemap",        //조직관리
        "bulletin_mng"     => "fa fa-tasks"           //게시판관리
);
?>
