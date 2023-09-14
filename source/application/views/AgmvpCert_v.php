<html>
<head>
	<title><</title>
</head>
<body>

<form name="form1" action="/Common/okcert2" method="post">
	<input type="hidden" name="CP_CD" value="V63330000000">
	<input type="hidden" name="SITE_NAME" value="AG_eStore">
	<input type="hidden" name="RETURN_MSG" value="agmvp:<?=$umtp_id?>">
</form>

<!-- 휴대폰 본인확인 팝업 처리결과 정보 = phone_popup3 에서 값 입력 -->
<form name="kcbResultForm" method="post" target="_top">
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

	window.onload = function() {
		jsSubmit();
	}
</script>

</body>
</html>
