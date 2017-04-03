<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');

class TemplateInfoMngDAO extends CommonDAO {
    /**
     * @brief 생성자
     */
    function __construct() {
    }

    /**
     * @brief 카테고리 템플릿 노출정보 검색
     *
     * @param $conn  = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateTemplateInfo($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  cate_template_seqno";
        $query .= "\n        ,uniq_num";
        $query .= "\n        ,stan_name";
        $query .= "\n        ,CONCAT(cut_wid_size, '*', cut_vert_size) AS cut_size";
        $query .= "\n        ,CONCAT(work_wid_size, '*', work_vert_size) AS work_size";
        $query .= "\n        ,ai_origin_file_name";
        $query .= "\n        ,eps_origin_file_name";
        $query .= "\n        ,cdr_origin_file_name";
        $query .= "\n   FROM  cate_template";
        $query .= "\n  WHERE  1 = 1";
        if ($this->blankParameterCheck($param, "cate_sortcode")) {
            $query .= "\n    AND  cate_sortcode = ";
            $query .= $param["cate_sortcode"];
        }
        if ($this->blankParameterCheck($param, "cate_template_seqno")) {
            $query .= "\n    AND  cate_template_seqno = ";
            $query .= $param["cate_template_seqno"];
        }

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 카테고리 템플릿 파일정보 검색
     *
     * @param $conn  = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateTemplateFileInfo($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  cate_template_seqno";
        $query .= "\n        ,ai_file_path";
        $query .= "\n        ,ai_save_file_name";
        $query .= "\n        ,ai_origin_file_name";
        $query .= "\n        ,eps_file_path";
        $query .= "\n        ,eps_save_file_name";
        $query .= "\n        ,eps_origin_file_name";
        $query .= "\n        ,cdr_file_path";
        $query .= "\n        ,cdr_save_file_name";
        $query .= "\n        ,cdr_origin_file_name";
        $query .= "\n   FROM  cate_template";
        $query .= "\n  WHERE  1 = 1";
        if ($this->blankParameterCheck($param, "cate_sortcode")) {
            $query .= "\n    AND  cate_sortcode = ";
            $query .= $param["cate_sortcode"];
        }
        if ($this->blankParameterCheck($param, "cate_template_seqno")) {
            $query .= "\n    AND  cate_template_seqno = ";
            $query .= $param["cate_template_seqno"];
        }

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 카테고리 템플릿 중복된 값 있는지 검색
     *
     * @param $conn  = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function chkDupCateTemplate($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  1";
        $query .= "\n   FROM  cate_template";
        $query .= "\n  WHERE  1 = 1";
        if ($this->blankParameterCheck($param, "cate_sortcode")) {
            $query .= "\n    AND  cate_sortcode = ";
            $query .= $param["cate_sortcode"];
        }
        if ($this->blankParameterCheck($param, "uniq_num")) {
            $query .= "\n    AND  uniq_num = ";
            $query .= $param["uniq_num"];
        }
        if ($this->blankParameterCheck($param, "stan_name")) {
            $query .= "\n    AND  stan_name = ";
            $query .= $param["stan_name"];
        }

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 카테고리 사이즈 목록 검색
     *
     * @param $conn  = db connection
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectCateStanInfo($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n SELECT  A.name";
        $query .= "\n        ,CONCAT(cut_wid_size, '*', cut_vert_size) AS cut_size";
        $query .= "\n        ,A.cut_wid_size";
        $query .= "\n        ,A.cut_vert_size";
        $query .= "\n        ,CONCAT(work_wid_size, '*', work_vert_size) AS work_size";
        $query .= "\n        ,A.work_wid_size";
        $query .= "\n        ,A.work_vert_size";
        $query .= "\n   FROM  prdt_stan AS A";
        $query .= "\n        ,cate_stan AS B";
        $query .= "\n  WHERE  A.prdt_stan_seqno = B.prdt_stan_seqno";
        $query .= "\n    AND  B.cate_sortcode = %s";

        $query  = sprintf($query, $param["cate_sortcode"]);

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 카테고리 템플릿 정보 입력
     *
     * @param $conn  = db connection
     * @param $param = 입력정보 파라미터
     *
     * @retrurn 쿼리성공여부
     */
    function insertCateTemplate($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $temp = array();
        $temp["table"] = "cate_template";
        $temp["col"]["cate_sortcode"] = $param["cate_sortcode"];
        $temp["col"]["uniq_num"] = $param["uniq_num"];
        $temp["col"]["stan_name"] = $param["stan_name"];

        $temp["col"]["cut_wid_size"] = $param["cut_wid_size"];
        $temp["col"]["cut_vert_size"] = $param["cut_vert_size"];
        $temp["col"]["work_wid_size"] = $param["work_wid_size"];
        $temp["col"]["work_vert_size"] = $param["work_vert_size"];

        $temp["col"]["ai_file_path"] = $param["ai_file_path"];
        $temp["col"]["ai_save_file_name"] = $param["ai_save_file_name"];
        $temp["col"]["ai_origin_file_name"] = $param["ai_origin_file_name"];

        $temp["col"]["eps_file_path"] = $param["eps_file_path"];
        $temp["col"]["eps_save_file_name"] = $param["eps_save_file_name"];
        $temp["col"]["eps_origin_file_name"] = $param["eps_origin_file_name"];

        $temp["col"]["cdr_file_path"] = $param["cdr_file_path"];
        $temp["col"]["cdr_save_file_name"] = $param["cdr_save_file_name"];
        $temp["col"]["cdr_origin_file_name"] = $param["cdr_origin_file_name"];

        $rs = $this->insertData($conn, $temp);

        return $rs;
    }

    /**
     * @brief 카테고리 템플릿 정보 수정
     *
     * @param $conn  = db connection
     * @param $param = 입력정보 파라미터
     *
     * @retrurn 쿼리성공여부
     */
    function updateCateTemplate($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $temp = array();
        $temp["table"] = "cate_template";

        $temp["col"]["uniq_num"] = $param["uniq_num"];
        $temp["col"]["stan_name"] = $param["stan_name"];

        $temp["col"]["cut_wid_size"] = $param["cut_wid_size"];
        $temp["col"]["cut_vert_size"] = $param["cut_vert_size"];
        $temp["col"]["work_wid_size"] = $param["work_wid_size"];
        $temp["col"]["work_vert_size"] = $param["work_vert_size"];

        if ($this->blankParameterCheck($param, "ai_save_file_name")) {
            $temp["col"]["ai_file_path"] = $param["ai_file_path"];
            $temp["col"]["ai_save_file_name"] = $param["ai_save_file_name"];
            $temp["col"]["ai_origin_file_name"] = $param["ai_origin_file_name"];
        }

        if ($this->blankParameterCheck($param, "eps_save_file_name")) {
            $temp["col"]["eps_file_path"] = $param["eps_file_path"];
            $temp["col"]["eps_save_file_name"] = $param["eps_save_file_name"];
            $temp["col"]["eps_origin_file_name"] = $param["eps_origin_file_name"];
        }

        if ($this->blankParameterCheck($param, "cdr_save_file_name")) {
            $temp["col"]["cdr_file_path"] = $param["cdr_file_path"];
            $temp["col"]["cdr_save_file_name"] = $param["cdr_save_file_name"];
            $temp["col"]["cdr_origin_file_name"] = $param["cdr_origin_file_name"];
        }

        $temp["prk"] = "cate_template_seqno";
        $temp["prkVal"] = $param["cate_template_seqno"];

        $rs = $this->updateData($conn, $temp);

        return $rs;
    }

    /**
     * @brief 카테고리 템플릿 파일 정보 수정
     *
     * @param $conn  = db connection
     * @param $param = 입력정보 파라미터
     *
     * @retrurn 쿼리성공여부
     */
    function updateCateTemplateFileInfo($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $temp = array();
        $temp["table"] = "cate_template";

        $temp["col"]["ai_file_path"] = $param["ai_file_path"];
        $temp["col"]["ai_save_file_name"] = $param["ai_save_file_name"];
        $temp["col"]["ai_origin_file_name"] = $param["ai_origin_file_name"];

        $temp["col"]["eps_file_path"] = $param["eps_file_path"];
        $temp["col"]["eps_save_file_name"] = $param["eps_save_file_name"];
        $temp["col"]["eps_origin_file_name"] = $param["eps_origin_file_name"];

        $temp["col"]["cdr_file_path"] = $param["cdr_file_path"];
        $temp["col"]["cdr_save_file_name"] = $param["cdr_save_file_name"];
        $temp["col"]["cdr_origin_file_name"] = $param["cdr_origin_file_name"];

        $temp["prk"] = "cate_template_seqno";
        $temp["prkVal"] = $param["cate_template_seqno"];

        $rs = $this->updateData($conn, $temp);

        return $rs;
    }

    /**
     * @brief 카테고리 템플릿 정보 삭제
     *
     * @param $conn  = db connection
     * @param $seqno = 일련번호
     *
     * @retrurn 쿼리성공여부
     */
    function deleteCateTemplate($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false; 
        }

        $temp = array();
        $temp["table"] = "cate_template";
        $temp["prk"] = "cate_template_seqno";
        $temp["prkVal"] = $seqno;

        $rs = $this->deleteData($conn, $temp);

        return $rs;
    }
}
?>
