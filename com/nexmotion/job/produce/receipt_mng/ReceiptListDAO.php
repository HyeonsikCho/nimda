<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ProduceCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/produce/receipt_mng/ReceiptListHTML.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/common_lib/CommonUtil.php');

/**
 * @file ReceiptListDAO.php
 *
 * @brief 생산 - 접수관리 - 접수리스트 DAO
 */
class ReceiptListDAO extends ProduceCommonDAO {
 
    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 접수리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectReceiptList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $util = new CommonUtil;

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  A.order_num ";
            $query .= "\n       ,B.member_name ";
            $query .= "\n       ,B.office_nick ";
            $query .= "\n       ,B.member_seqno ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,E.order_detail ";
            $query .= "\n       ,E.stan_name ";
            $query .= "\n       ,C.cate_name ";
            $query .= "\n       ,E.print_tmpt_name ";
            $query .= "\n       ,A.order_regi_date ";
            $query .= "\n       ,E.count ";
            $query .= "\n       ,E.amt ";
            $query .= "\n       ,E.amt_unit_dvs ";
            $query .= "\n       ,A.order_state ";
            $query .= "\n       ,A.order_common_seqno ";
            $query .= "\n       ,D.resp_deparcode AS deparcode";
            $query .= "\n       ,E.order_detail_dvs_num";
        }
        $query .= "\n  FROM  order_common AS A ";
        $query .= "\n       ,member AS B ";
        $query .= "\n       ,cate AS C ";
        $query .= "\n       ,member_mng AS D ";
        $query .= "\n       ,order_detail AS E ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  A.cate_sortcode = C.sortcode ";
        $query .= "\n   AND  A.del_yn = 'N' ";
        $query .= "\n   AND  A.receipt_dvs = 'Manual' ";
        $query .= "\n   AND  A.member_seqno = D.member_seqno ";
        $query .= "\n   AND  D.mng_dvs = '일반' ";
        $query .= "\n   AND  A.order_common_seqno = E.order_common_seqno";
 
        //운영체제 검색
        if ($this->blankParameterCheck($param ,"oper_sys")) {
            $query .= "\n   AND  A.oper_sys = ". $param["oper_sys"];
        }
        //카테고리 검색
        if ($this->blankParameterCheck($param ,"cate_sortcode")) {
            $query .= "\n   AND  A.cate_sortcode = ". $param["cate_sortcode"];
        }
        //부서 검색
        if ($this->blankParameterCheck($param ,"depar_code")) {
            $query .= "\n   AND  D.resp_deparcode = " .$param["depar_code"];
        }
        //상태 검색
        if ($this->blankParameterCheck($param ,"order_state")) {
            $query .= "\n   AND  A.order_state = " . $param["order_state"];
        } else {
            $query .= "\n   AND  A.order_state >= " . $util->status2statusCode("접수대기");
            $query .= "\n   AND  A.order_state <= " . $util->status2statusCode("접수보류");
        }
        //주문일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  A.order_common_seqno = " . $param["order_common_seqno"];
        }
        //회원일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  B.member_seqno = " . $param["member_seqno"];
        }
        //인쇄물제목
        if ($this->blankParameterCheck($param ,"title")) {
            $val = substr($param["title"], 1, -1);
            $query .= "\n   AND  A.title LIKE '% " . $val . "%' ";
        }
        //주문일 접수일
        if ($this->blankParameterCheck($param ,"from")) {
            $val = substr($param["search_cnd2"], 1, -1);
            $query .= "\n   AND  A." . $val ." >= " . $param["from"];
        }
        if ($this->blankParameterCheck($param ,"to")) {
            $val = substr($param["search_cnd2"], 1, -1);
            $query .= "\n   AND  A." . $val. " <= " . $param["to"];
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
    
    /**
     * @brief 상태변경관리 접수리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectStatusReceiptList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $util = new CommonUtil;

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query .= "\nSELECT  A.order_num ";
            $query .= "\n       ,B.member_name ";
            $query .= "\n       ,A.title ";
            $query .= "\n       ,C.cate_name ";
            $query .= "\n       ,A.receipt_mng ";
            $query .= "\n       ,A.order_state ";
            $query .= "\n       ,A.order_common_seqno ";
            $query .= "\n       ,D.order_detail_seqno";
            $query .= "\n       ,D.order_detail_dvs_num";
        }
        $query .= "\n  FROM  order_common AS A ";
        $query .= "\n       ,member AS B ";
        $query .= "\n       ,cate AS C ";
        $query .= "\n       ,order_detail AS D ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  A.cate_sortcode = C.sortcode ";
        $query .= "\n   AND  A.order_common_seqno = D.order_common_seqno";

        //상태 검색
        if ($this->blankParameterCheck($param ,"order_state")) {
            $query .= "\n   AND  A.order_state = " . $param["order_state"];
        } else {
            $query .= "\n   AND  A.order_state IN (" . $util->status2statusCode("접수중") . ", " 
                . $util->status2statusCode("시안요청중") . ", " . $util->status2statusCode("접수보류") . ")";
        }
        //조건 검색
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  A.title LIKE '%" . $val . "%' ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 주문 상태 변경
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateStatus($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  order_common ";
        $query .= "\n       SET  order_state = %s ";
        $query .= "\n     WHERE  order_common_seqno = %s ";

        $query = sprintf($query,
                         $param["state"],
                         $param["seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 접수팝업 - 주문 상세
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectReceiptView($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nSELECT  A.order_num ";
        $query .= "\n       ,B.member_name ";
        $query .= "\n       ,B.office_nick ";
        $query .= "\n       ,A.title ";
        $query .= "\n       ,A.oper_sys ";
        $query .= "\n       ,C.cate_name ";
        $query .= "\n       ,D.amt ";
        $query .= "\n       ,D.amt_unit_dvs ";
        $query .= "\n       ,D.order_detail ";
        $query .= "\n       ,D.stan_name ";
        $query .= "\n       ,D.print_tmpt_name ";
        $query .= "\n       ,A.memo ";
        $query .= "\n       ,A.stor_release_yn ";
        $query .= "\n       ,D.count ";
        $query .= "\n       ,D.receipt_mng ";
        $query .= "\n       ,D.order_detail_dvs_num";
        $query .= "\n       ,D.order_detail_seqno";
        $query .= "\n  FROM  order_common AS A ";
        $query .= "\n       ,member AS B ";
        $query .= "\n       ,cate AS C ";
        $query .= "\n       ,order_detail AS D ";
        $query .= "\n WHERE  A.member_seqno = B.member_seqno ";
        $query .= "\n   AND  A.cate_sortcode = C.sortcode ";
        $query .= "\n   AND  A.order_common_seqno = D.order_common_seqno ";
        $query .= "\n   AND  A.del_yn = 'N' ";
 
        //주문공통 일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  A.order_common_seqno = " . $param["order_common_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 접수팝업 - 배송정보
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderDlvr($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nSELECT  addr ";
        $query .= "\n       ,name ";
        $query .= "\n       ,dlvr_way ";
        $query .= "\n       ,dlvr_sum_way ";
        $query .= "\n  FROM  order_dlvr ";
        $query .= "\n WHERE  tsrs_dvs = '수신' ";
 
        //주문공통 일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  order_common_seqno = " . $param["order_common_seqno"];
        }

        return $conn->Execute($query);
    }
 
    /**
     * @brief 접수
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateReceipt($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  order_common ";
        $query .= "\n       SET  order_state = %s ";
        $query .= "\n           ,receipt_mng = %s ";
        $query .= "\n     WHERE  order_common_seqno = %s ";

        $query = sprintf($query,
                         $param["order_state"],
                         $param["receipt_mng"],
                         $param["seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 접수 취소시 결재금액, 회원 일련번호 가져옴
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOrderCancelInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nSELECT  pay_price ";
        $query .= "\n       ,member_seqno ";
        $query .= "\n  FROM  order_common ";
        $query .= "\n WHERE  1 = 1 ";
 
        //주문공통 일련번호
        if ($this->blankParameterCheck($param ,"order_common_seqno")) {
            $query .= "\n   AND  order_common_seqno = " . $param["order_common_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 회원 선입금 정보 가져옴
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectMemberPrepay($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\nSELECT  prepay_price ";
        $query .= "\n  FROM  member ";
        $query .= "\n WHERE  1 = 1 ";
 
        //주문공통 일련번호
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  member_seqno = " . $param["member_seqno"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 주문 취소
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateOrderDel($conn, $param) {

        $util = new CommonUtil;
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  order_common ";
        $query .= "\n       SET  eraser = %s ";
        $query .= "\n           ,del_yn = 'Y' ";
        $query .= "\n           ,order_state = " . $util->status2statusCode("주문취소");
        $query .= "\n     WHERE  order_common_seqno = %s ";

        $query = sprintf($query,
                         $param["eraser"],
                         $param["seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 선입금 환불
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updatePrepayBack($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  member ";
        $query .= "\n       SET  prepay_price = %s ";
        $query .= "\n     WHERE  member_seqno = %s ";

        $query = sprintf($query,
                         $param["prepay_price"],
                         $param["member_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 주문 상세 인쇄파일 주문상세별 파일존재여부
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
    function selectOrderDetailFileCheck($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n         SELECT  A.order_detail_seqno ";
        $query .= "\n           FROM  order_detail AS A ";
        $query .= "\nLEFT OUTER JOIN  order_detail_count_file AS B ";
        $query .= "\n             ON  A.order_detail_seqno = B.order_detail_seqno ";
        $query .= "\n          WHERE  A.order_common_seqno = " . $param["order_common_seqno"];
        //$query .= "\n            AND  B.order_detail_seqno IS NULL ";
        $query .= "\n          LIMIT  1 ";

        return $conn->Execute($query);
    }
     */

    /**
     * @brief update 할 파일 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectFileUpload($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n         SELECT  order_detail_count_file_seqno";
        $query .= "\n                ,order_detail_seqno ";
        $query .= "\n                ,order_detail_file_num ";
        $query .= "\n                ,seq";
        $query .= "\n           FROM  order_detail_count_file";
        $query .= "\n          WHERE  order_detail_seqno = %s";
        $query .= "\n            AND  file_path is null";
        $query .= "\n       ORDER BY  order_detail_file_num, seq";
        $query .= "\n          LIMIT  0,1 ";

        $query = sprintf($query, $param["order_detail_seqno"]);

        return $conn->Execute($query);
    }

    /**
     * @brief order_detail_count_file 파일 업로드update
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateFileUpload($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  order_detail_count_file";
        $query .= "\n       SET  origin_file_name = %s ";
        $query .= "\n           ,save_file_name = %s ";
        $query .= "\n           ,file_path = %s ";
        $query .= "\n           ,size = %s ";
        $query .= "\n           ,print_file_path = %s ";
        $query .= "\n           ,print_file_name = %s ";
        $query .= "\n           ,preview_file_path = %s ";
        $query .= "\n           ,preview_file_name = %s ";
        $query .= "\n           ,tmp_file_path = %s ";
        $query .= "\n           ,tmp_file_name = %s ";
        $query .= "\n     WHERE  order_detail_seqno = %s ";
        $query .= "\n       AND  seq = %s ";

        $query = sprintf($query,
                         $param["origin_file_name"],
                         $param["save_file_name"],
                         $param["file_path"],
                         $param["size"],
                         $param["print_file_path"],
                         $param["print_file_name"],
                         $param["preview_file_path"],
                         $param["preview_file_name"],
                         $param["tmp_file_path"],
                         $param["tmp_file_name"],
                         $param["order_detail_seqno"],
                         $param["seq"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief order_detail_count_file 파일 삭제 update
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function updateFileDelete($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
  
        $query  = "\n    UPDATE  order_detail_count_file";
        $query .= "\n       SET  origin_file_name = null";
        $query .= "\n           ,save_file_name = null";
        $query .= "\n           ,file_path = null";
        $query .= "\n           ,size = null";
        $query .= "\n     WHERE  order_detail_count_file_seqno = %s ";

        $query = sprintf($query, $param["order_detail_count_file_seqno"]);

        $resultSet = $conn->Execute($query);
 
        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 주문별 옵션 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectOptName($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT C.opt_name";
        $query .= "\n  FROM order_common AS A";
        $query .= "\n      ,order_detail AS B";
        $query .= "\n      ,order_opt_history AS C";
        $query .= "\n WHERE A.order_common_seqno = B.order_common_seqno";
        $query .= "\n   AND A.order_common_seqno = C.order_common_seqno";
        $query .= "\n   AND B.order_detail_seqno = " . $param["order_detail_seqno"];

        return $conn->Execute($query);
    }

    /**
     * @brief 카테고리 이름와 상위 카테고리 이름 가져옴 
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateName($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT A.cate_name AS high_cate_name";
        $query .= "\n      ,B.cate_name";
        $query .= "\n  FROM cate AS A";
        $query .= "\n      ,cate AS B";
        $query .= "\n WHERE A.sortcode = B.high_sortcode";
        $query .= "\n   AND B.sortcode = " . $param["cate_sortcode"];

        return $conn->Execute($query);
    }
}
?>
