<div class="title-box">
	<h5>장바구니 담기</h5>

	<button type="button" class="close-btn">닫기</button>
</div>

<div class="form-box">
	<form action="/Order" method="post" id="cart-form" target="frmTarget">
		<input type="hidden" name="mode" value="cart"/>
		<input type="hidden" name="goods_id" value="<?php echo $GOODS_ID;?>" />
		<div class="input-row-group">
			<div class="input-row not-label">
				<div class="dropdown-box placeholder">
					<input type="hidden" name="option" id="option" />

					<button type="button" tabindex="-1" placeholder="옵션을 선택해 주세요.">옵션을 선택해 주세요.</button>

					<div class="dropdown-list">
						<ul>
							<?php foreach ($GOOD_PRICES as $gs) { ?>
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
			</div>
		</div>

		<div class="submit-btn">
			<input type="submit" value="장바구니 담기" />
		</div>
	</form>
</div>

<iframe name="frmTarget" style="display:none;border:1px solid;width:100px;height:50px"></iframe>

<script>
	$("#<?=$_GET["modal"]?>").bind("modalShow", function(e, content) {
		content.find("#cart-form").submit(function() {
			if (this.option.value == "") {
				alert("옵션을 선택해 주세요.");

				return false;
			}
		});
	});
</script>
