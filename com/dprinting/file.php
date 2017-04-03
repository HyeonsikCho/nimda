<?
/***********************************************************************************
 *** 프로 젝트 : CJ 송장 출력
 *** 개발 영역 : 파일 모듈
 *** 개  발  자 : 김성진
 *** 개발 날짜 : 2016.08.04
 ***********************************************************************************/

class CLS_File {

    function FileRead($fileName) {
        if (!file_exists($fileName)) {
            echo ("파일이 존재하지 않습니다.");
        } else {
            $fp = fopen($fileName, "r");
            if(!$fp) echo ("파일을 여는데 실패했습니다. 다시 확인하시길 바랍니다.");
            $content = fread($fp, filesize($fileName));
            fclose($fp);

            return $content;
        }
    }

    function FileWrite($fileName, $content, $mode) {
        if (!$fileName || !$content) {
            echo ("파일명 또는 내용을 입력하세요!!");
        } else {
            $fp = fopen($fileName, $mode);
            if(!$fp) echo ("파일을 여는데 실패했습니다. 다시 확인하시길 바랍니다.");
            fwrite($fp, $content);
            fclose($fp);
        }
    }

    function FileDelete($fileName) {
        if (!file_exists($fileName)) {
            echo ("파일이 존재하지 않습니다.");
        } else {
            @chmod($fileName, 0775);
            $handle = @unlink($fileName);
            return $handle;
        }
    }

    function oracleConnMassageFileLogs($fileName, $oci_error, $mode) {
        $content = "\n\n==================================================================================================\n";
        $content .= ">>>>> ".date("Y-m-d H:i:s")." <<<<<\n";
        $content .= "==================================================================================================\n";
        $content .= ">>>>> [message] <<<<<\n";
        $content .= "# Code -> ".$oci_error['code']."\n";
        $content .= "# Message -> ".iconv("UTF8", "EUCKR", $oci_error['message'])."\n";
        $content .= "# OffSet -> ".$oci_error['offset']."\n";
        $content .= "# SqlText -> ".iconv("UTF8", "EUCKR", $oci_error['sqltext'])."\n";
        $this->FileWrite($fileName, $content, $mode);
    }

}
?>