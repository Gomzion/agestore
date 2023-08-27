<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details 관리자 상품관리
class SanGoods extends CI_Controller {

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('common','sess','nocache','alert','url'));
		$this->load->library(array('form_validation','upload','image_lib','pagination','GoodClass'));
		$this->load->model(array('Good_m'));

		NoCache($this);

		// 세션보유여부체크
		check_sess("/San");
	}

	/// @brief 관리자 상품관리
	/// @return none
	public function index()
	{
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;

		// pagenation config
		$config['base_url'] = '/SanGoods?';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 7;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 상품정보 가져오기
		$data["GOODS"] = $this->Good_m->getGoodsList("",$config,$page);

		// 페이지 구분
		$data["page_name"] = "product_setting";

		$this->load->view('Head_v',$data);
		$this->load->view('SanGoods_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 상품추가폼
	/// @return none
	public function newone()
	{
		// 페이지 구분
		$data["page_name"] = "product_setting";

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 상품 추가
		$data["GOODS_ID"] = "";
		$data["GOOD_PHOTOS"] = array();
		$data["GOOD_PRICES"] = array();

		$this->load->view('Head_v',$data);
		$this->load->view('SanGoodsEdit_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 상품수정
	/// @return none
	public function edit()
	{
		// 페이지 구분
		$data["page_name"] = "product_setting";

		// 상품 추가
		$data["GOODS_ID"] = $this->uri->segment(3);

		// 구분값
		$data["GTYPE"] = $this->Good_m->selectGoodType();

		// 상품정보
		$data["GOOD"] = $this->Good_m->getGood($data["GOODS_ID"]);

		// 상품 사진
		$data["GOOD_PHOTOS"] = $this->Good_m->getGoodPhoto($data["GOODS_ID"]);

		// 상품 가격
		$data["GOOD_PRICES"] = $this->Good_m->getGoodPrice($data["GOODS_ID"]);

		$this->load->view('Head_v',$data);
		$this->load->view('SanGoodsEdit_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 상품 등록 및 수정
	/// @return none
	public function mody()
	{
		//------ test code
		/*echo "<pre>";print_r($_POST);echo "</pre>";
		$files = $_FILES['product_image'];
		for ($i = 0; $i < count($files['name']); $i++) {
			echo $i."/".$files['name'][$i]."<br>";
		}
		echo "============================================================================\n<br/>";*/
		//exit;

		// goods 생성
		$newgood = new GoodClass();

		$newgood->GOODS_NM = $this->input->post('product_name', TRUE);
		$newgood->GOODS_SAP_CD = $this->input->post('sap_code', TRUE);
		$newgood->GOODS_BRIF_TX = $this->input->post('product_desc', FALSE);
		$newgood->GTYPE_CD = $this->input->post('category', TRUE);
		$newgood->GOODS_TX = $this->input->post('product_content', FALSE);
		$price_unit = $this->input->post('price_unit', TRUE);
		$price_before_sale = $this->input->post('price_before_sale', TRUE);
		$price_sale = $this->input->post('price_sale', TRUE);

		// array
		$represent = $this->input->post('represent', TRUE);
		if (!in_array('Y', $represent)) $represent[0] = 'Y';
		$photo_sq = $this->input->post('photo_sq', TRUE);
		if(!$photo_sq) $photo_sq = array();
		$f_name = $this->input->post('f_name', TRUE);
		if(!$f_name) $f_name = array();
		$f_type = $this->input->post('f_type', TRUE);
		if(!$f_type) $f_type = array();
		$f_content = $this->input->post('f_content', TRUE);
		if(!$f_content) $f_content = array();

		// good_id
		$new = false;
		$newgood->GOODS_ID = $this->input->post('good_id', TRUE);
		if(!$newgood->GOODS_ID) {
			$new = true;
			$newgood->GOODS_ID = $newgood->GTYPE_CD.date('ymdHis');
		}

		// price
		$newgood->setPriceARR($price_unit, $price_before_sale, $price_sale);

		// 이미지
		$newgood->setImageARR($photo_sq, $f_name, $f_type, $f_content, $represent);

		$sqidx = 0;
		// Check if files were uploaded
		if (isset($_FILES['product_image'])) {
			$files = $_FILES['product_image'];

			// Loop through the uploaded files
			for ($i = 0; $i < count($files['name']); $i++) {
				$fileName = $files['name'][$i];
				$fileType = $files['type'][$i];
				$fileTmpName = $files['tmp_name'][$i];

				while($photo_sq[$sqidx] != "") $sqidx++;

				// Resize the image
				$config['image_library'] = 'gd2';
				$config['source_image'] = $fileTmpName;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = TARGET_WIDTH;
				$config['height'] = TARGET_HEIGHT;

				$this->image_lib->clear();
				$this->image_lib->initialize($config);
				$this->image_lib->resize();

				// Get the binary data of the resized image
				$imageData = file_get_contents($fileTmpName);
				$base64Image = base64_encode($imageData);

				$newgood->addImageFile($sqidx++, $fileName, $fileType, $base64Image);
			}
		}

		//echo "<pre>";print_r($newgood);echo "</pre>";

		if($new) $rst = $this->Good_m->insertGoodNew($newgood); else $rst = $this->Good_m->updateGood($newgood);
		if($rst) alertUrl("정상적으로 적용 되었습니다.", "/SanGoods"); else alertBack("저장에 실패하였습니다. 다시 진행 해주세요.");
	}
}
