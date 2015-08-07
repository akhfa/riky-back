<?php 
	class Dict_screen extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_dict_screen(){
			$query = $this->db->query('SELECT * from dict_screen');
			return $query->result();	
		}

		function check_and_add($screen_id, $screen_name,$category_id){
			$screen_id_res = $this->check_screen_exist($screen_name,$category_id);
			if($screen_id_res === NULL){
				$this->insert_screen($screen_id, $screen_name,$category_id);
				return $screen_id;
			}
			return $screen_id_res;
		}

		function insert_screen($screen_id, $screen_name,$category_id){
			$data = array(
			   'id' => $screen_id ,
			   'name' => $screen_name ,
			   'id_category' => $category_id
			);

			$this->db->insert('dict_screen', $data);
		}

		function check_screen_exist($screen_name,$category_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_screen', array(//making selection
	            'name' => $screen_name, 'id_category' => $category_id
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return NULL; // screen is not exist in table
	        } else {
	        	return $query->row()->id; // screen is already exist in table
	        }
		}

		function get_screens_same_category($category_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_screen', array(//making selection
	            'id_category' => $category_id
	        ));

	        return $query->result();
		}

		function get_category($screenid){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_screen', array(//making selection
	            'id' => $screenid
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return NULL; // screen is not exist in table
	        } else {
	        	return $query->row()->id_category; // screen is already exist in table
	        }
		}
		
	}
?>