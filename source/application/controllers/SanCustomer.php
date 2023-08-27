<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once './vendor/autoload.php'; // Include the autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/// @details 관리자 고객 관리
class SanCustomer extends CI_Controller
{

	/// @brief 생성자
	/// @return none
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','common','sess','nocache','alert'));
		$this->load->library(array('pagination','UserClass'));
		$this->load->model(array('User_m','Order_m'));

		NoCache($this);

		// 세션보유여부체크
		check_sess("/San");
	}

	/// @brief 관리자 고객정보 관리
	/// @return none
	public function index()
	{
		$data["sw"] = $this->input->get('sw');
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;

		// pagenation config
		$config['base_url'] = '/SanCustomer?sw='.$data["sw"].'&';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 10;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 고객정보 가져오기
		$data["customer"] = $this->User_m->selectCustomer($data["sw"], $page, $config);
		//echo "<pre>";print_r($data["customer"]);echo "</pre>";

		/*$value = array();
		foreach ($data["customer"] as $cd => $ct) {
			$tmp = array($cd, $ct->HOSPITAL_NM, $ct->USER_NM, $ct->HOSPITAL_CORP_CD, $ct->HOSPITAL_SALES_NM, number_format($ct->TOTAL_PAY_PAID));
			array_push($value, $tmp);
		}
		echo "<pre>";print_r($value);echo "</pre>";*/

		// 페이지 구분
		$data["page_name"] = "customer";

		$this->load->view('Head_v',$data);
		$this->load->view('SanCustomer_v',$data);
		$this->load->view('Tail_v',$data);
	}

	/// @brief 관리자 고객정보 상세
	/// @return none
	public function Detail()
	{
		$h_id = $this->uri->segment(3);

		$data["range"] = $this->input->get('range');
		if(!$data["range"]) $data["range"] = 6;
		$page = $this->input->get('per_page');
		if(!$page) $page = 1;

		// pagenation config
		$config['base_url'] = '/SanCustomer/Detail/'.$h_id.'?range='.$data["range"].'&';
		$config['use_page_numbers'] = true;
		$config['per_page'] = 5;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;

		// 기관정보 가져오기
		$data["HOSPITAL"] = $this->User_m->getHospital($h_id);
		//echo "<pre>";print_r($data["HOSPITAL"]);echo "</pre>";

		// 기관에 속한 유저들 가져오기
		$data["USERS"] = $this->User_m->getUsersOfHospital($h_id);
		//echo "<pre>";print_r($data["USERS"]);echo "</pre>";

		$id_arr = array();
		foreach ($data["USERS"] as $dt) {
			$id_arr[] = $dt->USER_ID;
		}
		//echo "<pre>";print_r($id_arr);echo "</pre>";

		// 그 유저들의 거래내역 가져오기
		$data["PAYLIST"] = $this->Order_m->getPayOfUsers($id_arr, $data["range"], $config, $page);
		//echo "<pre>";print_r($data["PAYLIST"]);echo "</pre>";

		// 페이지 구분
		$data["page_name"] = "customer";

		$this->load->view('Head_v',$data);
		$this->load->view('SanCustomerDetail_v',$data);
		$this->load->view('Tail_v',$data);
	}

	public function downExcel()
	{
		$sw = $this->input->get('sw');

		// 고객정보 가져오기
		$customer = $this->User_m->selectCustomer($sw, -1);

		$spreadsheet = IOFactory::load('./components/excels/customer.xlsx');
		$sheet = $spreadsheet->getActiveSheet();

		$value = array();
		foreach ($customer as $cd => $ct) {
			if($ct->HOSPITAL_SALES_CD) $snm = $ct->HOSPITAL_SALES_NM; else $snm = 'N';
			$tmp = array($cd, $ct->HOSPITAL_NM, $ct->USER_NM, $ct->HOSPITAL_CORP_CD, $snm, number_format($ct->TOTAL_PAY_PAID));
			array_push($value, $tmp);
		}

		// 배열을 C3부터 입력
		$sheet->fromArray($value, NULL, 'A2');

		//$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		// Create a new Excel Writer object
		$writer = new Xlsx($spreadsheet);

		// Set the appropriate headers for download
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="AGeStore_customer_"'.date('YmdHis').'".xlsx"');
		header('Cache-Control: max-age=0');
		header('X-File-Download: 1');
		header('X-File-Saved: 1');

		// Output the spreadsheet directly to the browser
		$writer->save('php://output');
		exit(); // Terminate the script execution after sending the file
	}
}
