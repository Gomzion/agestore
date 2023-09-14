<?php

/// @details 로그 기록 관련 모델
class Log_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief 실명인증 로그 기록
	/// @details none
	/// @return boolean
	function insertLogOkCert($output)
	{
		// 추가
		$data = array(
			'LGOK_RST_CD' => $output['RSLT_CD'],
			'LGOK_RST_MSG' => $output['RSLT_MSG'],
			'LGOK_TRAN_CD' => $output['TX_SEQ_NO'],
			'LGOK_HPN_NO' => $output['TEL_NO'],
			'LGOK_NM' => $output['RSLT_NAME'],
			'LGOK_BIRTH' => $output['RSLT_BIRTHDAY'],
			'LGOK_SEX' => $output['RSLT_SEX_CD'],
			'LGOK_FRIN' => $output['RSLT_NTV_FRNR_CD'],
			'LGOK_DI' => $output['DI'],
			'LGOK_CI' => $output['CI'],
			'LGOK_RURL' => $output['RETURN_MSG'],
			'LGOK_REG_DT' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('TBL_LOG_OKCERT', $data)) {
			return true;
		} else {
			return false;
		}
	}
}
