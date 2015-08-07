<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_controller extends CI_Controller {

	public function search_faq(){		 
		// read JSon input
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->query) && isset($data_back->limit) && isset($data_back->page)){
			$query = $data_back->query;
			$limit = $data_back->limit;
			$page = $data_back->page;

			$this->load->model('FAQ');
			$this->load->model('Article');

			$data_send = array('status' => 'ok', 'artikel' => $this->Article->search($query, $limit, $page), 'FAQ' => $this->FAQ->search($query, $limit, $page));
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus mengirim request atribut query, limit, dan page');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}
}

?>


