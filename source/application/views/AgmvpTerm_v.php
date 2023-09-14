
	<div class="content-box">
		<div class="inner">
			<div class="title-box">
				<ul class="step">
					<li class="on">사용자 동의</li>
					<li>사용자 정보 입력</li>
				</ul>

				<h4>AG eStore 사용자 동의</h4>
			</div>

			<div class="desc-box">
				<p>안녕하세요. <?=$aname?> 선생님,</p>
				<p>AGMVP 서비스를 이용하시는 선생님을 위한 VIP 서비스 AG eStore 방문에 감사드립니다.<br/>본 서비스를 사용하기 위해서 몇가지 사용자 동의와 정보 입력이 필요합니다.</p>
			</div>

			<form action="/Agmvp/Join" method="post" id="join-form">
				<input type="hidden" name="aname" value="<?=$aname?>">
				<input type="hidden" name="aphone" value="<?=$aphone?>">
				<input type="hidden" name="userkey" value="<?=$userkey?>">
				<div class="term-box">
					<div class="term-row">
						<div class="head">
							<div class="check-item">
								<input type="checkbox" id="all-check" />
								<label for="all-check">전체 약관 동의</label>
							</div>
						</div>

						<p>본인은 아래 서비스 이용약관, 개인정보처리방침, 의료진 확인서를 모두 확인하고 서비스 이용에 필요한 필수 약관에 모두 동의 합니다.</p>
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
								<label for="agree-privacy"><span>[필수]</span> 개인정보처리방침</label>
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
						<button type="submit" class="btn type-primary">가입정보 입력</button>
					</div>

					<div class="btn-row">
						<button type="button" class="return-agmvp" onclick="javascript:top.location.href='<?=GOBACK_AGMVP?>';">이용하지 않고 AGMVP로 돌아가기</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script>
		$(function() {
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
