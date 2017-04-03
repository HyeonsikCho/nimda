<?
include_once($_SERVER["DOCUMENT_ROOT"] . '/com/nexmotion/common/util/ExcelLib.php');

class GradeSalePriceExcelUtil extends ExcelLib {
    // 등급별 할인율 위치저장 배열
    // key = col idx / val = 할인율
    var $rate_arr = array();

    /**
     * @brief 사용자가 검색한 조건으로 엑셀시트를 생성한다.
     *
     * @detail $price_info_dvs_arr 같은 경우에는 시작 인덱스가 1부터이기 때문에
     * 1 -> 2 -> 3 -> ... -> 0(마지막) 순으로 값을 넣는다
     *
     * @param $sheet_name         = 엑셀파일 워크시트명
     * @param $info_dvs_arr       = 각 시트 A열에 들어가는 정보 항목명(제조사 등)
     * @param $header_arr         = 정보 배열, 상단부분 만드는데 사용
     * @param $price_info_dvs_arr = 가격 정보 구분 배열(기존금액, 요율 등)
     * @param $price_dvs_arr      = 가격 구분 배열(평량, 판 수 등)
     * @param $price_arr          = 가격 배열
     * @param $grade_arr          = 등급 배열
     * @param $mono_yn            = 독판 여부
     */
    function makeGradeSalePriceExcelSheet($sheet_name,
                                          $info_dvs_arr,
                                          $header_arr,
                                          $price_info_dvs_arr,
                                          $price_dvs_arr,
                                          $price_arr,
                                          $grade_arr,
                                          $etprs_dvs,
                                          $mono_yn = 0) {
        // 정보를 입력할 워크시트 생성
        // 시트명이 숫자일 경우 시트생성이 안되는 경우 존재
        $sheet_name = strval($sheet_name);
        $this->active_sheet = $this->createSheet($sheet_name);

        // 정보부분 생성
        $this->makeExcelTitle($header_arr,
                              $info_dvs_arr,
                              $price_info_dvs_arr,
                              $grade_arr);

        // 가격부분 생성
        $header_arr_count = count($header_arr) * $this->chunk_col_count;
        $price_arr = $this->makePriceArr($price_dvs_arr,
                                         $price_arr,
                                         $etprs_dvs);

        if ($mono_yn === 0) {
            $this->makePlyGradeSalePriceExcelBody($price_dvs_arr,
                                                  $price_arr,
                                                  $header_arr_count);
        } else {
        /*
            $this->makeCalcPriceExcelBody($price_dvs_arr,
                                          $price_arr,
                                          $header_arr_count);
        */
        }
    }

    /**
     * makePriceExcel에서 사용하는 함수로써
     * 종이 정보 부분을 생성한다.
     */
    function makeExcelTitle($header_arr,
                            $info_dvs_arr,
                            $price_info_dvs_arr,
                            $grade_arr) {
        /*
         * A열
         */
        $info_dvs_count = count($info_dvs_arr);
        $this->setCellWidth('A', 11);

        for ($i = 1; $i <= $info_dvs_count; $i++) {
            $info = $info_dvs_arr[$i];
            $cell_name = "A" . $i;

            $this->active_sheet->setCellValue($cell_name, $info);
            // 글자 설정
            $this->setCellHAlign($cell_name, self::H_CENTER);
            $this->setCellVAlign($cell_name, self::V_CENTER);
            $this->setBold($cell_name);
            $this->setFontSize($cell_name, 10);
            // 테두리 설정
            if ($i === $info_dvs_count) {
                $this->setCellBorderEach($cell_name,
                                         "bottom",
                                         self::BORDER_THIN);
                $this->setCellBorderEach($cell_name,
                                         "left",
                                         self::BORDER_DOTTED);
                $this->setCellBorderEach($cell_name,
                                         "right",
                                         self::BORDER_DOTTED);
            } else {
                $this->setCellBorder($cell_name, self::BORDER_DOTTED);
            }
            // 배경색 설정
            $this->setCellBgColor($cell_name, self::YELLOW);
        }

        $header_arr_count = count($header_arr) * $this->chunk_col_count;

        /*
         * 종이 정보 부분
         * 등급부분은 셀병합을 안하므로 '<='에서 '<'로 변경
         */
        for ($row_idx = 1; $row_idx < $this->price_info_row_idx; $row_idx++) {

            // 병합 시작 셀 구하기용 변수
            $d1 = 1;
            $d2 = 0;
            $d3 = 0;

            // 병합 끝 셀 구하기용 변수
            $d1_last = 0;
            $d2_last = 0;
            $d3_last = 0;

            $k = 0; // $header_arr 인덱스용

            // A열은 이미 사용했으므로 1부터 시작
            for ($col_idx = 1; $col_idx <= $header_arr_count; $col_idx++) {

                $col_name = "";
                $col_name_last = "";

                // 이하 조건문은 현재 위치한 샐의 열 인덱스를 구하기 위한 조건문임
                if ($col_idx < self::EXCEL_2007_DIGIT_2) {
                    // 자리수가 한 자리 일 때

                    $col_name = self::ALPABET[$d1++ % 26];

                    if ($d1 === 26) {
                        $d1 = 0;
                    }

                } else if (self::EXCEL_2007_DIGIT_2 <= $col_idx
                                && $col_idx < self::EXCEL_2007_DIGIT_3) {
                    // 자리수가 두 자리 일 때

                    $col_name = sprintf("%s%s", self::ALPABET[$d2 % 26]
                                              , self::ALPABET[$d1++ % 26]);

                    if ($d1 === 26) {
                        $d1 = 0;
                        $d2++;
                    }

                } else if (self::EXCEL_2007_DIGIT_3 <= $col_idx
                                && $col_idx <= self::EXCEL_2007_MAX_COL) {
                    // 자리수가 세 자리 일 때

                    $col_name = sprintf("%s%s%s", self::ALPABET[$d3 % 26]
                                                , self::ALPABET[$d2 % 26]
                                                , self::ALPABET[$d1++ % 26]);

                    if ($d1 === 26) {
                        $d1 = 0;
                        $d2++;

                        if ($d2 === 26) {
                            $d2 = 0;
                            $d3++;
                        }
                    }
                }

                // 이하 조건문은 현재 병합할 샐의 마지막 열 인덱스를 구하기 위한 조건문임
                /*
                 * 현재 위치를 기준으로 몇 칸을 더 병합해야 하는지에 대한 값
                 *
                 * ex) 종이 가격의 경우 총 7칸을 병합해야 하는데 그렇다는 것은
                 *     현재 셀을 기준으로 6칸($this->chunk_col_count - 1)을
                 *     더 병합해야 하므로 현재 인덱스에서 6칸 증가
                 */
                $temp_col_idx = $col_idx + ($this->chunk_col_count - 1); 
                /*
                 * 병합해야 되는 셀에서 마지막 셀의 첫 번째 열 인덱스를 구함
                 *
                 * ex) 위의 ++연산 때문에 그대로 6을 더할 경우 7칸 째 인덱스를 잡게 됨
                 *     따라서 6을 더하고 1을 뺌, 즉 5를 더해줌
                 */
                $temp_d1 = $d1 + ($this->chunk_col_count - 2); 
                /*
                 * A~Z 중 어느 열인지 구함.
                 * 두자리, 세자리 일 경우에는 첫 번째 자리 열을 구하는 용도
                 */
                $d1_last = ($temp_d1 > 25) ? ($temp_d1 - 26) : $temp_d1;

                if ($temp_col_idx < self::EXCEL_2007_DIGIT_2) {
                    // 자리수가 한 자리 일 때

                    $col_name_last = self::ALPABET[$d1_last];

                } else if (self::EXCEL_2007_DIGIT_2 <= $temp_col_idx
                        && $temp_col_idx < self::EXCEL_2007_DIGIT_3) {
                    // 자리수가 두 자리 일 때

                    $col_name_last = sprintf("%s%s", self::ALPABET[$d2_last]
                                                   , self::ALPABET[$d1_last]);

                    // 첫 번째 자리가 Z이면 두 번째 자리는 다음 자리로 바뀌어야됨
                    if ($d1_last === 25) $d2_last = $d2 + 1;

                } else if (self::EXCEL_2007_DIGIT_3 <= $temp_col_idx
                        && $temp_col_idx <= self::EXCEL_2007_MAX_COL) {
                    // 자리수가 세 자리 일 때

                    $col_name_last = sprintf("%s%s%s", self::ALPABET[$d3_last]
                                                     , self::ALPABET[$d2_last]
                                                     , self::ALPABET[$d1_last]);

                    // 첫 번째  Z이면 두 번째 자리는 다음 자리로 바뀌어야됨
                    if ($d1_last === 25) $d2_last = $d2 + 1;
                    // 두 번째 자리가 Z이면 세 번째 자리는 다음 자리로 바뀌어야됨
                    if ($d2_last === 25) $d3_last = $d3 + 1;
                    // 두 번째 자리가 알파벳 수를 넘었을 경우 초기화
                    if ($d2_last === 26) $d2_last = 0;
                }

                if (($col_idx % $this->chunk_col_count) === $this->chunk_col_remainder) {
                    /*
                     * 정보셀을 병합하고 값을 입력
                     */

                    // 한 덩어리 병함
                    $merge_cells = sprintf("%s%s:%s%s", $col_name, $row_idx
                                                      , $col_name_last, $row_idx);
                    $this->mergeCells($merge_cells);
                    $this->setCellBorderTopBot($merge_cells,
                                               self::BORDER_DOTTED);
                    $this->setCellBorderLeftRight($merge_cells,
                                                  self::BORDER_THICK);
                    $this->setCellHAlign($merge_cells, self::H_CENTER);
                    $this->setCellVAlign($merge_cells, self::V_CENTER);
                    $this->setCellBgColor($merge_cells, self::GRAY);
                    $this->setFontSize($merge_cells, 10);
                    
                    // 정보
                    $temp_arr = explode('|', $header_arr[$k++]);
                    $cell_value = $temp_arr[$row_idx - 1];
                    $cell_value = ($cell_value === "") ? '-' : $cell_value;

                    $this->active_sheet
                         ->setCellValueByColumnAndRow($col_idx,
                                                      $row_idx,
                                                      $cell_value);
                }
            }
        }

        /*
         * 등급 행
         */
        $d1 = 1;
        $d2 = 0;
        $d3 = 0;

        // A열은 이미 사용했으므로 1부터 시작
        for ($col_idx = 1; $col_idx <= $header_arr_count; $col_idx++) {

            $col_name = "";

            // 이하 조건문은 현재 위치한 샐의 열 인덱스를 구하기 위한 조건문임
            if ($col_idx < self::EXCEL_2007_DIGIT_2) {
                /**
                 * 자리수가 한 자리 일 때
                 */

                $col_name = sprintf("%s", self::ALPABET[$d1++ % 26]);

                if ($d1 === 26) {
                    $d1 = 0;
                }

            } else if (self::EXCEL_2007_DIGIT_2 <= $col_idx
                    && $col_idx < self::EXCEL_2007_DIGIT_3) {
                /**
                 * 자리수가 두 자리 일 때
                 */

                $col_name = sprintf("%s%s", self::ALPABET[$d2 % 26]
                                          , self::ALPABET[$d1++ % 26]);

                if ($d1 === 26) {
                    /**
                     * 첫 번째 자리수의 인덱스가 알파벳 개수를 넘어갔을 때
                     */

                    $d1 = 0;
                    $d2++;
                }

            } else if (self::EXCEL_2007_DIGIT_3 <= $col_idx
                    && $col_idx <= self::EXCEL_2007_MAX_COL) {
                /**
                 * 자리수가 세 자리 일 때
                 */

                $col_name = sprintf("%s%s%s", self::ALPABET[$d3 % 26]
                                            , self::ALPABET[$d2 % 26]
                                            , self::ALPABET[$d1++ % 26]);

                if ($d1 === 26) {
                    /**
                     * 첫 번째 자리수의 인덱스가 알파벳 개수를 넘어갔을 때
                     */

                    $d1 = 0;
                    $d2++;

                    if ($d2 === 26) {
                        /**
                         * 두 번째 자리수의 인덱스가 알파벳 개수를 넘어갔을 때
                         */

                        $d2 = 0;
                        $d3++;
                    }
                }
            }

            // 글자가 다 보이도록 셀 너비 일괄변경
            $this->setCellWidth($col_name, 13);

            // row_idx가 고정
            $col_name .= $row_idx;

            // 셀 디자인 설정
            $i = $col_idx % $this->chunk_col_count; // 가격 구분 인덱스

            $this->setCellBorderEach($col_name,
                                     "bottom",
                                     self::BORDER_THIN);
            if ($i === 0) {
                $this->setCellBorderEach($col_name,
                                         "left",
                                         self::BORDER_DOTTED);
                $this->setCellBorderEach($col_name,
                                         "right",
                                         self::BORDER_THICK);
                $this->setBold($col_name);
            } else if ($i === 1) {
                $this->setCellBorderEach($col_name,
                                         "left",
                                         self::BORDER_THICK);
                $this->setCellBorderEach($col_name,
                                         "right",
                                         self::BORDER_DOTTED);
            } else {
                $this->setCellBorderEach($col_name,
                                         "left",
                                         self::BORDER_DOTTED);
                $this->setCellBorderEach($col_name,
                                         "right",
                                         self::BORDER_DOTTED);
                $this->setBold($col_name);
            }

            $this->setCellHAlign($col_name, self::H_CENTER);
            $this->setCellVAlign($col_name, self::V_CENTER);
            $this->setCellBgColor($col_name, self::GRAY);
            $this->setFontSize($col_name, 10);

            // 가격 정보 구분 값 입력
            $this->active_sheet
                 ->setCellValueByColumnAndRow($col_idx,
                                              $row_idx,
                                              $grade_arr[$i]);
        }

        /*
         * 가격 구분 행
         */
        $d1 = 1;
        $d2 = 0;
        $d3 = 0;

        $row_idx = $this->price_info_row_idx + 1;

        // A열은 이미 사용했으므로 1부터 시작
        for ($col_idx = 1; $col_idx <= $header_arr_count; $col_idx++) {

            $col_name = "";

            // 이하 조건문은 현재 위치한 샐의 열 인덱스를 구하기 위한 조건문임
            if ($col_idx < self::EXCEL_2007_DIGIT_2) {
                /**
                 * 자리수가 한 자리 일 때
                 */

                $col_name = sprintf("%s", self::ALPABET[$d1++ % 26]);

                if ($d1 === 26) {
                    $d1 = 0;
                }

            } else if (self::EXCEL_2007_DIGIT_2 <= $col_idx
                    && $col_idx < self::EXCEL_2007_DIGIT_3) {
                /**
                 * 자리수가 두 자리 일 때
                 */

                $col_name = sprintf("%s%s", self::ALPABET[$d2 % 26]
                                          , self::ALPABET[$d1++ % 26]);

                if ($d1 === 26) {
                    /**
                     * 첫 번째 자리수의 인덱스가 알파벳 개수를 넘어갔을 때
                     */

                    $d1 = 0;
                    $d2++;
                }

            } else if (self::EXCEL_2007_DIGIT_3 <= $col_idx
                    && $col_idx <= self::EXCEL_2007_MAX_COL) {
                /**
                 * 자리수가 세 자리 일 때
                 */

                $col_name = sprintf("%s%s%s", self::ALPABET[$d3 % 26]
                                            , self::ALPABET[$d2 % 26]
                                            , self::ALPABET[$d1++ % 26]);

                if ($d1 === 26) {
                    /**
                     * 첫 번째 자리수의 인덱스가 알파벳 개수를 넘어갔을 때
                     */

                    $d1 = 0;
                    $d2++;

                    if ($d2 === 26) {
                        /**
                         * 두 번째 자리수의 인덱스가 알파벳 개수를 넘어갔을 때
                         */

                        $d2 = 0;
                        $d3++;
                    }
                }
            }

            // 글자가 다 보이도록 셀 너비 일괄변경
            $this->setCellWidth($col_name, 13);
            // 글자가 다 보이도록 행 높이 일괄변경
            $this->setHeight($row_idx, 30);

            // row_idx가 고정
            $col_name .= $row_idx;

            // 셀 디자인 설정
            $i = $col_idx % $this->chunk_col_count; // 가격 구분 인덱스

            $this->setCellBorderEach($col_name,
                                     "bottom",
                                     self::BORDER_THIN);
            if ($i === 0) {
                $this->setCellBorderEach($col_name,
                                         "left",
                                         self::BORDER_DOTTED);
                $this->setCellBorderEach($col_name,
                                         "right",
                                         self::BORDER_THICK);
                $this->setBold($col_name);
                $this->setCellNumberFormatting($col_name, self::RATE_FORMAT);
            } else if ($i === 1) {
                $this->setCellBorderEach($col_name,
                                         "left",
                                         self::BORDER_THICK);
                $this->setCellBorderEach($col_name,
                                         "right",
                                         self::BORDER_DOTTED);
            } else {
                $this->setCellBorderEach($col_name,
                                         "left",
                                         self::BORDER_DOTTED);
                $this->setCellBorderEach($col_name,
                                         "right",
                                         self::BORDER_DOTTED);
                $this->setBold($col_name);
                $this->setCellNumberFormatting($col_name, self::RATE_FORMAT);
            }

            $this->setCellHAlign($col_name, self::H_CENTER);
            $this->setCellVAlign($col_name, self::V_CENTER);
            $this->setCellBgColor($col_name, self::GRAY);
            $this->setFontSize($col_name, 10);

            $cell_val = $price_info_dvs_arr[$i];

            if ($price_info_dvs_arr[$i] === true) {
                $cell_val = '=' . $this->rate_arr[$i];
            } else {
                $this->rate_arr[$i] = $col_name;
                $price_info_dvs_arr[$i] = true;
            }

            // 가격 정보 구분 값 입력
            $this->active_sheet
                 ->setCellValueByColumnAndRow($col_idx,
                                              $row_idx,
                                              $cell_val);
            // 내용 줄바꿈 처리
            $this->setTextLineChange($col_name);
        }
    }

    /**
     * @biref makePriceExcel에서 사용하는 함수로써<br/>
     * 모든 rs를 돌면서 해당하는 평량의 정보를 한 줄로 이어붙임
     *
     * @param $price_dvs_arr = 가격 구분 배열(평량, 수량 etc...)
     * @param $price_arr     = 가격 배열
     * @param $etprs_dvs     = 업체구분
     *
     * @return 정렬된 가격 배열
     */
    function makePriceArr($price_dvs_arr,
                          $price_arr,
                          $etprs_dvs) {
        $price_dvs_arr_count = count($price_dvs_arr);
        $price_arr_count = count($price_arr);

        $ret_arr = array();

        $blank_price = "||||";
        if ($etprs_dvs === "exist") {
            $blank_price = "||||||||";
        }

        for ($i = 0; $i < $price_dvs_arr_count; $i++) {
            $price_dvs = $price_dvs_arr[$i];

            $price_val = "";

            for ($j = 0; $j < $price_arr_count; $j++) {
                $price = $price_arr[$j][$price_dvs];
                //echo "$j : $price_dvs = $price\n";

                if (empty($price)) {
                    $price_val .= $blank_price;
                } else {
                    $price_val .= '|' . $price;
                }

            }

            $ret_arr[$price_dvs] = $price_val;
        }

        return $ret_arr;
    }

    /**
     * @brief makePriceExcel에서 사용하는 함수로써
     * 확정형 가격 부분을 생성한다.
     *
     * @param $price_dvs_arr  = 가격 구분 배열
     * @param $price_arr      = 가격 배열
     * @param $header_arr_count = 항목 열의 총 개수
     */
    function makePlyGradeSalePriceExcelBody($price_dvs_arr,
                                            $price_arr,
                                            $header_arr_count) {

        $form_base = "=ROUND(((%s/100)*%s)+%s, -2)"; // 등급할인 가격 수식

        // 가격 시작 행 row부터 시작하기 때문에 더해줌
        $price_dvs_arr_count = count($price_dvs_arr) + $this->price_row_idx;

        // 가격 자체가 없을 경우 빈 열 하나 생성하기 위해 값 증가
        if ($price_dvs_arr_count === $this->price_row_idx) {
            $price_dvs_arr_count++;
        }

        $color_arr = self::COLOR_ARR;

        $i = 0; // 가격 구분 배열 인덱스
        for ($row_idx = $this->price_row_idx;
                $row_idx < $price_dvs_arr_count;
                $row_idx++) {

            $price_dvs = $price_dvs_arr[$i++];
            $price = $price_arr[$price_dvs];
            $price_arr_temp = null;

            $cell_name = "A" . $row_idx;

            // A열 설정
            $this->setCellBorderEach($cell_name,
                                     "bottom",
                                     self::BORDER_DOTTED);
            $this->setCellBorderEach($cell_name,
                                     "left",
                                     self::BORDER_DOTTED);
            $this->setCellBorderEach($cell_name,
                                     "right",
                                     self::BORDER_DOTTED);

            $this->setBold($cell_name);
            $this->setFontSize($cell_name, 10);
            $this->setCellBgColor($cell_name, self::YELLOW);
            if (doubleval($price_dvs) < 1) {
                $this->setCellNumberFormatting($cell_name, "0.0");
            } else {
                $this->setCellNumberFormatting($cell_name, self::PRICE_FORMAT);
            }

            $this->setCellBorderLeftRight($cell_name, self::BORDER_DOTTED);
            $this->active_sheet
                 ->setCellValueByColumnAndRow(0,
                                              $row_idx,
                                              $price_dvs);
            //$this->setCellUnlock('A' . $row_idx);

            $j = 0; // 가격 배열에서 가격을 가져올 때 사용하는 인덱스
            $l = 0; // 가격을 실제 열에 넣을때 사용하는 인덱스

            $d1 = 1;
            $d2 = 0;
            $d3 = 0;

            $sell_price_col = "";

            for ($col_idx = 1; $col_idx <= $header_arr_count; $col_idx++) {

                $col_name = "";
                $col_name_last = "";

                // 이하 조건문은 현재 위치한 샐의 열 인덱스를 구하기 위한 조건문임
                if ($col_idx < self::EXCEL_2007_DIGIT_2) {
                    /**
                     * 자리수가 한 자리 일 때
                     */

                    $col_name = self::ALPABET[$d1++ % 26];

                    if ($d1 === 26) {
                        $d1 = 0;
                    }

                } else if (self::EXCEL_2007_DIGIT_2 <= $col_idx &&
                        $col_idx < self::EXCEL_2007_DIGIT_3) {
                    /**
                     * 자리수가 두 자리 일 때
                     */

                    $col_name = sprintf("%s%s", self::ALPABET[$d2 % 26]
                                              , self::ALPABET[$d1++ % 26]);

                    if ($d1 === 26) {
                        $d1 = 0;
                        $d2++;
                    }

                } else if (self::EXCEL_2007_DIGIT_3 <= $col_idx &&
                        $col_idx <= self::EXCEL_2007_MAX_COL) {
                    /**
                     * 자리수가 세 자리 일 때
                     */

                    $col_name = sprintf("%s%s%s", self::ALPABET[$d3 % 26]
                                                , self::ALPABET[$d2 % 26]
                                                , self::ALPABET[$d1++ % 26]);

                    if ($d1 === 26) {
                        $d1 = 0;
                        $d2++;

                        if ($d2 === 26) {
                            $d2 = 0;
                            $d3++;
                        }
                    }
                }

                $col_name .= $row_idx;

                $this->setCellHAlign($col_name, self::H_RIGHT);
                $this->setCellNumberFormatting($col_name, self::PRICE_FORMAT);
                $this->setFontSize($col_name, 10);

                // 가격 열 별 정보저장 및 가격 분리를 위한 인덱스
                $price_col_idx = $col_idx % $this->chunk_col_count;

                $price_arr_temp = explode("|", $price);

                if ($price_col_idx === 0) {
                    // 맨 마지막 등급할인금액

                    // 셀 스타일
                    $this->setBold($col_name);
                    $this->setCellBgColor($col_name, $color_arr[$price_col_idx]);
                    if ($row_idx === $this->price_row_idx) {
                        $this->setCellBorderEach($col_name,
                                                 "bottom",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "left",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "right",
                                                 self::BORDER_THICK);
                    } else {
                        $this->setCellBorderEach($col_name,
                                                 "top",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "bottom",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "left",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "right",
                                                 self::BORDER_THICK);
                    }

                    $rate_col = $this->rate_arr[$price_col_idx];

                    $form = sprintf($form_base, $rate_col
                                              , $sell_price_col
                                              , $sell_price_col);

                    $this->active_sheet
                         ->setCellValueByColumnAndRow($col_idx,
                                                      $row_idx,
                                                      $form);
                } else if ($price_col_idx === 1) {
                    // 정상판매가
                    $sell_price_col = $col_name;

                    // 셀 스타일
                    if ($row_idx === $this->price_row_idx) {
                        $this->setCellBorderEach($col_name,
                                                 "bottom",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "left",
                                                 self::BORDER_THICK);
                        $this->setCellBorderEach($col_name,
                                                 "right",
                                                 self::BORDER_DOTTED);
                    } else {
                        $this->setCellBorderEach($col_name,
                                                 "top",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "bottom",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "left",
                                                 self::BORDER_THICK);
                        $this->setCellBorderEach($col_name,
                                                 "right",
                                                 self::BORDER_DOTTED);
                    }

                    $this->active_sheet
                         ->setCellValueByColumnAndRow($col_idx,
                                                      $row_idx,
                                                      $price_arr_temp[$col_idx]);
                } else {
                    // 등급할인금액
                    // 셀 스타일
                    $this->setBold($col_name);
                    $this->setCellBgColor($col_name, $color_arr[$price_col_idx]);
                    if ($row_idx === $this->price_row_idx) {
                        $this->setCellBorderEach($col_name,
                                                 "bottom",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "left",
                                                 self::BORDER_DOTTED);
                        $this->setCellBorderEach($col_name,
                                                 "right",
                                                 self::BORDER_DOTTED);
                    } else {
                        $this->setCellBorder($col_name,
                                             self::BORDER_DOTTED);
                    }

                    $rate_col = $this->rate_arr[$price_col_idx];

                    $form = sprintf($form_base, $rate_col
                                              , $sell_price_col
                                              , $sell_price_col);

                    $this->active_sheet
                         ->setCellValueByColumnAndRow($col_idx,
                                                      $row_idx,
                                                      $form);
                }
            }
        }
    }
}
?>
