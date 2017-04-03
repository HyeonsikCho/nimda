<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/dataproc_mng/organ_mng/OrganMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();
$conn->StartTrans();

$fb = new FormBean();
$organDAO = new OrganMngDAO();
$check = 1;

$empl_seqno = $fb->form("mng_seq");

$t_param = array();
$t_param["table"] = "empl";
//판매채널
$t_param["col"]["cpn_admin_seqno"] = $fb->form("sell_site");
//관리자 이름
$t_param["col"]["name"] = $fb->form("mng_name");
//관리자 코드
$t_param["col"]["empl_code"] = $fb->form("empl_code");
//관리자 전화번호
$t_param["col"]["tel_num"] = $fb->form("tel_num");
//관리자 id
$t_param["col"]["empl_id"] = $fb->form("empl_id");
//부서 코드
$t_param["col"]["depar_code"] = $fb->form("depar_code");
//상위 부서 코드
$t_param["col"]["high_depar_code"] = substr($fb->form("depar_code"),0,3);
//권한 관리
$t_param["col"]["admin_auth"] = $fb->form("admin_auth");
//직책 코드
$t_param["col"]["job_code"] = $fb->form("job_code");
//직급 코드
$t_param["col"]["posi_code"] = $fb->form("posi_code");
//운영체제
$t_param["col"]["oper_sys"] = $fb->form("oper_sys");
//입사일
if ($fb->form("enter_date")) {
    $t_param["col"]["enter_date"] = $fb->form("enter_date");
}
//퇴사 여부
$t_param["col"]["resign_yn"] = "N";
//최근 수정 일자
$t_param["col"]["lately_modi_date"] = date("Y-m-d H:i:s", time());

//관리자 정보 수정
if ($empl_seqno) {

    $param = array();
    $param["table"] = "empl";
    $param["col"] = "empl_id";
    $param["where"]["empl_id"] = $fb->form("empl_id");
    $param["where"]["empl_seqno"] = $empl_seqno;

    $result = $organDAO->selectData($conn, $param);
    $origin_cnt = $result->recordCount();

    $param = array();
    $param["table"] = "empl";
    $param["col"] = "empl_id";
    $param["where"]["empl_id"] = $fb->form("empl_id");

    $result = $organDAO->selectData($conn, $param);
    $update_cnt = $result->recordCount();

    //아이디를 바꾸지 않았을때
    if ($origin_cnt == 0) {

        //id가 이미 존재하면
        if ($update_cnt > 0) {

            echo "3";
            exit;
        }
    }

    $t_param["prk"] = "empl_seqno";
    $t_param["prkVal"] = $empl_seqno;

    $result = $organDAO->updateData($conn, $t_param);

//부서 추가
} else {

    $param = array();
    $param["table"] = "empl";
    $param["col"] = "empl_id";
    $param["where"]["empl_id"] = $fb->form("empl_id");

    $result = $organDAO->selectData($conn, $param);
    $cnt = $result->recordCount();
    //id가 이미 존재하면
    if ($cnt > 0) {

        echo "3";
        exit;
    }

    $t_param["col"]["passwd"] = '0000';
    $result = $organDAO->insertData($conn, $t_param);
    if (!$result) {
        $check = 0;
    }


    $new_empl_seqno = $conn->insert_ID();
    $query  = "\n UPDATE empl";
    $query .= "\n    SET passwd = PASSWORD('0000')";
    $query .= "\n  WHERE empl_seqno = " . $new_empl_seqno;
    $result = $conn->Execute($query);

}

if (!$result) {
    $check = 0;
}

echo $check;

$conn->CompleteTrans();
$conn->close();
?>
