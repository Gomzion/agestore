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
								<a href="/" class="btn type-black-line size-big">로그인 페이지로 이동</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<form name="form1" action="/Common/okcert2" method="post">
		<input type="hidden" name="CP_CD" value="V63330000000">
		<input type="hidden" name="SITE_NAME" value="AG_eStore">
		<input type="hidden" name="RETURN_MSG" value="">
	</form>

	<!-- 휴대폰 본인확인 팝업 처리결과 정보 = phone_popup3 에서 값 입력 -->
	<form name="kcbResultForm" method="post" >
		<input type="hidden" name="CP_CD" />
		<input type="hidden" name="TX_SEQ_NO" />
		<input type="hidden" name="RSLT_CD" />
		<input type="hidden" name="RSLT_MSG" />

		<input type="hidden" name="RSLT_NAME" />
		<input type="hidden" name="RSLT_BIRTHDAY" />
		<input type="hidden" name="RSLT_SEX_CD" />
		<input type="hidden" name="RSLT_NTV_FRNR_CD" />

		<input type="hidden" name="DI" />
		<input type="hidden" name="CI" />
		<input type="hidden" name="CI2" />
		<input type="hidden" name="CI_UPDATE" />
		<input type="hidden" name="TEL_COM_CD" />
		<input type="hidden" name="TEL_NO" />

		<input type="hidden" name="RETURN_MSG" />
	</form>

	<script>
		function jsSubmit(){
			window.open("", "auth_popup", "width=430,height=640,scrollbars=yes");
			var form1 = document.form1;
			form1.target = "auth_popup";
			form1.submit();
		}
	</script>

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
					$("input[name='RETURN_MSG']").attr('value', "initpw:"+$("#email").val()+":"+$("#name").val());
					jsSubmit();
				}
			});
		});
	</script>
