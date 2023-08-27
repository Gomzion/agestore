<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 관리자 팝업 관리
class SanPopup extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','common','sess','nocache','alert'));
		$this->load->model(array('Popup_m'));

		NoCache($this);

		// 세션보유여부체크
		check_sess("/San");
	}

	/// @brief 관리자 팝업 관리
	/// @return none
	public function index()
	{
		// 팝업 가져오기
		$data["popup"] = $this->Popup_m->getPopupAll();
		//echo "<pre>";print_r($data["popup"]);echo "</pre>";

		// 페이지 구분
		$data["page_name"] = "popup_setting";

		$this->load->view('Head_v',$data);
		$this->load->view('SanPopup_v',$data);
		$this->load->view('Tail_v',$data);
	}


	/// @brief 팝업 갱신
	/// @return none
	public function updatePopup()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		if($this->Popup_m->updatePopup($_POST)) {
			alert('저장 되었습니다');
		} else {
			alert('저장에 실패 하였습니다');
		}
	}
}
