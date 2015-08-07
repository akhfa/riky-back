<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_controller extends CI_Controller {

	public function get_tag(){
		$this->load->model('Tag');

		$data_send = array('status' => 'ok', 'tag' => $this->Tag->get_tag());

		header("Content-type: application/json");
		echo json_encode($data_send);
	}
}

?>


