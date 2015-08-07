<?php 
	class Dict_activation extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_dict_activation(){
			$query = $this->db->get('dict_activation');
			return $query->result();
		}

		function check_and_add($activation_id, $activation_name, $screen_id){
			$activation_id_res = $this->check_activation_exist($activation_name, $screen_id);
			if($activation_id_res === NULL){
				$this->insert_activation($activation_id, $activation_name, $screen_id);
				return $activation_id;
			}
			return $activation_id_res;
		}

		function insert_activation($activation_id, $activation_name, $screen_id){
			$data = array(
			   'id' => $activation_id ,
			   'name' => $activation_name ,
			   'id_screen' => $screen_id
			);

			$this->db->insert('dict_activation', $data);
		}

		function check_activation_exist($activation_name,$screen_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_activation', array(//making selection
	            'name' => $activation_name, 'id_screen' => $screen_id
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return NULL; // activation is not exist in table
	        } else {
	        	return $query->row()->id; // activation is already exist in table
	        }
		}
		
		function get_num_same_screen($screen_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_activation', array(//making selection
	            'id_screen' => $screen_id
	        ));

	        return $query->num_rows();
		}

		function get_screen_id($activation_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_activation', array(//making selection
	            'id' => $activation_id
	        ));

	        return $query->row()->id_screen;
		}
	}
?>