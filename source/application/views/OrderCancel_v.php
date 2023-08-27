
	<div class="article">
		<form action="/Dlvy/Canceling" method="post" id="clame-form" target="frmTarget">
			<div class="article-title">
				<h6>주문 취소</h6>

				<div class="right-group">
					<ul class="step">
						<li class="on">주문 취소</li>
						<li>주문 취소 완료</li>
					</ul>
				</div>
			</div>

			<?php foreach ($Dlvy as $pg_sq => $dy) {?>
			<input type="hidden" name="pg_sq" value="<?php echo $pg_sq;?>">
			<input type="hidden" name="pcnt" value="<?php echo count($dy['GOODS']);?>">

			<div class="section-box">
				<div class="section-title">
					<h6>결제 상품</h6>
				</div>

				<div class="section-content">
					<div class="order-product-list has-check">
						<ul>
							<?php foreach ($dy['GOODS'] as $gs) {?>
							<li>
								<div class="check-item">
									<label>
										<input type="checkbox" name="product[]" value="<?php echo $gs['PAY_SQ']?>" checked />
									</label>
								</div>

								<a href="/Shop/Detail/<?php echo $gs['GOODS_ID']?>" class="product">
									<div class="product-thumb">
										<img src="<?php echo IMG_PATH.$gs['GPHOTO_NM'];?>">
									</div>

									<div class="product-info">
										<p class="product-name"><?php echo $gs['GOODS_ID']?></p>
										<?php if($this->session->userdata['sales']) {?>
											<p class="product-opt"><?php echo $gs['GPRICE_AMT'];?> BOX - <?php echo number_format($gs['GPRICE_SALE_PC']);?>원 <?php if($gs['GPRICE_PC']) {?><?php echo round(100-($gs['GPRICE_SALE_PC']/$gs['GPRICE_PC']*100));?>% 할인 상품<?php } ?></p>
										<?php } else { ?>
											<p class="product-opt"><?php echo $gs['GPRICE_AMT'];?> BOX - <?php echo number_format($gs['GPRICE_PC']);?>원</p>
										<?php } ?>
									</div>
								</a>
							</li>
							<?php } ?>

						</ul>
					</div>

					<div class="state-box">
						<p class="price">총 상품 가격 : <strong><?php echo number_format($dy['PAYGP_PAID']);?>원</strong></p>
					</div>
				</div>
			</div>
			<?php } ?>

			<p class="order-cancel-desc">취소 요청을 완료할 경우 주문 상태에 따라 상품이 배송될 수 있으며 배송 이후 회수 처리 됩니다.<br/>구매액의 환급은 결제 수단의 대행사 업체 사정으로 인해 영업일 기준 2~3일이 소요될 수 있습니다.</p>

			<div class="article-submit-btn">
				<input type="submit" value="주문 취소" />
			</div>
		</form>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		$(function() {
			$("#clame-form").submit(function() {
				const checkLength = $(this).find("input[name='product[]']:checked").length;

				if (checkLength === 0) {
					alert("취소할 상품을 선택해 주세요.");
					return false;
				}
			});
		});
	</script>
