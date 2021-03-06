<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/define/common_config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_define/common_info.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/common_lib/CommonUtil.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/after_mng/BasicAfterListDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/file/FileAttachDAO.php");

$connectionPool = new ConnectionPool(); $conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new BasicAfterListDAO();
$util = new CommonUtil();
$fileDAO = new FileAttachDAO();

if (!$fb->form("seqno")) {
    echo "<script>alert('잘못된 접근입니다.');</script>";
    exit;
}

$seqno = $fb->form("seqno");
$state_arr = $fb->session("state_arr");
$option_html = "<option value=\"%s\" %s>%s</option>";

//상세보기 출력 발주
$param = array();
$param["seqno"] = $seqno;

$rs = $dao->selectBasicAfterProcessView($conn, $param);

$flattyp_dvs = $rs->fields["flattyp_dvs"];
$typset_num = $rs->fields["typset_num"];

$img_html  = "<li data-thumb=\"%s\" width=\"884px\">";
$img_html .= "<img src=\"%s\" style=\"border-right: 1px dotted #ddd;\" width=\"884px\"/></li>";

//낱장형 여부
if ($flattyp_dvs == "Y") {

    $param = array();
    $param["table"] = "sheet_typset";
    $param["col"] = "sheet_typset_seqno";
    $param["where"]["typset_num"] = $typset_num;
 
    $sel_rs = $dao->selectData($conn, $param);

    //조판 파일
    $param = array();
    $param["table"] = "sheet_typset_preview_file";
    $param["col"] = "file_path, save_file_name";
    $param["where"]["sheet_typset_seqno"] = $sel_rs->fields["sheet_typset_seqno"];
 
    $picture_rs = $dao->selectData($conn, $param);

    $html_param["pic"] = "";
    while ($picture_rs && !$picture_rs->EOF) {
        $file_path = $picture_rs->fields["file_path"];
        $file_name = $picture_rs->fields["save_file_name"];

        $full_path = $file_path . $file_name;
        $chk_path = $_SERVER["DOCUMENT_ROOT"] . $full_path;

        if (is_file($chk_path) === false) {
            $full_path = NO_IMAGE;
            $html_param["pic"] .= sprintf($img_html, $full_path, $full_path); 
        } else {

            $temp = explode('.', $file_name);
            $ext = strtolower($temp[1]);

            $param = array();
            $param["fs"] = $full_path;
            $param["req_width"] = "300";
            $param["req_height"] = "225";

            $pic = $fileDAO->makeThumbnail($param);

            $html_param["pic"] .= sprintf($img_html, $file_path . $temp[0] . "_300_225." . $ext, $full_path); 
        }
        $picture_rs->moveNext();
    }

} else if ($flattyp_dvs == "N") {
    $param = array();
    $param["table"] = "brochure_typset";
    $param["col"] = "brochure_typset_seqno";
    $param["where"]["typset_num"] = $typset_num;

    $sel_rs = $dao->selectData($conn, $param);

    //조판 파일
    $param = array();
    $param["table"] = "brochure_typset_file";
    $param["col"] = "file_path, save_file_name";
    $param["where"]["brochure_typset_seqno"] = $sel_rs->fields["brochure_typset_seqno"];
 
    $picture_rs = $dao->selectData($conn, $param);
 
    $html_param["pic"] = "";
    while ($picture_rs && !$picture_rs->EOF) {
        $file_path = $picture_rs->fields["file_path"];
        $file_name = $picture_rs->fields["save_file_name"];

        $full_path = $file_path . $file_name;
        $chk_path = $_SERVER["DOCUMENT_ROOT"] . $full_path;

        if (is_file($chk_path) === false) {
            $full_path = NO_IMAGE;
            $html_param["pic"] .= sprintf($img_html, $full_path, $full_path); 
        } else {

            $temp = explode('.', $file_name);
            $ext = strtolower($temp[1]);

            $param = array();
            $param["fs"] = $full_path;
            $param["req_width"] = "300";
            $param["req_height"] = "225";

            $pic = $fileDAO->makeThumbnail($param);

            $html_param["pic"] .= sprintf($img_html, $file_path . $temp[0] . "_300_225." . $ext, $full_path); 
        }
        $picture_rs->moveNext();
    }
}

echo getBasicAfterImgPopup($html_param); 
$conn->close();
?>
