<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/// @file common_helper.php
/// @brief 공통 함수 모음
/// @author gomzion
/// @date 2023-07-05

/// @brief	한글 요일 보내기
/// @return	요일
function changeWeekNameKR($week) {
	$yoil = array("일","월","화","수","목","금","토");
	return $yoil[$week];
}

/// @brief	나이 계산
/// @return	나이
function get_age($birthday) {
	$today = date('Ymd');
	$birthday = date('Ymd', strtotime($birthday));
	$age = floor(($today - $birthday) / 10000);

	return $age;
}

/// @brief	핸드폰번호 입력 지원
/// @return	none
function phone_format($phone){
	$phone = preg_replace("/[^0-9]/", "", $phone);
	$length = strlen($phone);

	switch($length){
		case 11 :
			return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
			break;
		case 10:
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
			break;
		default :
			return $phone;
			break;
	}
}

/// @brief	모드 가져오기
/// @return	mode name
function getModes($lv) {
	$modes = array(0=>"",1=>"admin",9=>"user");

	return $modes[$lv];
}

/// @brief	날자 한글 변환
/// @return	한글로 변환한 날자
function changeHangulDate($_date){
	$tmp = explode('-', $_date);

	return $tmp[0]."년 ".$tmp[1]."월 ".$tmp[2]."일";
}

/// @brief	암호처리
/// @return	암호처리된 문자열
function convertToVarbinary($value) {
		$CI =& get_instance();
		$CI->load->library('encryption');
		$encryptedValue = $CI->encryption->encrypt($value);
		return $encryptedValue;
		//return hex2bin(bin2hex($encryptedValue));
}

/// @brief	복호처리
/// @return	복호처리된 문자열
function convertToVarchar($value) {
	$CI =& get_instance();
	$CI->load->library('encryption');
	$decryptedValue = $CI->encryption->decrypt($value);
	return $decryptedValue;
	//return hex2bin(bin2hex($encryptedValue));
}

?>
