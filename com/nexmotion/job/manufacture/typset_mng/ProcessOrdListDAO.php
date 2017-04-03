<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/ManufactureCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/manufacture/typset_mng/ProcessOrdListHTML.php');

/**
 * @file ProcessViewDAO.php
 *
 * @brief 생산 - 조판관리 - 생산공정관리 DAO
 */
class ProcessOrdListDAO extends ManufactureCommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    // 생산 지시서 구분
    function selectProduceOrdDvs($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT count(produce_ord_seqno) AS cnt, sum(amt) AS tot_amt";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";
        $query .= "\n   AND ord_dvs = " .$param["ord_dvs"];
        $query .= "\n   AND dvs = " .$param["dvs"];

        return $conn->Execute($query);
    }

    // 생산 지시 구분
    function selectOrdDvs($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT DISTINCT(ord_dvs)";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";

        if ($this->blankParameterCheck($param ,"ord_dvs")) {
            $query .= "\n  AND ord_dvs = " .$param["ord_dvs"];
        }

        if ($this->blankParameterCheck($param ,"preset_cate")) {
            $val = substr($param["preset_cate"], 1, -1);
            $query .= "\n   AND dvs LIKE '" . $val . "%'";
        }

        return $conn->Execute($query);
    }

    // 생산 구분
    function selectDvs($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT DISTINCT(dvs)";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";

        if ($this->blankParameterCheck($param ,"ord_dvs")) {
            $query .= "\n  AND ord_dvs = " .$param["ord_dvs"];
        }

        if ($this->blankParameterCheck($param ,"preset_cate")) {
            $val = substr($param["preset_cate"], 1, -1);
            $query .= "\n   AND dvs LIKE '" . $val . "%'";
        }

        return $conn->Execute($query);
    }

    // 생산 지시서 리스트
    function selectProduceOrdList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query  = "\nSELECT dvs, typset_num, paper, size, print_tmpt, amt, amt_unit, specialty_items";
        $query .= "\n  FROM produce_ord ";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";
        $query .= "\n   AND dvs = " .$param["dvs"];
        if ($this->blankParameterCheck($param ,"ord_dvs")) {
            $query .= "\n   AND ord_dvs = " .$param["ord_dvs"];
        }

        if ($this->blankParameterCheck($param ,"preset_cate")) {
            $val = substr($param["preset_cate"], 1, -1);
            $query .= "\n   AND dvs LIKE '" . $val . "%'";
        }

        return $conn->Execute($query);
    }

    // 작업지시서
    function selectTotalList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $date = substr($param["date"], 1, -1);
        $query .= "\nSELECT dvs";
        $query .= "\n      ,amt";
        $query .= "\n  FROM produce_ord";
        $query .= "\n WHERE date >= '" . $date . " 00:00:00'";
        $query .= "\n   AND date <= '" . $date . " 23:59:59'";
        $dvs = substr($param["dvs"], 1, -1);
        $query .= "\n   AND dvs LIKE '%" . $dvs . "%'";

        return $conn->Execute($query);
    }
}
?>
