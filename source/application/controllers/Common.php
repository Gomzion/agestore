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
		$this->load->model(array('User_m','Log_m'));
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

		if(!$_user = $this->User_m->getUser($newuser->USER_ID)) {
			// 실패
			alertBack('이메일 주소 / 비밀번호를 확인해 주세요');
		}

		// 복호화
		$decryptedPass = convertToVarchar($_user->USER_PW);

		// 비밀번호 비교
		if($newuser->USER_PW == $decryptedPass) {
			// 정상로그인

			// 접속 IP 기록을 확인한다
			//======================================
			// 현재는 ip 등록을 위한 실명인증 페이지가 없다
			//======================================
			//if($this->User_m->checkLoginIP($newuser->USER_ID)) {
				// 기록이 있다.

				// 비밀번호 틀린 횟수 초기화 -- 1부터
				if($_user->USER_PW_CNT >= 5) {
					alertBack('비밀번호를 5회 연속으로 틀려 비밀번호 초기화가 필요합니다.');
				} else {
					// 로그인세션 생성
					setLoginSession($_user);

					$this->User_m->updateUser(array('USER_PW_CNT'=>0));

					if($_user->USER_LV == LEVEL_ADMIN) replace("/SanGoods"); else replace("/Shop?category=");
				}
			//} else {
				// 없다.
				//alertBack('접속 기록이 없는 IP에서 접속하셨습니다. 실명인증이 필요합니다');
			//}
		} else {
			// 횟수 체크 5회 틀리면, 비밀번호 초기화로 이동
			$_passcnt = $_user->USER_PW_CNT + 1;

			if($_passcnt >= 5) {
				alertBack('비밀번호를 5회 연속으로 틀려 비밀번호 초기화가 필요합니다.');
			} else {
				// 비밀번호 틀린 횟수 갱신
				$this->User_m->updateUser(array('USER_PW_CNT'=>$_passcnt), $newuser->USER_ID);

				alertBack('비밀번호를 '.$_passcnt.'회 틀리셨습니다. 5회 이상 연속으로 틀릴 경우 비밀번호 초기화가 필요합니다.');
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

	/// @brief 아이디찾기_결과
	/// @return none
	public function FindIDEnd()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$data["user_name"] = $this->input->post('RSLT_NAME');
		$data["user_hpnum"] = $this->input->post('TEL_NO');

		// 가입여부 확인
		$data["user_id"] = $this->User_m->getUserHpnum($data["user_hpnum"]);
		//print_r($data);

		$this->load->view('Head_v',$data);
		$this->load->view('JoinFIndIDResult_v',$data);
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

	/// @brief 아이디찾기 관리자_결과
	/// @return none
	public function FindIDSanEnd()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$data["user_name"] = $this->input->post('RSLT_NAME');
		$data["user_hpnum"] = $this->input->post('TEL_NO');

		// 가입여부 확인
		$data["user_id"] = $this->User_m->getUserHpnum($data["user_hpnum"]);
		//print_r($data);

		$this->load->view('Head_v',$data);
		$this->load->view('SanFIndIDResult_v',$data);
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

	/// @brief 비밀번호 초기화 - 실명인증 결과
	/// @return none
	public function InitPWEnd()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$data["user_name"] = $this->input->post('RSLT_NAME');
		$data["user_hpnum"] = $this->input->post('TEL_NO');
		$seg = $this->input->post('RETURN_MSG');
		$rmsg_arr = array();
		if($seg) $rmsg_arr = explode(":", $seg);

		// 가입여부 확인
		$data["user_id"] = $this->User_m->getUserHpnum($data["user_hpnum"]);
		if($data["user_id"] == false) {
			echo("<script>alert('가입된 정보가 없습니다!'); history.back();</script>");
		} else {
			// 이메일/이름 확인
			if($data["user_id"]->USER_ID != $rmsg_arr[1] || $data["user_id"]->USER_NM != $rmsg_arr[2]) {
				echo("<script>alert('가입된 정보가 없습니다!'); history.back();</script>");
			}
		}

		$this->load->view('Head_v',$data);
		$this->load->view('JoinInitPWEnd_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 비밀번호 초기화 iframe
	/// @return none
	public function InitPWIframe()
	{
		$password = $this->input->post('password');
		$password_current = $this->input->post('password_current');
		$email = $this->input->post('email');
		$backurl = $this->input->post('backurl');

		$decryptedPass = convertToVarchar($password_current);

		// 현재 비밀번호
		if($password == $decryptedPass) {
			echo("<script>alert('현재 비밀번호와 동일한 비밀번호는 사용할 수 없습니다!');</script>");
		} else {
			$this->User_m->updatePassword($password, $email);
			$this->User_m->updateUser(array('USER_PW_CNT'=>0), $email);

			echo("<script>alert('비밀번호가 변경되었습니다. 다시 로그인 해 주세요'); top.location.href='".$backurl."';</script>");
		}
		//echo $email."/".$password."/".$decryptedPass;
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

	/// @brief 비밀번호 초기화 관리자 - 실명인증 결과
	/// @return none
	public function InitPWSanEnd()
	{
		// 페이지 구분
		$data["page_name"] = "find";

		$data["user_name"] = $this->input->post('RSLT_NAME');
		$data["user_hpnum"] = $this->input->post('TEL_NO');
		$seg = $this->input->post('RETURN_MSG');
		$rmsg_arr = array();
		if($seg) $rmsg_arr = explode(":", $seg);

		// 가입여부 확인
		$data["user_id"] = $this->User_m->getUserHpnum($data["user_hpnum"]);
		if($data["user_id"] == false) {
			echo("<script>alert('가입된 정보가 없습니다!'); history.back();</script>");
		} else {
			// 이메일/이름 확인
			if($data["user_id"]->USER_ID != $rmsg_arr[1] || $data["user_id"]->USER_NM != $rmsg_arr[2]) {
				echo("<script>alert('가입된 정보가 없습니다!'); history.back();</script>");
			}
		}

		$this->load->view('Head_v',$data);
		$this->load->view('SanInitPWEnd_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 실명인증 단계2
	/// @return none
	public function okcert2()
	{
		/**************************************************************************
		 * okcert3 휴대폰 본인확인 서비스 파라미터
		 **************************************************************************/

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 회원사 사이트명, URL
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$SITE_NAME	=	$_REQUEST["SITE_NAME"];
		$SITE_URL 	=   $_SERVER['HTTP_HOST'];

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' KCB로부터 부여받은 회원사코드(아이디) 설정 (12자리)
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$CP_CD = $_REQUEST["CP_CD"];

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 리턴 URL 설정
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' opener(phone_popup1.php)의 도메일과 일치하도록 설정해야 함.
		//' (http://www.test.co.kr과 http://test.co.kr는 다른 도메인으로 인식하며, http 및 https도 일치해야 함)
		$RETURN_URL = "http://".$_SERVER['HTTP_HOST']."/Common/okcert3";// 인증 완료 후 리턴될 URL (도메인 포함 full path)

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 인증요청사유코드 (가이드 문서 참조)
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$RQST_CAUS_CD ="00";

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 채널 코드 (공백가능. 필요한 회원사에서만 입력)
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//$CHNL_CD	=	$_REQUEST["CHNL_CD"];
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 리턴메시지 (공백가능. returnUrl에서 같이 전달받고자 하는 값이 있다면 설정.)
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$RETURN_MSG = $_REQUEST["RETURN_MSG"];

		// ########################################################################
		// # 타겟 및 인증팝업URL : 운영/테스트 전환시 변경 필요
		// ########################################################################
		$target = "PROD";	// 테스트="TEST", 운영="PROD"
		//$popupUrl = "";	// 테스트 URL
		$popupUrl = "https://safe.ok-name.co.kr/CommonSvl";	// 운영 URL

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 라이센스 파일
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$license = "./assets/".$CP_CD."_IDS_01_".$target."_AES_license.dat";

		/**************************************************************************
		okcert3 request param JSON String
		 **************************************************************************/
		$params  = '{ "CP_CD":"'.$CP_CD.'",';
		$params .= '"RETURN_URL":"'.$RETURN_URL.'",';
		$params .= '"SITE_NAME":"'.$SITE_NAME.'",';
		$params .= '"SITE_URL":"'.$SITE_URL.'",';

		// user param
		//$params .= '"CHNL_CD":"'.$CHNL_CD.'",';
		$params .= '"RETURN_MSG":"'.$RETURN_MSG.'",';

		//' 거래일련번호는 기본적으로 모듈 내에서 자동 채번되고 채번된 값을 리턴해줌.
		//'	회원사가 직접 채번하길 원하는 경우에만 아래 코드를 주석 해제 후 사용.
		//' 각 거래마다 중복 없는 $을 생성하여 입력. 최대길이:20바이트
		//$params .= '"TX_SEQ_NO":"'."123456789012345".'",';

		$params .= '"RQST_CAUS_CD":"'.$RQST_CAUS_CD.'" }';


		$svcName = "IDS_HS_POPUP_START";
		$out = NULL;

		// okcert3 실행
		$ret = okcert3_u($target, $CP_CD, $svcName, $params, $license, $out);	// UTF-8

		/**************************************************************************
		okcert3 응답 정보
		 **************************************************************************/
		$RSLT_CD = "";						// 결과코드
		$RSLT_MSG = "";						// 결과메시지
		$MDL_TKN = "";						// 모듈토큰
		$TX_SEQ_NO = "";					// 거래일련번호

		if ($ret == 0) {// 함수 실행 성공일 경우 변수를 결과에서 얻음
			$output = json_decode($out,true);		// $output = UTF-8

			$RSLT_CD = $output['RSLT_CD'];
			$RSLT_MSG = $output["RSLT_MSG"];

			if(isset($output["TX_SEQ_NO"])) $TX_SEQ_NO = $output["TX_SEQ_NO"]; // 필요 시 거래 일련 번호 에 대하여 DB저장 등의 처리

			if( $RSLT_CD == "B000" ) { // B000 : 정상건
				$MDL_TKN = $output['MDL_TKN'];
			}
		}
		else {
			echo ("<script>alert('Fuction Fail / ret: ".$ret."'); self.close();</script>");
		}

		echo "
		<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title></title>
		<script>
			function request(){
				document.form1.action = '$popupUrl';
				document.form1.method = 'post';
		
				document.form1.submit();
			}
		</script>
		</head>
		
		<body>
		<form name='form1'>
			<!-- 인증 요청 정보 -->
			<!--// 필수 항목 -->
			<input type='hidden' name='tc' value='kcb.oknm.online.safehscert.popup.cmd.P931_CertChoiceCmd'/>  <!-- 변경불가-->
			<input type='hidden' name='cp_cd' value='$CP_CD'>	<!-- 회원사코드 -->
			<input type='hidden' name='mdl_tkn' value='$MDL_TKN'>	<!-- 모듈토큰 -->
			<input type='hidden' name='target_id' value=''>
			<!-- 필수 항목 //-->
		</form>";

		if ($RSLT_CD == "B000") {
			//인증요청
			echo ("<script>request();</script>");
		} else {
			// 실패 로그 기록
			$this->Log_m->insertLogOkCert($output);

			//요청 실패 페이지로 리턴
			echo ("<script>alert('".$RSLT_CD." : ".$RSLT_MSG."'); self.close();</script>");
		}

		echo "</body></html>";
	}

	/// @brief 실명인증 단계3
	/// @return none
	public function okcert3()
	{
		/**************************************************************************
		 * okcert3 본인확인 서비스 파라미터
		 **************************************************************************/
		/* 팝업창 리턴 항목 */
		$MDL_TKN	=	$_GET["mdl_tkn"];			// 모듈토큰

		// ########################################################################
		// # KCB로부터 부여받은 회원사코드(아이디) 설정 (12자리)
		// ########################################################################
		$CP_CD = "V63330000000";				// 회원사코드(아이디)

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 타겟 : 운영/테스트 전환시 변경 필요
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$target = "PROD"; // 테스트="TEST", 운영="PROD"

		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		//' 라이센스 파일
		//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
		$license = "./assets/".$CP_CD."_IDS_01_".$target."_AES_license.dat";


		/**************************************************************************
		okcert3 request param JSON String
		 **************************************************************************/
		$params = '{ "MDL_TKN":"'.$MDL_TKN.'" }';


		$svcName = "IDS_HS_POPUP_RESULT";
		$out = NULL;

		// okcert3 실행
		$ret = okcert3_u($target, $CP_CD, $svcName, $params, $license, $out);  // UTF-8

		/**************************************************************************
		okcert3 응답 정보
		 **************************************************************************/
		$RSLT_CD = "";						// 결과코드
		$RSLT_MSG = "";						// 결과메시지
		$TX_SEQ_NO = "";					// 거래일련번호

		$RSLT_NAME		= "";
		$RSLT_BIRTHDAY 	= "";
		$RSLT_SEX_CD	= "";
		$RSLT_NTV_FRNR_CD="";

		$DI				= "";
		$CI				= "";
		$CI_UPDATE		= "";
		$TEL_COM_CD		= "";
		$TEL_NO			= "";

		$RETURN_MSG 	= "";				// 리턴메시지

		if($ret == 0) {		// 함수 실행 성공일 경우 변수를 결과에서 얻음
			$output = json_decode($out,true);		// $output = UTF-8

			$RSLT_CD	= $output['RSLT_CD'];
			$RSLT_MSG = $output["RSLT_MSG"];

			if(isset($output["TX_SEQ_NO"])) $TX_SEQ_NO = $output["TX_SEQ_NO"]; // 필요 시 거래 일련 번호 에 대하여 DB저장 등의 처리
			if(isset($output["RETURN_MSG"]))  $RETURN_MSG  = $output['RETURN_MSG'];

			if( $RSLT_CD == "B000" ) { // B000 : 정상건
				$RSLT_NAME = $output['RSLT_NAME'];

				$RSLT_BIRTHDAY	= $output['RSLT_BIRTHDAY'];
				$RSLT_SEX_CD	= $output['RSLT_SEX_CD'];
				$RSLT_NTV_FRNR_CD=$output['RSLT_NTV_FRNR_CD'];

				$DI				= $output['DI'];
				$CI 			= $output['CI'];
				$CI_UPDATE		= $output['CI_UPDATE'];
				$TEL_COM_CD		= $output['TEL_COM_CD'];
				$TEL_NO			= $output['TEL_NO'];
			}

			// 로그 기록
			$this->Log_m->insertLogOkCert($output);
		}

		echo "
		<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title></title>
		<script>
			function fncOpenerSubmit() {
				opener.document.kcbResultForm.CP_CD.value    	= '$CP_CD';
				opener.document.kcbResultForm.TX_SEQ_NO.value 	= '$TX_SEQ_NO';
				opener.document.kcbResultForm.RSLT_CD.value		= '$RSLT_CD';
				opener.document.kcbResultForm.RSLT_MSG.value	= '$RSLT_MSG';
				opener.document.kcbResultForm.RETURN_MSG.value	= '$RETURN_MSG';
        ";

		if($ret == 0) {
			echo "
				opener.document.kcbResultForm.RSLT_NAME.value        = '$RSLT_NAME';
				opener.document.kcbResultForm.RSLT_BIRTHDAY.value    = '$RSLT_BIRTHDAY';
				opener.document.kcbResultForm.RSLT_SEX_CD.value      = '$RSLT_SEX_CD';
				opener.document.kcbResultForm.RSLT_NTV_FRNR_CD.value = '$RSLT_NTV_FRNR_CD';
	
				opener.document.kcbResultForm.DI.value          = '$DI';
				opener.document.kcbResultForm.CI.value          = '$CI';
				opener.document.kcbResultForm.CI_UPDATE.value   = '$CI_UPDATE';
				opener.document.kcbResultForm.TEL_COM_CD.value  = '$TEL_COM_CD';
				opener.document.kcbResultForm.TEL_NO.value      = '$TEL_NO';
			";
		}

		/**************************************************************************
		최종 URL 연결, ':' 토큰으로 구분되어 있다.
		 **************************************************************************/
		$token = explode(":", $output['RETURN_MSG']);
		$rurl = "/";
		if($token[0] == "findid") {
			$rurl = "/Common/FindIDEnd";
		} else if($token[0] == "initpw") {
			$rurl = "/Common/InitPWEnd";
		} else if($token[0] == "sanfindid") {
			$rurl = "/Common/FindIDSanEnd";
		} else if($token[0] == "saninitpw") {
			$rurl = "/Common/InitPWSanEnd";
		} else if($token[0] == "agmvp") {
			$rurl = "/Agmvp/joinend";
		}

		echo "
				opener.document.kcbResultForm.action = '$rurl';
		
				opener.document.kcbResultForm.submit();
				self.close();
			}
		</script>
		</head>
		<body>
		";

		if($ret == 0) {
			//인증결과 복호화 성공
			// 인증결과를 확인하여 페이지분기등의 처리를 수행해야한다.
			if ($RSLT_CD == "B000") {
				echo ("<script>alert('본인인증성공'); fncOpenerSubmit();</script>");
			}
			else {
				echo ("<script>alert('본인인증실패 : ".$RSLT_CD." : ".$RSLT_MSG."'); self.close();</script>");
			}
		} else {
			//인증결과 복호화 실패
			echo ("<script>alert('인증결과복호화 실패 : ".$ret."'); self.close(); </script>");
		}

		echo "</body></html>";
	}
}
