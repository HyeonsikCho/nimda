<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/job/common/BasicMngCommonDAO.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/basic_mng/prdc_prdt_mng/AfterMngHTML.php");

class AfterMngDAO extends BasicMngCommonDAO {

    function __construct() {
    }

    /*
     * 후공정 정보 Select 
     * $conn : DB Connection
     * $param : $param["name"] = "후공정명"
     * $param : $param["depth1"] = "depth1명"
     * $param : $param["depth2"] = "depth2명"
     * $param : $param["depth3"] = "depth3명"
     * $param : $param["manu_seqno"] = "외부 업체 일련번호"
     * $param : $param["brand_seqno"] = "브랜드 일련번호"
     * $param : $param["amt"] = "수량"
     * $param : $param["crtr_unit"] = "기준 단위"
     * return : resultSet 
     */ 
    function selectPrdcAfterList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT  SQL_CALC_FOUND_ROWS";
        $query .= "\n            A.name";
        $query .= "\n           ,A.depth1";
        $query .= "\n           ,A.depth2";
        $query .= "\n           ,A.depth3";
        $query .= "\n           ,A.affil";
        $query .= "\n           ,A.subpaper";
        $query .= "\n           ,A.crtr_unit";
        $query .= "\n           ,A.after_seqno";
        $query .= "\n           ,B.name as brand";
        $query .= "\n           ,C.manu_name";
        $query .= "\n      FROM  after A";
        $query .= "\n           ,extnl_brand B";
        $query .= "\n           ,extnl_etprs C";
        $query .= "\n     WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno";
        $query .= "\n       AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno";

        //후공정 일련번호
        if ($this->blankParameterCheck($param ,"after_seqno")) {
            $query .= "\n        AND A.after_seqno =" . $param["after_seqno"];
        }

        //후공정명
        if ($this->blankParameterCheck($param ,"name")) {
            $query .= "\n        AND A.name =" . $param["name"];
        }
        
        //depth1
        if ($this->blankParameterCheck($param ,"depth1")) {
            $query .= "\n        AND A.depth1 =" . $param["depth1"];
        }
        
        //depth2
        if ($this->blankParameterCheck($param ,"depth2")) {
            $query .= "\n        AND A.depth2 =" . $param["depth2"];
        }
        
        //depth3
        if ($this->blankParameterCheck($param ,"depth3")) {
            $query .= "\n        AND A.depth3 =" . $param["depth3"];
        }

        //브랜드 일련번호
        if ($this->blankParameterCheck($param ,"brand_seqno")) {
            $query .= "\n        AND B.extnl_brand_seqno =" . $param["brand_seqno"];
        }
 
        //제조사 일련번호
        if ($this->blankParameterCheck($param ,"manu_seqno")) {
            $query .= "\n        AND B.extnl_etprs_seqno =" . $param["manu_seqno"];
        }

        //계열
        if ($this->blankParameterCheck($param ,"affil")) {
            $query .= "\n        AND A.affil =" . $param["affil"];
        }

        //절수
        if ($this->blankParameterCheck($param ,"subpaper")) {
            $query .= "\n        AND A.subpaper =" . $param["subpaper"];
        }

        //기준 단위
        if ($this->blankParameterCheck($param ,"crtr_unit")) {
            $query .= "\n        AND A.crtr_unit =" . $param["crtr_unit"];
        }

        $query .= "\n  ORDER BY  A.after_seqno";

        //limit 조건
        if ($this->blankParameterCheck($param ,"start") && $this->blankParameterCheck($param ,"end")) {
 
            $param["start"] = substr($param["start"], 1, -1);
            $param["end"] = substr($param["end"], 1, -1); 

            $query .= "\n LIMIT " . $param["start"] . ",";
            $query .= $param["end"]; 
        }
    
        $result = $conn->Execute($query);

        return $result;

    }

    /**
     * @brief 후공정 이름검색
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrdcAfterName($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  DISTINCT A.name";
        $query .= "\n   FROM  after AS A";
        $query .= "\n        ,extnl_brand AS B";
        $query .= "\n        ,extnl_etprs AS C";
        $query .= "\n  WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno";
        $query .= "\n    AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno";
        
        if ($this->blankParameterCheck($param ,"brand_seqno")) {
            $query .= "\n    AND  B.extnl_brand_seqno = ";
            $query .= $param["brand_seqno"];
        }
        if ($this->blankParameterCheck($param ,"etprs_seqno")) {
            $query .= "\n    AND  C.extnl_etprs_seqno = ";
            $query .= $param["etprs_seqno"];
        }
        
        $result = $conn->Execute($query);
        return $result;
    }

    /**
     * @brief 후공정 가격검색
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrdcAfterPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $except_arr = array("affil" => true); 

        $param = $this->parameterArrayEscape($conn, $param, $except_arr);

        $query  = "\n SELECT  T1.manu_name";
        $query .= "\n        ,T1.brand_name";
        $query .= "\n        ,T2.name";
        $query .= "\n        ,T2.depth1";
        $query .= "\n        ,T2.depth2";
        $query .= "\n        ,T2.depth3";
        $query .= "\n        ,T2.crtr_unit";
        $query .= "\n        ,T3.amt";
        $query .= "\n        ,T3.after_price_seqno AS price_seqno";
        $query .= "\n        ,T3.basic_price";
        $query .= "\n        ,T3.pur_rate";
        $query .= "\n        ,T3.pur_aplc_price";
        $query .= "\n        ,T3.pur_price";
        $query .= "\n FROM (SELECT  B.manu_name";
        $query .= "\n              ,A.name AS brand_name";
        $query .= "\n              ,A.extnl_brand_seqno";
        $query .= "\n         FROM  extnl_brand AS A";
        $query .= "\n              ,extnl_etprs AS B";
        $query .= "\n        WHERE  A.extnl_etprs_seqno = B.extnl_etprs_seqno";
        $query .= "\n          AND  B.pur_prdt = '후공정'";
        $query .= "\n          AND  A.name != ''";
        if ($this->blankParameterCheck($param, "brand_seqno")) {
            $query .= "\n          AND  A.extnl_brand_seqno = ";
            $query .= $param["brand_seqno"];
        } else if ($this->blankParameterCheck($param, "etprs_seqno")) {
            $query .= "\n          AND  B.extnl_etprs_seqno = ";
            $query .= $param["etprs_seqno"];
        }
        $query .= "\n      ) AS T1";
        $query .= "\n LEFT OUTER JOIN after AS T2";
        $query .= "\n ON T1.extnl_brand_seqno = T2.extnl_brand_seqno";
        $query .= "\n LEFT OUTER JOIN after_price AS T3";
        $query .= "\n ON T2.after_seqno = T3.after_seqno";
        $query .= "\n WHERE 1 = 1";
        if ($this->blankParameterCheck($param, "affil")) {
            $query .= "\n   AND T2.affil IN (";
            $query .= $param["affil"];
            $query .= ")";
        }
        if ($this->blankParameterCheck($param, "name")) {
            $query .= "\n   AND T2.name = ";
            $query .= $param["name"];
        }
        if ($this->blankParameterCheck($param, "depth1")) {
            $query .= "\n    AND  T2.depth1 = ";
            $query .= $param["depth1"];
        }
        if ($this->blankParameterCheck($param, "depth2")) {
            $query .= "\n    AND  T2.depth2 = ";
            $query .= $param["depth2"];
        }
        if ($this->blankParameterCheck($param, "depth3")) {
            $query .= "\n    AND  T2.depth3 = ";
            $query .= $param["depth3"];
        }
        if ($this->blankParameterCheck($param, "affil")) {
            $query .= "\n    AND  T2.affil = ";
            $query .= $param["affil"];
        }
        if ($this->blankParameterCheck($param, "subpaper")) {
            $query .= "\n    AND  T2.subpaper = ";
            $query .= $param["subpaper"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 후공정 가격검색
     *
     * @detail 가격 수정시에 사용
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectPrdcAfterPriceModi($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  D.after_price_seqno AS price_seqno";
        $query .= "\n        ,D.basic_price";
        $query .= "\n        ,D.pur_rate";
        $query .= "\n        ,D.pur_aplc_price";
        $query .= "\n        ,D.pur_price";

        $query .= "\n   FROM  after       AS A";
        $query .= "\n        ,extnl_brand AS B";
        $query .= "\n        ,extnl_etprs AS C";
        $query .= "\n        ,after_price AS D";

        $query .= "\n  WHERE  A.extnl_brand_seqno = B.extnl_brand_seqno";
        $query .= "\n    AND  B.extnl_etprs_seqno = C.extnl_etprs_seqno";
        $query .= "\n    AND  A.after_seqno       = D.after_seqno";
        if ($this->blankParameterCheck($param, "price_seqno")) {
            $query .= "\n    AND  D.after_seqno = ";
            $query .= $param["price_seqno"];
        }
        if ($this->blankParameterCheck($param, "name")) {
            $query .= "\n    AND  A.name = ";
            $query .= $param["name"];
        }
        if ($this->blankParameterCheck($param, "depth1")) {
            $query .= "\n    AND  A.depth1 = ";
            $query .= $param["depth1"];
        }
        if ($this->blankParameterCheck($param, "depth2")) {
            $query .= "\n    AND  A.depth2 = ";
            $query .= $param["depth2"];
        }
        if ($this->blankParameterCheck($param, "depth3")) {
            $query .= "\n    AND  A.depth3 = ";
            $query .= $param["depth3"];
        }
        if ($this->blankParameterCheck($param, "brand")) {
            $query .= "\n    AND  B.name = ";
            $query .= $param["brand"];
        }
        if ($this->blankParameterCheck($param, "manu")) {
            $query .= "\n    AND  C.manu_name = ";
            $query .= $param["manu"];
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 후공정 가격검색
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 쿼리 성공여부
     */
    function updatePrdcAfterPrice($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $temp = array();

        $temp["table"] = "after_price";

        $temp["col"]["pur_rate"]       = $param["pur_rate"];
        $temp["col"]["pur_aplc_price"] = $param["pur_aplc_price"];
        $temp["col"]["pur_price"]      = $param["pur_price"];

        $temp["prk"] = "after_price_seqno";
        $temp["prkVal"] = $param["price_seqno"];

        return $this->updateData($conn, $temp);
    }
}
?>
