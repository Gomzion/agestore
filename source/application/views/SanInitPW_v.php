
	<div class="find-box">
		<div class="inner">
			<div class="title-box">
				<h5>비밀번호 초기화</h5>
			</div>

			<div class="form-box">
				<p class="desc">안녕하세요. 비밀번호 초기화를 위해서는 실명 인증이 필요합니다.<br/>아이디와 이름을 입력 후 실명 인증 버튼을 눌러 인증을 완료해 주세요.</p>

				<div class="form-box">
					<form action="" method="post" id="find-form">
						<div class="input-row-group">
							<div class="input-row">
								<label for="email">
									<span class="valid-icon required">[필수]</span>
									이메일 주소
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
						</div>

						<div class="btn-row-group">
							<div class="btn-row">
								<input type="submit" class="btn type-primary size-big" value="실명 인증"/>
							</div>

							<div class="btn-row">
								<a href="/San" class="btn type-black-line size-big">로그인 페이지로 이동</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function() {
			$("#find-form").validate({
				rules: {
					email: {
						email: true,
						required: true
					},
					name: {
						required: true
					},
				},
				messages: {
					email : {
						email: "이메일 형식을 확인해 주세요.",
						required: "이메일을 입력해 주세요.",
					},
					name :"이름을 입력해 주세요.",
				},
				submitHandler: function(form) {
					form.submit();
				}
			});
		});
	</script>
