<?php

/// @details 유저테이블 클래스
class UserClass {
	// Properties
	protected $CI;

	// 클래스 변수
	public $USER_ID = "";
	public $USER_PW = "";
	public $USER_LV = "";
	public $USER_NM = "";
	public $USER_HPN_NO = "";
	public $USER_HPN_FL = "N";
	public $USER_AFFIL_NM = "";
	public $USER_AFFIL_NO = "";
	public $HOSPITAL_CD = "";
	public $HOSPITAL_SALES_CD = "";
	public $OPD_CD = "";
	public $USER_SIGN_IL = null;
	public $USER_TSV_DT = "";
	public $USER_TPP_DT = "";
	public $USER_LAST_DT = "";
	public $USER_REG_DT = "";

	/// @brief Constructor
	public function __construct() {
		// Code to run when the class is instantiated
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('alert');
		$this->CI->load->library('encryption');

		// 날짜 생성일 기준 초기화
		$date = date('Y-m-d H:i:s');
		$this->USER_TSV_DT = $this->USER_TPP_DT = $this->USER_LAST_DT = $this->USER_REG_DT = $date;
	}

	/// @brief Set Level
	/// @return none
	public function setLevel($LV) {
		// 등급: 관리자1, 영업사원5, 회원9
		$this->USER_LV = $LV;
	}
}
?>
