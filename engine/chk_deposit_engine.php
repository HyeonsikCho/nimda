#! /usr/local/bin/php -f
<?
/**
 * @file chk_deposit_engine.php
 *
 * @brief 입금대기중인 주문항목들 중 선입금액이 충분한 항목에 대해
 * 입금대기에서 접수대기로 상태를 변경시켜준다.
 *
 * @detail 입금대기 상태 주문검색 -> 해당 회원의 선입금 검색
 * -> 선입금액이 결제금액보다 클 경우 1. 주문상태 변경
 * 2. 주문부족금액 수정, 3. 선입금액 수정
 * -> 회원 결제내역 추가
 */

//*************** 프로세스 종료시 처리부분
declare(ticks=1);

function termProc() {
    echo "Kill PROCESS\n";
    @unlink(dirname(__FILE__) . "/temp/chk_deposit.pid");
    exit;
}
pcntl_signal(SIGINT , "termProc");
pcntl_signal(SIGTERM, "termProc");
//*************** 프로세스 종료시 처리부분

//*************** 프로세스 중복실행 방지부분
if (is_file(dirname(__FILE__) . "/temp/chk_deposit.pid") === true) {
    echo "process is running!\r\n";
    exit;
}

$pid_fd = fopen(dirname(__FILE__) . "/temp/chk_deposit.pid", 'w');
fwrite($pid_fd, getmypid());
fclose($pid_fd);
//*************** 프로세스 중복실행 방지부분

include_once(dirname(__FILE__) . '/common/ConnectionPool.php');
include_once(dirname(__FILE__) . '/dao/EngineDAO.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$dao = new EngineDAO();

$err_line = 0;

$state_arr = array();
$state_rs = $dao->selectStateAdmin($conn);

while ($state_rs && !$state_rs->EOF) {
    $fields = $state_rs->fields;
    $state_arr[$fields["erp_state_name"]] = $fields["state_code"];

    $state_rs->MoveNext();
}
unset($state_rs);

while (true) {
    //$conn->debug = 1;
    
    $rs = $dao->selectDepositOrderCommon($conn);

    while($rs && !$rs->EOF) {
        $fields = $rs->fields;

        $order_common_seqno = $fields["order_common_seqno"];
        $member_seqno       = $fields["member_seqno"];
        $cate_sortcode      = $fields["cate_sortcode"];
        $order_num          = $fields["order_num"];

        $pay_price        = intval($fields["pay_price"]);
        $sell_price       = intval($fields["sell_price"]);
        $grade_sale_price = intval($fields["grade_sale_price"]);
        $use_point_price  = intval($fields["use_point_price"]);
        $cp_price         = intval($fields["cp_price"]);
        $order_lack_price = intval($fields["order_lack_price"]);

        // 주문 카테고리 낱장여부 검색
        $flattyp_yn = $dao->selectCateFlattypYn($conn, $cate_sortcode);

        // 회원의 선입금액, 주문부족금액 검색 
        $member_rs = $dao->selectMemberPriceInfo($conn, $member_seqno);

        $prepay_price         = intval($member_rs["prepay_price"]);
        $sum_order_lack_price = intval($member_rs["order_lack_price"]);
        $cumul_sales_price    = intval($member_rs["cumul_sales_price"]);

        //echo "$member_seqno : $pay_price / $prepay_price \n";

        // 주문부족금액보다 선입금액이 큰지 비교
        if ($order_lack_price > $prepay_price) {
            // 선입금액보다 주문 부족금액이 큰 경우
            // 주문 부족금액에서 남은 선입금액을 차감하고
            // 선입금액을 0원으로 처리한다.
            $conn->StartTrans();

            // 전체 주문부족금액 차감, 보유 선입금 0원처리
            $sum_order_lack_price -= $prepay_price;

            $param = array();
            $param["seqno"]             = $member_seqno;
            $param["prepay_price"]      = '0';
            $param["order_lack_price"]  = $sum_order_lack_price;
            $param["cumul_sales_price"] = $cumul_sales_price + $prepay_price;

            //print_r($param);

            $ret = $dao->updateMemberPriceInfo($conn, $param);

            if ($ret === false || $conn->HasFailedTrans() === true) {
                //! 에러부분 처리필요
                $err_line = __LINE__;
                $conn->FailTrans();
                $conn->RollbackTrans();
                break;
            }

            // 해당하는 주문의 주문 부족금액 차감
            $order_lack_price -= $prepay_price;

            unset($param);
            $param["order_lack_price"] = $order_lack_price;
            $param["seqno"] = $order_common_seqno;

            $ret = $dao->updateDepositOrderLackPrice($conn, $param);

            if ($ret === false || $conn->HasFailedTrans() === true) {
                //! 에러부분 처리필요
                $err_line = __LINE__;
                $conn->FailTrans();
                $conn->RollbackTrans();
                break;
            }

            // 회원 결제내역 입력
            unset($param);

            $param["member_seqno"] = $member_seqno;
            $param["order_num"]    = $order_num;
            $param["sell_price"]   = '0';
            $param["sale_price"]   = '0';
            $param["pay_price"]    = $prepay_price;
            $param["exist_prepay"] = $prepay_price;
            $param["prepay_bal"]   = '0';

            //print_r($param);

            $ret = $dao->insertMemberPayHistory($conn, $param);

            if ($ret === false || $conn->HasFailedTrans() === true) {
                //! 에러부분 처리필요
                $err_line = __LINE__;
                $conn->FailTrans();
                $conn->RollbackTrans();
                break;
            }

            // 발행 대상 금액 or 미발행 대상 금액 차감
            unset($param);

            $param["member_seqno"] = $member_seqno;
            $param["price"]        = $prepay_price;

            $ret = updatePublicObjectPrice($conn, $dao, $param);

            if ($ret === false || $conn->HasFailedTrans() === true) {
                //! 에러부분 처리필요
                $err_line = __LINE__;
                $conn->FailTrans();
                $conn->RollbackTrans();
                break;
            }

            $conn->CompleteTrans();

            $rs->MoveNext();
            continue;
        }

        ///////////////////////////////////////////////////////////////////////

        $conn->StartTrans();

        $param = array();

        // 주문상태 변경
        $ret = null;

        $param["seqno"] = $order_common_seqno;
        if ($flattyp_yn === 'Y') {
            $param["state"] = $state_arr["접수대기"];
            $ret = $dao->updateDepositOrderState($conn, $param);
        } else {
            $param["state"] = $state_arr["조판대기"];
            $ret = $dao->updateDepositOrderState($conn, $param);
        }

        if ($ret === false || $conn->HasFailedTrans() === true) {
            //! 에러부분 처리필요
            $err_line = __LINE__;
            $conn->FailTrans();
            $conn->RollbackTrans();
            break;
        }

        unset($param);
        // 주문상세 상태 변경
        $param["seqno"] = $order_common_seqno;
        $param["state"] = $state_arr["조판대기"];

        $param["table"] = "order_detail";
        $ret = $dao->updateDepositOrderDetailState($conn, $param);

        if ($ret === false || $conn->HasFailedTrans() === true) {
            //! 에러부분 처리필요
            $err_line = __LINE__;
            $conn->FailTrans();
            $conn->RollbackTrans();
            break;
        }

        $param["table"] = "order_detail_brochure";
        $ret = $dao->updateDepositOrderDetailState($conn, $param);

        //print_r($param);

        if ($ret === false || $conn->HasFailedTrans() === true) {
            //! 에러부분 처리필요
            $err_line = __LINE__;
            $conn->FailTrans();
            $conn->RollbackTrans();
            break;
        }

        unset($param);
        // 주문부족금액 수정
        $sum_order_lack_price -= $order_lack_price;

        $param["seqno"]             = $member_seqno;
        $param["prepay_price"]      = $prepay_price - $order_lack_price;
        $param["order_lack_price"]  = $sum_order_lack_price;
        $param["cumul_sales_price"] = $cumul_sales_price + $pay_price;

        //print_r($param);

        $ret = $dao->updateMemberPriceInfo($conn, $param);

        if ($ret === false || $conn->HasFailedTrans() === true) {
            //! 에러부분 처리필요
            $err_line = __LINE__;
            $conn->FailTrans();
            $conn->RollbackTrans();
            break;
        }

        // 회원 결제내역 입력
        unset($param);

        $param["member_seqno"] = $member_seqno;
        $param["order_num"]    = $order_num;
        $param["sell_price"]   = $sell_price;
        $param["sale_price"]   = $grade_sale_price +
                                 $use_point_price +
                                 $cp_price;
        $param["pay_price"]    = $order_lack_price;
        $param["exist_prepay"] = $prepay_price;
        $param["prepay_bal"]   = $prepay_price - $order_lack_price;

        //print_r($param);

        $ret = $dao->insertMemberPayHistory($conn, $param);

        if ($ret === false || $conn->HasFailedTrans() === true) {
            //! 에러부분 처리필요
            $err_line = __LINE__;
            $conn->FailTrans();
            $conn->RollbackTrans();
            break;
        }

        // 발행 대상 금액 or 미발행 대상 금액 차감
        unset($param);

        $param["member_seqno"] = $member_seqno;
        $param["price"]        = $order_lack_price;

        $ret = updatePublicObjectPrice($conn, $dao, $param);

        if ($ret === false || $conn->HasFailedTrans() === true) {
            //! 에러부분 처리필요
            $err_line = __LINE__;
            $conn->FailTrans();
            $conn->RollbackTrans();
            break;
        }

        $conn->CompleteTrans();

        $rs->MoveNext();

        sleep(1);
    }

    echo "LOOP OUT\n";

    sleep(60);
   // $conn->debug = 0;
}

/******************************************************************************
 * 함수 영역
 *****************************************************************************/

/**
 * @brief 발행 대상 금액 수정함수
 *
 * @param $conn  = db 커넥션
 * @param $dao   = dao 객체
 * @param $param = 차감할 금액, 회원 일련번호
 *
 * @return 성공시 true, 실패시 false
 */
function updatePublicObjectPrice($conn, $dao, $param) {
    $member_seqno = $param["member_seqno"];
    $price        = $param["price"];

    echo "!!!! $price\n";

    $rs = $dao->selectPublicObjectPrice($conn, $member_seqno);

    $public_price   = intval($rs->fields["public_object_price"]);
    $unissued_price = intval($rs->fields["unissued_object_price"]);

    if ($unissued_price !== 0 && $price <= $unissued_price) {
        // 미발행 대상 금액이 있고 차감금액보다 큰 경우
        $unissued_price -= $price;
    } else if ($unissued_price !== 0 && $price > $unissued_price) {
        // 미발행 대상 금액이 있고 차감금액보다 작은 경우
        $public_price   = $price - $unissued_price;
        $unissued_price = 0;
    } else {
        // 발행 대상 금액에서 차감
        $public_price -= $price;
    }

    unset($param);
    $param["public_object_price"]   = $public_price;
    $param["unissued_object_price"] = $unissued_price;
    $param["member_seqno"]          = $member_seqno;

    $ret = $dao->updatePublicObjectPrice($conn, $param);

    if ($ret === false || $conn->HasFailedTrans() === true) {
        //! 에러부분 처리필요
        return false;
    }

    return true;
}
?>
