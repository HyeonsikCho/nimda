<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/after_mng/BasicAfterListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new BasicAfterListDAO();
$util = new CommonUtil();

$conn->StartTrans();
$check = 1;
$seqno_arr = explode(",", $fb->form("seqno"));

foreach ($seqno_arr as $key => $value) {

    $basic_after_op_seqno = $value;
    $state_arr = $fb->session("state_arr");

    $state = $state_arr["주문후공정대기"];

    //후공정 발주서 검색
    $param = array();
    $param["table"] = "basic_after_op";
    $param["col"] = "typset_num, flattyp_dvs, state";
    $param["where"]["basic_after_op_seqno"] = $basic_after_op_seqno;

    $sel_rs = $dao->selectData($conn, $param);

    $process_yn_state = $sel_rs->fields["state"];
    $process_yn = "Y";

    if ($process_yn_state == $state) {
        $process_yn = "N";
    }

    if ($process_yn == "Y") {
        $param = array();
        $param["flattyp_dvs"] = $sel_rs->fields["flattyp_dvs"];
        $param["typset_num"] = $sel_rs->fields["typset_num"];
        $param["state"] = $state;

        $check = $util->changeOrderState($conn, $dao, $param);

        //변경된 상태값 적용
        $param = array();
        $param["table"] = "basic_after_op";
        $param["col"]["state"] = $state;
        $param["prk"] = "basic_after_op_seqno";
        $param["prkVal"] = $basic_after_op_seqno;

        $rs = $dao->updateData($conn, $param);

        if (!$rs) {
            $check = 0;
        }

        $param = array();
        $param["table"] = "basic_after_work_report";
        $param["col"] = "basic_after_work_report_seqno";
        $param["where"]["basic_after_op_seqno"] = $basic_after_op_seqno;

        $rs = $dao->selectData($conn, $param);

        if (!$rs || $rs->EOF) {

            $param = array();
            $param["table"] = "basic_after_work_report";
            $param["col"]["worker_memo"] = "다중선택 완료처리";
            $param["col"]["extnl_etprs_seqno"] = $fb->session("extnl_etprs_seqno");
            $param["col"]["worker"] = $fb->session("name");
            $param["col"]["valid_yn"] = "Y";
            $param["col"]["state"] = $state;
            $param["col"]["basic_after_op_seqno"] = $basic_after_op_seqno;

            $rs = $dao->insertData($conn, $param);

            if (!$rs) {
                $check = 0;
            } 
        } else {

            //기존 작업일지 수정
            $param = array();
            $param["table"] = "basic_after_work_report";
            $param["col"]["work_end_hour"] = date("Y-m-d H:i:s");
            $param["col"]["state"] = $state;
            $param["prk"] = "basic_after_op_seqno";
            $param["prkVal"] = $basic_after_op_seqno;

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
