<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/basic_mng/prdc_prdt_mng/TypsetMngHTML.php");

class TypsetMngDAO extends BasicMngCommonDAO {

    function __construct() {

    }

    /*
     * 조판 이름 Select
     * $conn : DB Connection
     * $param : $param["search"] = "검색어"
     * return : resultSet
     */
    function selectTypsetName($conn, $param) {

        if (!$this->connectionCheck($conn)) return false;
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT  name";
        $query .= "\n           ,typset_format_seqno";
        $query .= "\n      FROM  typset_format";

        if ($this->blankParameterCheck($param ,"search")) {

            $search_str = substr($param["search"], 1, -1);

            $query .= "\n   WHERE  name like '%" . $search_str . "%' ";

        }
        $query .= "\n  ORDER BY typset_format_seqno ASC";

        $result = $conn->Execute($query);
        return $result;
    }

    /*
     * 조판 정보 Select
     * $conn : DB Connection
     * $param : $param["preset_name"] = "조판명"
     * $param : $param["affil_fs"] = "46계열"
     * $param : $param["affil_guk"] = "국계열"
     * $param : $param["affil_spc"] = "별계열"
     * $param : $param["file"] = "조판파일명"
     * return : resultSet
     */
    function selectTypsetList($conn, $param) {

        if (!$this->connectionCheck($conn)) {
            return false;
        }
 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  = "\nSELECT  COUNT(typset_format_seqno) AS cnt";
        //일반 쿼리
        } else if ($dvs == "SEQ") {
            $query  = "\n    SELECT  A.preset_name";
            $query .= "\n           ,A.preset_cate";
            $query .= "\n           ,A.affil";
            $query .= "\n           ,A.subpaper";
            $query .= "\n           ,A.wid_size";
            $query .= "\n           ,A.vert_size";
            $query .= "\n           ,A.process_yn";
            $query .= "\n           ,A.dscr";
            $query .= "\n           ,A.typset_format_seqno";
        }
        $query .= "\n      FROM  typset_format A";
        $query .= "\n     WHERE  1=1";

        //조판명
        if ($this->blankParameterCheck($param ,"typset_name")) {

            $query .= "\n        AND A.preset_name =";
            $query .=  $param["typset_name"];
        }

        //46계열
        if ($this->blankParameterCheck($param ,"affil_fs")) {

            $query .= "\n        AND (A.affil =" . $param["affil_fs"];

            //국계열
            if ($this->blankParameterCheck($param ,"affil_guk")) {

                $query .= "\n       OR A.affil=" . $param["affil_guk"];
            }

            //별규격계열
            if ($this->blankParameterCheck($param ,"affil_spc")) {

                $query .= "\n       OR A.affil=" . $param["affil_spc"];
            }

            $query .= "\n       )";

        //국계열
        } else if ($this->blankParameterCheck($param ,"affil_guk")) {

            $query .= "\n        AND (A.affil =" . $param["affil_guk"];

            //별규격 계열
            if ($this->blankParameterCheck($param ,"affil_spc")) {

                $query .= "\n       OR A.affil=" . $param["affil_spc"];
            }

            $query .= "\n       )";

        //별규격계열
        } else if ($this->blankParameterCheck($param ,"affil_spc")) {

            $query .= "\n        AND A.affil =" . $param["affil_spc"];

        }

        $query .= "\n  ORDER BY  A.typset_format_seqno ASC";

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        if ($dvs === "SEQ") {
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
}
?>
