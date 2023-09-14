
<div class="find-box">
	<div class="inner">
		<div class="title-box">
			<h5>새 비밀번호 설정</h5>
		</div>

		<div class="form-box">
			<p class="desc">안녕하세요. <?=$user_name?> 선생님<br/>새로운 비밀번호를 설정해 주세요. 비밀번호는 <strong>비밀번호는 영문 소문자, 대문자, 숫자, 특수문자를 모두 포함한 최소 8자리 이상 설정</strong>이 필요하며, 기존에 사용하신 이력이 있는 비밀번호는 사용할 수 없습니다.</p>

			<div class="form-box">
				<form action="/Common/InitPWIframe" method="post" id="change-form" target="frmTarget">
					<input type="hidden" name="email" value="<?=$user_id->USER_ID;?>">
					<input type="hidden" name="password_current" value="<?=$user_id->USER_PW;?>">
					<input type="hidden" name="backurl" value="/San">
					<div class="input-row-group">
						<div class="input-row">
							<label for="new_password">
								<span class="valid-icon required">[필수]</span>
								새 비밀번호
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
								새 비밀번호 확인
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
					</div>

					<div class="btn-row-group">
						<div class="btn-row">
							<input type="submit" class="btn type-primary size-big" value="비밀번호 변경"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

<script>
	$(function() {
		$("#change-form").validate({
			rules: {
				password: {
					required: true,
					pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#?!@$%^&*-])[A-Za-z\d#?!@$%^&*-]{8,}/
				},
				password_check: {
					required: true,
					equalTo : password
				},
			},
			messages: {
				password : {
					required:"비밀번호를 입력해 주세요.",
					pattern: "비밀번호 양식을 확인해 주세요."
				},
				password_check : {
					required:"비밀번호를 확인해 주세요.",
					equalTo: "비밀번호가 일치하지 않습니다."
				},
			},
			submitHandler: function(form) {
				form.submit();
			}
		});
	});
</script>
