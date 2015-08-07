<?php 
	class Dict_category extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_dict_category(){
			$query = null;
			$query = $this->db->get('dict_category');
			return $query->result();	
		}

		function check_and_add($category_id, $category_name){
			$category_id_res = $this->check_category_exist($category_name);
			if($category_id_res === NULL){
				$this->insert_category($category_id, $category_name);
				return $category_id;
			}
			return $category_id_res;
		}

		function insert_category($category_id, $category_name){
			$data = array(
			   'id' => $category_id ,
			   'kategori' => $category_name
			);

			$this->db->insert('dict_category', $data);
		}

		function check_category_exist($category_name){
			//echo ' check_category_exist ';
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_category', array(//making selection
	            'kategori' => $category_name
	        ));

	       // var_dump($query->num_rows());

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return NULL; // category is not exist in table
	        } else {
	        	return $query->row()->id; // category is exist in table
	        }
		}
		
	}
?>