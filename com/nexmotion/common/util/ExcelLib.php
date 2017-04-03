<?
/**
 * @file ExcelUtil.php
 *
 * @brief 다운로드할 엑셀파일 생성유틸
 */

include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/excel/PHPExcel.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/define/excel_define.php');

class ExcelLib {

    // 자리수 두 자리 일 때의 시작 열 인덱스
    const EXCEL_2007_DIGIT_2 = 26;
    // 자리수 세 자리 일 때의 시작 열 인덱스
    const EXCEL_2007_DIGIT_3 = 702; 
    // 엑셀 2007버전의 최대 열 개수
    const EXCEL_2007_MAX_COL = 16384;

    // 열 계산용 알파벳 배열
    const ALPABET = array( 0  => 'A'
                          ,1  => 'B'
                          ,2  => 'C'
                          ,3  => 'D'
                          ,4  => 'E'
                          ,5  => 'F'
                          ,6  => 'G'
                          ,7  => 'H'
                          ,8  => 'I'
                          ,9  => 'J'
                          ,10 => 'K'
                          ,11 => 'L'
                          ,12 => 'M'
                          ,13 => 'N'
                          ,14 => 'O'
                          ,15 => 'P'
                          ,16 => 'Q'
                          ,17 => 'R'
                          ,18 => 'S'
                          ,19 => 'T'
                          ,20 => 'U'
                          ,21 => 'V'
                          ,22 => 'W'
                          ,23 => 'X'
                          ,24 => 'Y'
                          ,25 => 'Z');

    // 스타일 상수
    // 넘버링 포멧
    const PRICE_FORMAT = '#,##0';
    const RATE_FORMAT  = '#,##0.000';
    // 글자 수평, 수직정렬
    const H_CENTER = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
    const H_RIGHT  = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
    const V_CENTER = PHPExcel_Style_Alignment::VERTICAL_CENTER;
    // 셀 테두리
    const BORDER_THIN   = PHPExcel_Style_Border::BORDER_THIN;
    const BORDER_THICK  = PHPExcel_Style_Border::BORDER_THICK;
    const BORDER_DOTTED = PHPExcel_Style_Border::BORDER_DOTTED;
    // 셀 배경색
    const YELLOW = "FFFF00";
    const GRAY   = "F2F2F2";
    // 셀 배경색 배열
    const COLOR_ARR = array(
        0 => "C5D9F0",
        2 => "F0DBDA",
        3 => "EBF0DF",
        4 => "E3DFEB",
        5 => "DAEDF2",
        6 => "FCE8D9",
        7 => "D9D9D9"
    );
    // 셀 보안
    const CELL_UNLOCK = PHPExcel_Style_Protection::PROTECTION_UNPROTECTED;
    const CELL_LOCK = PHPExcel_Style_Protection::PROTECTION_PROTECTED;

    var $obj_PHPExcel; // 엑셀파일객체
    var $active_sheet; // 현재 워크시트

    var $price_info_row_idx;  // 가격정보 행 끝 위치
    var $price_row_idx;   // 가격 행 시작위치
    var $chunk_col_count; // 정보 한 덩어리가 몇 개의 셀인지 저장하는 값
    var $chunk_col_remainder; // 정보 한 덩어리를 순회할 때 시작위치값(% 연산시 나머지값)

    function __construct() {
        register_shutdown_function("ExcelLib::destroy");
    }

    /**
     * @brief 에러났을 경우 파괴자 대신호출
     */
    static function destroy() {
        @$this->obj_PHPExcel->disconnectWorksheets();
        unset($this->active_sheet);
        unset($this->obj_PHPExcel);

        echo "MEM_ERR";
        exit;
    }

    /**
     * @brief 업로드한 엑셀파일을 이동하고
     * 정보 초기화
     *
     * @param $price_info_row_idx  = 항목 정보 행이 끝나는 위치
     * @param $chunk_col_count     = 가격정보 셀의 개수
     * @param $chunk_col_remainder = 가격정보 시작위치용 변수
     */
    function initExcelFileWriteInfo($price_info_row_idx,
                                    $chunk_col_count,
                                    $chunk_col_remainder) {

        $this->obj_PHPExcel = new PHPExcel();

        $this->price_info_row_idx = $price_info_row_idx;
        $this->price_row_idx = $price_info_row_idx + 2;
        $this->chunk_col_count = $chunk_col_count;
        $this->chunk_col_remainder = $chunk_col_remainder;
    }

    /**
     * @brief 셀의 경계선 스타일을 상하좌우 위치별로 설정하는 함수
     * 배경색이 넘어올 경우 배경색도 변경한다
     *
     * @param $cells    = 스타일을 적용할 셀 번호(A1. B1. A1:A9 ...)
     * @param $position = 경계선을 적용할 위치(top, bottom, left, right)
     * @param $style    = 경계선 스타일
     */
    function setCellBorderEach($cells, $position, $style) {
        $style_arr = array();
        $style_arr["borders"][$position]["style"] = $style;

        $this->active_sheet
             ->getStyle($cells)
             ->applyFromArray($style_arr);
    }

    /**
     * 셀의 경계선 스타일을 상하만 변경 하는 함수
     *
     * $position : 경계선을 적용할 위치(top, bottom, left, right)
     * $style    : 경계선 스타일
     */
    function setCellBorderTopBot($cells, $style) {
        $style_arr = array();
        $style_arr["borders"]["top"]["style"] = $style;
        $style_arr["borders"]["bottom"]["style"] = $style;

        $this->active_sheet
             ->getStyle($cells)
             ->applyFromArray($style_arr);
    }

    /**
     * 셀의 경계선 스타일을 좌우만 변경 하는 함수
     *
     * $position : 경계선을 적용할 위치(top, bottom, left, right)
     * $style    : 경계선 스타일
     */
    function setCellBorderLeftRight($cells, $style) {
        $style_arr = array();
        $style_arr["borders"]["left"]["style"] = $style;
        $style_arr["borders"]["right"]["style"] = $style;

        $this->active_sheet
             ->getStyle($cells)
             ->applyFromArray($style_arr);
    }

    /**
     * 셀의 경계선 스타일을 상하좌우 전체 변경 하는 함수
     *
     * $position : 경계선을 적용할 위치(top, bottom, left, right)
     * $style    : 경계선 스타일
     */
    function setCellBorder($cells, $style) {
        $style_arr = array();
        $style_arr["borders"]["top"]["style"] = $style;
        $style_arr["borders"]["bottom"]["style"] = $style;
        $style_arr["borders"]["left"]["style"] = $style;
        $style_arr["borders"]["right"]["style"] = $style;

        $this->active_sheet
             ->getStyle($cells)
             ->applyFromArray($style_arr);
    }

    /**
     * 셀의 배경색을 채우는 함수
     *
     * FFFF00 - 노란색
     * F2F2F2 - 회색
     */
    function setCellBgColor($cells, $bg_color) {
        $style_arr = array();
        $style_arr['type'] = PHPExcel_Style_Fill::FILL_SOLID;
        $style_arr['startcolor'] = array('rgb' => $bg_color);

        $this->active_sheet
             ->getStyle($cells)
             ->getFill()
             ->applyFromArray($style_arr);
    }

    /**
     * 셀의 문자서식을 숫자형으로 변경하는 함수(1,111,111...)
     */
    function setCellNumberFormatting($cells, $format) {
        $this->active_sheet
             ->getStyle($cells)
             ->getNumberFormat()
             ->setFormatCode($format);
    }

    /**
     * 셀의 수평정렬을 설정하는 함수
     *
     * PHPExcel_Style_Alignment::HORIZONTAL_CENTER : 가운데 정렬
     * PHPExcel_Style_Alignment::HORIZONTAL_RIGHT  : 오른쪽 정렬
     */
    function setCellHAlign($cells, $style) {
        $this->active_sheet
             ->getStyle($cells)
             ->getAlignment()
             ->setHorizontal($style);
    }

    /**
     * 셀의 수직정렬을 설정하는 함수
     *
     * PHPExcel_Style_Alignment::VERTICAL_TOP    : 상단 정렬
     * PHPExcel_Style_Alignment::VERTICAL_BOTTOM : 하단 정렬
     * PHPExcel_Style_Alignment::VERTICAL_CENTER : 가운데 정렬
     */
    function setCellVAlign($cells, $style) {
        $this->active_sheet
             ->getStyle($cells)
             ->getAlignment()
             ->setVertical($style);
    }

    /**
     * 글자를 bold 처리하는 함수
     */
    function setBold($cells) {
        $this->active_sheet
             ->getStyle($cells)
             ->getFont()
             ->setBold(TRUE);
    }

    /**
     * 셀의 글자크기를 설정하는 함수
     */
    function setFontSize($cells, $size) {
        $this->active_sheet
             ->getStyle($cells)
             ->getFont()
             ->setSize($size);
    }

    /**
     * 넘어온 셀 영역을 병합하는 함수
     */
    function mergeCells($cells) {
        $this->active_sheet->mergeCells($cells);
    }

    /**
     * 셀의 너비를 설정하는 함수
     */
    function setCellWidth($cells, $width) {
        $this->active_sheet
             ->getColumnDimension($cells)
             ->setWidth($width);
    }

    /**
     * 행의 높이를 설정하는 함수
     */
    function setHeight($row_idx, $height) {
        $this->active_sheet
             ->getRowDimension($row_idx)
             ->setRowHeight($height);
    }

    /**
     * 셀 내용 줄바꿈 처리 함수
     */
    function setTextLineChange($cell) {
        $this->active_sheet
             ->getStyle($cell)
             ->getAlignment()
             ->setWrapText(true);
    }

    /**
     * 셀의 잠금을 푸는 함수
     */
    function setCellUnlock($cells) {
        $this->active_sheet->getStyle($cells)
                           ->getProtection()
                           ->setLocked(self::CELL_UNLOCK);
    }

    /**
     * 가격 구분 배열(평량, 수량 ...)을
     * 오름차순으로 정렬하는 함수
     */
    function sortPriceDvsArr($price_dvs_arr) {
        $temp_arr = array();

        foreach ($price_dvs_arr as $key => $val) {
            if (is_numeric($key)) {
                $temp_arr[$key] = $val;
            } else {
                $key = doubleval($key);
                $temp_arr[$key] = $val;
            }
        }

        ksort($temp_arr);

        return $temp_arr;
    }

    /**
     * @brief 워크시트를 생성하고 생성된 워크시트의 기본 글꼴을 변경한 후<br/>
     * 엑셀 객체에 시트를 추가한 뒤 워크시트 객체를 반환하는 함수
     *
     * @detail 시트명 제약사항은 아래와 같다 
     *  : \ / ? * [ ] 문자는 들어갈 수 없다
     *  : 공백이 들어갈 수 없다
     *  : 시트명이 31자를 초과할 수 없다
     *
     * @param $sheet_name = 워크시트명
     */
    function createSheet($sheet_name) {

        $sheet_name = preg_replace("/\\\\/", '', $sheet_name);
        $sheet_name = preg_replace("/\//", '', $sheet_name);
        $sheet_name = preg_replace("/\?/", '', $sheet_name);
        $sheet_name = preg_replace("/\*/", '', $sheet_name);
        $sheet_name = preg_replace("/\[/", '', $sheet_name);
        $sheet_name = preg_replace("/\]/", '', $sheet_name);

        if (empty(trim($sheet_name)) === true) {
            $sheet_name = "sheet";
        }

        if (mb_strlen($sheet_name) > 30) {
            $sheet_name = substr($sheet_name, 0, 30);
        }

        $sheet = new PHPExcel_Worksheet($this->obj_PHPExcel, $sheet_name);

        $this->obj_PHPExcel->addSheet($sheet);

        $sheet->getDefaultStyle()->getFont()->setName("맑은 고딕");
		//$sheet->getDefaultStyle()->getFont()->setSize(11);

        // 보안정책 설정, 워크시트 전체 잠금 설정
        /*
        $sheet->getProtection()->setPassword('dprinting');
        $sheet->getProtection()->setSheet(true); // 필수
        $sheet->getProtection()->setSort(true);
        //$sheet->getProtection()->setInsertRows(false);
        */

        return $sheet;
    }

    /**
     * @brief 실제 엑셀 결과파일을 생성하고<br/>
     * 첫 번째 시트(아무 내용도 없음)를 삭제한 후<br/>
     * 모든 연결을 끊어버리는 함수<br/>
     *
     * @param $file_name = 생성할 파일이름
     *
     * @return 엑셀파일 경로
     */
    function createExcelFile($file_name) {
        $file_path = DOWNLOAD_PATH . '/' . $file_name . ".xlsx";

        $sheet_index = $this->obj_PHPExcel
                            ->getIndex($this->obj_PHPExcel
                                            ->getSheetByName('Worksheet'));
        $this->obj_PHPExcel->removeSheetByIndex($sheet_index);

        $obj_writer = PHPExcel_IOFactory::createWriter($this->obj_PHPExcel,
                                                       'Excel2007');
        $obj_writer->save($file_path);
        unset($obj_writer);

        $this->obj_PHPExcel->disconnectWorksheets();
        unset($this->active_sheet);
        unset($this->obj_PHPExcel);

        return $file_path;
    }
}
?>
