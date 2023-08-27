<?php

/// @details  상품 처리 관련 모델
class Good_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief	상품 목록 보기
	/// @return query result
	function getGoodsList($gtype="", $config=null, $page=1)
	{
		$this->db->select('G.GOODS_ID, G.GOODS_NM, G.GOODS_SAP_CD, G.GTYPE_CD, G.GOODS_BRIF_TX, G.GOODS_TX, P.GPHOTO_NM, PP.GPRICE_PC, PP.GPRICE_SALE_PC');
		$this->db->from('TBL_GOODS G');
		$this->db->join('(SELECT GOODS_ID, GPHOTO_NM FROM TBL_GOODS_PHOTO WHERE GPHOTO_FL = \'Y\') P', 'G.GOODS_ID = P.GOODS_ID', 'left');
		$this->db->join('(SELECT GOODS_ID, GPRICE_ORD, GPRICE_PC, GPRICE_SALE_PC FROM TBL_GOODS_PRICE WHERE GPRICE_ORD = (SELECT MIN(GPRICE_ORD) FROM TBL_GOODS_PRICE)) PP', 'G.GOODS_ID = PP.GOODS_ID', 'left');
		if($gtype) $this->db->where('G.GTYPE_CD', $gtype);
		$this->db->order_by('G.GOODS_REG_DT', 'desc');

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
			return array();
		}
	}

	/// @brief	상품정보 가져오기
	/// @return query result
	function getGood($gid)
	{
		$this->db->from('TBL_GOODS');
		$this->db->where('GOODS_ID',$gid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return array();
		}
	}

	/// @brief	상품 사진 가져오기
	/// @return query result
	function getGoodPhoto($gid)
	{
		$this->db->from('TBL_GOODS_PHOTO');
		$this->db->where('GOODS_ID',$gid);
		$this->db->order_by('GPHOTO_ORD','asc');
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}

	/// @brief	상품 옵션 가져오기
	/// @return query result
	function getGoodPrice($gid)
	{
		$this->db->from('TBL_GOODS_PRICE');
		$this->db->where('GOODS_ID',$gid);
		$this->db->order_by('GPRICE_ORD','asc');
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}

	/// @brief	상품 여러개 옵션 가져오기
	/// @return query result
	function getGoodsPrice($gid_arr)
	{
		if(!count($gid_arr)) return array();

		$this->db->from('TBL_GOODS_PRICE');
		$this->db->where_in('GOODS_ID',$gid_arr);
		$this->db->order_by('GOODS_ID','asc');
		$this->db->order_by('GPRICE_ORD','asc');
		$result = $this->db->get();
		//echo $this->db->last_query();

		// data sorting
		$data = array();
		foreach ($result->result() as $row) {
			if (!isset($data[$row->GOODS_ID])) {
				$data[$row->GOODS_ID] = array();
			}
			$tmp = new stdClass();
			$tmp->GPRICE_AMT = $row->GPRICE_AMT;
			$tmp->GPRICE_PC = $row->GPRICE_PC;
			$tmp->GPRICE_SALE_PC = $row->GPRICE_SALE_PC;

			array_push($data[$row->GOODS_ID], $tmp);
		}

		if ($result->num_rows() > 0) {
			return $data;
		} else {
			return array();
		}
	}

	/// @brief	상품 구분
	/// @return query result
	function selectGoodType()
	{
		$this->db->from('TBL_GOODS_TYPE');
		$this->db->order_by('GTYPE_ORD','desc');
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	/// @brief	신규 상품 저장
	/// @return boolean
	function insertGoodNew($data)
	{
		// Begin the transaction
		$this->db->trans_start();

		try {
			// Insert into TBL_GOODS
			$data1 = array(
				// Data for table 1
				'GOODS_ID' => $data->GOODS_ID,
				'GOODS_NM' => $data->GOODS_NM,
				'GOODS_SAP_CD' => $data->GOODS_SAP_CD,
				'GTYPE_CD' => $data->GTYPE_CD,
				'GOODS_BRIF_TX' => $data->GOODS_BRIF_TX,
				'GOODS_TX' => $data->GOODS_TX,
				'GOODS_USE_FL' => $data->GOODS_USE_FL,
				'GOODS_REG_DT' => date('Y-m-d H:i:s')
			);
			$this->db->insert('TBL_GOODS', $data1);
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback(); // Rollback if there was an error
				//echo 'Error inserting into table 1.';
				return false;
			}

			// 가격 삽입
			if(!$this->insertGoodPrice($data)) {
				$this->db->trans_rollback(); // Rollback if there was an error
				return false;
			}

			// 사진 삽입
			if(!$this->insertGoodPhoto($data)) {
				$this->db->trans_rollback(); // Rollback if there was an error
				return false;
			}

			// Commit the transaction if all inserts are successful
			$this->db->trans_commit();

			// Insert successful
			return true;
		} catch (Exception $e) {
			// An error occurred, rollback the transaction
			$this->db->trans_rollback();

			// Insert failed
			log_message('error', 'Database Error: ' . $this->db->error()['message']);
			return false;
		}
	}

	/// @brief	상품 업데이트
	/// @return boolean
	function updateGood($data)
	{
		// Begin the transaction
		$this->db->trans_start();

		try {
			// update into TBL_GOODS
			$data1 = array(
				// Data for table 1
				'GOODS_NM' => $data->GOODS_NM,
				'GOODS_SAP_CD' => $data->GOODS_SAP_CD,
				'GTYPE_CD' => $data->GTYPE_CD,
				'GOODS_BRIF_TX' => $data->GOODS_BRIF_TX,
				'GOODS_TX' => $data->GOODS_TX,
				'GOODS_USE_FL' => $data->GOODS_USE_FL,
			);
			$this->db->where('GOODS_ID', $data->GOODS_ID);
			$this->db->update('TBL_GOODS', $data1);
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback(); // Rollback if there was an error
				//echo 'Error inserting into table 1.';
				return false;
			}

			// 가격 테이블 삭제
			if(!$this->delGoodPrice($data->GOODS_ID)) {
				$this->db->trans_rollback(); // Rollback if there was an error
				return false;
			}

			// 사진 테이블 삭제
			if(!$this->delGoodPhoto($data->GOODS_ID)) {
				$this->db->trans_rollback(); // Rollback if there was an error
				return false;
			}

			// 가격 삽입
			if(!$this->insertGoodPrice($data)) {
				$this->db->trans_rollback(); // Rollback if there was an error
				return false;
			}

			// 사진 삽입
			if(!$this->insertGoodPhoto($data)) {
				$this->db->trans_rollback(); // Rollback if there was an error
				return false;
			}

			// Commit the transaction if all inserts are successful
			$this->db->trans_commit();

			// Insert successful
			return true;
		} catch (Exception $e) {
			// An error occurred, rollback the transaction
			$this->db->trans_rollback();

			// Insert failed
			log_message('error', 'Database Error: ' . $this->db->error()['message']);
			return false;
		}
	}

	/// @brief	가격정보 삭제
	/// @return boolean
	function delGoodPrice($good_id)
	{
		$this->db->where('GOODS_ID', $good_id);
		$this->db->delete('TBL_GOODS_PRICE');
		if ($this->db->trans_status() === false) {
			return false;
		}
		return true;
	}

	/// @brief	사진 삭제
	/// @return boolean
	function delGoodPhoto($good_id)
	{
		$this->db->where('GOODS_ID', $good_id);
		$this->db->delete('TBL_GOODS_PHOTO');
		if ($this->db->trans_status() === false) {
			return false;
		}
		return true;
	}

	/// @brief	가격 정보 추가
	/// @return boolean
	function insertGoodPrice($data)
	{
		// 가격 추가
		for($i=0;$i<count($data->GOODS_PRICE_ARR);$i++) {
			$tmp = $data->GOODS_PRICE_ARR[$i];

			// Insert into table 2
			$data2 = array(
				// Data for table 2
				'GPRICE_AMT' => $tmp->GPRICE_AMT,
				'GOODS_ID' => $data->GOODS_ID,
				'GPRICE_PC' => $tmp->GPRICE_PC,
				'GPRICE_SALE_PC' => $tmp->GPRICE_SALE_PC,
				'GPRICE_ORD' => $tmp->GPRICE_ORD,
			);
			$this->db->insert('TBL_GOODS_PRICE', $data2);
			if ($this->db->trans_status() === false) {
				return false;
			}
		}
		return true;
	}

	/// @brief	사진 추가
	/// @return boolean
	function insertGoodPhoto($data)
	{
		// 이미지 추가
		for($i=0;$i<count($data->GOODS_IMG_ARR);$i++) {
			$tmp = $data->GOODS_IMG_ARR[$i];

			// Insert the file as BLOB in MSSQL
			$sql = "INSERT INTO TBL_GOODS_PHOTO (GOODS_ID, GPHOTO_NM, GPHOTO_ORD, GPHOTO_FL) VALUES (?, ?, ?, ?)";
			$params = array($data->GOODS_ID, $tmp->fileName, $tmp->order, $tmp->represent);
			$this->db->query($sql, $params);
			if ($this->db->trans_status() === false) {
				return false;
			}
		}
		return true;
	}
}
