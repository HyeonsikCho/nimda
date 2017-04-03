<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');

/**
 * @file PaperMaterialMngDAO.php
 *
 * @brief 생산 - 자재관리 - 종이자재관리 DAO
 */
class StorageMngDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    function selectDetailInfo($conn) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\nSELECT  opt_name, seq ";
        $query .= "\n   FROM  opt_info ";

        return $conn->Execute($query);
    }

    function selectStorageList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.depo_finish_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '3120' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
                $val = substr($param["theday_yn"], 1, -1);
                if($val == "Y") {
                    $query .= "\n   AND E.opt_name = \"당일판\" ";
                }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.order_regi_date > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.order_regi_date <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        // 상품종류
        if ($this->blankParameterCheck($param ,"catecode")) {
            $query .= "\n   AND  A.cate_sortcode like ( ". $param["catecode"] . ")";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    function selectReleaseList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.depo_finish_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '3220' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
            $val = substr($param["theday_yn"], 1, -1);
            if($val == "Y") {
                $query .= "\n   AND E.opt_name = \"당일판\" ";
            }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.order_regi_date > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.order_regi_date <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        // 상품종류
        if ($this->blankParameterCheck($param ,"catecode")) {
            $query .= "\n   AND  A.cate_sortcode like ( ". $param["catecode"] . ")";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    function selectDeliveryList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  D.office_nick ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,A.depo_finish_date ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,B.order_detail ";
            $query .= "\n       ,B.amt ";
            $query .= "\n       ,B.count ";
            $query .= "\n       ,C.dlvr_way ";
            $query .= "\n       ,B.state ";
            $query .= "\n       ,B.order_detail_dvs_num ";
            $query .= "\n       ,B.order_common_seqno ";
            $query .= "\n       ,A.member_seqno ";
        } else if ($dvs == "ALL") {
            $query  = "\nSELECT  B.order_detail_dvs_num ";
        }
        $query .= "\n  FROM  order_common as A ";
        $query .= "\n  LEFT JOIN order_detail B on A.order_common_seqno = B.order_common_seqno ";
        $query .= "\n  LEFT JOIN order_dlvr C on B.order_common_seqno = C.order_common_seqno and C.tsrs_dvs = '수신' ";
        $query .= "\n  LEFT JOIN member D on A.member_seqno = D.member_seqno ";
        $query .= "\n  LEFT JOIN order_opt_history E on B.order_common_seqno = E.order_common_seqno and E.opt_name = \"당일판\" ";
        $query .= "\n  where 1=1 and B.state = '3330' ";
        //후공정 유무여부
        if ($this->blankParameterCheck($param ,"after_yn")) {
            $val = substr($param["after_yn"], 1, -1);
            $query .= "\n   AND  B.after_use_yn= '" . $val . "' ";
        }

        //당일판 여부
        if ($this->blankParameterCheck($param ,"theday_yn")) {
            $val = substr($param["theday_yn"], 1, -1);
            if($val == "Y") {
                $query .= "\n   AND E.opt_name = \"당일판\" ";
            }
        }

        //키워드
        if ($this->blankParameterCheck($param ,"keyword")) {
            $val = substr($param["keyword"], 1, -1);
            $query .= "\n   AND  D.office_nick LIKE '%" . $val . "%' ";
        }
        //발주일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.order_regi_date > " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd"], 1, -1);
            $query .= "\n   AND  A.order_regi_date <= " . $param["to"];
        }

        // 배송방법
        if ($this->blankParameterCheck($param ,"dlvr_way")) {
            $query .= "\n   AND  C.dlvr_way = " . $param["dlvr_way"];
        }

        // 상품종류
        if ($this->blankParameterCheck($param ,"catecode")) {
            $query .= "\n   AND  A.cate_sortcode like ( ". $param["catecode"] . ")";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\nORDER BY A.depo_finish_date desc";

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 접수리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPaperMaterialsMngList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  paper_op_seqno ";
            $query .= "\n       ,typset_num ";
            $query .= "\n       ,op_date ";
            $query .= "\n       ,name ";
            $query .= "\n       ,dvs ";
            $query .= "\n       ,color ";
            $query .= "\n       ,basisweight ";
            $query .= "\n       ,op_affil ";
            $query .= "\n       ,op_size ";
            $query .= "\n       ,stor_subpaper ";
            $query .= "\n       ,stor_size ";
            $query .= "\n       ,grain ";
            $query .= "\n       ,amt ";
            $query .= "\n       ,amt_unit ";
            $query .= "\n       ,memo ";
            $query .= "\n       ,typ ";
            $query .= "\n       ,typ_detail ";
            $query .= "\n       ,orderer ";
            $query .= "\n       ,flattyp_dvs ";
            $query .= "\n       ,state ";
            $query .= "\n       ,regi_date ";
            $query .= "\n       ,extnl_brand_seqno ";
            $query .= "\n       ,storplace ";
        }
        $query .= "\n   FROM  paper_op ";
        $query .= "\n   WHERE  1=1 ";

        //주문일 접수일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd1"], 1, -1);
            $query .= "\n   AND  " . $val ." >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd1"], 1, -1);
            $query .= "\n   AND  " . $val. " <= " . $param["to"];
        }

        //발주상태
        if ($this->blankParameterCheck($param ,"state")) {
            $query .= "\n   AND  state = " . $param["state"];
        }

        //발주번호
        if ($this->blankParameterCheck($param ,"paper_op_seqno")) {
            $query .= "\n   AND  paper_op_seqno = " . $param["paper_op_seqno"];
        }

        //수주처
        if ($this->blankParameterCheck($param ,"extnl_brand_seqno")) {
            $query .= "\n   AND  extnl_brand_seqno = " . $param["extnl_brand_seqno"];
        }

        //입고처
        if ($this->blankParameterCheck($param ,"storplace")) {
            $query .= "\n   AND  storplace = " . $param["storplace"];
        }

        $query .= "\n   ORDER BY paper_op_seqno DESC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 종이발주 취소
     *
     * @param $conn  = connection identifier
     * @param $param = 조건 파라미터
     *
     * @return 검색결과
     */
    function updatePaperMaterialsMngCancel($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nUPDATE  paper_op ";
        $query .= "\n   SET  state = '530' ";
        $query .= "\n WHERE  paper_op_seqno = %s ";

        $query = sprintf($query, $param["paper_op_seqno"]);

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
?>
