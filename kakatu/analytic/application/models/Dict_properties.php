<?php 
	class Dict_properties extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_dict_properties(){
			$query = $this->db->get('dict_properties');
			return $query->result();
		}

		function check_and_add($property_id, $property_name,$screen_id){
			$property_id_res = $this->check_property_exist($property_name, $screen_id);
			if($property_id_res === NULL){
				$this->insert_property($property_id, $property_name, $screen_id);
				return $property_id;
			}
			return $property_id_res;
		}

		function insert_property($property_id, $property_name, $screen_id){
			$data = array(
			   'id' => $property_id ,
			   'name' => $property_name ,
			   'id_screen' => $screen_id
			);

			$this->db->insert('dict_properties', $data);
		}

		function check_property_exist($property_name,$screen_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('dict_properties', array(//making selection
	            'name' => $property_name, 'id_screen' => $screen_id
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return NULL; // property is not exist in table
	        } else {
	        	return $query->row()->id; // property is already exist in table
	        }
		}
		
	}
?>