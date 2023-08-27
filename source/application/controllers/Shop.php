<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 상품 보여주기 관련 콘트롤러
class Shop extends CI_Controller {

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('common','sess','nocache'));
		$this->load->library(array('pagination','GoodClass'));
		$this->load->model(array('Good_m','Order_m'));

		// 세션보유여부체크
		check_sess("/");
	}

	/// @brief 상품목록
	/// @return none
	public function index()
	{
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;
		$data["category"] = $this->input->get('category');

		// pagenation config
		$config['base_url'] = '/Shop?category='.$data["category"].'&';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 8;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 상품정보 가져오기
		$data["GOODS"] = $this->Good_m->getGoodsList($data["category"],$config,$page);

		// 페이지 구분
		$data["page_name"] = "product";

		$this->load->view('Head_v',$data);
		$this->load->view('ShopList_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 상품상세
	/// @return none
	public function detail()
	{
		// 상품
		$data["GOODS_ID"] = $this->uri->segment(3);
		$data["category"] = $this->uri->segment(4);

		// 장바구니 수량
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 상품정보
		$data["GOOD"] = $this->Good_m->getGood($data["GOODS_ID"]);

		// 상품 사진
		$data["GOOD_PHOTOS"] = $this->Good_m->getGoodPhoto($data["GOODS_ID"]);

		// 상품 가격
		$data["GOOD_PRICES"] = $this->Good_m->getGoodPrice($data["GOODS_ID"]);

		// 페이지 구분
		$data["page_name"] = "product_detail";

		$this->load->view('Head_v',$data);
		$this->load->view('ShopProductDetail_v',$data);
		$this->load->view('Tail_v',$data);
	}

	public function cart()
	{
		$data["GOODS_ID"] = $this->input->get('good_id');
		$data["GOOD_PRICES"] = $this->Good_m->getGoodPrice($data["GOODS_ID"]);

		$this->load->view('ListCart_v',$data);
	}
}
