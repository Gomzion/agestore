<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/// @file sess_helper.php
/// @brief 세션 헬퍼
/// @author gomzion
/// @date 2014-02-06

/// @brief		function::check_sess//세션 체크 후 세션변수에 저장
/// @return	    none
/// @author	    gomzion
/// @date 		2014-02-06
function check_sess($url)
{
    $h_this =& get_instance();

    if(@$h_this->session->userdata['logged_in'] != true) {
        echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$h_this->config->item('charset')."\">";
        //alerturl('로그인이 필요합니다.', '/');
		echo "<script type='text/javascript'> alert('로그인이 필요합니다'); top.location.replace('".$url."'); </script>";
    }
}

function setLoginSession($user)
{
	$h_this =& get_instance();

	$sales = 1;
	if($user->USER_LV == LEVEL_USER && $user->HOSPITAL_SALES_CD != "") {
		$sales = 0;
	}

	// 로그인세션 생성
	$newdata = array(
		"id" => $user->USER_ID,
		"name" => $user->USER_NM,
		"level" => $user->USER_LV,
		"mode" => getModes($user->USER_LV),		// user, admin
		"sales" => $sales,
		"logged_in" => true,
	);
	$h_this->session->set_userdata($newdata);
}

function setLogout()
{
	$h_this =& get_instance();

	// Log out
	$h_this->session->unset_userdata('id');
	$h_this->session->unset_userdata('name');
	$h_this->session->unset_userdata('level');
	$h_this->session->unset_userdata('mode');
	$h_this->session->unset_userdata('sales');
	$h_this->session->unset_userdata('logged_in');

	// Destroy the session
	$h_this->session->sess_destroy();
}

?>
