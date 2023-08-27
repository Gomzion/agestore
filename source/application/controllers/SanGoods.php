<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once './vendor/autoload.php'; // Include the autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/// @details 관리자 상품관리
class SanGoods extends CI_Controller {

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('common','sess','nocache','alert','url'));
		$this->load->library(array('form_validation','upload','image_lib','pagination','GoodClass'));
		$this->load->model(array('Good_m','Order_m','Good_m'));

		NoCache($this);

		// 세션보유여부체크
		check_sess("/San");
	}

	/// @brief 관리자 상품관리
	/// @return none
	public function index()
	{
		$data["page"] = $this->input->get('per_page');
		if(!$data["page"]) $data["page"] = 1;

		// pagenation config
		$config['base_url'] = '/SanGoods?';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 7;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 상품정보 가져오기
		$data["GOODS"] = $this->Good_m->getGoodsList("",$config,$data["page"]);

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

		// 페이지 구분
		$data["page_name"] = "product_setting";

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
		$newgood->setImageARR($photo_sq, $f_name, $represent);

		//echo "<pre>";print_r($newgood);echo "</pre>";

		$sqidx = 0;
		// Check if files were uploaded
		if (isset($_FILES['product_image'])) {
			$fileCount = count($_FILES['product_image']['name']);

			for ($i = 0; $i < $fileCount; $i++) {
				$tmpFilePath = $_FILES['product_image']['tmp_name'][$i];

				while($photo_sq[$sqidx] != "") $sqidx++;

				if ($tmpFilePath !== "") {
					$fileData = file_get_contents($tmpFilePath);
					$fileName = $_FILES['product_image']['name'][$i];
					$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
					$saveName = $newgood->GOODS_ID.date('his').$i.".".$fileExtension;

					// Specify the directory where you want to save the files
					$uploadPath = IMG_GOODPATH.$saveName;

					// Save the file
					file_put_contents($uploadPath, $fileData);

					$newgood->addImageFile($sqidx++, $saveName);
				}
			}
		}

		//echo "<pre>";print_r($newgood);echo "</pre>";

		if($new) $rst = $this->Good_m->insertGoodNew($newgood); else $rst = $this->Good_m->updateGood($newgood);
		if($rst) alertUrl("정상적으로 적용 되었습니다.", "/SanGoods"); else alert("저장에 실패하였습니다. 다시 진행 해주세요.");
	}

	/// @brief 관리자 구매내역
	/// @return none
	public function OrderList()
	{
		$data["g_id"] = $this->input->get('g_id');
		$data["bp"] = $this->input->get('bp');
		$data["st"] = $this->input->get('st');
		$data["sw"] = $this->input->get('sw');
		$data["range"] = $this->input->get('range');
		if(!$data["range"]) $data["range"] = 6;
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;

		// pagenation config
		$config['base_url'] = '/SanGoods/OrderList?g_id='.$data["g_id"].'&bp='.$data["bp"].'&sw='.$data["sw"].'&st='.$data["st"].'&range='.$data["range"].'&';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 10;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 상품 정보
		$data["GOOD"] = $this->Good_m->getGood($data["g_id"]);
		//echo "<pre>";print_r($data["GOOD"]);echo "</pre>";

		// 매출액 (실제 거래금액 기준)
		$data["ALLPAID"] = $this->Order_m->getAllPaidOfGood($data["g_id"]);
		//echo "<pre>";print_r($data["ALLPAID"]);echo "</pre>";

		// 상품 기준 구매내역
		$data["PAYLIST"] = $this->Order_m->getPayOfGood($data["g_id"], $data["st"], $data["sw"], $data["range"], $config, $page);

		// 페이지 구분
		$data["page_name"] = "product_setting";

		$this->load->view('Head_v',$data);
		$this->load->view('OrderList_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 구매내역
	/// @return none
	public function downExcelPurchase()
	{
		$data["g_id"] = $this->input->get('g_id');
		$data["st"] = $this->input->get('st');
		$data["sw"] = $this->input->get('sw');
		$data["range"] = $this->input->get('range');

		// 상품 정보
		$good = $this->Good_m->getGood($data["g_id"]);

		// 매출액 (실제 거래금액 기준)
		$allpaid = $this->Order_m->getAllPaidOfGood($data["g_id"]);

		// 상품 기준 구매내역
		$paylist = $this->Order_m->getPayOfGood($data["g_id"], $data["st"], $data["sw"], $data["range"]);

		$spreadsheet = IOFactory::load('./components/excels/purchase.xlsx');
		$sheet = $spreadsheet->getActiveSheet();

		$value = array();
		foreach ($paylist as $ct) {
			$stmsg = "";
			if($ct->PAYGP_ST == ST_READY) $stmsg = "상품 준비중";
			else if($ct->PAYGP_ST == ST_DLVY_ING) $stmsg = "배송중";
			else if($ct->PAYGP_ST == ST_DLVY_FINISH) $stmsg = "배송 완료";
			else if($ct->PAYGP_ST == ST_CANCEL) $stmsg = "주문 취소";
			else if($ct->PAYGP_ST == ST_EXCHANGE) $stmsg = "교환 신청";
			else if($ct->PAYGP_ST == ST_CLAME) $stmsg = "반품 신청";
			else if($ct->PAYGP_ST == ST_EXCHANGE_FINISH) $stmsg = "교환 완료";
			else if($ct->PAYGP_ST == ST_CLAME_FINISH) $stmsg = "반품 완료";

			$tmp = array($stmsg, substr($ct->PAYGP_REG_DT, 0, 16), $ct->HOSPITAL_NM, $ct->USER_NM, $ct->GPRICE_AMT.'BOX - '.number_format($ct->PAY_PAID).'원 '.$ct->PAY_SALE.'% 할인 상품', number_format($ct->PAY_PAID));
			array_push($value, $tmp);
		}

		$sheet->setCellValue('B2', $good->GOODS_NM);
		$sheet->setCellValue('E2', number_format($allpaid));
		$sheet->fromArray($value, NULL, 'A5');

		// Create a new Excel Writer object
		$writer = new Xlsx($spreadsheet);

		// Set the appropriate headers for download
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="AGeStore_purchase_"'.date('YmdHis').'".xlsx"');
		header('Cache-Control: max-age=0');
		header('X-File-Download: 1');
		header('X-File-Saved: 1');

		// Output the spreadsheet directly to the browser
		$writer->save('php://output');
		exit(); // Terminate the script execution after sending the file
	}

	/// @brief 관리자 장바구니 리스트
	/// @return none
	public function CartList()
	{
		$data["g_id"] = $this->input->get('g_id');
		$data["bp"] = $this->input->get('bp');
		$data["sw"] = $this->input->get('sw');
		$data["range"] = $this->input->get('range');
		if(!$data["range"]) $data["range"] = 6;
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;

		// pagenation config
		$config['base_url'] = '/SanGoods/CartList?g_id='.$data["g_id"].'&bp='.$data["bp"].'&sw='.$data["sw"].'&range='.$data["range"].'&';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 10;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 상품 정보
		$data["GOOD"] = $this->Good_m->getGood($data["g_id"]);
		//echo "<pre>";print_r($data["GOOD"]);echo "</pre>";

		// 상품 기준 구매내역
		$data["CARTLIST"] = $this->Order_m->getCartList($data["g_id"], $data["sw"], $data["range"], $config, $page);
		//echo "<pre>";print_r($data["CARTLIST"]);echo "</pre>";

		// 페이지 구분
		$data["page_name"] = "product_setting";

		$this->load->view('Head_v',$data);
		$this->load->view('CartList_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 장바구니 현황
	/// @return none
	public function downExcelCartList()
	{
		$data["g_id"] = $this->input->get('g_id');
		$data["sw"] = $this->input->get('sw');
		$data["range"] = $this->input->get('range');

		// 상품 정보
		$good = $this->Good_m->getGood($data["g_id"]);

		// 상품 기준 구매내역
		$cartlist = $this->Order_m->getCartList($data["g_id"], $data["sw"], $data["range"]);

		$spreadsheet = IOFactory::load('./components/excels/cartlist.xlsx');
		$sheet = $spreadsheet->getActiveSheet();

		$value = array();
		foreach ($cartlist as $ct) {
			$tmp = array($ct->CART_REG_DATETIME, $ct->HOSPITAL_NM, $ct->USER_NM, $ct->GPRICE_AMT.'BOX - '.number_format($ct->PAY_PAID).'원 '.$ct->PAY_SALE.'% 할인 상품');
			array_push($value, $tmp);
		}

		$sheet->setCellValue('B2', $good->GOODS_NM);
		$sheet->fromArray($value, NULL, 'A5');

		// Create a new Excel Writer object
		$writer = new Xlsx($spreadsheet);

		// Set the appropriate headers for download
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="AGeStore_cartlist_"'.date('YmdHis').'".xlsx"');
		header('Cache-Control: max-age=0');
		header('X-File-Download: 1');
		header('X-File-Saved: 1');

		// Output the spreadsheet directly to the browser
		$writer->save('php://output');
		exit(); // Terminate the script execution after sending the file
	}
}
