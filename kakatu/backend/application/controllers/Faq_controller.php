<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_controller extends CI_Controller {

	public function get_faq_item(){		 
		// read JSon input
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id)){
			$id = $data_back->id;
			$this->load->model('FAQ');

			$feedback_detail = $this->FAQ->get_feedback_detail($id);
			$tags = $this->FAQ->get_tag_list($id);

			$_fq = $this->FAQ->get_faq_item($id);
			if($_fq != null){
				$data_send = array("status" => $qq, "id" => $_fq->id_feedback, "judul" => $_fq->judul, "pesan" => $_fq->pesan, "wkt" => $_fq->wkt, "color" => $_fq->color, "kategori" => $this->FAQ->get_kategori($id), "date_update" => $_fq->date_update, "feedback_detail" => $feedback_detail, "tag" => $tags);
				$this->FAQ->add_count($data_back->id);
			}
			else
				$data_send = array("status" => "fail", "description" => "tidak ditemukan FAQ dengan id '" . $data_back->id . "'.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id FAQ');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	public function get_recommendation_faq_list(){
		// from client
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->limit) && isset($data_back->page) && isset($data_back->id))
		{
			$limit = $data_back->limit;
			$page = $data_back->page;
			$offset = $offset = ($page - 1) * $limit;

			$datsend = array('userid' => $data_back->id);
			$data_string = json_encode($datsend);

			// $ch = curl_init('http://172.98.199.125/analytic/index.php/Backend_request/get_json');
			// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			//     'Content-Type: application/json',                                                                                
			//     'Content-Length: ' . strlen($data_string))                                                                       
			// );                                                                                                                   
	                                                                                                                     
			// $result = curl_exec($ch);

			// $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			// if ( $status != 201 ) {
			//     die("Error: call to URL failed with status $status, response $result, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch));
			// }

			// curl_close($ch);

			// $data_back2 = json_decode($result, true);

			$data_back2 = json_decode(file_get_contents('http://172.98.199.125/analytic/index.php/Backend_request/get_json'));

			$this->load->model('FAQ');
			$faq_list = array();

			foreach($data_back2->categories as $row){
				if($row->category != 'advanced'){
					$list_temp = $this->FAQ->get_faq_by_level($row->category, $row->level);
					foreach($list_temp as $row2)
						$faq_list[] = $row2;
				}
			}
			$list = array();
			if($offset < count($faq_list)){
				$i = $offset;
				do{
					$list[] = $faq_list[$i];
					$i++;
				} while ($i < count($faq_list) && $i < $offset+$limit);
			}
			else
				$list = [];

			$data_send = array('status' => 'ok', 'userid' => $data_back2->userid, 'FAQ' => $list);
		}
		else
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id user, limit, dan page.');

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	// alternatif (belom)
	// public function get_recommendation_faq_list_2(){
	// 	// get request from Analytic System
	// 	$json = file_get_contents('http://localhost/kakatupedia/analytic/Backend_request/get_json');
	// 	$data_back = json_decode($json);

	// 	$this->load->model('FAQ');
	// 	$faq_list = array();

	// 	foreach($data_back->categories as $row){
	// 		if($row->category != 'advanced'){
	// 			$list_temp = $this->FAQ->get_faq_by_level($row->category, $row->level);
	// 			foreach($list_temp as $row2)
	// 				$faq_list[] = $row2;
	// 		}
	// 	}

	// 	$data_send = array('status' => ok, 'userid' => $data_back->userid, 'FAQ' => $faq_list);

	// 	header("Content-type: application/json");
	// 	echo json_encode($data_send);
	// }

	// public function add_faq(){
	// 	// read JSon input
	// 	$data_back = json_decode(file_get_contents('php://input'));

	// 	if(isset($data_back->id_feedback)){
	// 		$id = $data_back->id_feedback;
	// 		if ($data_back->batch == 0){
	// 			$this->load->model('FAQ');
	// 			$this->FAQ->insert_faq($data_back);
	// 		}
	// 		else {

	// 		}
	// 	}
	// 	else{
	// 		$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id FAQ');
	// 	}

	// 	header("Content-type: application/json");
	// 	echo json_encode($data_send);
	// }

	function get_top_10_faq_list(){
		$this->load->model('Faq');
		$data_send = array('status' => 'ok', 'FAQ' => $this->Faq->get_top_10());

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	function get_related_faq(){
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id) && isset($data_back->limit) && isset($data_back->page)){
			$id = $data_back->id;
			$this->load->model('FAQ');

			$_fq = $this->FAQ->get_faq_item($id);
			if($_fq != null){
				$data_send = array('status' => 'ok', 'FAQ' => $this->FAQ->get_related_faq($id, $data_back->limit, $data_back->page));
			}
			else
				$data_send = array("status" => "fail", "description" => "tidak ditemukan FAQ dengan id '" . $id . "'.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id FAQ, limit, dan page.');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	/******************/
	/*  MISCELLANEOUS */
	/******************/

	function add_faq(){
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id_feedback)){
			$id = $data_back->id_feedback;
			$this->load->model('FAQ');

			$_fq = $this->FAQ->get_faq_item($id);
			if($_fq == null){
				$stat = $this->FAQ->add_faq($data_back);
				if($stat)
					$data_send = array('status' => 'ok');
				else
					$data_send = array('status' => 'error', 'description' => 'gagal menambahkan FAQ');
			}
			else
				$data_send = array("status" => "fail", "description" => "FAQ dengan id '" . $id . "' sudah ada.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id_feedback, ');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	function add_feedback_detail(){
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id_feedback) && isset($data_back->id_feedback_detail) && isset($data_back->id_admin) && isset($data_back->komentar)){
			$id = $data_back->id_feedback;
			$this->load->model('FAQ');

			$_fq = $this->FAQ->get_faq_item($id);
			if($_fq != null){
				$stat = $this->FAQ->add_feedback_detail($data_back);
				if($stat)
					$data_send = array('status' => 'ok');
				else
					$data_send = array('status' => 'error', 'description' => 'gagal menambahkan feedback detail');
			}
			else
				$data_send = array("status" => "fail", "description" => "tidak ditemukan FAQ dengan id '" . $id . "'.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id_feedback_detail, id_feedback, id_admin, dan komentar');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}
	
	function add_tag(){
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id_feedback) && isset($data_back->tag)){
			$id = $data_back->id_feedback;
			$this->load->model('FAQ');

			$_fq = $this->FAQ->get_faq_item($id);
			if($_fq != null){
				$stat = $this->FAQ->add_tag($id, $data_back->tag);
				if($stat)
					$data_send = array('status' => 'ok');
				else
					$data_send = array('status' => 'error', 'description' => 'gagal menambahkan tag');
			}
			else
				$data_send = array("status" => "fail", "description" => "tidak ditemukan FAQ dengan id '" . $id . "'.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id FAQ dan tag');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}
}

?>


