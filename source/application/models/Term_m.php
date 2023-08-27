<?php

/// @details 약관 관련 모델
class Term_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief 약관 두개 모두 가져오기
	/// @details 적용일이 오늘 이후인 가장 최근 약관2가지 가져오기, 단 사용 가능한 것만
	/// @return result query
	function selectPossibleTermAll()
	{
		$data = array();

		$privacy = $this->getTermTopOne('1');
		array_push($data, $privacy);

		$service = $this->getTermTopOne('2');
		array_push($data, $service);

		return $data;
	}

	function getTermTopOne($t_fl)
	{
		$this->db->from('TBL_TERMS');
		$this->db->where('TERMS_FL',$t_fl);
		$this->db->where('TERMS_USE_FL','Y');
		$this->db->order_by('TERMS_APY_DA','DESC');
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief 약관 전체 가져오기
	/// @return 약관 내용
	function getTermAll() {
		$this->db->from('TBL_TERMS');
		$this->db->order_by('TERMS_REG_DT','desc');
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	/// @brief 약관 가져오기
	/// @return 약관 내용
	function getTerm($t_sq)
	{
		$this->db->from('TBL_TERMS');
		$this->db->where('TERMS_SQ',$t_sq);
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief 약관 갱신
	/// @return boolean
	function updateTerm($data)
	{
		$terms_sq = $data["TERMS_SQ"];
		unset($data["TERMS_SQ"]);

		$this->db->where('TERMS_SQ', $terms_sq);
		if ($this->db->update('TBL_TERMS', $data)) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 약관 등록
	/// @return boolean
	function insertTerm($data)
	{
		unset($data["TERMS_SQ"]);
		$data["TERMS_REG_DT"] = date('Y-m-d H:i:s');

		if ($this->db->insert('TBL_TERMS', $data)) {
			//echo $this->db->last_query();
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/// @brief 약관 삭제
	/// @return boolean
	function delTerm($t_sq)
	{
		// 1,2는 삭제 못함
		if($t_sq < 3) return false;

		$this->db->where('TERMS_SQ', $t_sq);
		if ($this->db->delete('TBL_TERMS')) {
			return true;
		} else {
			return false;
		}
	}
}
