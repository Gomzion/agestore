	<div class="content-box">
		<div class="inner">
			<div class="title-box">
				<ul class="step">
					<li>사용자 동의</li>
					<li class="on">사용자 정보 입력</li>
				</ul>

				<h4>AG eStore 사용자 정보 입력</h4>
			</div>

			<form action="/Agmvp/joininput" method="post" id="join-form" target="frmTarget" enctype="multipart/form-data">
				<input type="hidden" name="aname" value="<?=$aname?>">
				<input type="hidden" name="aphone" value="<?=$aphone?>">
				<input type="hidden" name="userkey" value="<?=$userkey?>">
				<div class="form-box">
					<div class="term-row">
						<div class="head">
							<div class="check-item">
								<input type="checkbox" name="agree_dlvy" id="agree-dlvy" />
								<label for="agree-dlvy"><span>[필수]</span> 납품용 이용 서비스 정보 동의</label>
							</div>

							<button type="button" class="modal-btn" data-modal="term-dlvy">전문보기</button>

							<button type="button" class="toggle-btn">펼치기/접기</button>
						</div>

						<div class="term-area">
							<div class="inner">
								<p><?php echo $terms[1]->TERMS_TX;?></p>
							</div>
						</div>
					</div>

					<div class="input-row-group">
						<div class="input-row">
							<label for="business_number">
								<span class="valid-icon required">[필수]</span>
								사업자 등록번호
							</label>

							<div class="input-box">
								<input type="text"
									   name="business_number"
									   id="business_number"
									   class="business-number"
									   maxlength="12"
									   placeholder="사업자 등록번호를 입력해 주세요."
								/>
							</div>
						</div>

						<div class="input-row">
							<p class="label">
								<span class="valid-icon required">[필수]</span>
								사업자 등록증
							</p>

							<div class="file-box">
								<div class="input-file">
									<label>
										<input type="file" name="business_license" required />
										<p class="placeholder" placeholder="파일 선택">파일 선택</p>
									</label>
								</div>
							</div>
						</div>

						<div class="input-row">
							<label for="sales_info">
								<span class="valid-icon optional">[선택]</span>
								영업 사원 정보
							</label>

							<div class="input-box">
								<input type="text"
									   name="sales_info"
									   id="sales_info"
									   placeholder="영업 사원 정보를 입력해 주세요."
								/>
							</div>

							<p class="desc">담당 영업사원이 없는 경우 별도의 할인 서비스를 제공합니다.</p>
						</div>
					</div>
				</div>

				<div class="form-sign" id="sign">
					<canvas></canvas>
					<p class="placeholder">서명을 입력해 주세요.</p>
					<input type="hidden" name="signature" id="signature-input">
				</div>

				<div class="btn-row-group">
					<div class="btn-row">
						<button type="submit" class="btn type-primary">실명인증 후 가입 완료</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<iframe name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		$(function() {
			$("#agree-dlvy").change(function() {
				if ($(this).is(":checked")) {
					$(this).closest(".term-row").addClass("checked");

					if (!$(this).data("first")) {
						$(this).data("first", true);
						$(this).closest(".term-row").addClass("on");
						$(this).closest(".term-row").find(".term-area").slideUp();
					}
				} else {
					$(this).closest(".term-row").removeClass("checked");
				}
			});

			$(".toggle-btn").click(function() {
				const row = $(this).closest(".term-row");

				row.toggleClass("on");
				row.find(".term-area").slideToggle();
			});

			$("#join-form").validate({
				rules: {
					business_number: {
						required: true,
						pattern: /^[0-9]{3}-[0-9]{2}-[0-9]{5}$/
					},
					business_license: {
						required: true
					},
				},
				messages: {
					business_number: {
						required:"사업자 등록번호를 입력해 주세요",
						pattern:"사업자 등록번호 형식을 확인해 주세요<br/>예시) 123-45-67890"
					},
					business_license: "사업자 등록증을 첨부해 주세요",
				},
				submitHandler: function(form) {
					if (!$(form.agree_dlvy).is(":checked")) {
						alert("납품용 이용 서비스 정보 동의에 동의해 주세요.");
						return;
					}

					// 개발시 협업 필요합니다.
					// 1. data_blob 형태로 받아서 처리하실건지..
					// 2. 이미지파일 형태를 받으실건지
					// 3. 폼 전송전에 별도의 ajax 처리해서 업로드처리된 이미지 url만 받으실건지

					// 2번의 경우 file 형태를 input에 value 처리할 수가 없어서
					// 기존 폼으로 처리가 안되며, FormData 만들어서 ajax 처리해야합니다.

					if (sign["sign"].isEmpty()) {
						alert("서명을 입력해 주세요.");
						return;
					}

					const signatureData = sign["sign"].toDataURL(); // Get the signature as base64 string
					$("#signature-input").val(signatureData);

					form.submit();
					//$(".form-sign canvas")[0].toBlob((blob) => {
					//	let file = new File([blob], "sign.jpg", { type: "image/jpeg" });
					//}, 'image/jpeg');
				}
			});
		});
	</script>
