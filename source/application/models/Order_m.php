<?php

/// @details 주문 관련 모델
class Order_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief 장바구니 추가
	/// @details none
	/// @return boolean
	function insertCart($good_id, $amt, $pay, $sale)
	{
		// user_id
		$user_id = $this->session->userdata['id'];

		// 추가
		$data = array(
			'USER_ID' => $user_id,
			'GPRICE_AMT' => $amt,
			'GOODS_ID' => $good_id,
			'PAY_PAID' => $pay,
			'PAY_SALE' => $sale,
			'CART_REG_DT' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('TBL_CART', $data)) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 장바구니 전체 가져오기
	/// @details none
	/// @return query result
	function getCart()
	{
		// user_id
		$user_id = $this->session->userdata['id'];

		$this->db->select('c.CART_SQ, c.USER_ID, c.GPRICE_AMT, c.GOODS_ID, c.CART_REG_DT,
                  g.GOODS_NM, gp.GPHOTO_FL, gp.GPHOTO_SQ, gp.GPHOTO_NM, gp.GPHOTO_ORD,
                  p.GPRICE_PC, p.GPRICE_SALE_PC');
		$this->db->from('TBL_CART c');
		$this->db->join('TBL_GOODS g', 'c.GOODS_ID = g.GOODS_ID');
		$this->db->join('TBL_GOODS_PHOTO gp', 'c.GOODS_ID = gp.GOODS_ID');
		$this->db->join('TBL_GOODS_PRICE p', 'c.GOODS_ID = p.GOODS_ID AND p.GPRICE_AMT = c.GPRICE_AMT');
		$this->db->where('gp.GPHOTO_FL', 'Y');
		$this->db->where('c.USER_ID', $user_id);
		$this->db->order_by('c.CART_SQ','asc');
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}

	function getReady($gid_arr)
	{
		$query = null;

		foreach ($gid_arr as $condition) {
			$subquery = $this->db->select('G.GOODS_ID, G.GOODS_NM, GP.GPHOTO_NM, P.GPRICE_AMT, P.GPRICE_PC, P.GPRICE_SALE_PC')
				->from('TBL_GOODS G')
				->join('TBL_GOODS_PHOTO GP', 'G.GOODS_ID = GP.GOODS_ID AND GP.GPHOTO_FL = \'Y\'')
				->join('TBL_GOODS_PRICE P', 'G.GOODS_ID = P.GOODS_ID')
				->where('G.GOODS_ID',$condition[0])
				->where('P.GPRICE_AMT',$condition[1])
				->get_compiled_select();

			if ($query === null) {
				$query = $subquery;
			} else {
				$query .= " UNION ALL " . $subquery;
			}
		}
		$result = $this->db->query($query);
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}

	/// @brief 장바구니 수량 가져오기
	/// @details none
	/// @return query result
	function getCartAmount()
	{
		// user_id
		$user_id = $this->session->userdata['id'];

		$this->db->from('TBL_CART');
		$this->db->where('USER_ID',$user_id);
		$result = $this->db->get();
		//echo $this->db->last_query();

		$cnt = $result->num_rows();
		if($cnt > 9) $cnt="9+";

		return $cnt;
	}

	/// @brief 배송정보 가져오기
	/// @details none
	/// @return query result
	function getDlvyAmount()
	{
		// user_id
		$user_id = $this->session->userdata['id'];

		$this->db->select('PG.USER_ID, PG.PAYGP_SQ, PG.PAYGP_ST, PG.PAYGP_REQ_TX, PG.PAYGP_DELI_CD, PG.PAYGP_PAID_CD, PG.PAYGP_PG_CD, PG.PAYGP_PG_MSG, PG.PAYGP_STSRT_DT, PG.PAYGP_STEND_DT, PG.PAYGP_REG_DT, P.GPRICE_AMT, P.GOODS_ID, P.PAY_PAID');
		$this->db->from('TBL_PAY_GROUP PG');
		$this->db->join('TBL_PAY P', 'PG.PAYGP_SQ = P.PAYGP_SQ');
		$this->db->where('PG.USER_ID', $user_id);
		$this->db->where_in('PG.PAYGP_ST', array(ST_READY, ST_DLVY_ING));

		$result = $this->db->get();
		//echo $this->db->last_query();

		$cnt = $result->num_rows();
		if($cnt > 9) $cnt="9+";

		return $cnt;
	}

	/// @brief 장바구니 삭제
	/// @details none
	/// @return boolean
	function delCart($sq)
	{
		$this->db->where('CART_SQ', $sq);
		if ($this->db->delete('TBL_CART')) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 장바구니(배열) 삭제
	/// @details none
	/// @return boolean
	function delCarts($data)
	{
		$sq_array = array();
		foreach ($data as $dd) {
			$sq_array[] = $dd->CART_SQ;
		}

		$this->db->where_in('CART_SQ', $sq_array);
		if ($this->db->delete('TBL_CART')) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 장바구니(SQ) 삭제
	/// @details none
	/// @return boolean
	function delCartMulti($sq_array)
	{
		$this->db->where_in('CART_SQ', $sq_array);
		if ($this->db->delete('TBL_CART')) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 장바구니 변경
	/// @details none
	/// @return boolean
	function updateCart($cart_sq, $cart_op)
	{
		$this->db->where('CART_SQ', $cart_sq);
		if ($this->db->update('TBL_CART', array('GPRICE_AMT'=>$cart_op))) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief paygroup 입력
	/// @details none
	/// @return paygroup_id
	function insertPayGroup($memo)
	{
		// user_id
		$user_id = $this->session->userdata['id'];

		// 추가
		$data = array(
			'USER_ID' => $user_id,
			'PAYGP_ST' => '10',
			'PAYGP_REQ_TX' => $memo,
			'PAYGP_REG_DT' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('TBL_PAY_GROUP', $data)) {
			//echo $this->db->last_query();
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/// @brief pay 입력
	/// @details none
	/// @return boolean
	function insertPay($paygroup_id, $goods_arr)
	{
		// user_id
		$sess_sales = $this->session->userdata['sales'];

		// 추가
		// Iterate over the array and insert values
		foreach ($goods_arr as $item) {
			$goods_id = $item->GOODS_ID;
			$gprice_amt = $item->GPRICE_AMT;
			if($sess_sales) {
				$gprice = $item->GPRICE_SALE_PC;
				$gsale = round(100-($item->GPRICE_SALE_PC/$item->GPRICE_PC*100));
			} else {
				$gprice = $item->GPRICE_PC;
				$gsale = 0;
			}

			$data = array(
				'PAYGP_SQ' => $paygroup_id,
				'GPRICE_AMT' => $gprice_amt,
				'GOODS_ID' => $goods_id,
				'PAY_PAID' => $gprice,
				'PAY_SALE' => $gsale
			);

			$this->db->insert('TBL_PAY', $data);
		}

		return true;
	}

	/// @brief paygroup 복사
	/// @details none
	/// @return paygroup_id
	function copyPayGroup($pg_sq, $st)
	{
		// 기존의 paygroup를 가져온다.
		$this->db->from('TBL_PAY_GROUP');
		$this->db->where('PAYGP_SQ',$pg_sq);
		$result = $this->db->get();
		$ori_paygp = $result->row();

		// user_id
		$user_id = $this->session->userdata['id'];

		// 추가
		$data = array(
			'USER_ID' => $user_id,
			'PAYGP_ST' => $st,
			'PAYGP_REQ_TX' => $ori_paygp->PAYGP_REQ_TX,
			'PAYGP_DELI_CD' => $ori_paygp->PAYGP_DELI_CD,
			'PAYGP_PAID_CD' => $ori_paygp->PAYGP_PAID_CD,
			'PAYGP_PG_CD' => $ori_paygp->PAYGP_PG_CD,
			'PAYGP_PG_MSG' => $ori_paygp->PAYGP_PG_MSG,
			'PAYGP_STSRT_DT' => $ori_paygp->PAYGP_STSRT_DT,
			'PAYGP_STEND_DT' => $ori_paygp->PAYGP_STEND_DT,
			'PAYGP_REG_DT' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('TBL_PAY_GROUP', $data)) {
			//echo $this->db->last_query();
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/// @brief pay 갈아타기
	/// @details none
	/// @return boolean
	function updatePay($paygroup_id, $pay_arr)
	{
		$data = array(
			'PAYGP_SQ' => $paygroup_id
		);

		$this->db->where_in('PAY_SQ', $pay_arr);
		$this->db->update('TBL_PAY', $data);

		return $this->db->affected_rows();
	}

	/// @brief pay그룹 삭제
	/// @details none
	/// @return boolean
	function delPayGroup($pg_sq)
	{
		$this->db->where('PAYGP_SQ', $pg_sq);
		if ($this->db->delete('TBL_PAY_GROUP')) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 유저들의 결제내역
	/// @details none
	/// @return 결제내역
	function getPayOfUsers($id_arr, $_range=6, $config=null, $page=1)
	{
		if(count($id_arr) == 0) {
			$config['total_rows'] = 0;
			$config['cur_page'] = 0;
			$this->pagination->initialize($config);

			return false;
		}

		$this->db->select('P.*, G.PAYGP_ST, G.PAYGP_REQ_TX, G.PAYGP_DELI_CD, G.PAYGP_PAID_CD, G.PAYGP_PG_CD, G.PAYGP_PG_MSG, G.PAYGP_STSRT_DT, G.PAYGP_STEND_DT, G.PAYGP_REG_DT, U.USER_NM, G2.GOODS_NM');
		$this->db->from('TBL_PAY P');
		$this->db->join('TBL_PAY_GROUP G', 'G.PAYGP_SQ = P.PAYGP_SQ');
		$this->db->join('TBL_USER U', 'U.USER_ID = G.USER_ID');
		$this->db->join('TBL_GOODS G2', 'G2.GOODS_ID = P.GOODS_ID');
		$this->db->where_in('G.USER_ID', $id_arr);
		if ($_range <= 12) {
			$this->db->where('G.PAYGP_REG_DT >= DATEADD(MONTH, -' . $_range . ', GETDATE())');
		} else if ($_range >= 2023) {
			$this->db->where('YEAR(G.PAYGP_REG_DT)', $_range);
		}
		$this->db->order_by('G.PAYGP_REG_DT', 'DESC');

		// Get the total count of records
		$total_rows = $this->db->count_all_results('', false);

		// Initialize the pagination library
		$config['total_rows'] = $total_rows;
		$config['cur_page'] = $page;
		$this->pagination->initialize($config);

		// Set the pagination limit and offset
		$limit = $config['per_page'];
		$offset = ($page - 1) * $limit;
		$this->db->limit($limit, $offset);

		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	/// @brief 상품의 전체 구매액
	/// @details none
	/// @return 구매액수
	function getAllPaidOfGood($g_id)
	{
		$this->db->select_sum('PAY_PAID');
		$this->db->from('TBL_PAY');
		$this->db->where('GOODS_ID',$g_id);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row()->PAY_PAID;
		} else {
			return 0;
		}
	}

	/// @brief 상품별 결제내역
	/// @details none
	/// @return 결제내역
	function getPayOfGood($g_id, $st=0, $sw="", $_range=6, $config=null, $page=1)
	{
		$this->db->select('P.GPRICE_AMT, P.PAY_PAID, P.PAY_SALE, G.PAYGP_ST, G.PAYGP_REG_DT, U.USER_NM, H.HOSPITAL_NM');
		$this->db->from('TBL_PAY P');
		$this->db->join('TBL_PAY_GROUP G', 'P.PAYGP_SQ = G.PAYGP_SQ');
		$this->db->join('TBL_USER U', 'G.USER_ID = U.USER_ID');
		$this->db->join('TBL_HOSPITAL H', 'U.HOSPITAL_CD = H.HOSPITAL_CD');
		$this->db->where('P.GOODS_ID', $g_id);
		if ($st) {
			$this->db->where('G.PAYGP_ST', $st);
		}
		if ($_range <= 12) {
			$this->db->where('G.PAYGP_REG_DT >= DATEADD(MONTH, -' . $_range . ', GETDATE())');
		} else if ($_range >= 2023) {
			$this->db->where('YEAR(G.PAYGP_REG_DT)', $_range);
		}
		if ($sw) {
			// 검색어
			$this->db->group_start();
			$this->db->like('H.HOSPITAL_NM', $sw);
			$this->db->or_like('U.USER_NM', $sw);
			$this->db->group_end();
		}
		$this->db->order_by('G.PAYGP_REG_DT', 'DESC');

		if($config != null) {
			// Get the total count of records
			$total_rows = $this->db->count_all_results('', false);

			// Initialize the pagination library
			$config['total_rows'] = $total_rows;
			$config['cur_page'] = $page;
			$this->pagination->initialize($config);

			// Set the pagination limit and offset
			$limit = $config['per_page'];
			$offset = ($page - 1) * $limit;
			$this->db->limit($limit, $offset);
		}

		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}

	/// @brief 상품별 장바구니 현황
	/// @details none
	/// @return 결제내역
	function getCartList($g_id, $sw="", $_range=6, $config=null, $page=1)
	{
		$this->db->select("C.CART_SQ, C.GPRICE_AMT, C.PAY_PAID, C.PAY_SALE, CONVERT(varchar(16), C.CART_REG_DT, 120) AS CART_REG_DATETIME, U.USER_NM, H.HOSPITAL_NM");
		$this->db->from('TBL_CART C');
		$this->db->join('TBL_USER U', 'C.USER_ID = U.USER_ID');
		$this->db->join('TBL_HOSPITAL H', 'U.HOSPITAL_CD = H.HOSPITAL_CD');
		$this->db->where('C.GOODS_ID', $g_id);
		if ($_range <= 12) {
			$this->db->where('C.CART_REG_DT >= DATEADD(MONTH, -' . $_range . ', GETDATE())');
		} else if ($_range >= 2023) {
			$this->db->where('YEAR(C.CART_REG_DT)', $_range);
		}
		if ($sw) {
			// 검색어
			$this->db->group_start();
			$this->db->like('H.HOSPITAL_NM', $sw);
			$this->db->or_like('U.USER_NM', $sw);
			$this->db->group_end();
		}
		$this->db->order_by('C.CART_REG_DT', 'DESC');

		if($config != null) {
			// Get the total count of records
			$total_rows = $this->db->count_all_results('', false);

			// Initialize the pagination library
			$config['total_rows'] = $total_rows;
			$config['cur_page'] = $page;
			$this->pagination->initialize($config);

			// Set the pagination limit and offset
			$limit = $config['per_page'];
			$offset = ($page - 1) * $limit;
			$this->db->limit($limit, $offset);
		}

		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}
}
