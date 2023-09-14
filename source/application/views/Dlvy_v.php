	<div class="article">
		<div class="article-title">
			<h6>배송 정보</h6>

			<div class="right-group">
				<ul class="period">
					<li><a href="/Dlvy?range=6" <?php if($range == 6) echo "class='on'";?>>최근 6개월</a></li>
					<?php for($i=2023;$i<=date('Y');$i++) { ?>
						<li><a href="/Dlvy?range=<?php echo $i;?>" <?php if($range == $i) echo "class='on'";?>><?php echo $i;?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<div class="dlvy-list">
			<ul>
				<?php foreach ($Dlvy as $pg_sq => $dy) {
					$dayAndWeek = "";
					if($dy['PAYGP_STEND_DT']) {
						$date = substr($dy['PAYGP_STEND_DT'], 0, 10);
						$dayOfWeek = date("l", strtotime($date));
						$date_arr = explode('-', $date);
						$dayAndWeek = $date_arr[1]."/".$date_arr[2]."(".$dayOfWeek.")";
					}
					?>
				<li>
					<div class="list-head">
						<p class="order-date"><?php echo substr($dy['PAYGP_REG_DT'], 0, 10);?></p>

						<div class="right-group">
							<?php if($dy['PAYGP_ST'] == ST_READY) { ?>
								<span class="sticker ready">상품 준비중</span>
							<?php } else if($dy['PAYGP_ST'] == ST_DLVY_ING) { ?>
								<span class="sticker dlvy-ing">배송중</span>
							<?php } else if($dy['PAYGP_ST'] == ST_DLVY_FINISH) { ?>
								<span class="sticker dlvy-finish">배송완료</span>
								<p class="sticker-date"><?php echo $dayAndWeek;?> 도착</p>
							<?php } else if($dy['PAYGP_ST'] == ST_CANCEL) { ?>
								<span class="sticker cancel">주문 취소</span>
							<?php } else if($dy['PAYGP_ST'] == ST_EXCHANGE) { ?>
								<span class="sticker clame">교환 신청</span>
							<?php } else if($dy['PAYGP_ST'] == ST_CLAME) { ?>
								<span class="sticker clame">반품 신청</span>
							<?php } else if($dy['PAYGP_ST'] == ST_EXCHANGE_FINISH) { ?>
								<span class="sticker clame-finish">교환 완료</span>
								<p class="sticker-date"><?php echo $dayAndWeek;?> 완료</p>
							<?php } else if($dy['PAYGP_ST'] == ST_CLAME_FINISH) { ?>
								<span class="sticker clame-finish">반품 완료</span>
								<p class="sticker-date"><?php echo $dayAndWeek;?> 완료</p>
							<?php } ?>
						</div>
					</div>

					<div class="list-body">
						<div class="order-product-list">
							<ul>
								<?php foreach ($dy['GOODS'] as $gs) {?>
								<li>
									<a href="/Shop/Detail/<?php echo $gs['GOODS_ID']?>" class="product">
										<div class="product-thumb">
											<img src="<?php echo IMG_PATH.$gs['GPHOTO_NM'];?>">
										</div>

										<div class="product-info">
											<p class="product-name"><?php echo $gs['GOODS_NM']?></p>
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

							<div class="state-btns">
								<?php if($dy['PAYGP_ST'] == ST_READY) { ?>
									<a href="/Dlvy/Cancel/<?php echo $pg_sq;?>" class="cancel-btn">주문 취소</a>
								<?php } else if($dy['PAYGP_ST'] == ST_DLVY_ING) { ?>
									<a href="#" class="dlvy-check-btn">배송조회</a>
									<a href="/Dlvy/Clame/<?php echo $pg_sq;?>" class="clame-btn">교환, 반품 신청</a>
								<?php } else if($dy['PAYGP_ST'] == ST_DLVY_FINISH) { ?>
									<a href="/Dlvy/Clame/<?php echo $pg_sq;?>" class="clame-btn">교환, 반품 신청</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</li>
				<?php } ?>

				<?php if(count($Dlvy) == 0) {?>
					<li class="empty">배송 정보가 없습니다.</li>
				<?php } ?>
			</ul>
		</div>

		<div class="paging">
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>

	<script>
		$(function() {
		});
	</script>
