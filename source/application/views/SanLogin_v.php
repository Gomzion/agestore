	<div class="login-box">
		<h1>AG e-Store</h1>
		<p class="admin-mark">관리자 시스템</p>

		<div class="form-box">
			<form action="/Common/login" method="post" id="login-form">
				<div class="input-row-group">
					<div class="input-row">
						<label for="email">이메일 주소</label>
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
						<label for="password">비밀번호</label>
						<div class="input-box">
							<input type="password"
								   name="password"
								   id="password"
								   autocomplete="off"
								   placeholder="비밀번호를 입력해 주세요."
							/>
						</div>
					</div>
				</div>

				<div class="etc-btn-group">
					<ul>
						<li><a href="/Common/FindIDSan">아이디 찾기</a></li>
						<li><a href="/Common/InitPWSan">비밀번호 초기화</a></li>
					</ul>

					<a href="/san/jointerm">회원가입</a>
				</div>

				<div class="btn-row-group">
					<div class="btn-row">
						<input type="submit" class="btn type-primary size-big" value="로그인" />
					</div>
				</div>
			</form>
		</div>

		<ul class="terms">
			<li><a href="/Modal/termFullService">서비스 이용약관</a></li>
			<li><a href="/Modal/termFullPrivacy">개인정보 취급 방침</a></li>
		</ul>

		<p class="copy-right">저작권 표시 및 관련 사항  Copy right © SHC co ,. Ltd 2023.All rights reserved.</p>
	</div>

	<script>
		$(function() {
			<?php if($popup->POPUP_USE_FL == 'Y') {?>
			if (getCookie("login-popup") != "1")
				modalShow({
					modal:"login-popup",
					idx:1000,
					width:400,
					height:400
				});
			<?php } ?>

			$("#login-form").validate({
				rules: {
					email: {
						email: true,
						required: true
					},
					password: {
						required: true
					},
				},
				messages: {
					email : {
						email: "이메일 형식을 확인해 주세요.",
						required: "이메일을 입력해 주세요.<br/>예시) userid@iqvia.com",
					},
					password :"비밀번호를 입력해 주세요.",
				},
				submitHandler: function(form) {
					form.submit();
				}
			});
		});
	</script>
