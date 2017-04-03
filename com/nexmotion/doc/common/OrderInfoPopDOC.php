<?
/**
 * @param 주문정보 팝업 html 생성
 *
 * @detail $param에는 주문 공통 테이블에 저장되어있는
 * 기본정보(basic_info)
 * 추가정보(add_info)
 * 가격정보(price_info)
 * 결제정보(pay_info)
 * html이 넘어온다
 *
 * @param $param = 정보 배열 
 * 
 * @return 팝업 html
 */
function makeOrderInfoHtml($param) {
    $basic_info = $param["prdt_basic_info"];
    $add_info   = $param["prdt_add_info"];
    $price_info = $param["prdt_price_info"];
    $pay_info   = $param["prdt_pay_info"];

    $html = <<<html
            <dl>
                <dt class="tit">
                    <h4>주문내용</h4>
                </dt>
                <dt class="cls">
                    <button type="button" onclick="hideRegiPopup()" class="btn btn-sm btn-danger fa fa-times"></button>
                </dt>
            </dl>  

            <div class="pop-base">
                <div class="pop-content">
                    <div class="form-group">
                        $basic_info
                        $add_info
                        $price_info
                        $pay_info
                    </div>  
                </div> <!-- pop-content -->
            </div>
            <!-- pop-base -->
	 	<div class="form-group">			
                <p class="tac mt15" onclick="hideRegiPopup()">
                    <label class="btn btn_md fix_width120"> 닫기</label>          
                </p>
            </div>
            <br />
html;

    return $html;
}
?>
