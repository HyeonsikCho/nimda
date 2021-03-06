#! /usr/local/bin/php -q

<?
/***********************************************************************************
*** 프로 젝트 : CJ송장번호
*** 개발 영역 : CJ송장번호 데이터 삽입
*** 개  발  자 : 김성진
*** 개발 날짜 : 2017.07.27
***********************************************************************************/

/***********************************************************************************
 *** 기본 인클루드
 ***********************************************************************************/

include_once(dirname(__FILE__) . '/common/ConnectionPool.php');
include_once(dirname(__FILE__) . '/dao/EngineDAO.php');
include_once(dirname(__FILE__) . '/common/EngineCommon.php');
include_once(dirname(__FILE__) . '/EngineCommonFunc.php');


/***********************************************************************************
 *** 클래스 선언
 ***********************************************************************************/

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$engineDAO = new EngineDAO();
$util = new EngineCommon();
$commonFunc = new EngineCommonFunc();


/***********************************************************************************
*** 시작
***********************************************************************************/

echo "\n========================================";
echo "\n>>>>>>> 송장 번호 삽입 엔진 시작 <<<<<<<";
echo "\n========================================\n\n";


/***********************************************************************************
 *** 프로세스 시작
 ***********************************************************************************/

$process_start_time = time();


/***********************************************************************************
 *** 송장번호 시작~종료 (변경가능)
 ***********************************************************************************/

$in_start_num = "337084887191";   // 시작 송장번호
$in_end_num = "337087887181";     // 종료 송장번호


/***********************************************************************************
*** 송장번호 계산정의
***********************************************************************************/

$parse_first_num = substr($in_start_num, 0, 2);
$parse_second_num = substr($in_end_num, 0, 2);

$parse_inc_start_num = substr($in_start_num, 2, 9);
$parse_inc_end_num = substr($in_end_num, 2, 9);

$in_tot_count = $parse_inc_end_num - $parse_inc_start_num;
$scs_count = 0;
$min_count = 0;
$num_percent = 0;
$work_percent = 1;

$param['ship_div'] = "01";
$param['status'] = "Y";
$param['regi_date'] = date("Y-m-d H:i:s");


/***********************************************************************************
 *** 송장번호 계산 처리
 ***********************************************************************************/

 if ($parse_first_num != $parse_second_num) {
     echo "*** 시작 송장번호와 종료 송장번호에 앞 2자리가 다릅니다. [송장규칙 오류]\n";
     exit;
 }

 if ($in_last_start_num != $in_last_end_num) {
     echo "*** 시작 송장번호와 종료 송장번호에 뒷 1자리가 다릅니다. [송장규칙 오류]\n";
     exit;
 }


/***********************************************************************************
 *** 시작
 ***********************************************************************************/

echo "*** 송장번호 데이터 총 ".number_format($in_tot_count)."개를 삽입니다.\n\n";
echo "==> 송장번호 데이터 삽입중...[진행률:0%]\n";


/***********************************************************************************
*** 데이터 처리
***********************************************************************************/

for ($i = $parse_inc_start_num; $i <= $parse_inc_end_num; $i++) {
     $mod_num = $i % 7;
     $param['in_number'] = $parse_first_num.$i.$mod_num;

     $chkRes = $engineDAO->getInvoiceDataCheck($conn, $param['in_number']);

     if ($chkRes == "SUCCESS") {
         $nRes = $engineDAO->setInvoiceNumberinsertDataComplete($conn, $param);

         if ($nRes == "SUCCESS") {
             $scs_count++;
         } else if ($nRes == "DBCON_LOST") {
             echo "\n==> 예기치못한 오류로 DB 연결이 종료 되었습니다.\n";
             exit;
         }

     } else if ($chkRes == "DBCON_LOST") {
         echo "\n==> 예기치못한 오류로 DB 연결이 종료 되었습니다.\n";
         exit;
     }

    $num_percent = round((($i - $parse_inc_start_num) / $in_tot_count) * 100);

    if ($num_percent == $work_percent) {
        echo "==> 송장번호 데이터 삽입중...[진행률:".$num_percent."%]\n";
        $work_percent++;
    }
}


/***********************************************************************************
 *** DB 연결 종료
 ***********************************************************************************/

$conn->close();


/***********************************************************************************
*** 처리 결과
***********************************************************************************/

if ($in_tot_count == $scs_count) {
    echo "\n\n*** 정상적으로 데이터 삽입이 완료 되었습니다.\n";
} else {
    $min_count = $in_tot_count - $scs_count;
    echo "\n\n*** 데이터 삽입이 완료 되었습니다. (삽입된 데이터:".number_format($scs_count)."개)";
    echo "\n*** 데이터 삽입이 중복 되었습니다. (중복된 데이터:".number_format($min_count)."개)\n";
}


/***********************************************************************************
 *** 프로세스 종료
 ***********************************************************************************/

$process_end_time = time();


/***********************************************************************************
*** 프로세스 처리 시간
***********************************************************************************/

$process_time = round(($process_end_time - $process_start_time) / 60);
echo "\n\n==> 총 걸린시간 : ".$process_time."분 소요\n";


/***********************************************************************************
 *** 시작
 ***********************************************************************************/

echo "\n\n========================================";
echo "\n>>>>>>> 송장 번호 삽입 엔진 종료 <<<<<<<";
echo "\n========================================\n\n";

?>
