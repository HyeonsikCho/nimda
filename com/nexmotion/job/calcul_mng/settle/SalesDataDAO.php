<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/calcul_mng/settle/SalesDataHTML.php");

/**
 * @file SalesDataDAO.php
 *
 * @brief 정산관리 - 결산 - 매출자료 DAO
 */
class SalesDataDAO extends CommonDAO {

    function __construct() {
    }

    /*
     * 매출자료 list Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectSalesList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n  SELECT  A.order_regi_date";
        $query .= "\n         ,C.depar_name";
        $query .= "\n         ,A.oper_sys";
        $query .= "\n         ,D.cate_name";
        $query .= "\n         ,A.pay_price";
        $query .= "\n         ,A.use_point_price";
        $query .= "\n         ,A.grade_sale_price";
        $query .= "\n         ,A.event_price";
        $query .= "\n         ,A.cp_price";
        $query .= "\n    FROM  order_common A";
        $query .= "\n         ,member B";
        $query .= "\n         ,depar_admin C";
        $query .= "\n         ,cate D";
        $query .= "\n         ,member_mng E";
        $query .= "\n         ,empl F";
        $query .= "\n   WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n     AND  A.order_state > '1180'";
        $query .= "\n     AND  B.member_seqno = E.member_seqno";
        $query .= "\n     AND  E.resp_deparcode = C.depar_code";
        $query .= "\n     AND E.resp_deparcode = F.depar_code ";
        $query .= "\n     AND A.order_mng = F.name ";
        $query .= "\n     AND  A.cate_sortcode = D.sortcode";

        //카테고리가 있을때
        if ($this->blankParameterCheck($param ,"cpn_admin_seqno")) {
            $query .= "\n     AND  A.cpn_admin_seqno = ";
            $query .= $param["cpn_admin_seqno"];
            $query .= "\n            AND  C.cpn_admin_seqno = ";
            $query .= $param["cpn_admin_seqno"];
        }

        //카테고리가 있을때
        if ($this->blankParameterCheck($param ,"sortcode")) {

            $query .= "\n            AND D.sortcode LIKE '";
            $query .= substr($param["sortcode"], 1,-1) . "%'";

        }
 
        //팀 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"depar_dvs")) {

            $query .= "\n            AND  C.depar_code = ";
            $query .= $param["depar_dvs"];

        }
 
        //데이터구분(운영체제)이 있을때
        if ($this->blankParameterCheck($param ,"oper_sys")) {

            $query .= "\n            AND  A.oper_sys = ";
            $query .= $param["oper_sys"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);

            $query .= "\n           AND  A.order_regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);

            $query .= "\n           AND  A.order_regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $query .= "\n  ORDER BY A.order_regi_date DESC";
 
        //limit 조건
        if ($this->blankParameterCheck($param ,"start") 
                && $this->blankParameterCheck($param ,"end")) {
 
            $param["start"] = substr($param["start"], 1, -1);
            $param["end"] = substr($param["end"], 1, -1); 

            $query .= "\n LIMIT " . $param["start"] . ",";
            $query .= $param["end"]; 
        }

        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 매출자료 list Count
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function countSalesList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n  SELECT  count(*) AS cnt";
        $query .= "\n    FROM  order_common A";
        $query .= "\n         ,member B";
        $query .= "\n         ,depar_admin C";
        $query .= "\n         ,cate D";
        $query .= "\n         ,member_mng E";
        $query .= "\n         ,empl F";
        $query .= "\n   WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n     AND  B.member_seqno = E.member_seqno";
        $query .= "\n     AND  E.resp_deparcode = C.depar_code";
        $query .= "\n     AND  A.cate_sortcode = D.sortcode";
        $query .= "\n     AND E.resp_deparcode = F.depar_code ";
        $query .= "\n     AND A.order_mng = F.name ";
        $query .= "\n     AND  A.order_state > '1180'";
        $query .= "\n     AND  A.cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];
        $query .= "\n            AND  C.cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];

        //카테고리가 있을때
        if ($this->blankParameterCheck($param ,"sortcode")) {

            $query .= "\n            AND D.sortcode LIKE '";
            $query .= substr($param["sortcode"], 1,-1) . "%'";

        }

        //팀 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"depar_dvs")) {

            $query .= "\n            AND  C.depar_code = ";
            $query .= $param["depar_dvs"];

        }
 
        //데이터구분(운영체제)이 있을때
        if ($this->blankParameterCheck($param ,"oper_sys")) {

            $query .= "\n            AND  A.oper_sys = ";
            $query .= $param["oper_sys"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);

            $query .= "\n           AND  A.order_regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);

            $query .= "\n           AND  A.order_regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $result = $conn->Execute($query);

        return $result;

    }

    /*
     * 회원 list Count
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function countMember($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $query .= "\n  SELECT  COUNT(DISTINCT A.member_seqno) AS cnt";
        $query .= "\n    FROM  order_common A";
        $query .= "\n         ,member B";
        $query .= "\n         ,depar_admin C";
        $query .= "\n         ,cate D";
        $query .= "\n         ,member_mng E";
        $query .= "\n         ,empl F";
        $query .= "\n   WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n     AND  A.order_state != '1180'";
        $query .= "\n     AND  B.member_seqno = E.member_seqno";
        $query .= "\n     AND  E.resp_deparcode = C.depar_code";
        $query .= "\n     AND  A.cate_sortcode = D.sortcode";
        $query .= "\n     AND E.resp_deparcode = F.depar_code ";
        $query .= "\n     AND A.order_mng = F.name ";
        $query .= "\n     AND  A.cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];
        $query .= "\n     AND  C.cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];

        //카테고리가 있을때
        if ($this->blankParameterCheck($param ,"sortcode")) {

            $query .= "\n            AND D.sortcode LIKE '";
            $query .= substr($param["sortcode"], 1,-1) . "%'";

        }

        //팀 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"depar_dvs")) {

            $query .= "\n            AND  C.depar_code = ";
            $query .= $param["depar_dvs"];

        }
 
        //데이터구분(운영체제)이 있을때
        if ($this->blankParameterCheck($param ,"oper_sys")) {

            $query .= "\n            AND  A.oper_sys = ";
            $query .= $param["oper_sys"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);

            $query .= "\n           AND  A.order_regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);

            $query .= "\n           AND  A.order_regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $result = $conn->Execute($query);

        return $result;


    }

    /*
     * 매출 합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectSumPrice($conn, $param, $type) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        if ($type == "sales") {

            $query .= "\n  SELECT  SUM(A.pay_price)  AS sales";

        } else {

            $query .= "\n  SELECT  SUM(A.use_point_price)  AS point_price";
            $query .= "\n         ,SUM(A.grade_sale_price)  AS grade_price";
            $query .= "\n         ,SUM(A.event_price)  AS event_price";
            $query .= "\n         ,SUM(A.cp_price)  AS cp_price";

        }

        $query .= "\n    FROM  order_common A";
        $query .= "\n         ,member B";
        $query .= "\n         ,depar_admin C";
        $query .= "\n         ,cate D";
        $query .= "\n         ,member_mng E";
        $query .= "\n   WHERE  A.member_seqno = B.member_seqno";
        $query .= "\n     AND  B.member_seqno = E.member_seqno";
        $query .= "\n     AND  A.order_state != '1180'";
        $query .= "\n     AND  E.resp_deparcode = C.depar_code";
        $query .= "\n     AND  A.cate_sortcode = D.sortcode";
        $query .= "\n     AND  A.cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];
        $query .= "\n     AND  C.cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];

        //카테고리가 있을때
        if ($this->blankParameterCheck($param ,"sortcode")) {

            $query .= "\n            AND D.sortcode LIKE '";
            $query .= substr($param["sortcode"], 1,-1) . "%'";

        }

        //팀 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"depar_dvs")) {

            $query .= "\n            AND  C.depar_code = ";
            $query .= $param["depar_dvs"];

        }
 
        //데이터구분(운영체제)이 있을때
        if ($this->blankParameterCheck($param ,"oper_sys")) {

            $query .= "\n            AND  A.oper_sys = ";
            $query .= $param["oper_sys"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);

            $query .= "\n           AND  A.order_regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);

            $query .= "\n           AND  A.order_regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $result = $conn->Execute($query);

        return $result;

    }

    /*
     * 조정 합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectSumAdjustPrice($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query .= "\n  SELECT  SUM(price)  AS discount";
        $query .= "\n    FROM  adjust";
        $query .= "\n   WHERE  cpn_admin_seqno = ";
        $query .= $param["cpn_admin_seqno"];
        $query .= "\n     AND  input_dvs = '충전'";
        $query .= "\n     AND  input_dvs_detail IN ('주문취소'";
        $query .= "\n                              ,'사고처리'";
        $query .= "\n                              ,'별도견적'";
        $query .= "\n                              ,'DC'";
        $query .= "\n                              ,'후가공'";
        $query .= "\n                              ,'기타'";
        $query .= "\n                              ,'배송비')";

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);

            $query .= "\n           AND  regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);

            $query .= "\n           AND  regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $result = $conn->Execute($query);

        return $result;
    }

}

?>
