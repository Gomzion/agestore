<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 공통 함수들
class Common extends CI_Controller {

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','common', 'sess', 'nocache','alert'));
		$this->load->library(array('form_validation','UserClass'));
		$this->load->model(array('User_m'));
	}

	public function index()	{	}

	/// @brief 이메일 중복 검사
	/// @return none
	public function checkEmailDuplicate()
	{
		if ($this->User_m->checkUserID($_GET["email"])) {
			echo "false";
		} else {
			echo "true";
		}
	}

	/// @brief 사용자모드로 가기
	/// @return none
	public function goUser()
	{
		// 갈수 있다. 바꿔줘
		$this->session->set_userdata(array('mode' => 'user'));
		replace('/Shop');
	}

	/// @brief 관리자모드로 가기
	/// @return none
	public function goAdmin()
	{
		if($this->checkLvl() == LEVEL_ADMIN) {
			// 갈수 있다. 바꿔줘
			$this->session->set_userdata(array('mode' => 'admin'));
			replace('/SanGoods');
		} else {
			// 못 간다구..
			alerturl('관리자만 접근할 수 있습니다.','/Shop');
		}
	}

	/// @brief 레벨 검사 - 로그인 계정의 실제 레벨
	/// @return none
	public function checkLvl()
	{
		// 세션값
		$_id = $this->session->userdata('id');
		$_user = $this->User_m->getUser($_id);

		return $_user->USER_LV;
	}

	public function dnFile()
	{
		$hID = $this->input->get('id');

		if($hID) {
			$query = $this->db->get_where('TBL_HOSPITAL', array('HOSPITAL_CD' => $hID));
			$file = $query->row();

			// Set appropriate headers for file download
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $file->HOSPITAL_CORP_NM . '"');

			echo base64_decode($file->HOSPITAL_CORP_IL);
		}
	}

	/// @brief 로그인 처리
	/// @return none
	public function login()
	{
		// 172.23.0.1

		// user 생성
		$newuser = new UserClass();

		// 유효성 검사 및 XSS 처리
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');

		// 유효성 검사가 성공이면
		if ( $this->form_validation->run() === FALSE ) {
			// 실패
			alertBack('잘못된 입력입니다. 다시 입력해 주세요');
		}

		// 데이터 넣기
		$newuser->USER_ID = $this->input->post('email', TRUE);
		$newuser->USER_PW = $this->input->post('password', TRUE);

		if(!$_user = $this->User_m->getUserDetail($newuser->USER_ID)) {
			// 실패
			alertBack('이메일 주소 / 비밀번호를 확인해 주세요');
		}

		// 복호화
		$decryptedPass = convertToVarchar($_user->USER_PW);

		// 비밀번호 비교
		if($newuser->USER_PW == $decryptedPass) {
			// 정상로그인
			// 접속 IP 기록을 확인한다
			if($this->User_m->checkLoginIP($newuser->USER_ID)) {
				// 기록이 있다.

				// 로그인세션 생성
				setLoginSession($_user);

				// 비밀번호 틀린 횟수 초기화 -- 1부터
				if($_user->USER_PW_CNT >= 5) {
					alertBack('비밀번호를 5회 연속으로 틀려 비밀번호 초기화가 필요합니다.');
				} else {
					//$this->User_m->updateUserPWCnt($newuser->USER_ID,0);
					$this->User_m->updateUser(array('USER_PW_CNT'=>0));
				}

				if($_user->USER_LV == LEVEL_ADMIN) replace("/SanGoods"); else replace("/Shop?category=");
			} else {
				// 없다.
				//alertBack('접속 기록이 없는 IP에서 접속하셨습니다. 실명인증이 필요합니다');

				// ========= 테스트 코드
				setLoginSession($_user);
				$msg = "접속 기록이 없는 IP에서 접속하셨습니다. 실명인증이 필요합니다";
				if($_user->USER_LV == LEVEL_ADMIN) alertUrl($msg,'/SanGoods'); else alertUrl($msg,'/Shop?category=');
			}
		} else {
			// 횟수 체크 5회 틀리면, 비밀번호 초기화로 이동
			$_passcnt = $_user->USER_PW_CNT + 1;

			if($_passcnt >= 5) {
				alertBack('비밀번호를 5회 연속으로 틀려 비밀번호 초기화가 필요합니다.');
			} else {
				alertBack('비밀번호를 '.$_passcnt.'회 틀리셨습니다. 5회 이상 연속으로 틀릴 경우 비밀번호 초기화가 필요합니다.');

				// 비밀번호 틀린 횟수 갱신
				//$this->User_m->updateUser(array('USER_PW_CNT'=>$_passcnt), $newuser->USER_ID);
			}
		}
	}

	/// @brief 로그아웃 처리
	/// @return none
	public function logout()
	{
		// 레벨 저장
		$_level = $this->session->userdata('level');

		// 세션 삭제
		setLogout();

		if($_level == LEVEL_ADMIN) replace("/San"); else replace("/");
	}

	/// @brief 아이디찾기
	/// @return none
	public function FindID()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$this->load->view('Head_v',$data);
		$this->load->view('JoinFIndID_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 아이디찾기 관리자
	/// @return none
	public function FindIDSan()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$this->load->view('Head_v',$data);
		$this->load->view('SanFindID_V',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 비밀번호 초기화
	/// @return none
	public function InitPW()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$this->load->view('Head_v',$data);
		$this->load->view('JoinInitPW_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 비밀번호 초기화 관리자
	/// @return none
	public function InitPWSan()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$this->load->view('Head_v',$data);
		$this->load->view('SanInitPW_v',$data);
		$this->load->view('Tail_v',$data);
	}

}
