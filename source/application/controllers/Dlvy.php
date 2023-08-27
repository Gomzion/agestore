<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 상품 보여주기 관련 콘트롤러
class Dlvy extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('common', 'sess', 'nocache', 'alert'));
		$this->load->library(array('pagination','GoodClass'));
		$this->load->model(array('Good_m', 'Order_m', 'Dlvy_m'));

		// 세션보유여부체크
		check_sess("/");
	}

	/// @brief 상품목록
	/// @return none
	public function index()
	{
		$data["range"] = $this->input->get('range');
		if(!$data["range"]) $data["range"] = 6;
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;

		// pagenation config
		$config['base_url'] = '/Dlvy?range='.$data["range"].'&';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 5;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 주문정보 가져오기
		$data["Dlvy"] = $this->Dlvy_m->getDlvyList(0, $data["range"], $config, $page);
		//echo "<pre>";print_r($data["Dlvy"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "dlvy";

		$this->load->view('Head_v',$data);
		$this->load->view('Dlvy_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 주문 취소
	/// @return none
	public function Cancel()
	{
		$pg_sq = $this->uri->segment(3);

		// 주문정보 가져오기
		$data["Dlvy"] = $this->Dlvy_m->getDlvyList($pg_sq);
		//echo "<pre>";print_r($data["Dlvy"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "clame";

		$this->load->view('Head_v',$data);
		$this->load->view('OrderCancel_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 취소 진행
	/// @return none
	public function Canceling()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$pg_sq = $this->input->post('pg_sq', TRUE);
		$pcnt = $this->input->post('pcnt', TRUE);
		$product = $this->input->post('product', TRUE);

		// 새로운 paygp를 생성해. 기존값을 복사하는 형태로
		if($paygroup_id = $this->Order_m->copyPayGroup($pg_sq, ST_CANCEL)) {
			if($affected_pay = $this->Order_m->updatePay($paygroup_id, $product)) {
				// 모든 pay가 다 변경인거면, paygp를 삭제한다.
				if($pcnt == count($product)) {
					$this->Order_m->delPayGroup($pg_sq);
				}

				// 완료됨
				replacePost('/Dlvy/CancelEnd', array('pg_sq' => $paygroup_id));
			} else {
				alert('주문 취소를 실패했습니다.');
			}
		} else {
			alert('주문 취소를 실패했습니다.');
		}
	}

	/// @brief 취소 완료
	/// @return none
	public function CancelEnd()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$pg_sq = $this->input->post('pg_sq', TRUE);

		// 주문정보 가져오기
		$data["Dlvy"] = $this->Dlvy_m->getDlvyList($pg_sq);
		//echo "<pre>";print_r($data["Dlvy"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "clame";

		$this->load->view('Head_v',$data);
		$this->load->view('OrderCancelEnd_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 교환,반품
	/// @return none
	public function Clame()
	{
		$pg_sq = $this->uri->segment(3);

		// 주문정보 가져오기
		$data["Dlvy"] = $this->Dlvy_m->getDlvyList($pg_sq);
		//echo "<pre>";print_r($data["Dlvy"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "clame";

		$this->load->view('Head_v',$data);
		$this->load->view('OrderClame_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 교환,반품 진행
	/// @return none
	public function Claming()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$pg_sq = $this->input->post('pg_sq', TRUE);
		$pcnt = $this->input->post('pcnt', TRUE);
		$product = $this->input->post('product', TRUE);
		$mode = $this->input->post('mode', TRUE);

		$st = ST_EXCHANGE;
		if($mode == 'refund') $st = ST_CLAME;

		// 새로운 paygp를 생성해. 기존값을 복사하는 형태로
		if($paygroup_id = $this->Order_m->copyPayGroup($pg_sq, $st)) {
			if($affected_pay = $this->Order_m->updatePay($paygroup_id, $product)) {
				// 모든 pay가 다 변경인거면, paygp를 삭제한다.
				if($pcnt == count($product)) {
					$this->Order_m->delPayGroup($pg_sq);
				}

				// 완료됨
				replacePost('/Dlvy', array('pg_sq' => $paygroup_id));
			} else {
				alert('교환,반품 신청을 실패했습니다.');
			}
		} else {
			alert('교환,반품 신청을 실패했습니다.');
		}
	}
}
