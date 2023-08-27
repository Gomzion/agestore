<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 관리자 약관 관리
class SanTerm extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','common','sess','nocache','alert'));
		$this->load->library(array('UserClass'));
		$this->load->model(array('Term_m'));

		NoCache($this);

		// 세션보유여부체크
		check_sess("/San");
	}

	/// @brief 관리자 약관 관리
	/// @return none
	public function index()
	{
		// 약관 가져오기
		$data["terms"] = $this->Term_m->getTermAll();
		//echo "<pre>";print_r($data["terms"]);echo "</pre>";

		// 페이지 구분
		$data["page_name"] = "term_setting";

		$this->load->view('Head_v',$data);
		$this->load->view('SanTerm_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 이용 약관 내용 json으로 보내기
	/// @return none
	public function getTerm()
	{
		$terms_sq = $this->uri->segment(3);
		$terms = $this->Term_m->getTerm($terms_sq);
		$terms->TERMS_TX = htmlspecialchars($terms->TERMS_TX, ENT_QUOTES, 'UTF-8');
		echo "<pre>";print_r($terms);echo "</pre>";

		if ($terms) {
			//$json_terms = json_encode($terms);
			$json_terms = json_encode($terms, JSON_HEX_TAG);

			echo "<script>parent.getTermComplete('".$json_terms."');</script>";
		}
	}

	/// @brief 이용약관 업데이트
	/// @return none
	public function updateTerm()
	{
		echo "<pre>";print_r($_POST);echo "</pre>";

		$data["TERMS_SQ"] = $this->input->post('term_sq');
		$data["TERMS_FL"] = $this->input->post('category');
		$data["TERMS_TX"] = $this->input->post('term_content');
		$data["TERMS_USE_FL"] = $this->input->post('status');
		$data["TERMS_APY_DA"] = $this->input->post('apply_date');

		$term_sq = $data["TERMS_SQ"];
		if($data["TERMS_SQ"]) {
			// 기존
			$this->Term_m->updateTerm($data);
		} else {
			// 신규
			$term_sq = $this->Term_m->insertTerm($data);
		}

		$term_str = "개인정보 취급방침";
		if($data["TERMS_FL"] == 2) $term_str = "서비스 이용약관";

		$terms = array(
			'TERMS_SQ' => $term_sq,
			'TERMS_FL_NM' => $term_str,
			'TERMS_APY_DA' => $data["TERMS_APY_DA"]
		);
		$json_terms = json_encode($terms, JSON_HEX_TAG);
		echo "<script>parent.getTermUpdate('".$json_terms."');</script>";
	}

	/// @brief 약관 지우기
	/// @return none
	public function delTerm()
	{
		$terms_sq = $this->uri->segment(3);

		if($this->Term_m->delTerm($terms_sq)) {
			echo "<script>parent.getTermDel('YES');</script>";
		} else {
			echo "<script>parent.getTermDel('NO');</script>";
		}
	}
}
