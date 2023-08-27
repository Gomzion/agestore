<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/// @file nocache_helper.php
/// @brief 브라우저 캐쉬 막기
/// @author gomzion
/// @date 2014-07-26

/// @brief		function::NoCache
/// 브라우저 캐쉬 저장 안하게 및 UTF8 설정
/// @return	none
function NoCache($view)
{
	$view->output->set_header('Content-type:text/html;charset=UTF-8');
	$view->output->set_header('Cache-Control:no-cache,must-revalidate');
	$view->output->set_header('Pragma: no-cache');
	$view->output->set_header('Expires: content=0');

	//error_reporting(0);
}
?>
