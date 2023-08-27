	<div class="article">
		<div class="product-detail">
			<div class="product-top">
				<div class="product-top-thumb">
					<div class="current">
						<img src="<?php echo IMG_PATH.$GOOD_PHOTOS[0]->GPHOTO_NM;?>" id="img-zoom">
					</div>

					<div class="swiper">
						<ul class="swiper-wrapper">
							<?php for($i=0;$i<count($GOOD_PHOTOS);$i++) {?>
							<li class="swiper-slide">
								<button type="button" class="thumb-btn">
									<img src="<?php echo IMG_PATH.$GOOD_PHOTOS[$i]->GPHOTO_NM;?>">
								</button>
							</li>
							<?php } ?>
						</ul>
					</div>

					<div id="img-zoom-area"></div>
				</div>

				<div class="product-top-form">
					<form action="/Order" method="post" id="product-form" target="frmTarget">
						<input type="hidden" name="mode" id="mode" />
						<input type="hidden" name="goods_id" id="mode" value="<?php echo $GOOD->GOODS_ID;?>" />

						<h6 class="product-name"><?php echo $GOOD->GOODS_NM;?></h6>

						<h5>상품 요약</h5>

						<p class="product-desc"><?php echo nl2br($GOOD->GOODS_BRIF_TX);?></p>

						<div class="price-box">
							<?php if($this->session->userdata['sales']) {?>
							<p class="percent"><?php if(!$GOOD_PRICES[0]->GPRICE_PC) echo "0"; else echo round(100-($GOOD_PRICES[0]->GPRICE_SALE_PC/$GOOD_PRICES[0]->GPRICE_PC*100));?>%</p>
							<p class="sale-price"><?php echo number_format($GOOD_PRICES[0]->GPRICE_PC);?>원</p>
							<p class="price"><?php echo number_format($GOOD_PRICES[0]->GPRICE_SALE_PC);?>원</p>
							<?php } else { ?>
							<p class="price"><?php echo number_format($GOOD_PRICES[0]->GPRICE_PC);?>원</p>
							<?php } ?>
						</div>

						<div class="dropdown-box placeholder">
							<input type="hidden" name="option" id="option" />

							<button type="button" tabindex="-1" placeholder="옵션을 선택해 주세요.">옵션을 선택해 주세요.</button>

							<div class="dropdown-list">
								<ul>
									<?php foreach ($GOOD_PRICES as $gs) {?>
									<li>
										<?php if($this->session->userdata['sales']) {?>
											<button type="button" data-value="<?php echo $gs->GPRICE_AMT;?>"><?php echo $gs->GPRICE_AMT;?>BOX - <?php echo number_format($gs->GPRICE_SALE_PC);?>원 <?php if($gs->GPRICE_PC) { ?><?php echo round(100-($gs->GPRICE_SALE_PC/$gs->GPRICE_PC*100));?>% 할인 상품<?php } ?></button>
										<?php } else { ?>
											<button type="button" data-value="<?php echo $gs->GPRICE_AMT;?>"><?php echo $gs->GPRICE_AMT;?>BOX - <?php echo number_format($gs->GPRICE_PC);?>원</button>
										<?php } ?>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>

						<div class="buy-btns">
							<button type="button" class="buy-btn" data-mode="option">구매하기</button>
							<button type="button" class="buy-btn" data-mode="cart">장바구니 담기</button>
						</div>
					</form>
				</div>
			</div>

			<div class="product-info">
				<div class="title-box">
					<h6>상품 정보</h6>
				</div>

				<div class="info-text ck-content">
					<?php echo $GOOD->GOODS_TX;?>
				</div>
			</div>
		</div>
	</div>

	<iframe name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		function goToBuy(mode) {
			$("#mode").val(mode);

			/*if(mode == "cart")
				$('#product-form').attr('action', '/Order/Cart');
			else
				$('#product-form').attr('action', '/Order');*/

			$("#product-form").submit();
		}

		$(function() {
			let zoomElSize = 0;
			const zoomOpt = {
				squareOverlay:true,
				position:'right',
				rightPad: 20,
				zoomLevel:2,
			};

			if ($(window).width() >= 1024) {
				$("#img-zoom").extm(zoomOpt);
				zoomElSize = $(window).width() >= 1200 ? 1200 : 1024;
			}

			$(window).resize(function() {
				if ($(window).width() >= 1200) {
					if (zoomElSize != 1200) {
						$("#img-zoom").extmDestroy();

						$("#img-zoom").extm(zoomOpt);
						zoomElSize = 1200;
					}
				} else if ($(window).width() >= 1024) {
					if (zoomElSize != 1024) {
						$("#img-zoom").extmDestroy();

						$("#img-zoom").extm(zoomOpt);
						zoomElSize = 1024;
					}
				} else {
					$("#img-zoom").extmDestroy();
					zoomElSize = 0;
				}
			});

			const thumbSwiper = new Swiper('.product-top-thumb .swiper', {
				touchReleaseOnEdges:true,
				slidesPerView: "auto",
				spaceBetween: 10,
				slidesOffsetBefore:10,
				slidesOffsetAfter:10,
				direction:"horizontal",
				// Responsive breakpoints
				breakpoints: {
					// when window width is >= 1024px
					1024: {
						slidesOffsetBefore:0,
						slidesOffsetAfter:0,
						direction:"vertical",
					}
				}
			});

			$(".product-top-thumb .thumb-btn").click(function() {
				const tgSrc = $(this).find("img").attr("src");

				$(".product-top-thumb .current img").attr("src", tgSrc);
				$("#img-zoom").extmImageUpdate(tgSrc);
			});

			$(".buy-btn").click(function() {
				goToBuy($(this).data("mode"));
			});

			$("#product-form").submit(function() {
				if (this.option.value === "") {
					alert("옵션을 선택해 주세요.");

					return false;
				}
			});
		});
	</script>
