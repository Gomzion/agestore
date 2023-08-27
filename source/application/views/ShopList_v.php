	<div class="article">
		<div class="product-list">
			<ul>

				<?php foreach ($GOODS as $gs) {
					if($this->session->userdata['sales']) $PRICE = $gs->GPRICE_SALE_PC; else $PRICE = $gs->GPRICE_PC;
					?>
				<li data-idx="<?php echo $gs->GOODS_ID;?>">
					<a href="/Shop/detail/<?php echo $gs->GOODS_ID;?>/<?php echo $category;?>" class="product-link">
						<div class="thumb">
							<img src="<?php echo IMG_PATH.$gs->GPHOTO_NM;?>">
						</div>

						<p class="product-name"><?php echo $gs->GOODS_NM;?></p>
						<p class="product-desc"><?php echo nl2br($gs->GOODS_BRIF_TX);?></p>

						<p class="price"><strong><?php echo number_format($PRICE);?>원</strong>부터~</p>
					</a>

					<div class="buy-btns">
						<a href="/Order/ready?mode=direct&good_id=<?php echo $gs->GOODS_ID;?>&option=0">구매하기</a>

						<button type="button" class="modal-btn" data-modal="cart">장바구니 담기</button>
					</div>
				</li>
				<?php } ?>

			</ul>
		</div>

		<div class="paging">
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>

