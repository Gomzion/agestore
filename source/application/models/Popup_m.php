<?php

/// @details 약관 관련 모델
class Popup_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief 팝업 전체 가져오기
	/// @return 팝업 내용
	function getPopupAll() {
		$this->db->from('TBL_POPUP');
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			$data = array();
			foreach ($result->result() as $row) {
				if (!isset($data[$row->POPUP_FL])) {
					$data[$row->POPUP_FL] = new stdClass();
				}
				$data[$row->POPUP_FL]->POPUP_TX = $row->POPUP_TX;
				$data[$row->POPUP_FL]->POPUP_USE_FL = $row->POPUP_USE_FL;
			}
			return $data;
		} else {
			return false;
		}
	}

	/// @brief 팝업 가져오기
	/// @return 팝업 내용
	function getPopup($mode) {
		$this->db->from('TBL_POPUP');
		$this->db->where('POPUP_FL', $mode);
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief 팝업 갱신 -- 한방에
	/// @return boolean
	function updatePopup($post)
	{
		$dataL = array(
			'POPUP_TX' => $post['login_popup_content'],
			'POPUP_USE_FL' => $post['login_popup']
		);
		$dataM = array(
			'POPUP_TX' => $post['main_popup_content'],
			'POPUP_USE_FL' => $post['main_popup']
		);

		$this->db->where('POPUP_FL', 'L');
		$this->db->update('TBL_POPUP', $dataL);

		$this->db->where('POPUP_FL', 'M');
		$this->db->update('TBL_POPUP', $dataM);

		return true;
	}
}
