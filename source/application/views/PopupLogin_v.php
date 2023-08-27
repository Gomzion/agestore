<div class="notice-box ck-content">
	<?php echo $popup->POPUP_TX;?>
</div>

<div class="btn-box">
	<button type="button" class="today-close-btn">1일 동안 보지 않음</button>
	<button type="button" class="close-btn">닫기</button>
</div>

<script>
	$("#<?=$_GET["modal"]?>").bind("modalShow", function(e, content) {
		content.find(".today-close-btn").click(function() {
			setCookie("login-popup", "1", 1);

			modalHide(content);
		});
	});
</script>
