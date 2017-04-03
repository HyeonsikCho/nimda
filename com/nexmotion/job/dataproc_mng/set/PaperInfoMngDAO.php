<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/dataproc_mng/set/PaperInfoMngHTML.php");

class PaperInfoMngDAO extends CommonDAO {

    function __construct() {
    }

    /**
     * @brief 상품종이 정보 가져옴
     *
     * @param $conn = db connection
     *
     * @return 검색결과
     */
    function selectPrdtPaperInfo($conn, $dvs, $param) {
        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        if ($dvs === "name") {
            $query  = "\nSELECT  DISTINCT name";
        } else if ($dvs === "dvs") {
            $query  = "\nSELECT  DISTINCT dvs";
        } else if ($dvs === "color") {
            $query  = "\nSELECT  DISTINCT color";
        }

        $query .= "\n  FROM  prdt_paper ";

        //검색에 필요한 기본 조건
        $query .= "\n WHERE  1 = 1 ";

        //종이명
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = $param[name]";
        }
        //구분
        if ($this->blankParameterCheck($param ,"dvs")) {
            $query .= "\n   AND  dvs = $param[dvs]";
        }
        $query .= "\n  ORDER BY name";

        $rs = $conn->Execute($query);

        return makePaperInfoHtml($rs, $dvs);
    }

    /**
     * @brief 종이 미리보기 정보 가져옴
     *
     * @param $conn = db connection
     *
     * @return 검색결과
     */
    function selectPaperPreviewInfo($conn, $param) {
        //커넥션 체크
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\nSELECT  file_path";
        $query .= "\n       ,save_file_name";
        $query .= "\n       ,origin_file_name";
        $query .= "\n       ,paper_preview_seqno";

        $query .= "\n  FROM  paper_preview";
        $query .= "\n WHERE  1 = 1";

        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n   AND  name = $param[name]";
        }
        if ($this->blankParameterCheck($param ,"dvs")) {
            $query .= "\n   AND  dvs = $param[dvs]";
        }
        if ($this->blankParameterCheck($param ,"color")) {
            $query .= "\n   AND  color = $param[color]";
        }
        if ($this->blankParameterCheck($param ,"paper_preview_seqno")) {
            $query .= "\n   AND  paper_preview_seqno = $param[paper_preview_seqno]";
        }

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 재질 미리보기 데이터 입력
     *
     * @param $conn  = db connection
     * @param $param = 입력값 파라미터
     *
     * @return 입력결과
     */
    function insertPaperPreview($conn, $param) {
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        $temp = array();
        $temp["col"]["name"]             = $param["paper_name"];
        $temp["col"]["dvs"]              = $param["paper_dvs"];
        $temp["col"]["color"]            = $param["paper_color"];
        $temp["col"]["file_path"]        = $param["file_path"];
        $temp["col"]["save_file_name"]   = $param["save_file_name"];
        $temp["col"]["origin_file_name"] = $param["origin_file_name"];
        $temp["table"] = "paper_preview";

        return $this->insertData($conn, $temp);
    }

    /**
     * @brief 재질 미리보기 데이터 입력
     *
     * @param $conn  = db connection
     * @param $param = 입력값 파라미터
     *
     * @return 입력결과
     */
    function deletePaperPreview($conn, $param) {
        if (!$this->connectionCheck($conn)) {
            return false;
        }

        $temp = array();
        $temp["table"] = "paper_preview";
        $temp["prk"]    = "paper_preview_seqno";
        $temp["prkVal"] = $param["paper_preview_seqno"];

        return $this->deleteData($conn, $temp);
    }
}
?>
