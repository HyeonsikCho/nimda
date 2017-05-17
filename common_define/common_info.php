<?
//회원 상태
const MEMBER_STATE = array(
    "1" => "정상",
    "2" => "악성",
    "3" => "접수정지",
    "4" => "출고정지",
    "5" => "거래정지",
    "6" => "기타"
);

//배송판구분
const DLVRBOARD_DVS = array(
    "서울판",
    "지방판"
);

//판구분
const BOARD_DVS = array(
    "아그파 정판"
 //   "중국 일반판"
);

//계열구분
const AFFIL = array(
    "국",
    "46"
 //   "별"
);

//절수
const SUBPAPER = array(
    "전절",
    "2절",
    "4절"
 //   "별절"
);

const PRINT_PURP = array(
    "일반옵셋",
    "UV특수옵셋",
    "디지털인쇄",
    "마스터인쇄",
    "윤전인쇄",
    "그라비아인쇄",
    "실크인쇄",
    "기타인쇄"
);

// 4*6계열 종이 사이즈
const TYPE_46_SIZE = array( 1  => array("WID" => 1090, "VERT" => 768)
        ,2  => array("WID" => 768,  "VERT" => 545)
        ,4  => array("WID" => 545,  "VERT" => 394)
        ,8  => array("WID" => 394,  "VERT" => 272)
        ,16 => array("WID" => 272,  "VERT" => 197)
        ,32 => array("WID" => 197,  "VERT" => 136));

// 국계열 종이 사이즈
const TYPE_GUK_SIZE = array( 1  => array("WID" => 939, "VERT" => 636)
        ,2  => array("WID" => 636, "VERT" => 468)
        ,4  => array("WID" => 468, "VERT" => 318)
        ,8  => array("WID" => 318, "VERT" => 234)
        ,16 => array("WID" => 234, "VERT" => 159)
        ,32 => array("WID" => 159, "VERT" => 117));

// 별계열 종이 사이즈
const TYPE_SPC_SIZE = array(
    array("WID" => "910", "VERT" => "650"),
    array("WID" => "880", "VERT" => "625"),
    array("WID" => "788", "VERT" => "650"),
    array("WID" => "788", "VERT" => "570"),
    array("WID" => "760", "VERT" => "545")
);

// 4*6계열 판 사이즈
const TYPE_46_BOARD_SIZE = array(
    2 => array(
        0 => array(
            "WID"  => 860,
            "VERT" => 645
        ),
        1 => array(
            "WID"  => 760,
            "VERT" => 635
        )
    )
);

// 국계열 판 사이즈
const TYPE_GUK_BOARD_SIZE = array(
    1 => array(
        "WID"  => 1030,
        "VERT" => 800
    ),
    2 => array(
        "WID"  => 670,
        "VERT" => 550
    )
);

//배송유형 코드
const DLVR_TYP = array(
    "01" => "택배",
    "02" => "직배",
    "03" => "화물",
    "04" => "퀵(오토바이)",
    "05" => "퀵(지하철)",
    "06" => "인현동방문",
    "07" => "필동방문"
);

const DLVR_CODE = array(
    "02" => array(
         "서울1호"
        ,"서울2호"
        ,"서울3호"
        ,"서울4호"
        ,"서울5호"
        ,"서울6호"
        ,"서울7호"
        ,"서울8호"
        ,"서울9호"
        ,"서울10호"
        ,"서울11호"

        ,"직배2호"
        ,"직배3호"
        ,"직배4호"
        ,"직배5호"
        ,"직배6호"
        ,"직배7호"
        ,"직배8호"
        ,"직배9호"
    )
);

//배송비 지불 유형
const DLVR_PAY_TYP = array(
    "01" => "선불",
    "02" => "착불"
);

//등급 한글
const GRADE_KO = array(
    "10" => "새싹",
    "9"  => "신규",
    "8"  => "브론즈",
    "7"  => "실버라이트",
    "6"  => "실버",
    "5"  => "골드라이트",
    "4"  => "골드",
    "3"  => "플래티넘",
    "2"  => "VIP",
    "1"  => "VVIP"
);

//등급 영어
const GRADE_EN = array(
    "10" => "WELCOME",
    "9"  => "GENERAL",
    "8"  => "BRONZE",
    "7"  => "SILVERLITE",
    "6"  => "SILVER",
    "5"  => "GOLDLITE",
    "4"  => "GOLD",
    "3"  => "PLATINUM",
    "2"  => "VIP",
    "1"  => "VVIP"
);

const GRADE_IMAGE = array(
    "1"  => "text_membership_grade1",
    "2"  => "text_membership_grade2",
    "3"  => "text_membership_grade3",
    "4"  => "text_membership_grade4",
    "5"  => "text_membership_grade5",
    "6"  => "text_membership_grade6",
    "7"  => "text_membership_grade7",
    "8"  => "text_membership_grade8",
    "9"  => "text_membership_grade9",
    "10" => "text_membership_grade10"
);

//관리자 hash
const ADMIN_FLAG = array("ADMIN");

//메일 도메인
const EMAIL_DOMAIN = array(
        "naver.com",
        "nate.com",
        "gmail.com",
        "hanmail.net",
        "daum.net",
        "hotmail.com");

//전화 앞번호
const TEL_NUM = array(
        "02",
        "031",
        "032",
        "033",
        "041",
        "042",
        "043",
        "044",
        "051",
        "052",
        "053",
        "054",
        "055",
        "061",
        "062",
        "063",
        "064",
        "010",
        "070");

//휴대전화 앞번호
const CEL_NUM = array(
        "010",
        "011",
        "016",
        "017",
        "018");

//운영체제
const OPER_SYS = array(
        "IBM",
        "MAC");

//정산 입력 구분
const INSERT_DVS = array(
        "dvs" => array(
            "충전",
            "차감"   
        ),
        "충전" => array(

            "주문취소",
            "사고처리",
            "별도견적",
            "DC",
            "후가공",
            "기타"
        ),
        "차감" => array(

            "제품구입",
            "제품구입-수기",
            "사고처라",
            "별도견적",
            "재단비",
            "후가공",
            "배송비",
            "금액환불",
            "기타"
        )
);

/*은행정보 ->디비화 함*/
const BANK_INFO = array(
        "신한은행",
        "국민은행",
        "농협",
        "우리은행",
        "기업은행",
        "하나은행",
        "외환은행",
        "경남은행",
        "광주은행",
        "대구은행",
        "부산은행",
        "산업은행",
        "상호저축은행",
        "새마을은행",
        "수협",
        "신협",
        "씨티은행",
        "우체국은행",
        "전북은행",
        "제주은행",
        "SC제일은행",
        "HSBC은행",
        "도이치은행");

//마이페이지 인사
const GREETING = array(
        "1" => "새해을 맞아서 좋은일만 가득하시길 바래요.",
        "2" => "새봄을 맞아서 좋은일만 가득하시길 바래요.",
        "3" => "꽃샘추위에 감기 조심하시고 오늘도 좋은 하루 되세요.",
        "4" => "꽃샘추위에 감기 조심하시고 오늘도 좋은 하루 되세요.",
        "5" => "모두모두 기분좋은일 가득한 봄날이 되셨으면 좋겠네요.",
        "6" => "조록이 싱그러운 초여름입니다. 힘내시라고 초록기운 가득 보냅니다.",
        "7" => "많이 더운 날씨지만 더운 만큼 시원하게 웃을 일이 많은 하루였으면 좋겠습니다..",
        "8" => "계속되는 폭염으로 지치고 힘드시겠지만 오늘 하루는 즐거운 일만 가득하시길 바랄께요.",
        "9" => "여름내내 지쳐있던 몸과 마음까지 상쾌해질 수있는 행복한 가을 되시길 바래요",
        "10" => "청명한 가을 하늘 만큼 늘~기분좋은 일들만 가득한 날들 되시기를 바랍니다.",
        "11" => "날씨가 추워졌습니다. 감기 조심하시구요. 기분 좋은 일 많기를 빕니다",
        "12" => "날씨는 춥치만 마음은 따뜻하시기를 소망합니다. 행복한 겨울 보내세요."
);
?>
