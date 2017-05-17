<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/html/common/MakeCommonHtml.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/doc/common/OrderInfoPopDOC.php');

/*! 공통 DAO Class */
class CommonDAO {

    var $errorMessage = "";

    function __construct() {
    }

    /**
     * @brief 다중 데이터 수정 쿼리 함수 (공통) <br>
     *        param 배열 설명 <br>
     *        $param : <br>
     *        $param["table"] = "테이블명"<br>
     *        $param["col"]["컬럼명"] = "수정데이터" (다중)<br>
     *        $param["prk"] = "primary key colulm"<br>
     *        $param["prkVal"] = "primary data"  ex) 1,2,3,4
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function updateMultiData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $prkArr = str_replace(" ", "", $param["prkVal"]);
        $prkArr = str_replace("'", "", $prkArr);
        $prkArr = explode(",", $prkArr);

        $parkVal = "";

        for ($i = 0; $i < count($prkArr); $i++) {
            $prkVal .= $conn->qstr($prkArr[$i], get_magic_quotes_gpc()) . ",";
        }
        $prkVal = substr($prkVal, 0, -1);

        $query = "\n UPDATE " . $param["table"]  . " set";

        $i = 0;
        $col = "";
        $value = "";

        foreach ($param["col"] as $key => $val) {

            $inchr = $conn->qstr($val,get_magic_quotes_gpc());

            if ($i == 0) {
                $value  .= "\n " . $key . "=" . $inchr;
            } else {
                $value  .= "\n ," . $key . "=" . $inchr;
            }

            $i++;
        }

        $query .= $value;
        $query .= " WHERE " . $param["prk"] . " in(";
        $query .= $prkVal . ")";

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            $errorMessage = "데이터 수정에 실패 하였습니다.";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 데이터 수정 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["col"]["컬럼명"] = "수정데이터" (다중)<br>
     *        $param["prk"] = "primary key colulm"<br>
     *        $param["prkVal"] = "primary data" <br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function updateData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query = "\n UPDATE " . $param["table"]  . " set";

        $i = 0;
        $col = "";
        $value = "";

        foreach ($param["col"] as $key => $val) {

         //   $inchr = $val;
            $inchr = $conn->qstr($val,get_magic_quotes_gpc());

            if ($i == 0) {
                $value  .= "\n " . $key . "=" . $inchr;
            } else {
                $value  .= "\n ," . $key . "=" . $inchr;
            }

            $i++;
        }

        $query .= $value;
        $query .= " WHERE " . $param["prk"] . "=" . $conn->qstr($param["prkVal"], get_magic_quotes_gpc());

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 데이터 삽입 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["col"]["컬럼명"] = "데이터" (다중)<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function insertData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query = "\n INSERT INTO " . $param["table"] . "(";

        $i = 0;
        $col = "";
        $value = "";

        foreach ($param["col"] as $key => $val) {

            $inchr = $conn->qstr($val,get_magic_quotes_gpc());

            if ($i == 0) {
                $col  .= "\n " . $key;
                $value  .= "\n " . $inchr;
            } else {
                $col  .= "\n ," . $key;
                $value  .= "\n ," . $inchr;
            }

            $i++;
        }

        $query .= $col;
        $query .= "\n ) VALUES (";
        $query .= $value;
        $query .= "\n )";

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            $errorMessage = "데이터 입력에 실패 하였습니다.";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 데이터 삽입/수정 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["col"]["컬럼명"] = "데이터" (다중)<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function replaceData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query = "\n INSERT INTO " . $param["table"] . "(";

        $i = 0;
        $col = "";
        $value = "";

        foreach ($param["col"] as $key => $val) {

            $inchr = $conn->qstr($val,get_magic_quotes_gpc());
            if ($i == 0) {
                $col  .= "\n " . $key;
                $value  .= "\n " . $inchr;
            } else {
                $col  .= "\n ," . $key;
                $value  .= "\n ," . $inchr;
            }

            $i++;
        }

        $query .= $col;
        $query .= "\n ) VALUES (";
        $query .= $value;
        $query .= "\n )";
        $query .= "\n ON DUPLICATE KEY UPDATE";

        $i = 0;
        $col = "";
        $value = "";

        reset($param["col"]);

        foreach ($param["col"] as $key => $val) {

            $inchr = $conn->qstr($val,get_magic_quotes_gpc());

            if ($i == 0) {
                $value  .= "\n " . $key . "=" . $inchr;
            } else {
                $value  .= "\n ," . $key . "=" . $inchr;
            }

            $i++;
        }
        $query .= $value;

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            $errorMessage = "데이터 입력에 실패 하였습니다.";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 다중 데이터 삭제 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["prk"] = "primary key colulm" <br>
     *        $param["prkVal"] = "primary data"  ex) 1,2,3,4 <br>
     *        $param["not"] = "제외 검색"  ex) Y<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function deleteMultiData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query  = "\n DELETE ";
        $query .= "\n   FROM " . $param["table"];
        $query .= "\n  WHERE " . $param["prk"];
        $query .= "\n     IN (";

        $prkValCount = count($param["prkVal"]);
        for ($i = 0; $i < $prkValCount; $i++) {
            $val = $conn->qstr($param["prkVal"][$i], get_magic_quotes_gpc());
            $query .= $val;

            if ($i !== $prkValCount - 1) {
                $query .= ",";
            }
        }
        $query .= ")";

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            $errorMessage = "데이터 삭제에 실패 하였습니다.";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 데이터 삭제 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["prk"] = "primary key column"<br>
     *        $param["prkVal"] = "primary data" <br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function deleteData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query  = "\n DELETE ";
        $query .= "\n   FROM " . $param["table"];
        $query .= "\n  WHERE " . $param["prk"];
        $query .= "\n       =" . $conn->qstr($param["prkVal"], get_magic_quotes_gpc());

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            $errorMessage = "데이터 삭제에 실패 하였습니다.";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief 전체 데이터 삭제 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["prk"] = "primary key colulm"<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function deleteAllData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query  = "\n DELETE ";
        $query .= "\n   FROM " . $param["table"];
        $query .= "\n  WHERE " . $param["prk"] . " >= 0";

        $resultSet = $conn->Execute($query);

        if ($resultSet === FALSE) {
            $errorMessage = "데이터 삭제에 실패 하였습니다.";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief DISTINCT 데이터 검색 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["col"] = "컬럼명"<br>
     *        $param["where"]["컬럼명"] = "조건" (다중)<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function distinctData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query = "\n SELECT DISTINCT " . $param["col"] . " FROM " . $param["table"];
        $i = 0;
        $value = "";

        if ($param["where"]) {

            foreach ($param["where"] as $key => $val) {

                $inchr = $conn->qstr($val, get_magic_quotes_gpc());

                if ($i == 0) {
                        $value  .= "\n WHERE " . $key . "=" . $inchr;
                 } else {
                        $value  .= "\n   AND " . $key . "=" . $inchr;
                 }
                $i++;
            }
        }

        $query .= $value;

        if ($param["order"]) {
            $query .= "\n ORDER BY " . $param["order"];
        }

        //Query Cache
        if ($param["cache"] == 1) {
            $rs = $conn->CacheExecute(1800, $query);
        } else {
            $rs = $conn->Execute($query);
        }

        return $rs;
    }

    /**
     * @brief 데이터 검색 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["col"] = "컬럼명"<br>
     *        $param["where"]["컬럼명"] = "조건" (다중)<br>
     *        $param["not"]["컬럼명"] = "조건" (다중)<br>
     *        $param["order"] = "order by 조건"<br>
     *        $param["group"] = "group by 조건"<br>
     *        $param["cache"] = "1" 캐쉬 생성<br>
     *        $param["limit"]["start"] = 리미트 시작값<br>
     *        $param["limit"]["end"] =  리미트 종료값<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function selectData($conn, $param) {
        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        /*
        //주문배송, 회원, 주문 공통, 가상계좌, 견적
        if ($param["table"] == "member" || $param["table"] == "order_common" ||
            $param["table"] == "order_dlvr" || $param["table"] == "virt_ba_admin" ||
            $param["table"] == "esti") {
            echo "접근이 허용되지 않는 테이블 입니다.";
            return false;
        }
        */

        $query = "\n SELECT " . $param["col"] . " FROM " . $param["table"];

        $i = 0;
        $col = "";
        $value = "";

        if ($param["where"]) {

            foreach ($param["where"] as $key => $val) {

                $inchr = $conn->qstr($val,get_magic_quotes_gpc());

                if ($i == 0) {
                        $value  .= "\n WHERE " . $key . "=" . $inchr;
                 } else {
                        $value  .= "\n   AND " . $key . "=" . $inchr;
                 }
                $i++;
            }
        }

        //임시로 만듬
        if ($param["not"]) {
            foreach ($param["not"] as $key => $val) {

                $inchr = $conn->qstr($val,get_magic_quotes_gpc());
                $value  .= "\n AND NOT " . $key . "=" . $inchr;
                $i++;
            }
        }

        //like search
        if ($param["like"]) {
            foreach ($param["like"] as $key => $val) {

                $inchr = substr($conn->qstr($val,get_magic_quotes_gpc()),1, -1);

                if ($i == 0) {
                        $value  .= "\n WHERE " . $key . " LIKE '%" . $inchr . "%'";
                 } else {
                        $value  .= "\n   AND " . $key . " LIKE '%" . $inchr . "%'";
                 }
                $i++;
            }
        }

        //back like search
        if ($param["blike"]) {
            foreach ($param["blike"] as $key => $val) {

                $inchr = substr($conn->qstr($val,get_magic_quotes_gpc()),1, -1);

                if ($i == 0) {
                        $value  .= "\n WHERE " . $key . " LIKE '" . $inchr . "%'";
                 } else {
                        $value  .= "\n   AND " . $key . " LIKE '" . $inchr . "%'";
                 }
                $i++;
            }
        }

        $query .= $value;

        if ($param["group"]) {
            $query .= "\n GROUP BY " . $param["group"];
        }

        if ($param["order"]) {
            $query .= "\n ORDER BY " . $param["order"];
        }

        if ($param["limit"]) {

            $query .= "\n LIMIT " . $param["limit"]["start"] . ",";
            $query .= $param["limit"]["end"];
        }

        //Query Cache
        if ($param["cache"] == 1) {
            $rs = $conn->CacheExecute(1800, $query);
        } else {
            $rs = $conn->Execute($query);
        }

        return $rs;
    }

    /**
     * @brief COUNT 데이터 검색 쿼리 함수 (공통)<br>
     *        param 배열 설명<br>
     *        $param : $param["table"] = "테이블명"<br>
     *        $param["where"]["컬럼명"] = "조건" (다중)<br>
     *        $param["cache"] = "1" 캐쉬 생성<br>
     *        $param["limit"]["start"] = 리미트 시작값<br>
     *        $param["limit"]["end"] =  리미트 종료값<br>
     * @param $conn DB Connection
     * @param $param 파라미터 인자 배열
     * @return boolean
     */
    function countData($conn, $param) {

        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        $query = "\n SELECT count(*) cnt  FROM " . $param["table"];

        $i = 0;
        $col = "";
        $value = "";

        if ($param["where"]) {

            foreach ($param["where"] as $key => $val) {

                $inchr = $conn->qstr($val,get_magic_quotes_gpc());

                if ($i == 0) {
                        $value  .= "\n WHERE " . $key . "=" . $inchr;
                 } else {
                        $value  .= "\n   AND " . $key . "=" . $inchr;
                 }
                $i++;
            }
        }

        if ($param["like"]) {

            foreach ($param["like"] as $key => $val) {

                $inchr = $conn->qstr($val,get_magic_quotes_gpc());

                if ($i == 0) {
                        $value  .= "\n WHERE " . $key . " LIKE " . $inchr;
                 } else {
                        $value  .= "\n   AND " . $key . " LIKE " . $inchr;
                 }
                $i++;
            }
        }

        if ($param["group"]) {
            $query .= "\n GROUP BY " . $param["group"];
        }

       if ($param["limit"]) {

            $query .= "\n LIMIT " . $param["limit"]["start"] . ",";
            $query .= $param["limit"]["end"];
        }

        $query .= $value;

        $rs = $conn->Execute($query);
        return $rs;

    }

    /**
     * @brief 직원별 페이지 권한 검사
     * @param $conn DB Connection
     * @param $seqno 직원 일련번호
     * @param $path 페이지 경로
     * @return boolean
     */
    function checkAuth($conn, $seqno, $path) {

        if (!$conn) {
            echo "connection failed\n";
            return false;
        }

        $query  = "\n SELECT auth_yn FROM auth_admin_page";
        $query .= "\n  WHERE page_url='" . $path . "'";
        $query .= "\n    AND empl_seqno='" . $seqno . "'";

        $rs = $conn->Execute($query);

        if ($rs->fields["auth_yn"] == "N") {
            echo "<script>";
            echo "    alert('접근 권한이 없습니다.');";
            echo "    history.go(-1);";
            echo "</script>";

        //이것도 'N'일때로 추가해야함
        } else if (!$rs->fields["auth_yn"]) {
            echo "<script>";
            echo "    alert('접근 권한을 설정해주세요.');";
            echo "    history.go(-1);";
            echo "</script>";
        }
    }

    /**
     * @brief 직원별 페이지 권한 url
     * @param $conn DB Connection
     * @param $seqno 직원 일련번호
     * @param $path 페이지 경로
     * @return boolean
     */
    function selectAuthPage($conn, $param) {

        if (!$conn) {
            echo "connection failed\n";
            return false;
        }

        $query  = "\n SELECT page_url FROM auth_admin_page";
        $query .= "\n  WHERE empl_seqno='" . $param["empl_seqno"] . "'";
        $query .= "\n    AND auth_yn = 'Y'";

        //해당페이지
        if ($this->blankParameterCheck($param ,"section")) {
            $query .= "\n   AND page_url LIKE '%/" . $param["section"] . "/%'";

        }

        $query .= "\n  ORDER BY auth_admin_page_seqno ASC";
        $query .= "\n  LIMIT 1";

        $rs = $conn->Execute($query);

        return $rs;
    }



    /**
     * @brief 커넥션 검사
     * @param $conn DB Connection
     * @return boolean
     */
    function connectionCheck($conn) {
        if (!$conn) {
            echo "master connection failed\n";
            return false;
        }

        return true;
    }

    /**
     * @brief SQL 인젝션 방지용
     *
     * @param $conn  = DB Connection
     * @param $param = 검색조건
     *
     * @return 변환 된 인자
     */
    function parameterEscape($conn, $param) {
        $param = @htmlspecialchars($param, ENT_QUOTES, "UTF-8", false);
        $ret = $conn->qstr($param, get_magic_quotes_gpc());
        return $ret;
    }

    /**
     * @brief SQL 인젝션 방지용, 배열
     *
     * @detail $except_arr 배열은 $except["제외할 필드명"] = true
     * 형식으로 입력받는다.
     *
     * @param $conn       = DB Connection
     * @param $param      = 검색조건 배열
     * @param $except_arr = 이스케이프 제외할 필드명
     *
     * @return 변환 된 배열
     */
    function parameterArrayEscape($conn, $param, $except_arr = null) {
        if (!is_array($param)) return false;

        $arrSize = count($param);

        foreach ($param as $key => $val) {
            if ($except_arr[$key] === true) {
                continue;
            }

            if (is_array($val)) {
                $val = $this->parameterArrayEscape($conn, $val, $except_arr);
            } else {
                $val = $this->parameterEscape($conn, $val);
            }

            $param[$key] = $val;
        }

        return $param;
    }

   /**
     * @brief NULL 이거나 공백값('')이 아닌 파라미터만 체크
     * @param $param 임의의 배열 인자
     * @param $key 임의의 배열 인자의 키
     * @return boolean
     */
    function blankParameterCheck($param, $key) {
        // 파라미터가 빈 값이 아닐경우
        if ($param !== ""
                && isset($param[$key]) === true
                && $param[$key] !== ""
                && $param[$key] !== ''
                && $param[$key] !== "''"
                && $param[$key] !== "NULL"
                && $param[$key] !== "null") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief CUD 실패시 입력된 에러메시지 반환
     * @return 에러메시지
     */
    function getErrorMessage() {
        return $errorMessage;
    }

    /**
     * @brief 캐쉬를 삭제하는 함수
     * @param $conn DB Connection
     */
    function cacheFlush($conn) {
        $conn->CacheFlush();
    }

    /**
     * @brief 직원 권한 페이지 추가 기본 함수
     * @param $conn DB Connection
     * @param $auth_page 권한 페이지
     * @param $seqno 직원 일련번호
     */
    function addAuthDefault($conn, $auth_page, $seqno) {

        $page_url = "";
        $auth_info = "";

        $high_check = 1;
        $high_cnt = 1;
        $tmp_high_name = "";

        $most_check = 0;
        $most_cnt = 1;
        $tmp_most_name = "";

        foreach ($auth_page as $key => $val) {
            $page_url = $key;
            $auth_info = $val;

            $auth_list = explode("|", $auth_info);
            $most_page_name = $auth_list[0];
            $high_page_name = $auth_list[1];
            $page_name = $auth_list[2];

            if ($tmp_high_name == $high_page_name) {
                $high_check ++;

                //임시 이름과 상위 페이지 이름이 다를 경우
            } else {
                $tmp_high_name = $high_page_name;
                $high_check = 1;
            }

            //임시 이름와 최상위 페이지 이름이 같을 경우
            if ($tmp_most_name == $most_page_name) {

                //임시 이름와 최상위 페이지 이름이 다를 경우
            } else {
                $tmp_most_name = $most_page_name;
                $most_check ++;
            }

            $param["table"] = "auth_admin_page";
            $param["col"]["empl_seqno"] = $seqno;
            $param["col"]["page_url"] = $page_url;
            $param["col"]["most_page_name"] = $most_page_name;
            $param["col"]["high_page_name"] = $high_page_name;
            $param["col"]["page_name"] = $page_name;
            $param["col"]["auth_yn"] = "N";
            $param["col"]["array_num"] = $most_check;
            $param["col"]["detail_array_num"] = $high_check;

            $result = $this->insertData($conn,$param);
        }
    }

    /**
     * @brief 카테고리 검색
     *
     * @param $conn = connection identifier
     * @param $sortcode = connection identifier
     *
     * @return 검색결과
     */
    function selectCateList($conn, $sortcode = null) {
        $param = array();
        $param["col"]   = "sortcode, cate_name";
        $param["table"] = "cate";
        if ($sortcode === null) {
            $param["where"]["cate_level"] = "1";
        } else {
            $param["where"]["high_sortcode"] = $sortcode;
        }

        $rs = $this->selectData($conn, $param);

        $basic_option = "대분류(전체)";

        if (strlen($sortcode) === 3) {
            $basic_option = "중분류(전체)";
        } else if (strlen($sortcode) === 6) {
            $basic_option = "소분류(전체)";
        }

        return makeOptionHtml($rs, "sortcode", "cate_name", $basic_option);
    }

    /**
     * @brief 판매채널 검색
     *
     * @param $conn  = connection identifier
     *
     * @return option html
     */
    function selectSellSite($conn) {
        $temp = array();
        $temp["col"]   = "cpn_admin_seqno, sell_site";
        $temp["table"] = "cpn_admin";

        $rs = $this->selectData($conn, $temp);

        return makeOptionHtml($rs, "cpn_admin_seqno", "sell_site", "", "N");
    }

    /**
     * @brief 직원 부서(팀)
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectDeparInfo($conn, $param) {

        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $val = substr($param["depar_code"], 1, -1);

        $query  = "\nSELECT  A.depar_name ";
        $query .= "\n       ,A.depar_code ";
        $query .= "\n  FROM  depar_admin AS A ";
        $query .= "\n WHERE  (depar_code LIKE '" . $val ."%'";
        $query .= "\n    OR  depar_code = ". $param["depar_code2"] .")";
        $query .= "\n   AND  depar_level = 2";

        //카테고리 분류코드 빈값 체크
        if ($this->blankParameterCheck($param ,"sell_site")) {
            $query .= "\n   AND  A.cpn_admin_seqno = $param[sell_site] ";
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 접수(영업)팀 검색
     *
     * @param $conn  = connection identifier
     * @param $param = 검색조건 파라미터
     *
     * @return 검색결과
     */
    function selectReceiptDepar($conn, $param) {
        //커넥션 체크
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        //인젝션 어택 방지
        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT /* 접수팀 검색 공통쿼리  */";
        $query .= "\n        depar_code";
        $query .= "\n       ,depar_name";
        $query .= "\n   FROM depar_admin";
        $query .= "\n  WHERE high_depar_code = '003'";
        $query .= "\n    AND expo_yn = 'Y'";
        $query .= "\n    AND cpn_admin_seqno = " . $param["cpn_admin_seqno"];
        // 나중에 자기 속한 영업팀만 볼 경우 감안해서 주석
        // ca cm cs 별로 처리할건지 부서명으로 처리할건지 로직필요
        //if ($this->blankParameterCheck($param ,"depar_code")) {
        //    $query .= "\n    AND  A.depar_name = " . $param["depar_name"];
        //}

        return $conn->Execute($query);
    }

    /**
     * @brief 주문 일련번호로 주문 내용 팝업 html 생성
     *
     * @param $conn  = connection identifier
     * @param $seqno = 주문 일련번호
     *
     * @return 주문정보팝업 html
     */
    function selectOrderInfoHtml($conn, $seqno) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $seqno = $this->parameterEscape($conn, $seqno);

        $query  = "\n SELECT  prdt_basic_info";
        $query .= "\n        ,prdt_add_info";
        $query .= "\n        ,prdt_price_info";
        $query .= "\n        ,prdt_pay_info";

        $query .= "\n   FROM  order_common";
        $query .= "\n  WHERE  order_common_seqno = %s";

        $query  = sprintf($query, $seqno);

        $rs = $conn->Execute($query);

        return makeOrderInfoHtml($rs->fields);
    }

    /**
     * @brief 입력받은 값으로 사내 닉네임 검색
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     */
    function selectOfficeName($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  member_name";
        $query .= "\n        ,member_seqno";
        $query .= "\n        ,office_nick";
        $query .= "\n        ,cpn_admin_seqno";
        $query .= "\n        ,CONCAT(office_nick, '(', member_name, ')') AS full_name";
        $query .= "\n   FROM  member";
        $query .= "\n  WHERE  1 = 1";

        if ($this->blankParameterCheck($param, "sell_site")) {
            $query .= "\n    AND  cpn_admin_seqno = " . $param["sell_site"];
        }

        if ($this->blankParameterCheck($param, "office_nick")) {
            $query .= "\n    AND  office_nick LIKE '%%";
            $query .= substr($param["office_nick"], 1, -1);
            $query .= "%%'";
        }
        $query .= "\n    AND  withdraw_dvs = 1";

        return $conn->Execute($query);
    }

    /**
     * @brief 사내 닉네임 list Select
     *
     * @param $conn  = connection identifier
     * @param $param = 검색 조건 파라미터
     *
     * @return : resultSet
     */
    function selectOfficeNickList($conn, $param) {

        if (!$this->connectionCheck($conn)) return false;
        $param = $this->parameterArrayEscape($conn, $param);
        $query  = "\n    SELECT  office_nick";
        $query .= "\n           ,member_seqno";
        $query .= "\n      FROM  member";
        $query .= "\n     WHERE  cpn_admin_seqno = " . $param["sell_site"];
        $query .= "\n       AND  withdraw_dvs = '1'";

        //사내닉네임
        if ($this->blankParameterCheck($param, "search")) {
            $query .= "\n       AND office_nick LIKE '%";
            $query .= substr($param["search"], 1,-1) . "%'";
        }

        $result = $conn->Execute($query);

        return $result;
    }

     /**
     * @brief 아이디와 비밀번호로 직원 정보 검색
     *
     * @param $conn = connection identifier
     * @param $id   = 직원 id
     */
    function selectEmpl($conn, $id) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $id = $this->parameterEscape($conn, $id);

        $query  = "\nSELECT name ";        /* 직원이름 */
        $query .= "\n      ,enter_date ";  /* 로그인 시각 */
        $query .= "\n      ,passwd ";      /* 비밀번호 */
        $query .= "\n      ,empl_seqno ";  /* 일련번호 */
        $query .= "\n      ,cpn_admin_seqno ";  /* 판매채널 일련번호 */
        $query .= "\n      ,depar_code ";  /* 직원 부서코드 */
        $query .= "\n  FROM empl ";
        $query .= "\n WHERE empl_id = %s ";

        $query  = sprintf($query, $id);

        return $conn->Execute($query);
    }

    /**
     * @brief 입력받은 값으로 회원명, 사내닉네임
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     */
    function selectCndMember($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  member_name";
        $query .= "\n        ,office_nick";
        $query .= "\n        ,member_id";
        $query .= "\n        ,member_seqno";
        $query .= "\n        ,grade";
        $query .= "\n   FROM  member";
        $query .= "\n  WHERE  cpn_admin_seqno = $param[sell_site]";

        //조건 검색
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $field = substr($param["search_cnd"], 1, -1);
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  $field LIKE '%$val%' ";
        }
        if ($this->blankParameterCheck($param ,"member_seqno")) {
            $query .= "\n   AND  member_seqno = ";
            $query .= $param["member_seqno"];
        }

        $query .= "\n    AND  withdraw_dvs = 1";

        return $conn->Execute($query);
    }

    /**
     * @brief 입력받은 값으로 인쇄물제목, 주문번호
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     */
    function selectCndOrder($conn, $param) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  title ";
        $query .= "\n        ,order_num ";
        $query .= "\n        ,order_common_seqno ";
        $query .= "\n   FROM  order_common";
        $query .= "\n  WHERE  cpn_admin_seqno = $param[sell_site]";
        $query .= "\n    AND  del_yn = 'N'";

        //조건 검색
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $field = substr($param["search_cnd"], 1, -1);
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n   AND  $field LIKE '%$val%' ";
        }
        //주문 상태 검색
        if ($this->blankParameterCheck($param ,"order_state")) {
            $val = substr($param["order_state"], 1, -1);
            $query .= "\n   AND  order_state in " . $val;
        }

        return $conn->Execute($query);
    }

    /**
     * @brief 입력받은 값으로 발주번호
     *
     * @param $conn = connection identifier
     * @param $param = 검색조건 파라미터
     */
    function selectCndPaper($conn, $param) {

        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $param = $this->parameterArrayEscape($conn, $param);

        $query  = "\n SELECT  paper_op_seqno ";
        $query .= "\n        ,extnl_brand_seqno ";
        $query .= "\n        ,storplace ";
        $query .= "\n FROM  paper_op ";

        //조건 검색
        if ($this->blankParameterCheck($param ,"search_txt")) {
            $field = substr($param["search_cnd"], 1, -1);
            $val = substr($param["search_txt"], 1, -1);
            $query .= "\n WHERE $field LIKE '%$val%' ";
        }

        return $conn->Execute($query);
    }

    function selectPassword($conn, $pw) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $pw = $this->parameterEscape($conn, $pw);

        $query = "select PASSWORD(%s) AS pw;";
        $query = sprintf($query, $pw);

        $rs = $conn->Execute($query);

        return $rs->fields["pw"];
    }

    /**
     * @brief 배열값을 IN 조건 등에 들어갈 수 있도록 문자열로 변경
     *
     * @param $conn  = DB Connection
     * @param $param = 배열값
     *
     * @return 변환 된 배열
     */
    function arr2paramStr($conn, $param) {
        if (empty($param) === true || count($param) === 0) {
            return '';
        }

        $ret = "";

        foreach ($param as $val) {
            if (empty($val) === true) {
                continue;
            }

            $ret .= $this->parameterEscape($conn, $val) . ',';
        }

        return substr($ret, 0, -1);
    }

    /**
     * @brief 주문상태값 검색
     *
     * @param $conn = connection identifier
     *
     * @return 카테고리명
     */
    function selectStateAdmin($conn, $dvs = "") {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }
        $query  = "\n   SELECT  A.state_code";
        $query .= "\n          ,A.erp_state_name";
        $query .= "\n     FROM  state_admin AS A";

        if (!empty($dvs)) {
            $dvs = $this->parameterEscape($conn, $dvs);
            $query .= "\n    WHERE  A.dvs = " . $dvs;
        }
        $query .= "\n ORDER BY  A.state_code";

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 주문상태 구분값 검색
     *
     * @param $conn = connection identifier
     *
     * @return 카테고리명
     */
    function selectStateAdminDvs($conn) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query  = "\n   SELECT  DISTINCT A.dvs";
        $query .= "\n     FROM  state_admin AS A";
        $query .= "\n ORDER BY  A.state_code";

        $rs = $conn->Execute($query);

        return $rs;
    }

    /**
     * @brief 리스트 쿼리 row 수 반환
     *
     * @detail https://blog.asamaru.net/2015/09/11/using-sql-calc-found-rows-and-found-rows-with-mysql/
     *
     * @param $conn = connection identifier
     *
     * @return 카테고리명
     */
    function selectFoundRows($conn) {
        if ($this->connectionCheck($conn) === false) {
            return false;
        }

        $query = "SELECT FOUND_ROWS() AS count";

        $rs = $conn->Execute($query);

        return $rs->fields["count"];
    }
}
?>
