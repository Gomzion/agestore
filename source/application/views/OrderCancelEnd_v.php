	<div class="article">
		<div class="article-title">
			<h6>주문 취소</h6>

			<div class="right-group">
				<ul class="step">
					<li>주문 취소</li>
					<li class="on">주문 취소 완료</li>
				</ul>
			</div>
		</div>

		<p class="order-cancel-finish">주문 취소가 완료되었습니다.</p>

		<?php foreach ($Dlvy as $pg_sq => $dy) {?>
		<input type="hidden" name="pg_sq" value="<?php echo $pg_sq;?>">

		<div class="section-box">
			<div class="section-title">
				<h6>취소 상품</h6>
			</div>

			<div class="section-content">
				<div class="order-product-list">
					<ul>
						<?php foreach ($dy['GOODS'] as $gs) {?>
						<li>
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

		<div class="article-submit-btn">
			<a href="<?php echo HOME_USER;?>" class="return-home">홈 화면으로 돌아가기</a>
		</div>
	</div>
