SET FOREIGN_KEY_CHECKS = 0;
/* 제조사_원자재_재고_상세 */
TRUNCATE manu_mtra_stock_detail;
/* 제조사_원자재_재고_일 */
TRUNCATE manu_mtra_stock_day;
/* 제조사_원자재_재고 */
TRUNCATE manu_mtra_stock;
/* 제조사_종이_재고_상세 */
TRUNCATE manu_paper_stock_detail;
/* 제조사_종이_재고_일 */
TRUNCATE manu_paper_stock_day;
/* 제조사_종이_재고 */
TRUNCATE manu_paper_stock;
/* 주문 후공정_작업_일지 */
TRUNCATE after_work_report;
/* 생산_지시서 */
TRUNCATE produce_ord;
/* 주문 후공정_발주_작업파일 */
TRUNCATE after_op_work_file;
/* 주문 후공정_발주 */
TRUNCATE after_op;
/* 조판 후공정_발주 */
TRUNCATE basic_after_op;
/* 조판 후공정_작업_일지 */
TRUNCATE basic_after_work_report;
/* 종이_발주 */
TRUNCATE paper_op;
/* 인쇄_작업_일지 */
TRUNCATE print_work_report;
/* 인쇄_발주 */
TRUNCATE print_op;
/* 출력_작업_일지 */
TRUNCATE output_work_report;
/* 출력_발주 */
TRUNCATE output_op;
/* 책자_조판_파일 */
TRUNCATE brochure_typset_file;
/* 페이지_주문_상세_책자 */
TRUNCATE page_order_detail_brochure;
/* 책자_조판 */
TRUNCATE brochure_typset;
/* 조판_판형_파일 
TRUNCATE typset_format_file;*/
/* 주문_상세_건수_파일 */
TRUNCATE order_detail_count_file;
/* 수량_주문_상세_낱장 */
TRUNCATE amt_order_detail_sheet;
/* 낱장_조판_미리보기_파일 */
TRUNCATE sheet_typset_preview_file;
/* 낱장_조판_파일 */
TRUNCATE sheet_typset_file;
/* 낱장_조판 */
TRUNCATE sheet_typset;
/* 주문_상세_책자 */
TRUNCATE order_detail_brochure;
/* 주문_상세 */
TRUNCATE order_detail;
/* 주문_클레임_파일 */
TRUNCATE order_claim_file;
/* 주문_클레임 */
TRUNCATE order_claim;
/* 주문_후공정_내역 */
TRUNCATE order_after_history;
/* 주문_옵션_내역 */
TRUNCATE order_opt_history;
/* 주문_파일 */
TRUNCATE order_file;
/* 주문_묶음 */
TRUNCATE order_bun;
/* 주문_배송 */
TRUNCATE order_dlvr;
/* 주문_묶음_배송 */
TRUNCATE order_bun_dlvr;
/* 주문_공통 */
TRUNCATE order_common;
/* 주문_상세_건수_미리보기_파일 */
TRUNCATE order_detail_count_preview_file;
SET FOREIGN_KEY_CHECKS = 1;
