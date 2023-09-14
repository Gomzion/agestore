<?php

/// @details  회원 처리 관련 모델
class User_m extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/// @brief 저장된 UserID 확인
	/// @return boolean
	function checkUserID($userid)
	{
		$this->db->select('USER_ID');
		$this->db->from('TBL_USER');
		$this->db->where('USER_ID',$userid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 로그인 IP 기록
	/// @return boolean
	function checkLoginIP($userid)
	{
		// 접속 IP
		$ipAddress = $this->input->ip_address();

		$this->db->select('LOGIP_FL');
		$this->db->from('TBL_LOGIN_IP');
		$this->db->where('LOGIP_IP',$ipAddress);
		$this->db->where('USER_ID',$userid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief UserID 정보 가져오기
	/// @return 유저정보
	function getUser($userid)
	{
		$this->db->from('TBL_USER');
		$this->db->where('USER_ID',$userid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief UserID 정보 가져오기 - 임시정보
	/// @return 임시정보
	function getUserTemp($sq)
	{
		$this->db->from('TBL_USER_TEMP');
		$this->db->where('UTMP_SQ',$sq);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief UserID 정보 가져오기 - 실명인증
	/// @return 유저정보
	function getUserHpnum($hpnum)
	{
		$this->db->from('TBL_USER');
		$this->db->where("replace(USER_HPN_NO,'-','')",$hpnum);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief 유저정보 상세
	/// @return query result
	function getUserDetail($user_id='')
	{
		if(!$user_id) $user_id = $this->session->userdata['id'];

		$this->db->select('U.USER_ID, U.USER_PW, U.USER_LV, U.USER_NM, U.USER_HPN_NO, U.USER_HPN_FL, U.USER_AFFIL_NM, U.USER_AFFIL_NO,  U.HOSPITAL_CD, U.OPD_CD, U.USER_SIGN_IL, U.USER_PW_CNT, U.USER_TSV_DT, U.USER_TPP_DT, U.USER_LAST_DT, U.USER_REG_DT, H.HOSPITAL_NM, H.HOSPITAL_SDO_NM, H.HOSPITAL_SGG_NM, H.HOSPITAL_BLD_NM, H.HOSPITAL_CORP_CD, H.HOSPITAL_CORP_NM, H.HOSPITAL_SALES_CD, H.HOSPITAL_SALES_NM');
		$this->db->from('TBL_USER U');
		$this->db->join('TBL_HOSPITAL H', 'U.HOSPITAL_CD = H.HOSPITAL_CD', 'left');
		$this->db->where('U.USER_ID', $user_id);

		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief UserID update
	/// @return	none
	function updateUser($data, $userid='')
	{
		if(!$userid) $userid = $this->session->userdata['id'];

		$this->db->where('USER_ID',$userid);

		if ($this->db->update('TBL_USER', $data)) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief UserID update
	/// @return	none
	function updatePassword($newpw, $userid='')
	{
		if(!$userid) $userid = $this->session->userdata['id'];

		$encrptpw = convertToVarbinary($newpw);

		$sql = "UPDATE TBL_USER SET USER_PW = CONVERT(VARBINARY(MAX), ?) WHERE USER_ID = ?";

		if ($this->db->query($sql, array($encrptpw, $userid))) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief	신규 회원 저장
	/// @return boolean
	function insertUserNew($user)
	{
		$data = array(
			'USER_ID' => $user->USER_ID,
			'USER_PW' => $user->USER_PW,
			'USER_LV' => $user->USER_LV,
			'USER_NM' => $user->USER_NM,
			'USER_HPN_NO' => $user->USER_HPN_NO,
			'USER_HPN_FL' => $user->USER_HPN_FL,
			'USER_AFFIL_NM' => $user->USER_AFFIL_NM,
			'USER_AFFIL_NO' => $user->USER_AFFIL_NO,
			'HOSPITAL_CD' => $user->HOSPITAL_CD,
			'HOSPITAL_SALES_CD' => $user->HOSPITAL_SALES_CD,
			'OPD_CD' => $user->OPD_CD,
			'USER_TSV_DT' => $user->USER_TSV_DT,
			'USER_TPP_DT' => $user->USER_TPP_DT,
			'USER_LAST_DT' => $user->USER_LAST_DT,
			'USER_REG_DT' => $user->USER_REG_DT,
			'USER_SIGN_IL' => $user->USER_SIGN_IL
		);

		$data['USER_PW'] = convertToVarbinary($data['USER_PW']);

		$sql = "INSERT INTO TBL_USER (USER_ID, USER_PW, USER_LV, USER_NM, USER_HPN_NO, USER_HPN_FL, USER_AFFIL_NM, USER_AFFIL_NO, HOSPITAL_CD, HOSPITAL_SALES_CD, OPD_CD, USER_TSV_DT, USER_TPP_DT, USER_LAST_DT, USER_REG_DT, USER_SIGN_IL)
        VALUES (?, CONVERT(varbinary(max), ?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CONVERT(VARBINARY(MAX), ?))";

		$result = $this->db->query($sql, $data);

		if ($result) {
			// Insert successful
			return true;
		} else {
			// Insert failed
			log_message('error', 'Database Error: ' . $this->db->error()['message']);
			return false;
		}
	}

	/// @brief	신규 회원 저장 - 임시
	/// @return boolean
	function insertUserTemp($user, $filename, $filecontents)
	{
		$data = array(
			'UTMP_ID' => $user->USER_ID,
			'UTMP_NM' => $user->USER_NM,
			'UTMP_HPN_NO' => $user->USER_HPN_NO,
			'UTMP_SIGN_IL' => $user->USER_SIGN_IL,
			'UTMP_HOSP_CD' => $user->HOSPITAL_CD,
			'UTMP_SALES_CD' => $user->SALESMAN_CD,
			'UTMP_CORP_IL' => $filecontents,
			'UTMP_CORP_NM' => $filename
		);

		$sql = "INSERT INTO TBL_USER_TEMP (UTMP_ID, UTMP_NM, UTMP_HPN_NO, UTMP_SIGN_IL, UTMP_HOSP_CD, UTMP_SALES_CD, UTMP_CORP_IL, UTMP_CORP_NM)
        VALUES (?, ?, ?, CONVERT(varbinary(max), ?), ?, ?, CONVERT(VARBINARY(MAX), ?), ?)";

		$result = $this->db->query($sql, $data);

		if ($result) {
			// Insert successful
			return $this->db->insert_id();
		} else {
			// Insert failed
			log_message('error', 'Database Error: ' . $this->db->error()['message']);
			return -1;
		}
	}

	/// @brief 기관 정보 가져오기 - 사업자등록번호
	/// @return 결과 쿼리
	function getHospitalFromCorp($corpid, $sid='cso')
	{
		$this->db->from('TBL_HOSPITAL');
		$this->db->where('HOSPITAL_CORP_CD',$corpid);
		if($sid != 'cso') $this->db->where('HOSPITAL_SALES_CD',$sid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief 기관 정보 가져오기
	/// @return 결과 쿼리
	function getHospital($hid)
	{
		$this->db->select('HOSPITAL_CD, HOSPITAL_NM, HOSPITAL_CORP_CD, HOSPITAL_CORP_NM, HOSPITAL_SALES_CD, HOSPITAL_SALES_NM');
		$this->db->from('TBL_HOSPITAL');
		$this->db->where('HOSPITAL_CD',$hid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/// @brief 기관에 속한 유저들 가져오기
	/// @return 결과 쿼리
	function getUsersOfHospital($hid)
	{
		$this->db->select('USER_ID, USER_NM, USER_HPN_NO, USER_REG_DT, USER_SIGN_IL');
		$this->db->from('TBL_USER');
		$this->db->where('HOSPITAL_CD',$hid);
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return array();
		}
	}

	/// @brief 병원정보-시도 가져오기
	/// @return query result
	function getHospitalAddr()
	{
		$this->db->from('TBL_HOSPITAL');
		$result = $this->db->get();
		//echo $this->db->last_query();

		// data sorting
		$data = array();
		foreach ($result->result() as $row) {
			if (!isset($data[$row->HOSPITAL_SDO_NM])) {
				$data[$row->HOSPITAL_SDO_NM] = array();
			}
			if (!isset($data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM])) {
				$data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM] = array();
			}
			if (!isset($data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM][$row->HOSPITAL_NM])) {
				$data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM][$row->HOSPITAL_NM] = new stdClass();
			}

			$data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM][$row->HOSPITAL_NM]->HOSPITAL_CD = $row->HOSPITAL_CD;
			$data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM][$row->HOSPITAL_NM]->HOSPITAL_BLD_NM = $row->HOSPITAL_BLD_NM;
			$data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM][$row->HOSPITAL_NM]->HOSPITAL_CORP_CD = $row->HOSPITAL_CORP_CD;
			$data[$row->HOSPITAL_SDO_NM][$row->HOSPITAL_SGG_NM][$row->HOSPITAL_NM]->HOSPITAL_CORP_IL = $row->HOSPITAL_CORP_IL;
		}

		if ($result->num_rows() > 0) {
			return $data;
		} else {
			return array();
		}
	}

	/// @brief 병원정보-검색
	/// @return query result
	function getHospitalSearch($state, $region, $keyword)
	{
		$this->db->select('distinct(HOSPITAL_CD), HOSPITAL_NM, HOSPITAL_CORP_CD');
		$this->db->from('TBL_HOSPITAL');
		$this->db->where('HOSPITAL_SDO_NM', $state);
		$this->db->where('HOSPITAL_SGG_NM', $region);
		$this->db->like('HOSPITAL_NM', $keyword); // Use like() method for the district/county search
		//$this->db->group_by('HOSPITAL_CD');
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	/// @brief 진료과 정보 가져오기
	/// @return query result
	function getOPD()
	{
		$this->db->from('TBL_OPD');
		$result = $this->db->get();
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}

	/// @brief 병원정보 업데이트 - 파일만
	/// @return query result
	function updateHospitalFile($hid, $sales_co, $fname, $fcontent)
	{
		//$sql = "UPDATE TBL_HOSPITAL SET HOSPITAL_CORP_CD = ?, HOSPITAL_CORP_NM = ?, HOSPITAL_CORP_IL = CONVERT(VARBINARY(MAX), ?), HOSPITAL_SALES_CD = ?, HOSPITAL_SALES_NM = ? WHERE HOSPITAL_CD = ?";
		//$params = array($ccd, $fname, $fcontent, $sales_co, $sales_nm, $hid);
		//$this->db->query($sql, $params);

		$data = array();
		if ($fname) {
			$data['HOSPITAL_CORP_NM'] = $fname;
		}
		if ($fcontent) {
			$this->db->set('HOSPITAL_CORP_IL', "CONVERT(VARBINARY(MAX), '$fcontent')", FALSE);
			// Use FALSE as the third parameter to prevent CodeIgniter from automatically escaping the value
		}

		$this->db->where('HOSPITAL_CD', $hid);
		$this->db->where('HOSPITAL_SALES_CD', $sales_co);

		if ($this->db->update('TBL_HOSPITAL', $data)) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 병원정보-업데이트
	/// @return query result
	function updateHospital($hid, $ccd="", $fname="", $fcontent="", $sales_co="", $sales_nm="")
	{
		//$sql = "UPDATE TBL_HOSPITAL SET HOSPITAL_CORP_CD = ?, HOSPITAL_CORP_NM = ?, HOSPITAL_CORP_IL = CONVERT(VARBINARY(MAX), ?), HOSPITAL_SALES_CD = ?, HOSPITAL_SALES_NM = ? WHERE HOSPITAL_CD = ?";
		//$params = array($ccd, $fname, $fcontent, $sales_co, $sales_nm, $hid);
		//$this->db->query($sql, $params);

		$data = array();
		if ($ccd) {
			$data['HOSPITAL_CORP_CD'] = $ccd;
		}
		if ($fname) {
			$data['HOSPITAL_CORP_NM'] = $fname;
		}
		if ($fcontent) {
			$this->db->set('HOSPITAL_CORP_IL', "CONVERT(VARBINARY(MAX), '$fcontent')", FALSE);
			// Use FALSE as the third parameter to prevent CodeIgniter from automatically escaping the value
		}
		if ($sales_co) {
			$data['HOSPITAL_SALES_CD'] = $sales_co;
		}
		if ($sales_nm) {
			$data['HOSPITAL_SALES_NM'] = $sales_nm;
		}

		$this->db->where('HOSPITAL_CD', $hid);

		if ($this->db->update('TBL_HOSPITAL', $data)) {
			return true;
		} else {
			return false;
		}
	}

	/// @brief 병원 풀 이름
	/// @return query result
	function getHospitalFullName($hospital_cd)
	{
		$this->db->select('*');
		$this->db->from('TBL_HOSPITAL');
		$this->db->where('HOSPITAL_CD', $hospital_cd);
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			$tmp = $result->row();

			return "$tmp->HOSPITAL_NM ($tmp->HOSPITAL_SDO_NM $tmp->HOSPITAL_SGG_NM $tmp->HOSPITAL_BLD_NM)";
		} else {
			return "";
		}
	}

	/// @brief 고객 정보 가져오기
	/// @return query result
	function selectCustomer($sw="", $page=1, $config=null)
	{
		$this->db->select('H.HOSPITAL_CD, H.HOSPITAL_NM, H.HOSPITAL_CORP_CD, H.HOSPITAL_CORP_NM, H.HOSPITAL_SALES_CD, H.HOSPITAL_SALES_NM, U.USER_ID, U.USER_NM, COALESCE(SUM(P.PAY_PAID), 0) AS TOTAL_PAY_PAID');
		$this->db->from('TBL_HOSPITAL H');
		$this->db->join('TBL_USER U', 'H.HOSPITAL_CD = U.HOSPITAL_CD', 'left');
		$this->db->join('TBL_PAY_GROUP PG', 'U.USER_ID = PG.USER_ID AND PG.PAYGP_ST IN (\'10\', \'20\', \'21\')', 'left');
		$this->db->join('TBL_PAY P', 'PG.PAYGP_SQ = P.PAYGP_SQ', 'left');
		if ($sw) {
			// 검색어
			$this->db->like('H.HOSPITAL_NM', $sw);
			$this->db->or_like('U.USER_NM', $sw);
		}
		//$this->db->where('U.USER_LV >', LEVEL_ADMIN);
		$this->db->group_by('H.HOSPITAL_CD, H.HOSPITAL_NM, H.HOSPITAL_CORP_CD, H.HOSPITAL_SALES_CD, H.HOSPITAL_CORP_NM, H.HOSPITAL_SALES_NM, U.USER_ID, U.USER_NM');
		$this->db->order_by('H.HOSPITAL_CD','ASC');

		if($page>0) {
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
		//echo $this->db->last_query();

		if ($result->num_rows() > 0) {

			$emails = array();
			$data = array();
			foreach ($result->result() as $row) {
				// HOSPITAL_CD 기준으로 애들을 묶어.
				if (!isset($data[$row->HOSPITAL_CD])) {
					$data[$row->HOSPITAL_CD] = new stdClass();

					$data[$row->HOSPITAL_CD]->HOSPITAL_NM = $row->HOSPITAL_NM;
					$data[$row->HOSPITAL_CD]->HOSPITAL_CORP_CD = $row->HOSPITAL_CORP_CD;
					$data[$row->HOSPITAL_CD]->HOSPITAL_CORP_NM = $row->HOSPITAL_CORP_NM;
					$data[$row->HOSPITAL_CD]->HOSPITAL_SALES_CD = $row->HOSPITAL_SALES_CD;
					$data[$row->HOSPITAL_CD]->HOSPITAL_SALES_NM = $row->HOSPITAL_SALES_NM;
					$data[$row->HOSPITAL_CD]->USER_ID = $row->USER_ID;
					$data[$row->HOSPITAL_CD]->USER_NM = $row->USER_NM;
					$data[$row->HOSPITAL_CD]->TOTAL_PAY_PAID = $row->TOTAL_PAY_PAID;

					array_push($emails, $row->USER_ID);
				} else {
					if(!in_array($row->USER_ID, $emails)) {
						$data[$row->HOSPITAL_CD]->USER_ID .= ", ".$row->USER_ID;
						$data[$row->HOSPITAL_CD]->USER_NM .= ", ".$row->USER_NM;
						array_push($emails, $row->USER_ID);
					}
					$data[$row->HOSPITAL_CD]->TOTAL_PAY_PAID += $row->TOTAL_PAY_PAID;
				}
			}

			return $data;
		} else {
			return array();
		}
	}
}
