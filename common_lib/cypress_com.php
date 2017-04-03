<?
 /***********************************************************************************
 *** 프로 젝트 : CyPress 1
 *** 개발 영역 : Common Lib
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.06.15
 ***********************************************************************************/


 class CLS_Common {


	/***********************************************************************************
	 *** tag 문자셋 변환
	 ***********************************************************************************/

	 function HtmlTagReplace($tag) {
		$tag = str_replace ('\'', '&#039;', $tag);
		$tag = str_replace ('"', '&quot;', $tag);
		$tag = str_replace ('<', '&lt;', $tag);
		$tag = str_replace ('>', '&gt;', $tag);

		return $tag;
	 }


	 /***********************************************************************************
	 *** html 문자셋 변환
	 ***********************************************************************************/

	 function TagHtmlReplace($tag) {
		$tag = str_replace ('&#039;', '\'', $tag);
		$tag = str_replace ('&quot;', '"', $tag);
		$tag = str_replace ('&lt;', '<', $tag);
		$tag = str_replace ('&gt;', '>', $tag);

		return $tag;
	 }



	 /***********************************************************************************
	 *** 문자열 자르기
	 ***********************************************************************************/

	 function CutString($str, $num) {
		$len = strlen($str);
		if ($len <= $num) return $str;

		$strtmp = "";
		$ishan1 = 0;
		$ishan2 = 0;
		$strlength = $len;

		for ($i = 0; $i < $len; $i++) {
			 if(preg_match("/[xA1-xFE][xA1-xFE]/", $str[$i])) $strlength++;
		}

		for ($i = 0; $i < $strlength; $i++) {
			if ($ishan1 == 1) $ishan2 = 1;

			if (ord($str[$i]) > 127 && $ishan1 == 0) {
				$ishan2 = 0;
				$ishan1 = 1;
			}

			if ($ishan2 == 1) $ishan1 = 0;

			if (($i + 1) == $num) {
				if($ishan2 != 1) break;

				$strtmp .= $str[$i]; break;
			}

			$strtmp .= $str[$i];
		}

		return trim($strtmp)."...";
	 }


	 /***********************************************************************************
	 *** 문자열 치환
	 ***********************************************************************************/

	 function ASlash($str) {
		return trim(addslashes($str));
	 }

	 function SSlash($str) {
		 return trim(stripslashes($str));
	 }

	 function STags($str) {
		 return trim(strip_tags($str));
	 }

	 function STagsAddSlash($str) {
		 return trim(strip_tags(addslashes($str)));
     }

	 function STagsStripSlash($str) {
		 return trim(strip_tags(stripslashes($str)));
	 }

	 function STagsStripSlashReplace($str) {
		 return eregi_replace("<br />", "", trim(nl2br(strip_tags(stripslashes($str)))));
	 }

	 function STagsStripSlashBr($str) {
		 return trim(nl2br(strip_tags(stripslashes($str))));
	 }

	 function StripSlashBr($str) {
		 return trim(nl2br(stripslashes($str)));
     }


	 /***********************************************************************************
	 *** 날짜 포멧
	 ***********************************************************************************/

	 function DateType($date, $type, $symbol = "") {
		 if (intVal($date) > 0) {
			 $date = date("YmdHis", intVal($date));

			 switch($type) {
				 case "date" : $year = substr($date, 0, 4);
							   $month = substr($date, 4, 2);
							   $day = substr($date, 6, 2);
							   $get_date = $year.$symbol.$month.$symbol.$day;
							   break;
				 case "time" : $year = substr($date, 0, 4);
							   $month = substr($date, 4, 2);
							   $day = substr($date, 6, 2);
							   $hour = substr($date, 8, 2);
							   $min = substr($date,	10, 2);
							   $sec = substr($date, 12, 2);
							   $get_date = $year.$symbol.$month.$symbol.$day." ".$hour.":".$min.":".$sec;
							   break;
				 default : $get_date = $date; break;
			 }
		 } else {
			 $get_date = "";
		 }

		return $get_date;
	}


	/***********************************************************************************
	*** 램덤 숫자
	***********************************************************************************/

	function Random($min, $max) {
		srand((double) microtime() * 1000000);
		$rand = rand($min, $max);

		return $rand;
	}


	/***********************************************************************************
	 *** 날짜 포멧 MS to Time   2011-02-22T07:40:00.000Z
	 ***********************************************************************************/

	 function MstDate2TimeDate($date) {
		 $date = explode("T", substr($date, 0, strlen($date) - 5));
		 $getTime = strtotime($date[0]." ".$date[1]);

		return $getTime;
	}


	/***********************************************************************************
	 *** 날짜 포멧 MS to Time   May 30 2007 03:34:48:763PM
	 ***********************************************************************************/

	 function MsDate2TimeDate($date) {
		 $date = substr($date, 0, strlen($date) - 6);
		 $getTime = strtotime($date);

		return $getTime;
	}


	/***********************************************************************************
	 *** 변수값 체크
	 ***********************************************************************************/

	 function isValCheck($value, $msg) {
		 if (!$value)  {
			 echo("$msg 이(가) 존재하지 않습니다.");
			 exit;
		 }
	}


	/******************************************************************
    ***  접근 방법 체크
    ******************************************************************/

	function hostCheck() {
		if (!preg_match("/".$_SERVER['HTTP_HOST']."/i", $_SERVER['HTTP_REFERER'])) {
			echo ("올바른 접근방법이 아닙니다.");
			exit;
		 }
	}


	/***********************************************************************************
	 *** 면수
	 ***********************************************************************************/

	 function getColorSideCode($cName) {
		if ($cName == "단면") {
			$side = 1;
		} else if($cName == "양면") {
			$side = 2;
		} else {
			$side = 3;
		}

		return $side;
	}


	/***********************************************************************************
	 *** 진행코드
	 ***********************************************************************************/

	 function getPocessGoCode($osCode) {
		if ($osCode == "2120") {
			$nRes = 0;
		} else {
			$nRes = 1;
		}

		return $nRes;
	}


	 /***********************************************************************************
	  *** 면
	  ***********************************************************************************/

	 function getSideName($scData) {
		 if ($scData == "단면") {
			 $nRes = 1;
		 } else if($scData == "양면") {
			 $nRes = 2;
		 } else {
			 $nRes = 3;
		 }

		 return $nRes;
	 }



	/***********************************************************************************
  *** 조판구분
  ***********************************************************************************/

	 function getPenDivsion($pCode) {
		 $nRes = substr($pCode, 0, 1);

		 return $nRes;
	}


	/***********************************************************************************
	 *** PDF 경로
	***********************************************************************************/

	 function getPDFPath($ordRes) {
		 $nRes = "/(".$ordRes['od_prdt_name'].")".$ordRes['od_item_name']."/".$ordRes['od_kind_name']."/".$ordRes['od_tot_tmpt'];
		 $fChar = substr($ordRes['od_amt'], 0, 1);

		 if ($ordRes['od_item_name'] == "고급명함") {
			 if ($ordRes['od_kind_name'] == "휘라레명함" || $ordRes['od_kind_name'] == "반누보명함") {
                 if ($ordRes['od_amt'] == 300) {
				     $nRes = $nRes."_300/";
                 } else {
				     $nRes = $nRes."_200/";
                 }
			 } else if ($ordRes['od_kind_name'] == "랑데뷰명함") {
                 if ($ordRes['od_amt'] == 100 || $ordRes['od_amt'] == 500) {
				     $nRes = $nRes."_100/";
                 } else {
				     $nRes = $nRes."_200/";
                 }
			 } else {
				 $nRes = $nRes."_200/";
			 }
		 } else if ($ordRes['od_item_name'] == "일반명함") {

             if ($ordRes['od_kind_name'] == "고품격 코팅명함") {
                 $ordRes['od_kind_name'] = "코팅명함";
             } else if ($ordRes['od_kind_name'] == "고품격 무코팅명함") {
                 $ordRes['od_kind_name'] = "무코팅명함";
             }

             $nRes = "";
		     $nRes = "/(".$ordRes['od_prdt_name'].")".$ordRes['od_item_name']."/".$ordRes['od_kind_name']."/".$ordRes['od_tot_tmpt'];

             if ($ordRes['od_amt'] < 1000) {
                 if ($ordRes['od_amt'] == 200) {
                     $nRes = $nRes . "_200/";
                 } else {
                     $nRes = $nRes . "_500/";
                 }

             } else if ($ordRes['od_amt'] >= 1000 && $ordRes['od_amt'] < 20000) {
                 if ($ordRes['od_amt'] == 10000) {
                     $nRes = $nRes . "_10000/";
                 } else if ($ordRes['od_amt'] % 5000 == 0) {
                     $nRes = $nRes . "_5000/";
                 } else if ($ordRes['od_amt'] % 2500 == 0) {
                     $nRes = $nRes . "_2500/";
                 } else if ($ordRes['od_amt'] % 2000 == 0) {
                     $nRes = $nRes . "_2000/";
                 } else if ($ordRes['od_amt'] % 1000 == 0) {
                     $nRes = $nRes . "_1000/";
                 } else {
                     $nRes = $nRes . "_500/";
                 }

             } else if ($ordRes['od_amt'] >= 20000) {
                 $nRes = $nRes . "_10000/";
             } 

		 } else if ($ordRes['od_item_name'] == "합판 칼라봉투(일반형)" || $ordRes['od_item_name'] == "독판 칼라봉투(주문형)") {

		     $nRes = "/(".$ordRes['od_prdt_name'].")".$ordRes['od_item_name']."/".$ordRes['od_paper_name']."/".$ordRes['od_tot_tmpt']."_".$ordRes['od_amt']."/";

		 } else if ($ordRes['od_item_name'] == "합판전단(일반형)") {

             $paper_name_arr = explode(" ", $ordRes['od_paper_name']);
             $arr_cnt = count($paper_name_arr) - 1;

             $paper_basisweight = $paper_name_arr[$arr_cnt];

             $stan_name = $ordRes['od_stan_name'];

             $work = "";
             if ($stan_name == "A2") {
                 $work = "상업인쇄팀";
             } else if ($stan_name == "4절") {
                 $work = "상업인쇄팀";
             } else if ($stan_name == "비규격") {
                 $work = "상업인쇄팀";
             } else {
                 $work = "출력팀";
             }

             $affil = "";
             if (strpos($stan_name, "절") !== false) {
                 $affil = "46";
             } else {
                 $affil = "국";
             }

             if ($paper_basisweight == "90g") {
                 $nRes = "";
		         $nRes = "/(".$ordRes['od_prdt_name'].")".$ordRes['od_item_name']."/".$ordRes['od_kind_name']."_".$paper_basisweight."/".$work."_".$affil."/".$ordRes['od_tot_tmpt']."/";
             } else {
                 $work = "상업인쇄팀";

                 $nRes = "";
		         $nRes = "/(".$ordRes['od_prdt_name'].")".$ordRes['od_item_name']."/".$ordRes['od_kind_name']."_".$paper_basisweight."/".$work."_".$affil."/".$ordRes['od_tot_tmpt']."_".$ordRes['od_amt']."/";
             }
		 } else if ($ordRes['od_item_name'] == "초소량인쇄" || $ordRes['od_item_name'] == "독판전단(주문형)") {

             $paper_name_arr = explode(" ", $ordRes['od_paper_name']);
             $arr_cnt = count($paper_name_arr) - 1;

             $paper_basisweight = $paper_name_arr[$arr_cnt];

             $stan_name = $ordRes['od_stan_name'];

             $affil = "";
             if (strpos($stan_name, "절") !== false) {
                 $affil = "46";
             } else {
                 $affil = "국";
             }

             $work = "상업인쇄팀";

             $nRes = "";
		     $nRes = "/(".$ordRes['od_prdt_name'].")".$ordRes['od_item_name']."/".$ordRes['od_kind_name']."_".$paper_basisweight."/".$work."_".$affil."/".$ordRes['od_tot_tmpt']."_".$ordRes['od_amt']."/";
		 } else {
			 $nRes = $nRes."_".$ordRes['od_amt']."/";
		 }

		 return $nRes;
	 }


	 function getDivPath($nRes, $fChar) {
		 if ($fChar % 2 == 0) {
			 $nRes .= $nRes. "_200/";
		 } else {
			 $nRes = $nRes."_100/";
		 }

		 return $nRes;
	 }


	 function getPDFPath2($ordRes) {
		 $nRes = "/";
		 $nRes .= $ordRes['od_prdt_name'];
		 $nRes .= "/";
		 $nRes .= $ordRes['od_item_name'];
		 $nRes .= "/";
		 $nRes .= $ordRes['od_kind_name'];
		 $nRes .= "/";

		 return $nRes;
	 }


	 /***********************************************************************************
	  *** 미리보기 경로
	  ***********************************************************************************/

	 function getPreviewPath() {
		 $nRes = "/".date("Y")."/".date("m")."/".date("d")."/";

		 return $nRes;
	 }


	 function getDateToFormat($date) {
		 $nRes = preg_replace("/ /", "-", $date);
		 $nRes = preg_replace("/:/", "-", $nRes);

		 return $nRes;
	 }


	 function getAstricsFormat($ast) {
		 $nData = explode("*", $ast);
		 $nRes = $nData[0].$nData[1];

		 return $nRes;
	 }


	 /***********************************************************************************
	  *** 규격 비규격
	  ***********************************************************************************/

	 function getStandardNonStandardFormat($odWidth, $odHeight) {
		 // 명함
		 if ($odWidth == "86" && $odHeight == "52") {  		   // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "50") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "86" && $odHeight == "54") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "85" && $odHeight == "55") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "91" && $odHeight == "55") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "89" && $odHeight == "51") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "55") {  // 규격 (스티커)
			 $nRes = 1;

		// 스티커
		 } else if ($odWidth == "60" && $odHeight == "40") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "70" && $odHeight == "40") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "80" && $odHeight == "50") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "80" && $odHeight == "50") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "60") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "70") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "80") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "90") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "100") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "110") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "120") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "130") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "140") {  // 규격
			 $nRes = 1;
		 } else if ($odWidth == "90" && $odHeight == "150") {  // 규격
			 $nRes = 1;

		 // 기타
		 }	else {												// 비규격
			 $nRes = 0;
		 }

		 return $nRes;
	 }


	 /***********************************************************************************
	  *** 컬러 코드
	  ***********************************************************************************/

	 function getColorCodeFormat($Color) {
		 if ($Color == "GREEN") {           // 녹색
			 $nRes = 1;
		 //} else if ($Color == "YELLOW") {  // 노랑
			 //$nRes = 2;
		 } else if ($Color == "RED") {   	// 빨강
			 $nRes = 3;
		 } else if ($Color == "BLUE") {   	// 파랑
			 $nRes = 4;
		 } else if ($Color == "ORANGE") {  // 주황
			 $nRes = 5;
		 }	else {						    // 검정
			 $nRes = 0;
		 }

		 return $nRes;
	 }

 }
?>
