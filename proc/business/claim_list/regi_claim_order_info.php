<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/business/claim_mng/ClaimListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new ClaimListDAO();
$check = 1;

$conn->StartTrans();
$order_common_seqno = $fb->form("seqno");
$count = $fb->form("count");
$order_file_seqno = $fb->form("order_file_seqno");

//낱장여부
$rs = $dao->selectFlattypYn($conn, $seqno);
$flattyp_yn = $rs->fields["flattyp_yn"];

//주문공통 재주문
$dao->selectReOrder($conn, $order_common_seqno);
$new_order_common_seqno = $conn->Insert_ID();

//order_file Update (order_common_seqno)
$rs = $dao->selectMemberSeqno($conn, $order_common_seqno);
$member_seqno = $rs->fields["member_seqno"];

$param = array();
$param["table"] = "order_file";
$param["col"]["order_common_seqno"] = $new_order_common_seqno;
$param["col"]["member_seqno"] = $member_seqno;
$param["prk"] = "order_file_seqno";
$param["prkVal"] = $order_file_seqno;

$rs = $dao->updateData($conn,$param);
if (!$rs) {
    $check = 0;
}

//주문 상제 재주문
//낱장 형일 경우
if ($flattyp_yn == "Y") {
    $param = array();
    $param["table"] = "order_detail";
    $param["col"] = "typ ,page_amt ,cate_paper_mpcode
        ,spc_dscr ,detail_num ,order_detail_num ,work_size_wid ,work_size_vert
        ,cut_size_wid ,cut_size_vert ,tomson_size_wid ,tomson_size_vert 
        ,cate_beforeside_print_mpcode ,cate_beforeside_add_print_mpcode
        ,cate_aftside_print_mpcode ,cate_aftside_add_print_mpcode
        ,cut_front_wing_size_wid ,cut_front_wing_size_vert
        ,work_front_wing_size_wid ,work_front_wing_size_vert
        ,cut_rear_wing_size_wid ,cut_rear_wing_size_vert
        ,work_rear_wing_size_wid ,work_rear_wing_size_vert
        ,seneca_size";
    $param["where"]["order_common_seqno"] = $order_common_seqno;

    $rs = $dao->selectData($conn, $param);

    $param = array();
    $param["table"] = "order_detail";
    $param["col"]["typ"] = $rs->fields["typ"];
    $param["col"]["page_amt"] = $rs->fields["page_amt"];
    $param["col"]["cate_paper_mpcode"] = $rs->fields["cate_paper_mpcode"];
    $param["col"]["spc_dscr"] = $rs->fields["spc_dscr"];
    $param["col"]["detail_num"] = $rs->fields["detail_num"];
    $param["col"]["order_detail_num"] = $rs->fields["order_detail_num"];
    $param["col"]["work_size_wid"] = $rs->fields["work_size_wid"];
    $param["col"]["work_size_vert"] = $rs->fields["work_size_vert"];
    $param["col"]["cut_size_wid"] = $rs->fields["cut_size_wid"];
    $param["col"]["cut_size_vert"] = $rs->fields["cut_size_vert"];
    $param["col"]["tomson_size_wid"] = $rs->fields["tomson_size_wid"];
    $param["col"]["tomson_size_vert"] = $rs->fields["tomson_size_vert"];
    $param["col"]["cate_beforeside_print_mpcode"] = $rs->fields["cate_beforeside_print_mpcode"];
    $param["col"]["cate_beforeside_add_print_mpcode"] = $rs->fields["cate_beforeside_add_print_mpcode"];
    $param["col"]["cate_aftside_print_mpcode"] = $rs->fields["cate_aftside_print_mpcode"];
    $param["col"]["cate_aftside_add_print_mpcode"] = $rs->fields["cate_aftside_add_print_mpcode"];
    $param["col"]["cut_front_wing_size_wid"] = $rs->fields["cut_front_wing_size_wid"];
    $param["col"]["cut_front_wing_size_vert"] = $rs->fields["cut_front_wing_size_vert"];
    $param["col"]["work_front_wing_size_wid"] = $rs->fields["work_front_wing_size_wid"];
    $param["col"]["work_front_wing_size_vert"] = $rs->fields["work_front_wing_size_vert"];
    $param["col"]["cut_rear_wing_size_wid"] = $rs->fields["cut_rear_wing_size_wid"];
    $param["col"]["cut_rear_wing_size_vert"] = $rs->fields["cut_rear_wing_size_vert"];
    $param["col"]["work_rear_wing_size_wid"] = $rs->fields["work_rear_wing_size_wid"];
    $param["col"]["work_rear_wing_size_vert"] = $rs->fields["work_rear_wing_size_vert"];
    $param["col"]["seneca_size"] = $rs->fields["seneca_size"];

    for ($i=1; $i<=$count; $i++) {
        $dao->insertData($conn, $param);
    }

//책자형일 경우
} else {
    $dao->selectReOrderDetail($conn, $order_common_seqno);
}

$param = array();
$param["table"] = "order_claim";
$param["col"]["count"] = $fb->form("count");
$param["col"]["order_yn"] = "Y";
$param["prk"] = "order_common_seqno";
$param["prkVal"] = $order_common_seqno;

$rs = $dao->updateData($conn,$param);

if (!$rs) {
    $check = 0;
}

$conn->CompleteTrans();
$conn->Close();
echo $check;
?>
