	<div class="join-box">
		<div class="inner">
			<div class="title-box">
				<div class="step"><span class="current">2</span> / 3 단계</div>

				<h5>가입 정보 입력</h5>
			</div>

			<form action="/Login/Joininput" method="post" id="join-form" target="frmTarget" enctype="multipart/form-data">
				<div class="input-row-group">
					<div class="input-row">
						<label for="email">
							<span class="valid-icon required">[필수]</span>
							이메일
						</label>

						<div class="input-box">
							<input type="text"
								   name="email"
								   id="email"
								   autocomplete="off"
								   placeholder="이메일 주소를 입력해 주세요."
							/>
						</div>
					</div>

					<div class="input-row">
						<label for="name">
							<span class="valid-icon required">[필수]</span>
							이름
						</label>

						<div class="input-box">
							<input type="text"
								   name="name"
								   id="name"
								   autocomplete="off"
								   placeholder="이름을 입력해 주세요."
							/>
						</div>
					</div>

					<div class="input-row">
						<p class="label">
							<span class="valid-icon required">[필수]</span>
							소속 기관
						</p>

						<div class="dropdown-box agency placeholder">
							<input type="hidden" name="state" id="state" />

							<button type="button" tabindex="-1" placeholder="시/도 선택">시/도 선택</button>

							<div class="dropdown-list">
								<ul>
									<?php foreach ($hospital_sdo as $val) {?>
									<li>
										<button type="button" data-value="<?php echo $val;?>"><?php echo $val;?></button>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>

						<div class="dropdown-box agency placeholder" data-field="region">
							<input type="hidden" name="region" id="region" />

							<button type="button" tabindex="-1" placeholder="시/구/군 선택">시/구/군 선택</button>

							<div class="dropdown-list"><ul></ul></div>
						</div>
					</div>

					<div class="input-row not-label">
						<div class="input-box agency">
							<input type="text"
								   name="agency"
								   id="agency"
								   autocomplete="off"
								   placeholder="소속 기관을 검색해 주세요."
							/>
							<input type="hidden" name="hospital" id="hospital" />

							<button type="button" id="onekey-search" class="btn type-black size-small" tabindex="-1">검색</button>

							<div class="agency-list">
								<ul>
									<li>
										<button type="button">ABCDEFG12345678910</button>
									</li>
									<li>
										<button type="button">ABCDEFG12345678910</button>
									</li>
									<li>
										<button type="button">ABCDEFG12345678910</button>
									</li>
								</ul>
							</div>
						</div>
					</div>

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
									<input type="file" id="business_license" name="business_license" required />
									<p class="placeholder" placeholder="파일 선택">파일 선택</p>
								</label>
							</div>
						</div>
					</div>



					<div class="input-row">
						<p class="label">
							<span class="valid-icon required">[필수]</span>
							진료과
						</p>

						<div class="dropdown-box placeholder">
							<input type="hidden" name="department" id="department" />

							<button type="button" tabindex="-1">진료과를 선택해 주세요.</button>

							<div class="dropdown-list">
								<ul>
									<?php foreach ($opd as $val) {?>
									<li>
										<button type="button" data-value="<?php echo $val->OPD_CD;?>"><?php echo $val->OPD_NM;?></button>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>

					<div class="input-row">
						<label for="password">
							<span class="valid-icon required">[필수]</span>
							비밀번호
						</label>

						<div class="input-box">
							<input type="password"
								   name="password"
								   id="password"
								   autocomplete="off"
								   placeholder="비밀번호를 입력해 주세요."
							/>
						</div>

						<p class="hint">* 비밀번호는 영문 소문자, 대문자, 숫자, 특수 문자를 모두 포함한 최소 8자리 이상 설정</p>
					</div>

					<div class="input-row">
						<label for="password_check">
							<span class="valid-icon required">[필수]</span>
							비밀번호 확인
						</label>

						<div class="input-box">
							<input type="password"
								   name="password_check"
								   id="password_check"
								   autocomplete="off"
								   placeholder="비밀번호를 한번 더 입력해 주세요."
							/>
						</div>
					</div>

					<div class="input-row">
						<label for="phone">
							<span class="valid-icon required">[필수]</span>
							휴대폰 번호
						</label>

						<div class="input-box">
							<input type="text"
								   name="phone"
								   id="phone"
								   class="phone"
								   autocomplete="off"
								   placeholder="휴대폰 번호를 입력해 주세요."
							/>
						</div>
					</div>

					<div class="input-row">
						<label for="sales_info">
							<span class="valid-icon optional">[선택]</span>
							영업 사원 코드
						</label>

						<div class="input-box">
							<input type="text"
								   name="sales_info"
								   id="sales_info"
								   placeholder="영업 사원 코드를 입력해 주세요."
							/>
						</div>
					</div>
					<div class="input-row">
						<label for="sales_info">
							<span class="valid-icon optional">[선택]</span>
							영업 사원 이름
						</label>

						<div class="input-box">
							<input type="text"
								   name="sales_name"
								   id="sales_name"
								   placeholder="영업 사원 이름을 입력해 주세요."
							/>
						</div>

						<p class="desc">담당 영업사원이 없는 경우 별도의 할인 서비스를 제공합니다.</p>
					</div>

					<div class="form-sign" id="sign">
						<canvas></canvas>
						<p class="placeholder">서명을 입력해 주세요.</p>
						<input type="hidden" name="signature" id="signature-input">
					</div>
				</div>

				<div class="btn-row-group">
					<div class="btn-row">
						<button type="submit" class="btn type-primary size-big">회원 가입하기</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<iframe name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		$(function() {
			$("#state").on("change", function() {
				dummy = [];
				<?php foreach ($hospital_sdo as $val) {?>
				dummy['<?php echo $val;?>'] = [
					<?php foreach ($hospital_info[$val] as $sggNm => $sval) {?>
					{ label : "<?php echo $sggNm;?>", value : "<?php echo $sggNm;?>" },
					<?php } ?>
				]
				<?php } ?>

				dropdownRender($(".dropdown-box[data-field=region]"), dummy[this.value]);
			});

			$("#department").on("change", function() {
				$(this).closest(".input-row").removeClass("error-row").addClass("valid-row");
			});

			$("#join-form").validate({
				rules: {
					email: {
						email:true,
						required: true,
						maxlength: 100,
						remote: "/Common/checkEmailDuplicate"
					},
					name: {
						required: true,
						maxlength: 100
					},
					agency: {
						required: true
					},
					department: {
						required: true
					},
					password: {
						required: true,
						pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#?!@$%^&*-])[A-Za-z\d#?!@$%^&*-]{8,}/
					},
					password_check: {
						required: true,
						pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#?!@$%^&*-])[A-Za-z\d#?!@$%^&*-]{8,}/,
						equalTo : password
					},
					phone: {
						required: true,
						pattern:/^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/
					},
					business_number: {
						required: true,
						pattern: /^[0-9]{3}-[0-9]{2}-[0-9]{5}$/
					},
					business_license: {
						required: true
					},
				},
				messages: {
					email : {
						email: "이메일 형식을 확인해 주세요.",
						required: "이메일을 입력해 주세요.<br/>예시) userid@iqvia.com",
						maxlength: "최대 입력글자수는 100글자입니다.",
						remote : "이미 가입된 이메일입니다.<br/>다른 이메일을 사용해 주세요."
					},
					name : {
						required: "이름을 입력해 주세요.",
						maxlength: "최대 입력글자수는 100글자입니다."
					},
					department : "진료과를 입력해 주세요.",
					agency : "소속 기관을 입력해 주세요.",
					password : {
						required:"비밀번호를 입력해 주세요.",
						pattern: "비밀번호 양식을 확인해 주세요."
					},
					password_check : {
						required:"비밀번호를 확인해 주세요.",
						pattern: "비밀번호 양식을 확인해 주세요.",
						equalTo: "비밀번호가 일치하지 않습니다."
					},
					phone : {
						required: "연락처를 입력해 주세요.",
						pattern: "연락처를 형식에 맞게 입력해 주세요."
					},
					business_number: {
						required:"사업자 등록번호를 입력해 주세요",
						pattern:"사업자 등록번호 형식을 확인해 주세요<br/>예시) 123-45-67890"
					},
					business_license: "사업자 등록증을 첨부해 주세요",
				},
				submitHandler: function(form) {
					if (sign["sign"].isEmpty()) {
						alert("서명을 입력해 주세요.");
						return;
					}

					const signatureData = sign["sign"].toDataURL(); // Get the signature as base64 string
					$("#signature-input").val(signatureData);

					form.submit();
				}
			});
		});
	</script>
