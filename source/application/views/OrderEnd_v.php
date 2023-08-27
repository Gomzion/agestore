
	<div class="article">
		<form action="./order_finish.php" method="post" id="cart-form">
			<div class="article-title">
				<h6>주문완료</h6>

				<div class="right-group">
					<ul class="step">
						<li>주문/결제</li>
						<li class="on">주문완료</li>
					</ul>
				</div>
			</div>

			<p class="order-finish">주문이 완료되었습니다.</p>

			<div class="section-box">
				<div class="section-title">
					<h6>결제 상품</h6>
				</div>

				<div class="section-content">
					<div class="order-product-list">
						<ul>
							<?php
							$gprice_pc = 0;
							$gprice_sale_pc = 0;
							foreach ($CART as $ct) {
								$gprice_pc += $ct->GPRICE_PC;
								$gprice_sale_pc += $ct->GPRICE_SALE_PC;
								?>
							<li>
								<a href="#" class="product">
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
										<input type="hidden" name="option" />

										<?php if($this->session->userdata['sales']) {?>
											<button type="button" tabindex="-1" placeholder=""><?php echo $ct->GPRICE_AMT;?> BOX - <?php echo number_format($ct->GPRICE_SALE_PC);?>원 <?php if($ct->GPRICE_PC) { ?><?php echo round(100-($ct->GPRICE_SALE_PC/$ct->GPRICE_PC*100));?>% 할인 상품<?php } ?></button>
										<?php } else { ?>
											<button type="button" tabindex="-1" placeholder=""><?php echo $ct->GPRICE_AMT;?> BOX - <?php echo number_format($ct->GPRICE_PC);?>원</button>
										<?php } ?>
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
						<p class="sale-price"><span id="total-sale-price"><?php echo $gprice_pc;?></span>원</p>
						<p class="price"><span id="total-price"><?php echo $gprice_sale_pc;?></span>원</p>
					<?php } else { ?>
						<p class="price"><span id="total-price-no"><?php echo $gprice_pc;?></span>원</p>
					<?php } ?>
				</div>
			</div>

			<div class="article-submit-btn">
				<a href="<?php echo HOME_USER;?>" class="return-home">홈 화면으로 돌아가기</a>
			</div>
		</form>
	</div>

