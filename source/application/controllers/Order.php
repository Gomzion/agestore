<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 상품 보여주기 관련 콘트롤러
class Order extends CI_Controller {

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('common','sess','nocache','alert','url'));
		$this->load->library(array('pagination','GoodClass'));
		$this->load->model(array('Good_m','Order_m','User_m'));

		// 세션보유여부체크
		check_sess("/");
	}

	/// @brief 상품목록
	/// @return none
	public function index()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		//exit;

		// goods 생성
		$newgood = new GoodClass();

		$newgood->GOODS_ID = $this->input->post('goods_id', TRUE);
		$option = $this->input->post('option', TRUE);
		$mode = $this->input->post('mode', TRUE);

		if($mode == 'cart') {
			if($option) {
				// 가격을 가져온다.
				$priceData = $this->Good_m->getGoodPrice($newgood->GOODS_ID);
				//echo "<pre>";print_r($priceData);echo "</pre>";

				$price = 0;
				$sale = 0;
				foreach ($priceData as $pr) {
					if($pr->GPRICE_AMT == $option) {
						if($this->session->userdata['sales']) {
							$price = $pr->GPRICE_SALE_PC;
							$sale = round(100-($pr->GPRICE_SALE_PC/$pr->GPRICE_PC*100));
						} else {
							$price = $pr->GPRICE_PC;
						}
					}
				}

				if($this->Order_m->insertCart($newgood->GOODS_ID, $option, $price, $sale)) {
					//장바구니 추가 성공
					echo "<script>parent.addCart();</script>";
				} else {
					alert('장바구니 추가에 실패했습니다');
				}
			} else {
				alert('옵션을 선택해 주세요');
			}
		} else if($mode == 'option') {
			// 바로 구매하는 로직으로
			replace("/Order/ready?mode=".$mode."&good_id=".$newgood->GOODS_ID."&option=".$option);
		}
	}

	/// @brief 장바구니
	/// @return none
	public function cart()
	{
		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 카트 내용가져오기
		$data["CART"] = $this->Order_m->getCart();
		//echo "<pre>";print_r($data);echo "</pre>";
		//exit;

		// 카트의 상품id로 옵션 전부 가져오기
		$goods_id_arr = array();
		foreach ($data["CART"] as $ct) $goods_id_arr[] = $ct->GOODS_ID;
		$data["OPT"] = $this->Good_m->getGoodsPrice($goods_id_arr);
		//echo "<pre>";print_r($data["OPT"]);echo "</pre>";

		// 페이지 구분
		$data["page_name"] = "cart";

		$this->load->view('Head_v',$data);
		$this->load->view('Cart_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 구매확인
	/// @return none
	public function ready()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		//echo "<pre>";print_r($_GET);echo "</pre>";

		$mode = $this->input->get('mode', TRUE);

		$goods_arr = array();
		if($mode == "cart") {
			$product = $this->input->post('product', TRUE);
			$gprice_sq = $this->input->post('gprice_sq', TRUE);
			$gprice_gid = $this->input->post('gprice_gid', TRUE);
			$gprice_amt = $this->input->post('gprice_amt', TRUE);
			$gprice_sale_pc = $this->input->post('gprice_sale_pc', TRUE);
			$gprice_pc = $this->input->post('gprice_pc', TRUE);

			//$goods_id_arr = $gprice_gid;
			for($i=0;$i<count($gprice_gid);$i++) {
				if(in_array($gprice_sq[$i], $product)) {
					$tmp = array();
					$tmp[0] = $gprice_gid[$i];
					$tmp[1] = $gprice_amt[$i];
					$tmp[2] = $gprice_sq[$i];

					array_push($goods_arr, $tmp);
				}
			}
		} else {
			$good_id = $this->input->get('good_id', TRUE);
			$option = $this->input->get('option', TRUE);

			$goods_arr[0] = array($good_id, $option, '');
		}
		//echo "<pre>";print_r($goods_arr);echo "</pre>";

		// 카트의 상품id로 옵션 전부 가져오기
		$goods_id_arr = array();
		foreach ($goods_arr as $ct) $goods_id_arr[] = $ct[0];
		$data["OPT"] = $this->Good_m->getGoodsPrice($goods_id_arr);

		// 옵션이 0이면 첫번째행으로 셋팅한다
		$tmp_goods_arr = array();
		foreach ($goods_arr as $ct) {
			if($ct[1] == 0) {
				$ct[1] = $data["OPT"][$ct[0]][0]->GPRICE_AMT;
			}
			array_push($tmp_goods_arr, $ct);
		}
		$goods_arr = $tmp_goods_arr;
		//echo "<pre>";print_r($goods_arr);echo "</pre>";

		// 주문 내용가져오기
		$data["CART"] = $this->Order_m->getReady($goods_arr);
		for($i=0;$i<count($data["CART"]);$i++) $data["CART"][$i]->CART_SQ = $goods_arr[$i][2];
		//echo "<pre>";print_r($data["CART"]);echo "</pre>";

		// 구매자정보 가져오기
		$data["USER"] = $this->User_m->getUserDetail();
		//echo "<pre>";print_r($data["USER"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "order";

		$this->load->view('Head_v',$data);
		$this->load->view('Order_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 결제요청
	/// @return none
	public function pay()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$gprice_sq = $this->input->post('gprice_sq', TRUE);
		$gprice_gid = $this->input->post('gprice_gid', TRUE);
		$gprice_amt = $this->input->post('gprice_amt', TRUE);
		$gprice_sale_pc = $this->input->post('gprice_sale_pc', TRUE);
		$gprice_pc = $this->input->post('gprice_pc', TRUE);

		$dlvy_memo = $this->input->post('dlvy_memo', TRUE);

		$goods_arr = array();
		for($i=0;$i<count($gprice_gid);$i++) {
			if(in_array($gprice_sq[$i], $gprice_sq)) {
				$tmp = new stdClass();
				$tmp->GOODS_ID = $gprice_gid[$i];
				$tmp->GPRICE_AMT = $gprice_amt[$i];
				$tmp->GPRICE_PC = $gprice_pc[$i];
				$tmp->GPRICE_SALE_PC = $gprice_sale_pc[$i];
				$tmp->CART_SQ = $gprice_sq[$i];

				array_push($goods_arr, $tmp);
			}
		}
		//echo "<pre>";print_r($goods_arr);echo "</pre>";

		// 구매자정보 가져오기
		$data["USER"] = $this->User_m->getUserDetail();
		//echo "<pre>";print_r($data["USER"]);echo "</pre>

		// PG모듈 호출 및 완료 페이지를 부른다.
		// 결제 처리를 한다.

		// 결제가 완료됨
		// pay_group , pay 등록
		if($paygroup_id = $this->Order_m->insertPayGroup($dlvy_memo)) {
			if($this->Order_m->insertPay($paygroup_id, $goods_arr)) {

				// 장바구니에서 지운다. 있는것만
				$this->Order_m->delCarts($goods_arr);

				// 완료됨
				replacePost('/Order/end', $_POST);
			} else {
				alert('결제 등록에 실패했습니다.');
			}
		} else {
			alert('결제 등록에 실패했습니다.');
		}
	}

	public function end()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";

		$gprice_sq = $this->input->post('gprice_sq', TRUE);
		$gprice_gid = $this->input->post('gprice_gid', TRUE);
		$gprice_amt = $this->input->post('gprice_amt', TRUE);
		$gprice_sale_pc = $this->input->post('gprice_sale_pc', TRUE);
		$gprice_pc = $this->input->post('gprice_pc', TRUE);

		$goods_arr = array();
		for($i=0;$i<count($gprice_gid);$i++) {
			if(in_array($gprice_sq[$i], $gprice_sq)) {
				$tmp = array();
				$tmp[0] = $gprice_gid[$i];
				$tmp[1] = $gprice_amt[$i];
				$tmp[2] = $gprice_sq[$i];

				array_push($goods_arr, $tmp);
			}
		}
		//echo "<pre>";print_r($goods_arr);echo "</pre>";

		// 카트의 상품id로 옵션 전부 가져오기
		$goods_id_arr = array();
		foreach ($goods_arr as $ct) $goods_id_arr[] = $ct[0];
		$data["OPT"] = $this->Good_m->getGoodsPrice($goods_id_arr);

		// 주문 내용가져오기
		$data["CART"] = $this->Order_m->getReady($goods_arr);
		for($i=0;$i<count($data["CART"]);$i++) $data["CART"][$i]->CART_SQ = $goods_arr[$i][2];
		//echo "<pre>";print_r($data["CART"]);echo "</pre>";

		// 구매자정보 가져오기
		$data["USER"] = $this->User_m->getUserDetail();
		//echo "<pre>";print_r($data["USER"]);echo "</pre>";

		// 장바구니
		$data["CARTAMT"] = $this->Order_m->getCartAmount();

		// 배송
		$data["DLVYAMT"] = $this->Order_m->getDlvyAmount();

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 페이지 구분
		$data["page_name"] = "order";

		$this->load->view('Head_v',$data);
		$this->load->view('OrderEnd_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 장바구니 삭제
	/// @return none
	public function delcart()
	{
		$cart_sq = $this->uri->segment(3);

		if($this->Order_m->delCart($cart_sq)) {
			//장바구니 삭제
			echo "<script>top.location.reload();</script>";
		} else {
			alert('장바구니 삭제에 실패했습니다');
		}
	}

	/// @brief 장바구니 다중 삭제
	/// @return none
	public function delcartmulti()
	{
		$cart_sq_arr = $this->input->post('cart', TRUE);
		echo "<pre>";print_r($cart_sq_arr);echo "</pre>";

		if($this->Order_m->delCartMulti($cart_sq_arr)) {
			//장바구니 삭제
			echo "<script>top.location.reload();</script>";
		} else {
			alert('장바구니 삭제에 실패했습니다');
		}
	}

	/// @brief 장바구니 옵션 변경
	/// @return none
	public function changecart()
	{
		$cart_sq = $this->uri->segment(3);
		$cart_op = $this->uri->segment(4);

		if($this->Order_m->updateCart($cart_sq, $cart_op)) {
			//echo "<script>parent.cart_update();</script>";
		} else {
			alert('장바구니 변경에 실패했습니다');
		}
	}
}
