<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 레이어 팝업 관련 콘트롤러
class Modal extends CI_Controller
{
	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('common'));
		$this->load->model(array('Term_m','Popup_m'));
	}

	public function index()
	{
	}

	/// @brief 서비스 이용약관 레이어팝업
	/// @return none
	public function terms_service()
	{
		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms[1];

		$this->load->view('InnerTermService_v', $data);
	}

	/// @brief  개인정보 처리방침 레이어팝업
	/// @return none
	public function terms_privacy()
	{
		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms[0];

		$this->load->view('InnerTermPrivacy_v', $data);
	}

	/// @brief 서비스 이용약관
	/// @return none
	public function termFullService()
	{
		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms[1];

		// 페이지 구분
		$data["page_name"] = "term";

		$this->load->view('Head_v',$data);
		$this->load->view('TermService_v', $data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief  개인정보 처리방침
	/// @return none
	public function termFullPrivacy()
	{
		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms[0];

		// 페이지 구분
		$data["page_name"] = "term";

		$this->load->view('Head_v',$data);
		$this->load->view('TermPrivacy_v', $data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief  팝업-로그인
	/// @return none
	public function PopupLogin()
	{
		// 팝업 가져오기
		$data["popup"] = $this->Popup_m->getPopup('L');

		$this->load->view('PopupLogin_v', $data);
	}
}
