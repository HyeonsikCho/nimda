<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/typset_mng/TypsetFormatHtml.php');

/**
 * @file TypsetForamtDAO.php
 *
 * @brief 생산 - 조판관리 - 자동판형관리 DAO
 */
class TypsetFormatDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 종이 내용 가져옴
     */
    function selectPaperList($conn, $param) {

        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        //카운트
        if ($dvs === "COUNT") {
            $query  = "\nSELECT  COUNT(paper_seqno) AS cnt";
        //일반 쿼리
        } else if ($dvs === "SEQ") {
            $query  = "\nSELECT  A.paper_seqno ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,B.name AS brand_name ";
            $query .= "\n       ,A.name AS paper_name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.wid_size ";
            $query .= "\n       ,A.vert_size ";
        }
        $query .= "\n  FROM  paper AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND A.name LIKE '%" . $val . "%'";
        }

        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type;
            }
        }

        if ($dvs === "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 출력 내용 가져옴
     */
    function selectOutputList($conn, $param) {

        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        //카운트
        if ($dvs === "COUNT") {
            $query  = "\nSELECT  COUNT(output_seqno) AS cnt";
        //일반 쿼리
        } else if ($dvs === "SEQ") {
            $query  = "\nSELECT  A.output_seqno ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,B.name AS brand_name ";
            $query .= "\n       ,A.name AS output_name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.wid_size ";
            $query .= "\n       ,A.vert_size ";
            $query .= "\n       ,A.board ";
        }
        $query .= "\n  FROM  output AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND A.name LIKE '%" . $val . "%'";
        }

        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type;
            }
        }

        if ($dvs === "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 인쇄 내용 가져옴
     */
    function selectPrintList($conn, $param) {

        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        //카운트
        if ($dvs === "COUNT") {
            $query  = "\nSELECT  COUNT(print_seqno) AS cnt";
        //일반 쿼리
        } else if ($dvs === "SEQ") {
            $query  = "\nSELECT  A.print_seqno ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,B.name AS brand_name ";
            $query .= "\n       ,A.name AS print_name ";
            $query .= "\n       ,A.affil ";
            $query .= "\n       ,A.wid_size ";
            $query .= "\n       ,A.vert_size ";
        }
        $query .= "\n  FROM  print AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND A.name LIKE '%" . $val . "%'";
        }

        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type;
            }
        }

        if ($dvs === "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 후공정 내용 가져옴
     */
    function selectAfterList($conn, $param) {

        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        //카운트
        if ($dvs === "COUNT") {
            $query  = "\nSELECT  COUNT(after_seqno) AS cnt";
        //일반 쿼리
        } else if ($dvs === "SEQ") {
            $query  = "\nSELECT  A.after_seqno ";
            $query .= "\n       ,C.manu_name ";
            $query .= "\n       ,B.name AS brand_name ";
            $query .= "\n       ,A.name AS after_name ";
            $query .= "\n       ,A.depth1 ";
            $query .= "\n       ,A.depth2 ";
            $query .= "\n       ,A.depth3 ";
        }
        $query .= "\n  FROM  after AS A ";
        $query .= "\n       ,extnl_brand AS B ";
        $query .= "\n       ,extnl_etprs AS C ";
        $query .= "\n WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno ";
        $query .= "\n   AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno ";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($this->blankParameterCheck($param ,"extnl_etprs_seqno")) {
            $query .= "\n   AND C.extnl_etprs_seqno = " . $param["extnl_etprs_seqno"];
        }

        if ($this->blankParameterCheck($param ,"search_txt")) {
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND A.name LIKE '%" . $val . "%'";
        }

        if ($this->blankParameterCheck($param ,"sorting")) {
            $sorting = substr($param["sorting"], 1, -1);
            $query .= "\n ORDER BY " . $sorting;

            if ($this->blankParameterCheck($param ,"sorting_type")) {
                $sorting_type = substr($param["sorting_type"], 1, -1);
                $query .= " " . $sorting_type;
            }
        }

        if ($dvs === "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 공정에 등록 된 후공정 내용 가져옴
     */
    function selectRegiAfterList($conn, $param) {

        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  A.after_seqno ";
        $query .= "\n       ,B.name AS after_name ";
        $query .= "\n       ,B.depth1 ";
        $query .= "\n       ,B.depth2 ";
        $query .= "\n       ,B.depth3 ";
        $query .= "\n       ,D.manu_name ";
        $query .= "\n  FROM  basic_produce_after AS A ";
        $query .= "\n       ,after AS B ";
        $query .= "\n       ,extnl_brand AS C ";
        $query .= "\n       ,extnl_etprs AS D ";
        $query .= "\n WHERE  A.after_seqno = B.after_seqno ";
        $query .= "\n   AND  B.extnl_brand_seqno = C.extnl_brand_seqno ";
        $query .= "\n   AND  C.extnl_etprs_seqno = D.extnl_etprs_seqno ";

        if ($this->blankParameterCheck($param ,"typset_format_seqno")) {
            $query .= "\n   AND  A.typset_format_seqno = $param[typset_format_seqno]";
        }

        return $conn->Execute($query);
    }
}
