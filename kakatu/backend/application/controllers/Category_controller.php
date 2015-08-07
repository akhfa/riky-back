<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_controller extends CI_Controller {

	public function get_category(){
		$this->load->model('Category');
		$_cat = $this->Category->get_category();

		$data_send = array("status" => "ok", "kategori" => $_cat);

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	public function get_category_item(){
		$data_back = json_decode(file_get_contents('php://input'));

		$id = $data_back->id;

		$this->load->model('Category');
		$_cat = $this->Category->get_category_item($id);

		$data_send = array('status' => ok, 'id' => $_cat->id, 'nama' => $_cat->kategori);

		header("Content-type: application/json");
		echo json_encode($data_send);
	}
}

?>


