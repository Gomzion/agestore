<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 관리자 로그인 화면 관련 콘트롤러
class San extends CI_Controller {

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','common','sess','nocache','alert'));
		$this->load->library(array('form_validation','UserClass'));
		$this->load->model(array('Term_m','User_m','Popup_m'));

		NoCache($this);
	}

	/// @brief 관리자 로그인
	/// @return none
	public function index()
	{
		// 팝업 가져오기
		$data["popup"] = $this->Popup_m->getPopup('L');

		// 페이지 구분
		$data["page_name"] = "login";

		$this->load->view('Head_v',$data);
		$this->load->view('SanLogin_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 회원가입 - 약관동의
	/// @return none
	public function jointerm()
	{
		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms;
		//print_r($data["terms"]);

		// 페이지 구분
		$data["page_name"] = "join";

		$this->load->view('Head_v',$data);
		$this->load->view('SanJoinTerm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 회원가입
	/// @return none
	public function join()
	{
		// 동의 체크 - 안했으면, 로그인으로 돌아감
		if(trim($this->input->post('agree_service', TRUE)) != "on" || trim($this->input->post('agree_privacy', TRUE)) != "on")
		{
			alertUrl('약관 동의가 되지 않았습니다. 로그인 화면으로 이동합니다','/san');
		}

		// 페이지 구분
		$data["page_name"] = "join";

		$this->load->view('Head_v',$data);
		$this->load->view('SanJoinForm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 회원가입 - 디비입력 처리
	/// @return none
	public function joininput()
	{
		// user 생성
		//** 관리자 회원가입은 관리자만 생성한다고 함 */
		$newuser = new UserClass();
		$newuser->setLevel(1);

		// 유효성 검사 및 XSS 처리
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('agency', 'agency', 'trim|required|xss_clean');
		$this->form_validation->set_rules('staffid', 'staffid', 'trim|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|xss_clean');

		// 유효성 검사가 성공이면
		if ( $this->form_validation->run() === FALSE ) {
			// 실패
			alertBack('잘못된 입력입니다. 다시 입력해 주세요');
		}

		// 데이터 넣기
		$newuser->USER_ID = $this->input->post('email', TRUE);
		$newuser->USER_NM = $this->input->post('name', TRUE);
		$newuser->USER_AFFIL_NM = $this->input->post('agency', TRUE);
		$newuser->USER_AFFIL_NO = $this->input->post('staffid', TRUE);
		$newuser->USER_PW = $this->input->post('password', TRUE);
		$newuser->USER_HPN_NO = $this->input->post('phone', TRUE);

		// 회원db에 입력 -- 비밀번호는 암호화
		if($this->User_m->insertUserNew($newuser)) {
			// 성공
			replacePost("/San/joinend", array("name"=>$newuser->USER_NM));
		} else {
			alertUrl("회원가입 처리가 되지 않았습니다. 다시 진행 해주세요.", "/san");
		}
	}

	/// @brief 관리자 회원가입 - 완료
	/// @return none
	public function joinend()
	{
		$data["name"] = $this->input->post('name');

		// 페이지 구분
		$data["page_name"] = "join";

		$this->load->view('Head_v',$data);
		$this->load->view('SanJoinEnd_v',$data);
		$this->load->view('Tail_v',$data);
	}
}
