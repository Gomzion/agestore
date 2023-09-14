<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 사용자 로그인 관련 콘트롤러
class Agmvp extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security', 'common', 'sess', 'nocache', 'alert'));
		$this->load->library(array('form_validation', 'UserClass'));
		$this->load->model(array('Term_m', 'User_m', 'Popup_m'));
	}

	/// @brief 약관동의
	/// @return none
	public function index()
	{
		$data["userkey"] = $this->input->post('userkey');
		$data["aname"] = $this->input->post('aname');
		$data["aphone"] = $this->input->post('aphone');

		//---------------------- 임시
		$data["userkey"] = "t023842394823942";
		$data["aname"] = "김은규";
		$data["aphone"] = "010-7272-4544";

		// 값이 없다
		if($data["userkey"] == "") {
			alertUrl("필수값이 누락되었습니다", "/");
		}

		// userkey가 있으면, 바로 로그인 처리
		$user = $this->User_m->getUser($data["userkey"]);
		if($data["userkey"] && $user) {
			// 로그인세션 생성
			setLoginSession($user);
			replace("/Shop?category=");
		} else {
			// 없으면, 회원가입
		}

		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms;
		//print_r($data["terms"]);

		// 페이지 구분
		$data["page_name"] = "agmvp_term";

		$this->load->view('Head_v',$data);
		$this->load->view('AgmvpTerm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 입력폼
	/// @return none
	public function join()
	{
		$data["userkey"] = $this->input->post('userkey');
		$data["aname"] = $this->input->post('aname');
		$data["aphone"] = $this->input->post('aphone');

		// 동의 체크 - 안했으면, 로그인으로 돌아감
		if($this->input->post('agree_service', TRUE) != "on" || $this->input->post('agree_privacy', TRUE) != "on")
		{
			alertBack('약관 동의가 되지 않았습니다.');
		}

		// 약관 가져오기
		$_terms = $this->Term_m->selectPossibleTermAll();
		$data["terms"] = $_terms;

		// 페이지 구분
		$data["page_name"] = "agmvp_term";

		$this->load->view('Head_v',$data);
		$this->load->view('AgmvpForm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 회원가입 - 디비입력 처리
	/// @return none
	public function joininput()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		//$files = $_FILES['business_license'];
		//echo $files['name']."<br>";

		$corp_cd = $this->input->post('business_number', TRUE);
		$corp_sales = $this->input->post('sales_info', TRUE);

		// 사업자번호로 조회된 기관이 있는지
		$_hospital = $this->User_m->getHospitalFromCorp($corp_cd);
		if(!$_hospital) {
			alert('사업자 등록번호가 잘못되었거나, 없는 기관입니다');
			exit;
		}

		// 사업자번호 + 영업사원번호로 조회된 기관이 있는지
		$_hospital = $this->User_m->getHospitalFromCorp($corp_cd, $corp_sales);
		if(!$_hospital) {
			alert('영업사원코드가 잘못되었거나, 없는 코드입니다');
			exit;
		}

		// user 생성
		$newuser = new UserClass();
		$newuser->setLevel(9);

		// 데이터 넣기
		$newuser->USER_ID = $this->input->post('userkey', TRUE);
		$newuser->USER_NM = $this->input->post('aname', TRUE);
		$newuser->USER_HPN_NO = $this->input->post('aphone', TRUE);
		$newuser->USER_SIGN_IL = $this->input->post('signature');
		$newuser->HOSPITAL_CD = $_hospital->HOSPITAL_CD;
		$newuser->SALESMAN_CD = $corp_sales;

		// 병원 데이터 넣기
		$fileData = file_get_contents($_FILES['business_license']['tmp_name']);
		$base64fileData = base64_encode($fileData);
		$fileName = $_FILES['business_license']['name'];

		// 임시 데이터 저장
		$data['umtp_id'] = $this->User_m->insertUserTemp($newuser, $fileName, $base64fileData);

		$this->load->view('AgmvpCert_v',$data);
	}

	/// @brief 회원가입 - 디비입력 완료
	/// @return none
	public function joinend()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$seg = $this->input->post('RETURN_MSG');
		$rmsg_arr = null;
		if($seg) $rmsg_arr = explode(":", $seg);

		if($rmsg_arr) {
			// 임시 저장을 가져온다
			$tmp_user = $this->User_m->getUserTemp($rmsg_arr[1]);

			// user 생성
			$newuser = new UserClass();
			$newuser->setLevel(9);

			// 데이터 넣기
			$newuser->USER_ID = $tmp_user->UTMP_ID;
			$newuser->USER_NM = $tmp_user->UTMP_NM;
			$newuser->USER_HPN_NO = 'Y';
			$newuser->USER_HPN_NO = $tmp_user->UTMP_HPN_NO;
			$newuser->USER_SIGN_IL = $tmp_user->UTMP_SIGN_IL;
			$newuser->HOSPITAL_CD = $tmp_user->UTMP_HOSP_CD;
			$newuser->HOSPITAL_SALES_CD = $tmp_user->UTMP_SALES_CD;

			// 사업자등록증 업데이트
			$this->User_m->updateHospitalFile($newuser->HOSPITAL_CD, $newuser->HOSPITAL_SALES_CD, $tmp_user->UTMP_CORP_NM, $tmp_user->UTMP_CORP_IL);

			// 회원db에 입력 -- 비밀번호는 암호화
			if($this->User_m->insertUserNew($newuser)) {
				// 성공

				// 가입 최종 후 agmvp에 회신
				$curl_rst = curl_contents("https://agmvp.co.kr/oauth/agmvp_sso_login.asp", array("userkey"=>$newuser->USER_ID, "regYN"=>"Y"));
				//print_r($curl_rst);
			} else {
				alerturl("회원가입 처리가 되지 않았습니다. 다시 진행 해주세요.", "/Agmvp");
			}

			// 페이지 구분
			$data["page_name"] = "join";

			setLoginSession($newuser);

			$this->load->view('Head_v',$data);
			$this->load->view('AgmvpJoinEnd_v',array("name"=>$newuser->USER_NM));
			$this->load->view('Tail_v',$data);
		} else {
			alerturl("회원가입 처리가 되지 않았습니다. 다시 진행 해주세요.", "/Agmvp");
		}
	}
}
