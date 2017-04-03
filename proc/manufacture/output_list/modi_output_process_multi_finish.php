<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/output_mng/OutputListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new OutputListDAO();
$util = new CommonUtil();

$conn->StartTrans();
$check = 1;
$seqno_arr = explode(",", $fb->form("seqno"));

foreach ($seqno_arr as $key => $value) {

    $output_op_seqno = $value;

    //출력 발주서 검색
    $param = array();
    $param["table"] = "output_op";
    $param["col"] = "typset_num, flattyp_dvs, state";
    $param["where"]["output_op_seqno"] = $output_op_seqno;

    $sel_rs = $dao->selectData($conn, $param);

    $process_yn_state = $sel_rs->fields["state"];
    $process_yn = "Y";

    $param = array();
    $param["table"] = "produce_process_flow";
    $param["col"] = "print_yn";
    $param["where"]["typset_num"] = $sel_rs->fields["typset_num"];

    $print_yn = $dao->selectData($conn, $param)->fields["print_yn"];

    //print_yn 이 Y 일경우 print_op 의 state 변경
    //            N 일경우 after_op 의 state 변경
    if ($print_yn == "Y") {
        $state = $util->status2statusCode("인쇄대기");

        if ($process_yn_state == $state) {
            $process_yn = "N";
        }

        if ($process_yn == "Y") {
            $param = array();
            $param["table"] = "print_op";
            $param["col"]["state"] = $state;
            $param["prk"] = "typset_num";
            $param["prkVal"] = $sel_rs->fields["typset_num"];

            $rs = $dao->updateData($conn, $param);

            if (!$rs) {
                $check = 0;
            }
        }

    } else if ($print_yn == "N") {

        if ($process_yn_state == $state) {
            $process_yn = "N";
        }

        //후공정은 하나의 주문이 출력, 인쇄가 완료 되면 후공정 대기로 변함
        $state = $util->status2statusCode("조판후공정대기");
    }

    if ($process_yn == "Y") {

        $param = array();
        $param["flattyp_dvs"] = $sel_rs->fields["flattyp_dvs"];
        $param["typset_num"] = $sel_rs->fields["typset_num"];
        $param["state"] = $state;

        $check = $util->changeOrderState($conn, $dao, $param);

        //변경된 상태값 적용
        $param = array();
        $param["table"] = "output_op";
        $param["col"]["state"] = $state;
        $param["prk"] = "output_op_seqno";
        $param["prkVal"] = $output_op_seqno;

        $rs = $dao->updateData($conn, $param);

        if (!$rs) {
            $check = 0;
        }

        $param = array();
        $param["table"] = "output_work_report";
        $param["col"] = "output_work_report_seqno";
        $param["where"]["output_op_seqno"] = $output_op_seqno;

        $rs = $dao->selectData($conn, $param);

        if (!$rs || $rs->EOF) {

            $param = array();
            $param["table"] = "output_work_report";
            $param["col"]["worker_memo"] = "다중선택 완료처리";
            $param["col"]["extnl_etprs_seqno"] = $fb->session("extnl_etprs_seqno");
            $param["col"]["worker"] = $fb->session("name");
            $param["col"]["valid_yn"] = "Y";
            $param["col"]["state"] = $state;
            $param["col"]["output_op_seqno"] = $output_op_seqno;

            $rs = $dao->insertData($conn, $param);

            if (!$rs) {
                $check = 0;
            } 
        } else {

            //기존 작업일지 수정
            $param = array();
            $param["table"] = "output_work_report";
            $param["col"]["work_end_hour"] = date("Y-m-d H:i:s");
            $param["col"]["state"] = $state;
            $param["prk"] = "output_op_seqno";
            $param["prkVal"] = $output_op_seqno;

            $rs = $dao->updateWorkReport($conn, $param);

            if (!$rs) {
                $check = 0;
            }
        }
    }
}

$conn->CompleteTrans();
$conn->Close();
echo $check; 
?>
