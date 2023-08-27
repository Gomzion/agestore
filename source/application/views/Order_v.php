
	<div class="article">
		<form action="/Order/pay" method="post" id="cart-form" target="frmTarget">
			<div class="article-title">
				<h6>주문/결제</h6>

				<div class="right-group">
					<ul class="step">
						<li class="on">주문/결제</li>
						<li>주문완료</li>
					</ul>
				</div>
			</div>

			<div class="section-box buyer">
				<div class="section-title">
					<h6>구매자 정보</h6>
				</div>

				<div class="section-content">
					<p class="buyer-info"><strong>구매자 정보 : </strong><?php echo $USER->USER_NM;?></p>
					<p class="buyer-info"><strong>소속기관 : </strong><?php echo $USER->HOSPITAL_NM;?> (<?php echo $USER->HOSPITAL_SDO_NM;?> <?php echo $USER->HOSPITAL_SGG_NM;?> <?php echo $USER->HOSPITAL_BLD_NM;?>)</p>
					<p class="buyer-info"><strong>연락처 : </strong><?php echo $USER->USER_HPN_NO;?></p>
					<p class="buyer-info"><strong>배송 요청사항 : </strong></p>
					<textarea name="dlvy_memo" id="dlvy_memo" placeholder="배송 요청사항을 입력해 주세요."></textarea>
				</div>
			</div>

			<div class="section-box">
				<div class="section-title">
					<h6>결제 상품</h6>
					<p class="warning">* 상품 수량을 변경하실 경우 변경 후 적용 버튼을 눌러주세요.</p>
				</div>

				<div class="section-content">
					<div class="order-product-list">
						<ul>
							<?php foreach ($CART as $ct) {?>
							<li id="li_<?php echo $ct->CART_SQ?>">
								<input type="hidden" name="gprice_sq[]" value="<?php echo $ct->CART_SQ?>">
								<input type="hidden" name="gprice_gid[]" value="<?php echo $ct->GOODS_ID?>">
								<input type="hidden" name="gprice_amt[]" value="<?php echo $ct->GPRICE_AMT?>">
								<input type="hidden" name="gprice_sale_pc[]" value="<?php echo $ct->GPRICE_SALE_PC?>">
								<input type="hidden" name="gprice_pc[]" value="<?php echo $ct->GPRICE_PC?>">

								<a href="/Shop/Detail/<?php echo $ct->GOODS_ID?>" class="product">
									<div class="product-thumb">
										<img src="<?php echo IMG_PATH.$ct->GPHOTO_NM;?>">
									</div>

									<div class="product-info">
										<p class="product-name"><?php echo $ct->GOODS_NM?></p>
										<?php if($this->session->userdata['sales']) {?>
											<p class="product-opt"><?php echo $OPT[$ct->GOODS_ID][0]->GPRICE_AMT;?> BOX - <?php echo number_format($OPT[$ct->GOODS_ID][0]->GPRICE_SALE_PC);?>원 <?php if($OPT[$ct->GOODS_ID][0]->GPRICE_PC) {?><?php echo round(100-($OPT[$ct->GOODS_ID][0]->GPRICE_SALE_PC/$OPT[$ct->GOODS_ID][0]->GPRICE_PC*100));?>% 할인 상품<?php } ?></p>
										<?php } else { ?>
											<p class="product-opt"><?php echo $OPT[$ct->GOODS_ID][0]->GPRICE_AMT;?> BOX - <?php echo number_format($OPT[$ct->GOODS_ID][0]->GPRICE_PC);?>원</p>
										<?php } ?>
									</div>
								</a>

								<div class="option-box">
									<div class="dropdown-box placeholder">
										<input type="hidden" name="option[]" />

										<button type="button" tabindex="-1" placeholder="옵션을 선택해 주세요.">옵션을 선택해 주세요.</button>

										<div class="dropdown-list" id="ol_<?php echo $ct->CART_SQ?>">
											<ul>
												<?php foreach ($OPT[$ct->GOODS_ID] as $ot) {?>
												<li>
													<?php if($this->session->userdata['sales']) {?>
														<button type="button" data-value="<?php echo $ot->GPRICE_AMT;?>"><?php echo $ot->GPRICE_AMT;?>BOX - <?php echo number_format($ot->GPRICE_SALE_PC);?>원 <?php if($ot->GPRICE_PC) { ?><?php echo round(100-($ot->GPRICE_SALE_PC/$ot->GPRICE_PC*100));?>% 할인 상품<?php } ?></button>
													<?php } else { ?>
														<button type="button" data-value="<?php echo $ot->GPRICE_AMT;?>"><?php echo $ot->GPRICE_AMT;?>BOX - <?php echo number_format($ot->GPRICE_PC);?>원</button>
													<?php } ?>
												</li>
												<?php } ?>
											</ul>
										</div>
									</div>

									<div class="opt-btns">
										<button type="button" name="apply-btn" class="apply-btn" data-mode="<?php echo $ct->CART_SQ?>">적용</button>
										<button type="button" class="delete-btn" data-mode="<?php echo $ct->CART_SQ?>">삭제</button>
									</div>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="order-price-box">
				<p class="caption">총 상품 가격 :</p>

				<div class="price-box">
					<?php if($this->session->userdata['sales']) {?>
						<p class="sale-price"><span id="total-sale-price"></span>원</p>
						<p class="price"><span id="total-price"></span>원</p>
					<?php } else { ?>
						<p class="price"><span id="total-price-no"></span>원</p>
					<?php } ?>
				</div>
			</div>

			<p class="order-desc">위 주문 내용을 확인 하였으며, 회원 본인은 개인정보 이용 및 제공 및 결제에 동의합니다.<br/>결제하기 버튼을 클릭하면 결제 화면으로 이동됩니다.</p>

			<div class="article-submit-btn">
				<input type="submit" value="결제하기" />
			</div>
		</form>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		var flagPaid = false;

		// 옵션값을 처리하기 위해 옵션 정보를 저장한다
		var priceOpts = {};
		<?php foreach ($OPT as $ky => $ot) {
			echo "priceOpts['$ky'] = {};\n";
			foreach ($ot as $oot) {
				echo "priceOpts['$ky']['$oot->GPRICE_AMT'] = {};\n";
				echo "priceOpts['$ky']['$oot->GPRICE_AMT']['GPRICE_PC'] = ".$oot->GPRICE_PC.";\n";
				echo "priceOpts['$ky']['$oot->GPRICE_AMT']['GPRICE_SALE_PC'] = ".$oot->GPRICE_SALE_PC.";\n";
			}
		} ?>

		// 금액 계산 다시 하기 (체크된 것만)
		function total_calc() {
			var totalSalePrice = 0;
			var totalPrice = 0;
			var totalPriceNo = 0;

			// Iterate over all checkboxes
			$("input[type='hidden'][name='gprice_sq[]']").each(function() {
				// If the checkbox is checked
				var cartSq = $(this).val();

				var li = $(this).closest("li");
				var salePriceInput = li.find("input[name='gprice_sale_pc[]']");
				var priceInput = li.find("input[name='gprice_pc[]']");

				var salePrice = parseFloat(salePriceInput.val());
				var price = parseFloat(priceInput.val());

				if (isNaN(salePrice) || isNaN(price)) {
					console.error("Invalid price value:", salePrice, price);
					// Handle the error or skip this iteration
					return; // Skip to the next iteration
				}

				// Update the total sale price and total price
				totalSalePrice += salePrice;
				totalPrice += price;
				totalPriceNo += price;
			});

			// Update the total prices
			$("#total-sale-price").text(totalPrice);
			$("#total-price").text(totalSalePrice);
			$("#total-price-no").text(totalPriceNo);
		}

		$(document).ready(function() {
			total_calc();

			<?php foreach ($CART as $ct) { ?>
			$("#ol_<?php echo $ct->CART_SQ; ?> button[data-value='<?php echo $ct->GPRICE_AMT; ?>']").trigger("click");
			<?php } ?>
		});

		$(function() {
			$(".apply-btn").click(function() {
				var sq = $(this).data("mode");
				var selectedValue = $("#ol_" + sq).find("button.selected").data("value");

				var li = $(this).closest("li");
				var gidInput = li.find("input[name='gprice_gid[]']");
				var amtInput = li.find("input[name='gprice_amt[]']");
				var salePriceInput = li.find("input[name='gprice_sale_pc[]']");
				var priceInput = li.find("input[name='gprice_pc[]']");

				var gid = gidInput.val();
				var amt = amtInput.val();
				var salePrice = parseFloat(salePriceInput.val());
				var price = parseFloat(priceInput.val());

				amtInput.val(selectedValue);
				priceInput.val(priceOpts[gid][selectedValue]['GPRICE_PC']);
				salePriceInput.val(priceOpts[gid][selectedValue]['GPRICE_SALE_PC']);

				total_calc();
			});

			$(".delete-btn").click(function() {
				var sq = $(this).data("mode");
				// Get the <li> element by its id
				var liElement = document.getElementById("li_"+sq);

				// Remove the <li> element
				liElement.remove();

				const checkLength = $(this).find("input[name='product[]']:checked").length;

				if (checkLength === 0) {
					// 메인으로
					top.location.href = '/Shop';
				}

				total_calc();
			});

			$("#cart-form").submit(function() {
				if (flagPaid) {
					alert("중복 결제 시도는 불가능합니다. 새로 고침 해주세요");
					return false;
				}
				flagPaid = true;
			});
		});
	</script>
