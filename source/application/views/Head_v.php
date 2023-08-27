<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AG e-Store</title>

	<!-- font -->
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

	<!-- css -->
	<link rel="stylesheet" href="/assets/css/reset.min.css">
	<link rel="stylesheet" href="/assets/css/common.min.css">
	<link rel="stylesheet" href="/assets/css/modal.min.css">
	<link rel="stylesheet" href="/assets/css/responsive.min.css">
	<?php if ($page_name && is_file("./assets/css/".$page_name.".min.css")) { ?>
		<link rel="stylesheet" href="/assets/css/<?=$page_name?>.min.css">
	<?php } ?>

	<!-- js -->
	<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<script src="/assets/js/jquery.validate.js"></script>
	<script src="/assets/js/jquery.validate.additional-method.js"></script>
	<script src="/assets/js/extm.js"></script>

	<!-- swiper js -->
	<link rel="stylesheet" href="/assets/js/swiper/swiper-bundle.min.css"/>
	<script src="/assets/js/swiper/swiper-bundle.min.js"></script>

	<!-- signature -->
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

	<!-- date-picker -->
	<link rel="stylesheet" href="/assets/js/datepicker/datepicker.css">
	<script src="/assets/js/datepicker/datepicker.js"></script>

	<!-- custom js -->
	<script src="/assets/js/ckeditor/ckeditor.js"></script>
	<!--<script src="/assets/js/ckeditor/translation.ko.js"></script>-->
	<script src="/assets/js/cookie.js"></script>
	<script src="/assets/js/common.js"></script>
	<script src="/assets/js/loading.js"></script>
	<script src="/assets/js/dropdown.js"></script>
	<script src="/assets/js/onekey.js"></script>
	<script src="/assets/js/file.js"></script>
	<script src="/assets/js/modal.js"></script>
	<script src="/assets/js/drawer.js"></script>
	<script src="/assets/js/sign-canvas.js"></script>
	<?php if ($page_name && is_file("./assets/js/".$page_name.".js")) { ?>
		<script src="/assets/js/<?=$page_name?>.js"></script>
	<?php } ?>
	<script>
		function addCart() {
			var cartElement = $('#cart');
			var numberElement = cartElement.find('span');
			var cartDrawerElement = $('#cartd');
			var numberDrawerElement = cartDrawerElement.find('span');

			// Check if the <span> element exists
			if (numberElement.length > 0) {
				var currentValue = parseInt(numberElement.text(), 10);
				var newValue = currentValue + 1;
				if(newValue > 9) newValue = '9+';
				numberElement.text(newValue);
				numberDrawerElement.text(newValue);
			} else {
				// Create a new <span> element with the number
				var newNumberElement = $('<span>', { text: '1' });
				var newNumberDrawerElement = $('<span>', { text: '1' });
				cartElement.append(newNumberElement);
				cartDrawerElement.append(newNumberDrawerElement);
			}

			// 창닫기
			modalHide($('.modal'));
		}
	</script>
</head>
<body>

<div id="wrap" class="<?=$page_name?> <?=$this->session->userdata('mode')?>">
	<?php
	if ($page_name == "agmvp_term") {
		include("./components/layouts/header.agmvp.php");
	} else if ($page_name == "join" || $page_name == "term" || $page_name == "find") {
		include("./components/layouts/header.before.php");
	} else if ($page_name != "login") {
		if ($this->session->userdata('mode') === "admin") {
			include("./components/layouts/header.admin.php");
		} else {
			include("./components/layouts/header.user.php");
			include("./components/layouts/drawer.user.php");
		}
	}
	?>
	<div id="container">
