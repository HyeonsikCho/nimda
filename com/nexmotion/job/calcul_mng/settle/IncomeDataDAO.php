<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/job/common/CommonDAO.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/com/nexmotion/html/calcul_mng/settle/IncomeDataHTML.php");

/**
 * @file IncomeDataDAO.php
 *
 * @brief 정산관리 - 결산 - 수입자료 DAO
 */
class IncomeDataDAO extends CommonDAO {

    function __construct() {
    }

    /*
     * 금전출납부 수입자료 list Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectIncomeList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    SELECT  B.member_seqno, B.office_nick, SUM(A.sales_price) AS sales_price , SUM(A.adjust_price) AS adjust_price, SUM(A.depo_price) as depo_price";
        $query  .= "\n    FROM day_settle AS A, member AS B";
        $query  .= "\n    WHERE A.member_seqno = B.member_seqno ";

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {
            $from = substr($param["date_from"], 1, -1);
            $query .= "\n           AND  update_date >= '";
            $query .= $from . " 00:00:00'";
        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {
            $to = substr($param["date_to"], 1, -1);
            $query .= "\n           AND  update_date <= '";
            $query .= $to . " 23:59:59'";
        }

        //회원 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n           AND  A.member_seqno = ";
            $query .= $param["member_seqno"];

        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }
        $query  .= "\n    GROUP BY B.member_seqno ";
        $query .= "\n  ORDER BY B.member_seqno DESC";

        //limit 조건
        if ($this->blankParameterCheck($param ,"start")
            && $this->blankParameterCheck($param ,"end")) {

            $param["start"] = substr($param["start"], 1, -1);
            $param["end"] = substr($param["end"], 1, -1);

            $query .= "\n LIMIT " . $param["start"] . ",";
            $query .= $param["end"];
        }

        /*
        $query  = "\n    SELECT  A.evid_date";
        $query .= "\n           ,A.regi_date";
        $query .= "\n           ,A.income_price";
        $query .= "\n           ,A.trsf_income_price";
        $query .= "\n           ,A.depo_withdraw_path";
        $query .= "\n           ,A.depo_withdraw_path_detail";
        $query .= "\n           ,B.office_nick";
        $query .= "\n      FROM  cashbook A";
        $query .= "\n      LEFT OUTER JOIN member B";
        $query .= "\n        ON  A.member_seqno = B.member_seqno";
        $query .= "\n     WHERE  (dvs = 'income'";
        $query .= "\n        OR   dvs = 'trsf_income')";

        //판매채널이 있을때
        if ($this->blankParameterCheck($param ,"cpn_admin_seqno")) {

            $query .= "\n            AND  A.cpn_admin_seqno = ";
            $query .= $param["cpn_admin_seqno"];

        }

        //회원 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n           AND  A.member_seqno = ";
            $query .= $param["member_seqno"];

        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);
            $query .= "\n           AND  A.regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);
            $query .= "\n           AND  A.regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $query .= "\n  ORDER BY A.regi_date DESC";
 
        //limit 조건
        if ($this->blankParameterCheck($param ,"start") 
                && $this->blankParameterCheck($param ,"end")) {
 
            $param["start"] = substr($param["start"], 1, -1);
            $param["end"] = substr($param["end"], 1, -1); 

            $query .= "\n LIMIT " . $param["start"] . ",";
            $query .= $param["end"]; 
        }
*/
        $result = $conn->Execute($query);

        return $result;
    }

    function selectWithDraw($conn, $param) {
        if (!$this->connectionCheck($conn)) return false;
        $param = $this->parameterArrayEscape($conn, $param);

        $query   = "\n    SELECT depo_price, depo_way, deal_date";
        $query  .= "\n    FROM member_pay_history ";
        $query  .= "\n    WHERE dvs = '입금' AND member_seqno = " . $param['member_seqno'] . "";

            //시작날짜가 있을때
            if ($this->blankParameterCheck($param ,"from_date")) {
                $from = substr($param["from_date"], 1, -1);
                $query .= "\n           AND  deal_date >= '";
                $query .= $from . " 00:00:00'";
        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"to_date")) {
            $to = substr($param["to_date"], 1, -1);
            $query .= "\n           AND  deal_date <= '";
            $query .= $to . " 23:59:59'";
        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }

        $rs = $conn->Execute($query);
        return $rs;
    }

    function selectWithDrawSum($conn, $param) {
        if (!$this->connectionCheck($conn)) return false;
        //$param['member_seqno'] = $this->parameterArrayEscape($conn, $param['member_seqno']);

        $query   = "\n    SELECT COUNT(*) as cnt, SUM(sales_price) as sales_price, SUM(adjust_price) as adjust_price, SUM(depo_price) as depo_price ";
        $query  .= "\n    FROM day_settle AS A";
        $query  .= "\n    WHERE 1=1 ";

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {
            $from = substr($param["date_from"], 1, -1);
            $query .= "\n           AND  update_date >= '";
            $query .= $param["date_from"] . " 00:00:00'";
        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {
            $to = substr($param["date_to"], 1, -1);
            $query .= "\n           AND  update_date <= '";
            $query .= $param["date_to"] . " 23:59:59'";
        }

        //회원 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n           AND  A.member_seqno = ";
            $query .= $param["member_seqno"];

        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }

        $rs = $conn->Execute($query);
        return $rs;
    }

    function selectIncomeSum($conn, $param)
    {
        if (!$this->connectionCheck($conn)) return false;
        $param = $this->parameterArrayEscape($conn, $param);

        $query = "\n    SELECT  SUM(A.sales_price), SUM(A.adjust_price)";
        $query .= "\n    FROM day_settle AS A, member AS B";
        $query .= "\n    WHERE A.member_seqno = B.member_seqno ";

        $rs = $conn->Execute($query);
        return $rs;
    }

    /*
     * 금전출납부 수입자료 list Count
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function countIncomeList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n    select SUM(cnt) as sum from (SELECT count(*) AS cnt ";
        $query  .= "\n    FROM day_settle AS A, member AS B";
        $query  .= "\n    WHERE A.member_seqno = B.member_seqno ";

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {
            $from = substr($param["date_from"], 1, -1);
            $query .= "\n           AND  update_date >= '";
            $query .= $from . " 00:00:00'";
        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {
            $to = substr($param["date_to"], 1, -1);
            $query .= "\n           AND  update_date <= '";
            $query .= $to . " 23:59:59'";
        }

        //회원 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n           AND  A.member_seqno = ";
            $query .= $param["member_seqno"];

        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }
        $query  .= "\n    GROUP BY B.member_seqno ";
        $query .= "\n  ORDER BY B.member_seqno DESC) as a group by cnt";

        /*
        $query  = "\n    SELECT  count(*) cnt";
        $query .= "\n      FROM  cashbook A";
        $query .= "\n      LEFT OUTER JOIN member B";
        $query .= "\n        ON  A.member_seqno = B.member_seqno";
        $query .= "\n     WHERE  (dvs = 'income'";
        $query .= "\n        OR   dvs = 'trsf_income')";

        //판매채널이 있을때
        if ($this->blankParameterCheck($param ,"cpn_admin_seqno")) {

            $query .= "\n            AND  A.cpn_admin_seqno = ";
            $query .= $param["cpn_admin_seqno"];

        }

        //회원 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n           AND  A.member_seqno = ";
            $query .= $param["member_seqno"];

        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);
            $query .= "\n           AND  A.regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);
            $query .= "\n           AND  A.regi_date <= '";
            $query .= $to . " 23:59:59'";

        }
*/
        $result = $conn->Execute($query);

        return $result;
    }

    /*
     * 이체수입,이체지출 합계 가져오기 Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectIncomeSumPrice($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $dvs = substr($param["dvs"], 1,-1);

        $query  = "\n    SELECT";
        $query .= "\n            SUM(income_price)  AS income";
        $query .= "\n           ,SUM(trsf_income_price)   AS trsf_income";
        $query .= "\n      FROM  cashbook A";
        $query .= "\n      LEFT OUTER JOIN member B";
        $query .= "\n        ON  A.member_seqno = B.member_seqno";
        $query .= "\n     WHERE  (dvs = 'income'";
        $query .= "\n        OR   dvs = 'trsf_income')";

        if ($this->blankParameterCheck($param ,"sum_dvs")) {

            $query .= "\n       AND  A.depo_withdraw_path = ";
            $query .= $param["sum_dvs"];

        }

        //판매채널이 있을때
        if ($this->blankParameterCheck($param ,"cpn_admin_seqno")) {

            $query .= "\n            AND  A.cpn_admin_seqno = ";
            $query .= $param["cpn_admin_seqno"];

        }

        //회원 일련번호가 있을때
        if ($this->blankParameterCheck($param ,"member_seqno")) {

            $query .= "\n           AND  A.member_seqno = ";
            $query .= $param["member_seqno"];

        }

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param ,"depo_path")) {

            $query .= "\n            AND  A.depo_withdraw_path = ";
            $query .= $param["depo_path"];

        }

        //입출금경로 상세가 있을때
        if ($this->blankParameterCheck($param ,"depo_path_detail")) {

            $query .= "\n            AND  A.depo_withdraw_path_detail = ";
            $query .= $param["depo_path_detail"];

        }

        //시작날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_from")) {

            $from = substr($param["date_from"], 1, -1);
            $query .= "\n           AND  A.regi_date >= '";
            $query .= $from . " 00:00:00'";

        }

        //종료날짜가 있을때
        if ($this->blankParameterCheck($param ,"date_to")) {

            $to = substr($param["date_to"], 1, -1);
            $query .= "\n           AND  A.regi_date <= '";
            $query .= $to . " 23:59:59'";

        }

        $result = $conn->Execute($query);

        return $result;
    }


    /*
     * 입출금 상세 list Select 
     * $conn : DB Connection
     * return : resultSet 
     */ 
    function selectPathDetail($conn, $param) {

        if (!$this->connectionCheck($conn)) return false; 
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\n    SELECT  B.name";
        $query .= "\n      FROM  depo_withdraw_path A";
        $query .= "\n           ,depo_withdraw_path_detail B";
        $query .= "\n     WHERE  A.depo_withdraw_path_seqno = ";
        $query .= "B.depo_withdraw_path_seqno";

        //입출금경로가 있을때
        if ($this->blankParameterCheck($param, "path")) {
            $query .= "\n       AND A.name = " . $param["path"];
        }

        $result = $conn->Execute($query);

        return $result;
    }



}
?>
