<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 사용자 로그인 관련 콘트롤러
class Login extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','common', 'sess', 'nocache','alert'));
		$this->load->library(array('form_validation','UserClass'));
		$this->load->model(array('Term_m','User_m','Popup_m'));
	}

	/// @brief 로그인 화면
	/// @return none
	public function index()
	{
		// 팝업 가져오기
		$data["popup"] = $this->Popup_m->getPopup('L');

		// 페이지 구분
		$data["page_name"] = "login";

		$this->load->view('Head_v',$data);
		$this->load->view('Login_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 회원가입 - 약관동의
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
		$this->load->view('JoinTerm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 입력폼
	/// @return none
	public function join()
	{
		// 동의 체크 - 안했으면, 로그인으로 돌아감
		if($this->input->post('agree_service', TRUE) != "on" || $this->input->post('agree_privacy', TRUE) != "on")
		{
			alertUrl('약관 동의가 되지 않았습니다. 로그인 화면으로 이동합니다','/');
		}

		// 병원정보
		$data["hospital_info"] = $this->User_m->getHospitalAddr();
		//print_r($data["hospital_info"]);

		// 시도 정보
		$data["hospital_sdo"] = array();
		foreach ($data["hospital_info"] as $sdoNm => $sdoData) {
			$data["hospital_sdo"][] = $sdoNm;
		}

		// 진료과 정보
		$data["opd"] = $this->User_m->getOPD();

		// 페이지 구분
		$data["page_name"] = "join";

		$this->load->view('Head_v',$data);
		$this->load->view('JoinForm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 회원가입 - 디비입력 처리
	/// @return none
	public function joininput()
	{
		/*echo "<pre>";print_r($_POST);echo "</pre>";
		$files = $_FILES['business_license'];
		echo $files['name']."<br>";*/
		//exit;

		// user 생성
		//** 관리자 회원가입은 관리자만 생성한다고 함 */
		$newuser = new UserClass();
		$newuser->setLevel(9);

		// 유효성 검사 및 XSS 처리
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('business_number', 'business_number', 'trim|required|xss_clean');

		// 유효성 검사가 성공이면
		if ( $this->form_validation->run() === FALSE ) {
			// 실패
			alert('잘못된 입력입니다. 다시 입력해 주세요');
		}

		// 데이터 넣기
		$newuser->USER_ID = $this->input->post('email', TRUE);
		$newuser->USER_NM = $this->input->post('name', TRUE);
		$newuser->HOSPITAL_CD = $this->input->post('hospital', TRUE);
		$newuser->OPD_CD = $this->input->post('department', TRUE);
		$newuser->USER_PW = $this->input->post('password', TRUE);
		$newuser->USER_HPN_NO = $this->input->post('phone', TRUE);
		$newuser->USER_SIGN_IL = $this->input->post('signature');
		$newuser->HOSPITAL_SALES_CD = $this->input->post('sales_info', TRUE);
		$business_number = $this->input->post('business_number', TRUE);

		// 사업자번호 + 영업사원번호로 조회된 기관이 있는지
		$_hospital = $this->User_m->getHospitalFromCorp($business_number, $newuser->HOSPITAL_SALES_CD);
		if(!$_hospital) {
			alert('영업사원코드가 잘못되었거나, 없는 코드입니다');
			exit;
		}

		// 병원 데이터 넣기
		//$corp_sales_name = $this->input->post('sales_name', TRUE);
		$fileData = file_get_contents($_FILES['business_license']['tmp_name']);
		$base64fileData = base64_encode($fileData);
		$fileName = $_FILES['business_license']['name'];

		// 사업자등록증 업데이트
		$this->User_m->updateHospitalFile($newuser->HOSPITAL_CD, $newuser->HOSPITAL_SALES_CD, $fileName, $base64fileData);

		// 회원db에 입력 -- 비밀번호는 암호화
		if($this->User_m->insertUserNew($newuser)) {
			// 성공
			replacePost("/Login/joinend", array("name"=>$newuser->USER_NM));
		} else {
			alert("회원가입 처리가 되지 않았습니다. 다시 진행 해주세요.");
		}
	}

	/// @brief 회원가입 - 완료
	/// @return none
	public function joinend()
	{
		$data["name"] = $this->input->post('name');

		// 페이지 구분
		$data["page_name"] = "join";

		$this->load->view('Head_v',$data);
		$this->load->view('JoinEnd_v',$data);
		$this->load->view('Tail_v',$data);
	}

	public function orch()
	{
		$_state = $_GET["state"];
		$_region = $_GET["region"];
		$_keyword = $_GET["keyword"];

		$data = $this->User_m->getHospitalSearch($_state,$_region,$_keyword);

		$result = array(
			"result" => "ok",
			"list" => array()
		);

		foreach ($data as $val) {
			array_push($result["list"], array("code" => "$val->HOSPITAL_CD","value" => "$val->HOSPITAL_NM","corp_cd" => "$val->HOSPITAL_CORP_CD"));
		}

		echo json_encode($result);
	}
}
