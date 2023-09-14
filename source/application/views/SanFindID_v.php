
	<div class="find-box">
		<div class="inner">
			<div class="title-box">
				<h5>아이디 찾기</h5>
			</div>

			<div class="form-box">
				<p class="desc">아이디를 찾으시려면 실명 인증이 필요합니다.</p>

				<div class="btn-row-group">
					<div class="btn-row">
						<button type="button" class="btn type-primary size-big" onClick="jsSubmit();">실명 인증</button>
					</div>

					<div class="btn-row">
						<a href="/San" class="btn type-black-line size-big">로그인 페이지로 이동</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<form name="form1" action="/Common/okcert2" method="post">
		<input type="hidden" name="CP_CD" value="V63330000000">
		<input type="hidden" name="SITE_NAME" value="AG_eStore">
		<input type="hidden" name="RETURN_MSG" value="sanfindid">
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
