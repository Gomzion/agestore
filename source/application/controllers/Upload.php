<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/// @details CK에디터용 업로드 기능
class Upload extends CI_Controller {

	public function image() {
		$config = array(
			'upload_path' => './uploads/image/', // Adjust the upload directory path
			'allowed_types' => 'gif|jpg|png', // Allowed file types
			'max_size' => 20480 // Maximum file size in KB
		);

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('upload')) {
			$uploadedData = $this->upload->data();
			$fileUrl = './uploads/image/' . $uploadedData['file_name'];

			$response = array(
				'success' => true,
				'url' => '/uploads/image/' . $uploadedData['file_name']
			);
		} else {
			$response = array(
				'success' => false,
				'error' => $this->upload->display_errors('', '')
			);
		}

		echo json_encode($response);
	}

}


