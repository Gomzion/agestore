	<div class="join-box">
		<div class="inner">
			<div class="title-box">
				<div class="step"><span class="current">1</span> / 3 단계</div>

				<h5>약관 동의</h5>
			</div>

			<div class="desc-box">
				<p>본인은 아래 서비스 이용 약관, 개인정보 처리방침을 모두 확인하고 서비스 이용에 필요한 필수 약관에 모두 동의 합니다.</p>
			</div>

			<form action="/San/join" method="post" id="join-form">
				<div class="term-box">
					<div class="term-row">
						<div class="head">
							<div class="check-item">
								<input type="checkbox" id="all-check" />
								<label for="all-check">전체 약관 동의</label>
							</div>
						</div>

						<p>본인은 아래 서비스 이용약관, 개인정보 처리방침을 모두 확인하고 서비스 이용에 필요한 필수 약관에 모두 동의 합니다.</p>
					</div>

					<div class="term-row">
						<div class="head">
							<div class="check-item">
								<input type="checkbox" name="agree_service" id="agree-service" />
								<label for="agree-service"><span>[필수]</span> 서비스 이용약관</label>
							</div>

							<button type="button" class="modal-btn" data-modal="term-service">전문보기</button>

							<button type="button" class="toggle-btn">펼치기/접기</button>
						</div>

						<div class="term-area">
							<div class="inner">
								<p><?php echo $terms[1]->TERMS_TX;?></p>
							</div>
						</div>
					</div>

					<div class="term-row">
						<div class="head">
							<div class="check-item">
								<input type="checkbox" name="agree_privacy" id="agree-privacy" />
								<label for="agree-privacy"><span>[필수]</span> 개인정보 처리방침</label>
							</div>

							<button type="button" class="modal-btn" data-modal="term-privacy">전문보기</button>

							<button type="button" class="toggle-btn">펼치기/접기</button>
						</div>

						<div class="term-area">
							<div class="inner">
								<p><?php echo $terms[0]->TERMS_TX;?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="btn-row-group">
					<div class="btn-row">
						<button type="submit" class="btn type-primary size-big">가입정보 입력</button>
					</div>

					<div class="btn-row">
						<button type="button" class="back-btn-term btn type-black-line size-big">취소</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		$(function() {
			$(".back-btn-term").click(function() {
				if (confirm("회원가입을 취소 하시겠습니까?")) {
					history.go(-1);
				}
			});

			$("#all-check").change(function() {
				if ($(this).is(":checked")) {
					$(".term-row").addClass("checked");

					$("#agree-service, #agree-privacy").prop("checked", true);
					$("#agree-service, #agree-privacy").trigger("change");
				} else {
					$(".term-row").removeClass("checked");

					$("#agree-service, #agree-privacy").prop("checked", false);
				}
			});

			$("#agree-service, #agree-privacy").change(function() {
				const totalLength = $("#join-form input[type=checkbox]:not(#all-check)").length,
					checkedLength = $("#agree-service:checked, #agree-privacy:checked:checked").length;

				if (checkedLength == totalLength) {
					$("#all-check").prop("checked", true).closest(".term-row").addClass("checked");
				} else {
					$("#all-check").prop("checked", false).closest(".term-row").removeClass("checked");
				}

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
					agree_service: {
						required: true
					},
					agree_privacy: {
						required: true
					},
				},
				messages: {
					agree_service : "서비스 이용약관에 동의해 주세요.",
					agree_privacy : "개인정보 처리방침에 동의해 주세요.",
				},
				onkeyup:false,
				onclick:false,
				showErrors: function(errorMap,errorList){
					if (this.numberOfInvalids()) {
						alert(errorList[0].message);
					}
				},
				submitHandler: function(form) {
					form.submit();
				}
			});
		});
	</script>
