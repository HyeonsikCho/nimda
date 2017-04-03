<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/manufacture/item_mng/PaperOpMngDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperOpMngDAO();

$basisweight_tmp = $fb->form("basisweight");

preg_match('/(.+)(.)/', $basisweight_tmp, $match); 

$basisweight = $match[1];
$basisweight_unit = $match[2];

//종이 정보리스트
$param = array();
$param["extnl_etprs_seqno"] = $fb->form("extnl_etprs_seqno");
$param["sorting"] = $fb->form("sorting");
$param["sorting_type"] = $fb->form("sorting_type");
$param["name"] = $fb->form("name");
$param["dvs"] = $fb->form("dvs");
$param["color"] = $fb->form("color");
$param["basisweight"] = $basisweight;
$param["basisweight_unit"] = $basisweight_unit;

$rs = $dao->selectPaperInfoList($conn, $param);

$list = makePaperInfoListHtml($rs, $param);

echo $list;
$conn->close();
?>
