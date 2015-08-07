<?php 
	class Testmodel extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function getSomething(){
			$query = $this->db->query('SELECT kategori from dict_category');
			return $query->result();
		}

		function getAll(){
			$query = $this->db->query('SELECT * from dict_category');
			return $query->result();
		}		
	}
?>