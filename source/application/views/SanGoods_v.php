
	<div class="article product-setting">
		<div class="product-list">
			<ul>
				<li class="edit-btn">
					<a href="/SanGoods/newone">
						<p>+ 상품 추가</p>
					</a>
				</li>

				<?php foreach ($GOODS as $gs) {?>
				<li data-idx="<?php echo $gs->GOODS_ID;?>">
					<a href="#" class="product-link">
						<div class="thumb">
							<img src="<?php echo IMG_PATH.$gs->GPHOTO_NM;?>">
						</div>

						<p class="product-name"><?php echo $gs->GOODS_NM;?></p>
						<p class="product-desc"><?php echo nl2br($gs->GOODS_BRIF_TX);?></p>

						<p class="price"><strong><?php echo number_format($gs->GPRICE_SALE_PC);?>원</strong>부터~</p>
					</a>

					<div class="btn-row-group">
						<div class="btn-row">
							<a href="/SanGoods/CartList?g_id=<?php echo $gs->GOODS_ID;?>&bp=<?php echo $page;?>" class="btn type-black size-small">장바구니 현황</a>
							<a href="/SanGoods/OrderList?g_id=<?php echo $gs->GOODS_ID;?>&bp=<?php echo $page;?>" class="btn type-primary size-small">구매 내역</a>
						</div>

						<div class="btn-row">
							<a href="/SanGoods/edit/<?php echo $gs->GOODS_ID;?>" class="btn type-black-line size-small">정보 수정</a>
						</div>
					</div>
				</li>
				<?php } ?>
			</ul>
		</div>

		<div class="paging">
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>
