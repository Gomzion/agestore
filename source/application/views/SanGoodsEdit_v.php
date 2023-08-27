
	<div class="article product-edit">
		<form action="/SanGoods/mody" method="post" id="product-form" enctype="multipart/form-data" target="frmTarget">
			<input type="hidden" name="good_id" value="<?php echo $GOODS_ID;?>" />
			<div class="article-title">
				<h6>상품 <?php echo $GOODS_ID == "" ? "추가" : "수정"; ?></h6>

				<button type="button" class="btn size-small back-btn">상품 <?php echo $GOODS_ID == "" ? "추가" : "수정"; ?> 취소</button>
			</div>

			<div class="section-box info">
				<div class="section-title">
					<h6>상품 기본 정보</h6>
				</div>

				<div class="section-content">
					<div class="input-row-group">
						<div class="input-row">
							<label for="product_name">상품명</label>

							<div class="input-box">
								<input type="text"
									   name="product_name"
									   id="product_name"
									   placeholder="상품명을 입력해 주세요."
									   value="<?php if($GOODS_ID) echo $GOOD->GOODS_NM;?>"
								/>
							</div>
						</div>

						<div class="input-row">
							<label for="sap_code">SAP Code</label>

							<div class="input-box">
								<input type="text"
									   name="sap_code"
									   id="sap_code"
									   placeholder="SAP Code를 입력해 주세요."
									   value="<?php if($GOODS_ID) echo $GOOD->GOODS_SAP_CD;?>"
								/>
							</div>
						</div>

						<div class="input-row">
							<label for="product_desc">상품 요약</label>

							<div class="input-box">
							  <textarea
								  name="product_desc"
								  id="product_desc"
								  placeholder="상품 요약 내용을 입력해 주세요."
							  ><?php if($GOODS_ID) echo $GOOD->GOODS_BRIF_TX;?></textarea>
							</div>
						</div>

						<div class="input-row">
							<p class="label">상품 구분</p>

							<div class="dropdown-box placeholder">
								<input type="hidden" name="category" id="category" />

								<button type="button" tabindex="-1" placeholder="상품 구분을 선택해 주세요.">상품 구분을 선택해 주세요.</button>

								<div class="dropdown-list">
									<ul>
										<?php foreach ($GTYPE as $type) {?>
										<li>
											<button type="button" data-value="<?php echo $type->GTYPE_CD;?>">
												<?php echo $type->GTYPE_NM;?>
											</button>
										</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="section-box price">
				<div class="section-title">
					<h6>상품 가격 정보</h6>

					<button type="button" class="btn type-black size-small" id="price-add-btn">+ 가격 정보 추가</button>
				</div>

				<div class="section-content">
					<div class="price-list">

						<?php $n = 0; do { ?>
						<div class="price-item">
							<div class="drag-bar">드래그 바</div>

							<div class="input-box">
								<input type="text"
									   name="price_unit[]"
									   placeholder="단위를 입력해 주세요."
									   value="<?php if($GOODS_ID) echo $GOOD_PRICES[$n]->GPRICE_AMT;?>"
								/>
							</div>

							<div class="input-box">
								<input type="text"
									   name="price_before_sale[]"
									   placeholder="할인 전 가격을 입력해 주세요."
									   value="<?php if($GOODS_ID) echo $GOOD_PRICES[$n]->GPRICE_PC;?>"
								/>
							</div>

							<div class="input-box">
								<input type="text"
									   name="price_sale[]"
									   placeholder="할인 가격을 입력해 주세요."
									   value="<?php if($GOODS_ID) echo $GOOD_PRICES[$n]->GPRICE_SALE_PC;?>"
								/>
							</div>
						</div>
						<?php $n++; } while($n < count($GOOD_PRICES)); ?>

					</div>
				</div>
			</div>

			<div class="section-box image">
				<div class="section-title">
					<h6>상품 이미지</h6>

					<label>
						<input type="file" id="product-image" accept="image/*">
						<p class="btn type-black size-small">+ 상품 이미지 추가</p>
					</label>
				</div>

				<div class="section-content">
					<div class="image-list">
						<?php foreach($GOOD_PHOTOS as $gs) {?>
						<div class="image-item">
							<div class="drag-bar">드래그 바</div>

							<div class="thumb">
								<input type="hidden" name="photo_sq[]" value="<?php echo $gs->GPHOTO_SQ; ?>" />
								<input type="hidden" name="f_name[]" value="<?php echo $gs->GPHOTO_NM; ?>" />
								<input type="hidden" id="represent" name="represent[]" value="<?php echo $gs->GPHOTO_FL; ?>" />
								<img src="<?php echo IMG_PATH.$gs->GPHOTO_NM;?>">
							</div>

							<div class="btn-group">
								<button type="button" id="represent-btn" <?php if($gs->GPHOTO_FL == 'Y') echo "class='on'"; ?>>대표 이미지</button>

								<button type="button" class="btn size-small" id="image-delete-btn">삭제</button>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="section-box content">
				<div class="section-title">
					<h6>상품 정보</h6>
				</div>

				<div class="section-content">
					<textarea name="product_content">
						<?php if($GOODS_ID) echo $GOOD->GOODS_TX;?>
					</textarea>
				</div>
			</div>

			<div class="btn-row-group">
				<div class="btn-row">
					<input type="submit" class="btn type-primary size-big" value="상품 <?php echo $GOODS_ID == "" ? "추가" : "수정"; ?>" />
				</div>
			</div>
		</form>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script type="text/html" id="price-template">
		<div class="price-item">
			<div class="drag-bar">드래그 바</div>

			<div class="input-box">
				<input type="text"
					   name="price_unit[]"
					   placeholder="단위를 입력해 주세요."
				/>
			</div>

			<div class="input-box">
				<input type="text"
					   name="price_before_sale[]"
					   placeholder="할인 전 가격을 입력해 주세요."
				/>
			</div>

			<div class="input-box">
				<input type="text"
					   name="price_sale[]"
					   placeholder="할인 가격을 입력해 주세요."
				/>
			</div>
		</div>
	</script>

	<script type="text/html" id="image-template">
		<div class="image-item">
			<div class="drag-bar">드래그 바</div>

			<div class="thumb">
				<input type="hidden" name="photo_sq[]" value="" />
				<input type="hidden" name="f_name[]" value="" />
				<input type="hidden" id="represent" name="represent[]" value="N" />
				<img src="<%=imgUri%>" />
			</div>

			<div class="btn-group">
				<button type="button" id="represent-btn">대표 이미지</button>

				<button type="button" class="btn size-small" id="image-delete-btn">삭제</button>
			</div>
		</div>
	</script>

	<script>
		const sortableOptions = {
			axis: "x",
			cursor: "move",
			revert: true,
			handle: ".drag-bar",
		};

		$(document).ready(function() {
			<?php if($GOODS_ID) { ?>
				$(".dropdown-box button[data-value='<?php echo $GOOD->GTYPE_CD; ?>']").trigger("click");
			<?php } ?>
		});

		$(function() {
			const priceTemplate = _.template($("#price-template").html()),
				imageTemplate = _.template($("#image-template").html());

			let editor = null;

			$(".price-list").sortable({
				...sortableOptions,
				axis:"y",
				items: "> .price-item",
				placeholder: "price-placeholder"
			});

			$(".image-list").sortable({
				...sortableOptions,
				axis:"x",
				items: "> .image-item",
				placeholder: "image-placeholder"
			});

			ClassicEditor.create($("textarea[name=product_content]")[0], {
				...ckeditorDefaultOptions,
				simpleUpload: {
					// The URL that the images are uploaded to.
					uploadUrl: '/upload/image',

					// Enable the XMLHttpRequest.withCredentials property.
					withCredentials: true,

					// Set the maximum file size to 0 for unlimited size.
					maxFileSize: 5 * 1024 * 1024
				}
			}).then(newEditor => {
				editor = newEditor;
			});

			$(document).on("click", "#price-add-btn", function(e) {
				let templateHtml = priceTemplate();

				$(".price-list").append(templateHtml);
			});

			$(document).on("change", "#product-image", function(e) {
				const reader = new FileReader()

				let file = e.target.files[0];

				reader.onload = function(e) {
					let templateHtml = imageTemplate({
						imgUri:e.target.result
					});

					$(".image-list").append(templateHtml);

					let fileInput = document.createElement("input");

					fileInput.type = "file";
					fileInput.name = `product_image[]`;
					fileInput.files = new FileList(file);

					$(".image-list .image-item:last-child .thumb").prepend(fileInput);

					$(this).val("");
				};

				reader.readAsDataURL(file);
			});

			$(document).on("click", "#represent-btn", function(e) {
				$(".image-list #represent-btn").removeClass("on");
				$(".image-list #represent").val("N");
				$(this).addClass("on");
				$(this).closest(".image-item").find("#represent").val("Y");
			});

			$(document).on("click", "#image-delete-btn", function(e) {
				$(this).closest(".image-item").remove();

				if ($(".image-list .image-item").length === 0) {
					$(".image-list").empty();
				}
			});

			$("#product-form").validate({
				rules: {
					product_name: {
						required: true
					},
					sap_code: {
						required: true
					},
					product_desc: {
						required: true
					},
					category: {
						required: true
					},
				},
				messages: {
					product_name : "상품명을 입력해 주세요.",
					sap_code : "SAP Code를 입력해 주세요.",
					product_desc : "상품 요양 내용을 입력해 주세요.",
					category : "상품 구분을 선택해 주세요.",
				},
				submitHandler: function(form) {
					if ($(".image-item").length === 0) {
						alert("상품 이미지를 등록해 주세요.");
						return;
					}

					editor.updateSourceElement();

					setTimeout(function() {
						form.submit();
					}, 100);
				}
			});
		});
	</script>
