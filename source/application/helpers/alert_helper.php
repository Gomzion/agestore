<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/// @file alert_helper.php
/// @brief 자바스크립트 경고창 헬퍼
/// @date 2014-02-06

/// @brief		function::alert//자바스크립트 alert 출력
/// @param	msg::경고 문구
/// @param	exit::스크립트 종료 플래그(기본 FALSE)
/// @return	none
function alert($msg='에러입니다', $exit=false)
{
    echo "<script type='text/javascript'> alert('".$msg."'); </script>";
    if ($exit) exit;
}

/// @brief		function::alerturl//자바스크립트 alert 출력 후 url 이동
/// @param	msg::경고 문구
/// @param	url::이동할 URL
/// @return	none
function alertUrl($msg='이동합니다', $url='')
{
	echo "<script type='text/javascript'> alert('".$msg."'); top.location.href = '".$url."'; </script>";
    exit;
}

/// @brief		function::alert_close//자바스크립트 alert 출력 후 창 닫기
/// @param	msg::경고 문구
/// @return	none
function alertClose($msg='종료합니다')
{
	echo "<script type='text/javascript'> alert('".$msg."'); window.close(); </script>";
}

/// @brief		function::replace//자바스크립트 replace 실행
/// @param	url::이동할 URL (기본 /)
/// @return	none
function replace($url='/') {
    echo "<script type='text/javascript'>";
    if ($url)
        echo "top.location.replace('".$url."');";
    echo "</script>";
    exit;
}

/// @brief		function::replacePost//post로 값을 보냄
/// @param	url::이동할 URL (기본 /)
/// @return	none
function replacePost($_url = '/', $_data = array()) {
	echo "<form id='myForm' method='post' action='" . $_url . "' target='_top'>";
	foreach ($_data as $name => $value) {
		if (is_array($value)) {
			foreach ($value as $index => $item) {
				echo "<input type='hidden' name='" . htmlspecialchars($name . "[]", ENT_QUOTES) . "' value='" . htmlspecialchars($item, ENT_QUOTES) . "'>";
			}
		} else {
			echo "<input type='hidden' name='" . htmlspecialchars($name, ENT_QUOTES) . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "'>";
		}
	}
	echo "<script>document.getElementById('myForm').submit();</script>";
}

/// @brief		function::alertback//자바스크립트 alert 출력 후 history.back() 이동
/// @param	msg::경고 문구
/// @return	none
function alertBack($msg='돌아갑니다')
{
	echo "<script type='text/javascript'> alert('".$msg."'); top.history.back(); </script>";
    exit;
}

/// @brief		function::alertreload//자바스크립트 alert 출력 후 페이지 갱신
/// @param	msg::경고 문구
/// @return	none
function alertReload($msg='새로고침합니다')
{
	echo "<script type='text/javascript'> alert('".$msg."'); top.location.reload(); </script>";
    exit;
}
/* End of file */
