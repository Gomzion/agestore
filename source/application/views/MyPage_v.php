	<div class="article">
		<form action="/My/ChangePassword" method="post" id="mypage-form" target="frmTarget">
			<div class="article-title">
				<h6>내 정보</h6>
			</div>

			<div class="section-box mypage">
				<div class="section-title">
					<h6>의료진 정보</h6>
				</div>

				<div class="section-content">
					<div class="mypage-info">
						<p>의료진 이름 : </p>
						<?php echo $USER->USER_NM; ?>
					</div>

					<div class="mypage-info">
						<p class="big">소속기관 : </p>
						<div class="agency-box">
							<?php echo $USER->HOSPITAL_NM;?> (<?php echo $USER->HOSPITAL_SDO_NM;?> <?php echo $USER->HOSPITAL_SGG_NM;?> <?php echo $USER->HOSPITAL_BLD_NM;?>)
							<button type="button" class="modal-btn" data-modal="onekey">소속 기관 정보 변경</button>
						</div>
					</div>

					<div class="mypage-info" id="corp_no">
						<p>사업자등록번호 : </p>
						<?php echo $USER->HOSPITAL_CORP_CD; ?>
					</div>

					<div class="mypage-info">
						<p class="big">연락처 : </p>
						<div class="phone-box">
							<div class="input-row">
								<div class="input-box">
									<input type="text"
										   name="phone"
										   id="phone"
										   class="phone"
										   placeholder="연락처를 입력해 주세요."
										   value="<?php echo $USER->USER_HPN_NO; ?>"
									/>

									<button type="button" class="input-delete-btn" tabindex="-1">입력 값 지우기</button>
								</div>

								<button type="button" class="phone-change-cert">연락처 변경</button>
							</div>

							<div class="input-row" style="display:none;">
								<div class="input-box">
									<input type="text"
										   name="cert_no"
										   id="cert_no"
										   class="number"
										   placeholder="인증 번호 입력"
									/>
								</div>

								<button type="button" class="phone-change">인증번호 확인</button>
							</div>
						</div>
					</div>

					<?php if($USER->USER_SIGN_IL) {?>
					<div class="mypage-info">
						<p class="big">서명 : </p>
						<img src="<?php echo $USER->USER_SIGN_IL;?>" alt="Image" style="width:300px;border:1px solid">
					</div>
					<?php } ?>
				</div>
			</div>

			<div class="section-box mypage">
				<div class="section-title">
					<h6>담당자 정보</h6>
				</div>

				<div class="section-content">
					<div class="mypage-info">
						<p>영업 담당자 : </p>
						<?php if($USER->HOSPITAL_SALES_CD) echo "$USER->HOSPITAL_SALES_NM ($USER->HOSPITAL_SALES_CD)"; else echo "업음"; ?>
					</div>
				</div>
			</div>

			<div class="section-box mypage password">
				<div class="section-title">
					<h6>비밀번호 변경</h6>
				</div>

				<div class="section-content">
					<div class="mypage-info">
						<p>현재 비밀번호 : </p>
						<div class="input-box">
							<input type="password"
								   name="current_password"
								   id="current_password"
								   autocomplete="off"
								   placeholder="현재 비밀번호를 입력해 주세요."
							/>
						</div>
					</div>

					<div class="mypage-info">
						<p>변경 비밀번호 : </p>
						<div class="input-box">
							<input type="password"
								   name="new_password"
								   id="new_password"
								   autocomplete="off"
								   placeholder="변경 비밀번호를 입력해 주세요."
							/>
						</div>
					</div>

					<div class="mypage-info">
						<p>비밀번호 확인 : </p>
						<div class="input-box">
							<input type="password"
								   name="new_password_check"
								   id="new_password_check"
								   autocomplete="off"
								   placeholder="변경 비밀번호를 한번 더 입력해 주세요."
							/>
						</div>
					</div>
				</div>
			</div>

			<div class="article-submit-btn">
				<input type="submit" value="비밀번호 변경" />
			</div>
		</form>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		function PasswdComplete(msg) {
			alert(msg);

			$('#current_password').val('');
			$('#new_password').val('');
			$('#new_password_check').val('');
		}

		function HPnumComplete() {
			$("#phone").prop("readonly", false);
			$(".phone-box").find(".input-delete-btn").show();
			$(".phone-box").find(".input-row:last-child").hide();

			//alert('연락처가 변경되었습니다');
		}

		function OrganComplete(hfn, cco) {
			//document.getElementById('textSpan').textContent = hfn;
			$(".agency-box").contents()[0].textContent = hfn;
			$("#corp_no").contents()[2].textContent = cco;

			modalHide($('.modal'));
		}

		$(function() {
			$("#mypage-form").validate({
					rules: {
						current_password: {
							required: true,
							pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#?!@$%^&*-])[A-Za-z\d#?!@$%^&*-]{8,}/
						},
						new_password: {
							required: true,
							pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#?!@$%^&*-])[A-Za-z\d#?!@$%^&*-]{8,}/
						},
						new_password_check: {
							required: true,
							equalTo : new_password
						},
					},
					messages: {
						current_password : {
							required:"현재 비밀번호를 입력해 주세요.",
						},
					new_password : {
						required:"변경 비밀번호를 입력해 주세요.",
						pattern: "비밀번호 양식을 확인해 주세요."
					},
					new_password_check : {
						required:"변경 비밀번호를 확인해 주세요.",
						equalTo: "비밀번호가 일치하지 않습니다."
					},
				},
				submitHandler: function(form) {
					form.submit();
				}
			});

			$(".phone-change-cert").click(function() {
				const phoneStr = $("#phone").val(),
					regex = new RegExp(/^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/);

				if (phoneStr === "") {
					alert("연락처를 입력해 주세요.");
					$("#phone").focus();
					return;
				}

				if (!regex.test(phoneStr)) {
					alert("연락처 형식을 확인해 주세요.");
					$("#phone").focus();
					return;
				}

				$("#phone").prop("readonly", true);
				$(this).closest(".phone-box").find(".input-delete-btn").hide();
				$(this).closest(".phone-box").find(".input-row:last-child").show();
			});

			$(".phone-change").click(function() {
				const certNo = $("#cert_no").val();
				const phoneStr = $("#phone").val();

				if (certNo === "") {
					alert("인증번호를 입력해 주세요.");
					$("#cert_no").focus();
					return;
				}

				// Get the reference to the iframe element
				var iframe = document.getElementById('frmHiddenTarget');

				// Set the URL of the iframe
				iframe.src = '/My/updateHPNum/'+phoneStr;
			});
		});
	</script>
