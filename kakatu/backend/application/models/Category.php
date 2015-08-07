<?php 
	class Category extends CI_Model {

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		function get_category(){
			$query = $this->db->select('id, kategori AS nama')->get('dict_category');
			return $query->result();
		}

		function get_category_item($id_cat){
			$query = $this->db->select('id, kategori')->get_where('dict_category', array('id' => $id_cat));
			foreach($query->result() as $row)
				$result = $row;
			return $result;
		}
	}
?>