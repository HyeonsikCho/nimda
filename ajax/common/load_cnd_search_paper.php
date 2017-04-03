<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new CommonDAO();

$search_cnd = $fb->form("search_cnd");
$search_txt = $fb->form("search_txt");

$param = array();
$param["search_cnd"] = $search_cnd;
$param["search_txt"] = $search_txt;

$rs = $dao->selectCndPaper($conn, $param);

$arr = array();
$arr["opt"] = $search_cnd;
$arr["opt_val"] = "paper_op_seqno";
$arr["func"] = "cnd";

echo makeSearchListSub($rs, $arr);
$conn->Close();
?>
