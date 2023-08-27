	<div class="join-box">
		<div class="inner">
			<div class="title-box">
				<div class="step"><span class="current">2</span> / 3 단계</div>

				<h5>가입 정보 입력</h5>
			</div>

			<form action="/San/joininput" method="post" id="join-form" target="frmTarget">
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
							소속
						</p>

						<div class="input-box">
							<input type="text"
								   name="agency"
								   id="agency"
								   autocomplete="off"
								   placeholder="소속을 입력해 주세요."
							/>
						</div>
					</div>

					<div class="input-row">
						<p class="label">
							<span class="valid-icon optional">[선택]</span>
							사번
						</p>

						<div class="input-box">
							<input type="text"
								   name="staffid"
								   id="staffid"
								   autocomplete="off"
								   placeholder="사번을 입력해 주세요."
							/>
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
						required: true,
						maxlength: 100
					},
					staffid: {
						required: false,
						pattern: /^[0-9]{8}$/,
						maxlength: 8
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
					staffid : {
						maxlength: "최대 입력글자수는 8글자입니다."
					},
					agency : {
						required: "소속 기관을 입력해 주세요.",
						maxlength: "최대 입력글자수는 100글자입니다."
					},
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
				},
				submitHandler: function(form) {
					form.submit();
				}
			});
		});
	</script>
