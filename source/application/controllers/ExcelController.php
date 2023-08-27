<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once './vendor/autoload.php'; // Include the autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends CI_Controller {

	public function generate()
	{
		// Create a new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set the active sheet
		$sheet = $spreadsheet->getActiveSheet();

		// Set some sample data
		$sheet->setCellValue('A1', 'Hello');
		$sheet->setCellValue('B1', 'World');

		// Create a new Excel Writer object
		$writer = new Xlsx($spreadsheet);

		// Set the output file path
		$outputFile = './uploads/output.xlsx';

		// Save the Excel file
		$writer->save($outputFile);

		echo 'Excel file generated successfully!';
	}

}
