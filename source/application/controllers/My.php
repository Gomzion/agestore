<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 상품 보여주기 관련 콘트롤러
class My extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security', 'common', 'sess', 'nocache','alert'));
		$this->load->library(array('UserClass'));
		$this->load->model(array('User_m','Order_m','Good_m'));

		// 세션보유여부체크
		check_sess("/");
	}

	/// @brief 상품목록
	/// @return none
	public function index()
	{
		// 내정보 가져오기
		$data["USER"] = $this->User_m->getUserDetail();
		//echo "<pre>";print_r($data["USER"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "mypage";

		$this->load->view('Head_v',$data);
		$this->load->view('MyPage_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 핸드폰번호 변경
	/// @return none
	public function updateHPNum()
	{
		$hpnum = $this->uri->segment(3);
		//echo $hpnum;

		$this->User_m->updateUser(array('USER_HPN_NO'=>$hpnum));

		echo "<script>parent.HPnumComplete();</script>";
	}

	/// @brief 비밀번호 변경
	/// @return none
	public function ChangePassword()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$current_password = $this->input->post('current_password', TRUE);
		$new_password = $this->input->post('new_password', TRUE);
		$new_password_check = $this->input->post('new_password_check', TRUE);

		// 내정보 가져오기
		$user = $this->User_m->getUserDetail();

		// 복호화
		$decryptedPass = convertToVarchar($user->USER_PW);

		//echo "$current_password, $decryptedPass";

		if($current_password == $decryptedPass) {
			if($current_password == $new_password) {
				alert('동일한 비밀번호로는 변경이 불가능합니다.');
			} else {
				// 변경한다.
				$this->User_m->updatePassword($new_password);

				echo "<script>parent.PasswdComplete('비밀번호가 변경되었습니다.');</script>";
			}
		} else {
			alert('현재 비밀번호가 틀립니다.');
		}
	}

	/// @brief 소속기관 변경창
	/// @return none
	public function ChangeOrgan()
	{
		// 내정보 가져오기
		$data["USER"] = $this->User_m->getUserDetail();

		// 병원정보
		$data["hospital_info"] = $this->User_m->getHospitalAddr();

		// 시도 정보
		$data["hospital_sdo"] = array();
		foreach ($data["hospital_info"] as $sdoNm => $sdoData) {
			$data["hospital_sdo"][] = $sdoNm;
		}

		$data["GOODS_ID"] = $this->input->get('good_id');

		$this->load->view('ModalOrgan_v',$data);
	}

	/// @brief 소속기관 변경 진행
	/// @return none
	public function updateOrgan()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		//$files = $_FILES['business_license'];
		//echo $files['name']."<br>";

		// 데이터 넣기
		$hospital = $this->input->post('hospital', TRUE);

		// hospital 값이 없으면 안바뀐거임
		// 그럼 가져와야해.
		if(!$hospital) {
			$user = $this->User_m->getUserDetail();
			$hospital = $user->HOSPITAL_CD;
		}

		// 병원 데이터 넣기
		$corp_cd = $this->input->post('business_number', TRUE);

		$fileName = "";
		$base64fileData = "";
		if (isset($_FILES['business_license']) && $_FILES['business_license']['error'] === UPLOAD_ERR_OK) {
			// Check for successful file upload and no errors
			$fileData = file_get_contents($_FILES['business_license']['tmp_name']);

			if ($fileData !== false) {
				// File content was successfully read
				$base64fileData = base64_encode($fileData);
				$fileName = $_FILES['business_license']['name'];
			} else {
				// Error reading file content
				echo "Failed to read the file.";
			}
		} else {
			// File upload failed or not found
			echo "File upload failed.";
		}

		$this->User_m->updateHospital($hospital, $corp_cd, $fileName, $base64fileData);

		$this->User_m->updateUser(array('HOSPITAL_CD'=>$hospital));

		// 병원 이름 가져오기
		$hospital_full_name = $this->User_m->getHospitalFullName($hospital);

		echo "<script>parent.OrganComplete('".$hospital_full_name."','".$corp_cd."');</script>";
	}
}
