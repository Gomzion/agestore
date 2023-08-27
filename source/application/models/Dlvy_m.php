<?php

/// @details 배송 관련 모델
class Dlvy_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief 배송정보 가져오기
	/// @details none
	/// @return query result
	function getDlvyList($one=0, $_range=6, $config=null, $page=1)
	{
		// user_id
		$user_id = $this->session->userdata['id'];

		$this->db->select('PG.USER_ID, PG.PAYGP_SQ, PG.PAYGP_ST, PG.PAYGP_REQ_TX, PG.PAYGP_DELI_CD, PG.PAYGP_PAID_CD, PG.PAYGP_PG_CD, PG.PAYGP_PG_MSG, PG.PAYGP_STSRT_DT, PG.PAYGP_STEND_DT, PG.PAYGP_REG_DT, P.PAY_SQ, P.GPRICE_AMT, P.GOODS_ID, P.PAY_PAID, G.GOODS_NM, GP.GPHOTO_NM, GP2.GPRICE_PC, GP2.GPRICE_SALE_PC');
		$this->db->from('TBL_PAY_GROUP PG');
		$this->db->join('TBL_PAY P', 'PG.PAYGP_SQ = P.PAYGP_SQ');
		$this->db->join('TBL_GOODS G', 'P.GOODS_ID = G.GOODS_ID');
		$this->db->join('TBL_GOODS_PHOTO GP', 'P.GOODS_ID = GP.GOODS_ID AND GP.GPHOTO_FL = \'Y\'');
		$this->db->join('TBL_GOODS_PRICE GP2', 'P.GOODS_ID = GP2.GOODS_ID AND P.GPRICE_AMT = GP2.GPRICE_AMT');
		if ($one) {
			$this->db->where('PG.PAYGP_SQ', $one);
		} else {
			$this->db->where('PG.USER_ID', $user_id);

			if ($_range <= 12) {
				$this->db->where('PG.PAYGP_REG_DT >= DATEADD(MONTH, -' . $_range . ', GETDATE())');
			} else if ($_range >= 2023) {
				$this->db->where('YEAR(PG.PAYGP_REG_DT)', $_range);
			}
		}
		$this->db->order_by('PG.PAYGP_REG_DT', 'desc');

		if (!$one) {
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
			$_result = $result->result();

			// data parsing
			$data = array();
			foreach ($_result as $row) {
				if (!isset($data[$row->PAYGP_SQ])) {
					$data[$row->PAYGP_SQ] = array();

					$data[$row->PAYGP_SQ]['PAYGP_ST'] = $row->PAYGP_ST;
					$data[$row->PAYGP_SQ]['PAYGP_REQ_TX'] = $row->PAYGP_REQ_TX;
					$data[$row->PAYGP_SQ]['PAYGP_DELI_CD'] = $row->PAYGP_DELI_CD;
					$data[$row->PAYGP_SQ]['PAYGP_PAID_CD'] = $row->PAYGP_PAID_CD;
					$data[$row->PAYGP_SQ]['PAYGP_PG_CD'] = $row->PAYGP_PG_CD;
					$data[$row->PAYGP_SQ]['PAYGP_PG_MSG'] = $row->PAYGP_PG_MSG;
					$data[$row->PAYGP_SQ]['PAYGP_STSRT_DT'] = $row->PAYGP_STSRT_DT;
					$data[$row->PAYGP_SQ]['PAYGP_STEND_DT'] = $row->PAYGP_STEND_DT;
					$data[$row->PAYGP_SQ]['PAYGP_REG_DT'] = $row->PAYGP_REG_DT;
					$data[$row->PAYGP_SQ]['PAYGP_PAID'] = 0;
					$data[$row->PAYGP_SQ]['GOODS'] = array();
				}
				$data[$row->PAYGP_SQ]['PAYGP_PAID'] += $row->PAY_PAID;

				$tmp2 = array();
				$tmp2['PAY_SQ'] = $row->PAY_SQ;
				$tmp2['GOODS_ID'] = $row->GOODS_ID;
				$tmp2['GPRICE_AMT'] = $row->GPRICE_AMT;
				$tmp2['PAY_PAID'] = $row->PAY_PAID;
				$tmp2['GOODS_NM'] = $row->GOODS_NM;
				$tmp2['GPHOTO_NM'] = $row->GPHOTO_NM;
				$tmp2['GPRICE_PC'] = $row->GPRICE_PC;
				$tmp2['GPRICE_SALE_PC'] = $row->GPRICE_SALE_PC;

				array_push($data[$row->PAYGP_SQ]['GOODS'], $tmp2);
			}

			return $data;
		} else {
			return array();
		}
	}
}
