<?php 
	class Tag extends CI_Model {

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		function get_tag(){
			$query = $this->db->get('dict_tag');
			$result = array();
			foreach($query->result() as $row)
				$result[] = $row->tag;
			return $result;
		}

		function is_tag_exist($tag){
			$query = $this->db->get_where('dict_tag', array('tag' => $tag));
			foreach($query->result() as $row)
				$result = $row;
			if(isset($row))
				return true;
			else
				return false;
		}

		function add_tag($tag){
			// prekondisi : $tag belum ada pada dict_tag
			$this->db->insert('dict_tag', array('tag' => $tag));
		}
	}
?>