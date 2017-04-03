<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/CommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/calcul_mng/tab/PurTabListHTML.php');

/**
 * @file PurTabListDAO.php
 *
 * @brief 정산 - 계산서 - 매입계산서리스트 DAO
 */


class PurTabListDAO extends CommonDAO {

    /**
     * @brief 생성자
     *
     */
    function __construct() {
    }

    /**
     * @brief 외부업체list Select
     *
     * @param $conn  = connection identifier
     * @param $param = 검색 조건 파라미터
     *
     * @return : resultSet 
     */ 
    function selectEtprsInfo($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\n    SELECT  A.manu_name";
        $query .= "\n           ,A.extnl_etprs_seqno";
        $query .= "\n           ,B.crn";
        $query .= "\n           ,B.repre_name";
        $query .= "\n           ,B.addr";
        $query .= "\n           ,B.addr_detail";
        $query .= "\n           ,B.bc";
        $query .= "\n           ,B.tob";
        $query .= "\n           ,A.extnl_etprs_seqno";
        $query .= "\n      FROM  extnl_etprs A";
        $query .= "\n LEFT JOIN  extnl_etprs_bls_info B";
        $query .= "\n        ON  A.extnl_etprs_seqno = B.extnl_etprs_seqno";
        $query .= "\n     WHERE  1=1";

        //제조 업체 이름
        if ($this->blankParameterCheck($param, "manu_name")) {
            $query .= "\n       AND manu_name LIKE '%";
            $query .= substr($param["manu_name"], 1,-1) . "%'";
        }

        $result = $conn->Execute($query);

        return $result;
    }
 
    /**
     * @brief 매입계산서 리스트
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPurTabList($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1, -1);

        if ($dvs == "COUNT") {
            $query  ="\nSELECT  COUNT(*) AS cnt ";
        } else if ($dvs == "SEQ") {
            $query  = "\nSELECT  write_date ";
            $query .= "\n       ,pur_cpn ";
            $query .= "\n       ,crn ";
            $query .= "\n       ,item ";
            $query .= "\n       ,supply_price ";
            $query .= "\n       ,vat ";
            $query .= "\n       ,tot_price ";
            $query .= "\n       ,pur_tab_seqno ";
        }
        $query .= "\n  FROM  pur_tab";
        $query .= "\n WHERE  1=1";

        //년도
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND cpn_admin_seqno = ". $param["sell_site"];
        }

        //년도
        if ($this->blankParameterCheck($param ,"start_year")) {
            $query .= "\n   AND  year >= " . $param["start_year"] . " AND year <= " . $param["end_year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"start_mon")) {
            $query .= "\n   AND  mon >= " . $param["start_mon"] .  " AND mon <= " . $param["end_mon"];
        }
        //매입업체명
        if ($this->blankParameterCheck($param ,"pur_cpn")) {

            $pur_cpn = substr($param["pur_cpn"], 1, -1); 
            $query .= "\n   AND pur_cpn like '%" . $pur_cpn . "%'";
        }

        $s_num = substr($param["s_num"], 1, -1);
        $list_num = substr($param["list_num"], 1, -1);

        $query .= "\n   ORDER BY pur_tab_seqno DESC";

        if ($dvs == "SEQ") { 
            $query .= "\nLIMIT ". $s_num . ", " . $list_num;
        }

        return $conn->Execute($query);
    }
 
     /*
     * 업체수, 매입합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectPurSumPrice($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            COUNT(DISTINCT pur_cpn)  AS etprs_cnt";
        $query .= "\n           ,SUM(tot_price)   AS pur_total";
        $query .= "\n           ,SUM(supply_price)   AS supply_price";
        $query .= "\n           ,SUM(vat)   AS vat";
        $query .= "\n      FROM  pur_tab";
        $query .= "\n WHERE  1=1";

        //년도
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND cpn_admin_seqno = ". $param["sell_site"];
        }

        //년도
        if ($this->blankParameterCheck($param ,"start_year")) {
            $query .= "\n   AND  year >= " . $param["start_year"] . " AND year <= " . $param["end_year"];
        }

        //월
        if ($this->blankParameterCheck($param ,"start_mon")) {
            $query .= "\n   AND  mon >= " . $param["start_mon"] .  " AND mon <= " . $param["end_mon"];
        }
        //매입업체명
        if ($this->blankParameterCheck($param ,"pur_cpn")) {

            $pur_cpn = substr($param["pur_cpn"], 1, -1); 
            $query .= "\n   AND  pur_cpn like '%" . $pur_cpn . "%'";
        }

        $result = $conn->Execute($query);

        return $result;
    }


    /*
        * 업체수, 매입합계 가져오기 Select
        * $conn : DB Connection
        * return : resultSet
        */
    function selectPurDetail($conn, $param) {

        if (!$this->connectionCheck($conn)) return false;
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            A.pur_tab_seqno ";
        $query .= "\n           ,A.write_date ";
        $query .= "\n           ,A.item ";
        $query .= "\n           ,A.supply_price ";
        $query .= "\n           ,A.vat ";
        $query .= "\n           ,B.manu_name ";
        $query .= "\n           ,B.extnl_etprs_seqno ";
        $query .= "\n           ,C.crn ";
        $query .= "\n           ,C.repre_name ";
        $query .= "\n           ,C.addr ";
        $query .= "\n           ,C.bc ";
        $query .= "\n           ,C.tob ";
        $query .= "\n      FROM  pur_tab AS A ";
        $query .= "\n      INNER JOIN extnl_etprs AS B ON A.extnl_etprs_seqno = B.extnl_etprs_seqno ";
        $query .= "\n      INNER JOIN extnl_etprs_bls_info AS C ON B.extnl_etprs_seqno = C.extnl_etprs_seqno ";
        $query .= "\n     WHERE  A.pur_tab_seqno = ". $param["pur_tab_seqno"];

        $result = $conn->Execute($query);

        return $result;
    }

    /*
        * 업체수, 매입합계 가져오기 Select
        * $conn : DB Connection
        * return : resultSet
        */
    function updatePurDetail($conn, $param) {
        if (!$this->connectionCheck($conn)) return false;
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n   UPDATE pur_tab set ";
        $query .= "\n    cpn_admin_seqno = %s ";
        $query .= "\n  , extnl_etprs_seqno = %s ";
        $query .= "\n  , pur_cpn = %s ";
        $query .= "\n  , crn = %s ";
        $query .= "\n  , repre_name = %s ";
        $query .= "\n  , addr = %s ";
        $query .= "\n  , bc = %s ";
        $query .= "\n  , tob = %s ";
        $query .= "\n  , write_date = %s ";
        $query .= "\n  , item = %s ";
        $query .= "\n  , supply_price = %s ";
        $query .= "\n  , vat = %s ";
        $query .= "\n  , tot_price = %s ";
        $query .= "\n  , year = %s ";
        $query .= "\n  , mon = %s "; //15
        $query .= "\n   WHERE pur_tab_seqno = %s";

        $query = sprintf($query
            ,$param["cpn_admin_seqno"]
            ,$param["extnl_etprs_seqno"]
            ,$param["pur_cpn"]
            ,$param["crn"]
            ,$param["repre_name"]
            ,$param["addr"]
            ,$param["bc"]
            ,$param["tob"]
            ,$param["write_date"]
            ,$param["item"]
            ,$param["supply_price"]
            ,$param["vat"]
            ,$param["tot_price"]
            ,$param["year"]
            ,$param["mon"]
            ,$param["pur_tab_seqno"]);

        $rs = $conn->Execute($query);
        return $rs;
    }
}
?>
