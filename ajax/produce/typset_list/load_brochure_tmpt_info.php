<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/typset_mng/TypsetListDAO.php");

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new TypsetListDAO();

$param = array();
$param["table"] = "brochure_typset";
$param["col"] = "beforeside_tmpt ,beforeside_spc_tmpt 
                ,aftside_tmpt ,aftside_spc_tmpt
                ,print_amt ,print_amt_unit";
$param["where"]["brochure_typset_seqno"] = $fb->form("seqno");

$rs = $dao->selectData($conn, $param);

$tot_tmpt = intVal($rs->fields["beforeside_tmpt"]) + 
     intVal($rs->fields["beforeside_spc_tmpt"]) + 
     intVal($rs->fields["aftside_tmpt"]) + 
     intVal($rs->fields["aftside_spc_tmpt"]);

echo $rs->fields["beforeside_tmpt"] . "♪" . 
     $rs->fields["beforeside_spc_tmpt"] . "♪" .
     $rs->fields["aftside_tmpt"] . "♪" . 
     $rs->fields["aftside_spc_tmpt"] . "♪" . 
     $tot_tmpt . "♪" .
     $rs->fields["print_amt"] . "♪" . 
     $rs->fields["print_amt_unit"];

$conn->close();
?>
