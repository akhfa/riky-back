<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_controller extends CI_Controller {

	public function get_article_list(){
		$data_back = json_decode(file_get_contents('php://input'));
		$limit = $data_back->limit;
		$page = $data_back->page;

		$this->load->model('Article');

		$_art = $this->Article->get_article_list($limit, $page);

		$data_send = array('status' => 'ok', 'artikel' => $_art);

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	public function get_article_item(){		 
		// read JSon input
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id)){
			$id = $data_back->id;

			$this->load->model('Article');

			$_art = $this->Article->get_article_item($id);
			if(isset($_art->id)){
				$gambar = $this->Article->get_gambar_list($id);
				$tags = $this->Article->get_tag_list($id);

				$data_send = array('status' => 'ok', 'id' => $_art->id, 'judul' => $_art->judul, 'konten' => $_art->konten, 'kategori' => $_art->kategori, 'gambar' => $gambar, 'tag' => $tags);
			}
			else
				$data_send = array('status' => 'fail', 'description' => 'tidak ditemukan artikel dengan id \'' . $id . '\'.');
		}
		else
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id artikel');

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	function get_related_article(){
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id) && isset($data_back->limit) && isset($data_back->page)){
			$id = $data_back->id;
			$this->load->model('Article');

			$_art = $this->Article->get_article_item($id);
			if($_art != null){
				$data_send = array('status' => 'ok', 'artikel' => $this->Article->get_related_article($id, $data_back->limit, $data_back->page));
			}
			else
				$data_send = array("status" => "fail", "description" => "tidak ditemukan artikel dengan id '" . $id . "'.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id artikel, limit, dan page.');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}

	/******************/
	/*  MISCELLANEOUS */
	/******************/
	
	public function add_tag(){
		$data_back = json_decode(file_get_contents('php://input'));

		if(isset($data_back->id_artikel) && isset($data_back->tag)){
			$id = $data_back->id_artikel;
			$this->load->model('Article');

			$_art = $this->Article->get_article_item($id);
			if($_art != null){
				$stat = $this->Article->add_tag($id, $data_back->tag);
				if($stat)
					$data_send = array('status' => 'ok');
				else
					$data_send = array('status' => 'error', 'description' => 'gagal menambahkan tag');
			}
			else
				$data_send = array("status" => "fail", "description" => "tidak ditemukan artikel dengan id '" . $id . "'.");
		}
		else{
			$data_send = array('status' => 'fail', 'description' => 'harus melakukan request id artikel dan tag');
		}

		header("Content-type: application/json");
		echo json_encode($data_send);
	}
}

?>


