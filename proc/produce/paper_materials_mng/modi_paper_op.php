<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/util/ConnectionPool.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/common/entity/FormBean.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/produce/paper_materials_mng/PaperMaterialsMngDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/pageLib.php');

$connectionPool = new ConnectionPool();
$conn = $connectionPool->getPooledConnection();

$fb = new FormBean();
$dao = new PaperMaterialsMngDAO();
$conn->StartTrans();

//삭제할 발주id
$paper_op_seqno = $fb->form("paper_op_seqno");
$paper_op_seqnos = explode("&", $paper_op_seqno);
$flag = true;

for ($i=0;$i<count($paper_op_seqnos);$i++) {
    $tmp = explode("=", $paper_op_seqnos[$i]);
    
    if ($dao->selectOpState($conn, $tmp[1]) != "520") {
        $param = array();
        $param["paper_op_seqno"] = $tmp[1]; 
        
        if( !$dao->updateOpState($conn, $param) ) {
            $flag = false;
            break;
        }
    }
}

if ($flag)
    echo "발주되었습니다.";
else
    echo "발주실패했습니다.";

$conn->CompleteTrans();
$conn->close();
?>
