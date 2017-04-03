<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/typset_mng/ProcessViewHtml.php');

/**
 * @file ProcessViewDAO.php
 *
 * @brief 생산 - 조판관리 - 공정확인리스트 DAO
 */
class ProcessViewDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 공정확인 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectProcessViewList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\n(SELECT COUNT(*) AS acnt ";
            $query .= "\n   FROM sheet_typset AS A ";
            $query .= "\n       ,typset_format AS B ";
            $query .= "\n  WHERE A.typset_format_seqno = B.typset_format_seqno ";
            if ($this->blankParameterCheck($param ,"from")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val ." > " . $param["from"];
            }
            if ($this->blankParameterCheck($param ,"to")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val. " <= " . $param["to"];
            }
            if ($this->blankParameterCheck($param ,"typset_num")) {
                $val = substr($param["typset_num"], 1, -1);
                $query .= "\n   AND  A.typset_num LIKE '%" . $val . "%' ";
            }
            if ($this->blankParameterCheck($param ,"preset_cate")) {
                $query .= "\n    AND B.preset_cate = " . $param["preset_cate"];
            }

            $query .= "\n) UNION ALL ( ";
            $query .= "\n SELECT COUNT(*) AS bcnt ";
            $query .= "\n   FROM brochure_typset AS A ";
            $query .= "\n       ,typset_format AS B ";
            $query .= "\n  WHERE A.typset_format_seqno = B.typset_format_seqno ";
            if ($this->blankParameterCheck($param ,"from")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val ." > " . $param["from"];
            }
            if ($this->blankParameterCheck($param ,"to")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val. " <= " . $param["to"];
            }
            if ($this->blankParameterCheck($param ,"typset_num")) {
                $val = substr($param["typset_num"], 1, -1);
                $query .= "\n   AND  A.typset_num LIKE '%" . $val . "%' ";
            }
            if ($this->blankParameterCheck($param ,"preset_cate")) {
                $query .= "\n    AND B.preset_cate = " . $param["preset_cate"];
            }
            $query .= "\n  ) ";

        } else if ($dvs == "SEQ") {
            $query  = "\n(SELECT A.typset_num ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,A.specialty_items ";
            $query .= "\n       ,A.regi_date ";
            $query .= "\n   FROM sheet_typset AS A ";
            $query .= "\n       ,typset_format AS B ";
            $query .= "\n  WHERE A.typset_format_seqno = B.typset_format_seqno ";
 
            if ($this->blankParameterCheck($param ,"from")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val ." > " . $param["from"];
            }
            if ($this->blankParameterCheck($param ,"to")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val. " <= " . $param["to"];
            }
            if ($this->blankParameterCheck($param ,"typset_num")) {
                $val = substr($param["typset_num"], 1, -1);
                $query .= "\n   AND  A.typset_num LIKE '%" . $val . "%' ";
            }
            if ($this->blankParameterCheck($param ,"preset_cate")) {
                $query .= "\n    AND B.preset_cate = " . $param["preset_cate"];
            }

            $query .= "\n) UNION ALL ( ";
            $query .= "\n SELECT A.typset_num ";
            $query .= "\n       ,A.state ";
            $query .= "\n       ,A.specialty_items ";
            $query .= "\n       ,A.regi_date ";
            $query .= "\n   FROM brochure_typset AS A ";
            $query .= "\n       ,typset_format AS B ";
            $query .= "\n  WHERE A.typset_format_seqno = B.typset_format_seqno ";
            if ($this->blankParameterCheck($param ,"from")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val ." > " . $param["from"];
            }
            if ($this->blankParameterCheck($param ,"to")) {
                $val = substr($param["date_cnd"], 1, -1);
                $query .= "\n   AND  A." . $val. " <= " . $param["to"];
            }
            if ($this->blankParameterCheck($param ,"typset_num")) {
                $val = substr($param["typset_num"], 1, -1);
                $query .= "\n   AND  A.typset_num LIKE '%" . $val . "%' ";
            }
            if ($this->blankParameterCheck($param ,"preset_cate")) {
                $query .= "\n    AND B.preset_cate = " . $param["preset_cate"];
            }
            $query .= "\n  ) ";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs == "SEQ") {
            $query .= "\nORDER BY regi_date DESC ";
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
}
?>
