<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/calcul_mng/settle/DayCloseHTML.php");

/**
 * @file DayCloseDAO.php
 *
 * @brief 정산관리 - 결산 - 일마감 DAO
 */
class DayCloseDAO extends CommonDAO {

    function __construct() {
    }
 
    /**
     * @brief 일마감 마지막 날짜 가져오기
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectLastCloseDay($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\nSELECT  close_date ";
        $query .= "\n  FROM  day_close";
        $query .= "\n WHERE  cpn_admin_seqno = ". $param["sell_site"];
        $query .= "\n   AND  close_yn = 'Y'";

        if ($this->blankParameterCheck($param ,"year")) {
            $query .= "\n   AND  year = " . $param["year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"mon")) {
            $query .= "\n   AND  mon = " . $param["mon"];
        }
        $query .= "\nORDER BY close_date DESC";

        $query .= "\nLIMIT 1";

        return $conn->Execute($query);
    }
 



}

?>
