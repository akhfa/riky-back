<?php 
	class Article extends CI_Model {

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		function get_article_list($limit, $page){
			$offset = ($page - 1) * $limit;
			$query = $this->db->select('id, judul')->order_by('wkt', 'DESC')->order_by('count', 'DESC')->get('artikel', $limit, $offset);
			$result = array();
			foreach($query->result() as $row){
				$result[] = array('id' => $row->id, 'judul' => $row->judul, 'kategori' => $this->get_kategori($row->id));
			}
			return $result;
		}

		function get_article_item($id){
			$query = $this->db->get_where('artikel', array('id' => $id));
			foreach($query->result() as $row){
				$result = array('id' => $row->id, 'judul' => $row->judul, 'konten' => $row->konten, 'kategori' => $this->get_kategori($id));
			}
			return $result;
		}

		function get_related_article($id_artikel, $limit, $page){
			$offset = ($page - 1) * $limit;
			$kategori = $this->get_kategori($id_artikel);
			$tags = $this->get_tag_list($id_artikel);
			
			$this->db->select('artikel.id_artikel as id, judul, dict_category.kategori as kategori')->join('tag_artikel', 'artikel.id_artikel = tag_artikel.id_artikel')->join('klasifikasi_artikel', 'artikel.id_artikel = klasifikasi_artikel.id_artikel');
			$this->db->join('dict_category', 'klasifikasi_feedback.klasifikasi = dict_category.id');
			$this->db->where('dict_kategori.kategori', $kategori)->group_start();
			foreach($tags as $row)
				$this->db->or_where('tag', $row);
			$query1 = $this->db->group_end()->get_compiled_select();
			$this->db->reset_query();

			$this->db->select('artikel.id_artikel as id, judul, dict_category.kategori as kategori')->join('tag_artikel', 'artikel.id_artikel = tag_artikel.id_artikel')->join('klasifikasi_artikel', 'artikel.id_artikel = klasifikasi_artikel.id_artikel');
			$this->db->join('dict_category', 'klasifikasi_feedback.klasifikasi = dict_category.id');
			foreach($tags as $row)
				$this->db->or_where('tag', $row);
			$query2 = $this->db->get_compiled_select();

			$query = $this->db->from("($query1 UNION $query2)")->limit($limit, $offset)->get();

			return $query->result();
		}

		function get_gambar_list($id_art){
			$query = $this->db->get_where('gambar', array('id_artikel' => $id_art));
			return $query->result();
		}

		function get_kategori($id_artikel){
			$this->db->select('dict_category.kategori as kategori')->from('klasifikasi_artikel');
			$this->db->join('dict_category', 'klasifikasi_artikel.kategori = dict_category.id');
			$query = $this->db->where('id_artikel', $id_artikel)->get();
			foreach($query->result() as $row)
				$result = $row->kategori;
			if(!isset($result))
				$result = '';
			return $result;
		}

		function search($keyword, $limit, $page){
			$offset = ($page - 1) * $limit;
			$keys = explode(" ", $keyword);
			$result = array();

			$this->db->select('id, judul');
			foreach($keys as $key){
				$this->db->or_like('judul', $key);
				$this->db->or_like('konten', $key);
			}
			$query = $this->db->order_by('wkt', 'DESC')->order_by('count', 'DESC')->limit($limit, $offset)->get('artikel');
			foreach($query->result() as $row){
				if(!$this->is_already_inserted($row->id, $result))
					$result[] = array('id' => $row->id, 'judul' => $row->judul, 'kategori' => $this->get_kategori($row->id));
			}
			return $result;
		}

		function is_already_inserted($val, $array){
			$check = false;
			foreach($array as $row){
				if(in_array($val, $row)){
					$check = true;
					break;
				}
			}
			return $check;
		}

		function add_count($id_artikel){
			$query = "UPDATE artikel SET count = count+1 WHERE id_feedback = '".$id_artikel."'";
			$res = $this->db->query($query);
			return $res;
		}

		/******************/
		/*  MISCELLANEOUS */
		/******************/
		
		function add_tag($id_artikel, $tag){
			if(!$this->Tag->is_tag_exist($tag))
				$this->Tag->add_tag($tag);
			if($this->is_tag_already_added($id_artikel, $tag)){
				$object = array('id_artikel' => $id_artikel, 'tag' => $tag);
				return $this->insert('tag_artikel', $object);
			}
			else
				return true;
		}

		function get_tag_list($id_artikel){
			$query = $this->db->select('tag')->get_where('tag_artikel', array('id_artikel' => $id_artikel));
			$result = array();
			foreach($query->result() as $row)
				$result[] = $row->tag;
			return $result;
		}

		function is_tag_already_added($id_artikel, $tag){
			$query = $this->db->get_where('tag_artikel', array('id_artikel' => $id_feedback, 'tag' => $tag));
			foreach($query->result() as $row)
				$result = $row;
			if(isset($result))
				return true;
			else
				return false;
	}
?>