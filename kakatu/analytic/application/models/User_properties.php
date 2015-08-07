<?php 
	class User_properties extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_user_properties(){
			$query = $this->db->get('user_properties');
			return $query->result();
		}

		function check_exist($userid,$property_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('user_properties', array(//making selection
	            'id_user' => $userid, 'id_properties' => $property_id
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return FALSE; //  doesn't exist in table
	        } else {
	        	return TRUE; // already exist in table
	        }
		}

		function update_data($userid,$property_id){
			$data_exist = $this->check_exist($userid,$property_id);
			if($data_exist === FALSE){
				$this->insert_user_property($userid,$property_id);
			} else {
				$this->update_user_property($userid,$property_id);
			}
		}

		function insert_user_property($userid,$property_id){
			$data = array(
			   'id_user' => $userid ,
			   'id_properties' => $property_id ,
			   'count' => 1
			);

			$this->db->insert('user_properties', $data); 
		}

		function update_user_property($userid,$property_id){
			// get the count of property
			//$this->db->select('count');
			$property_count = $this->db->get_where('user_properties', array('id_user' => $userid, 'id_properties' => $property_id));
			$property_count = $property_count->row()->count;
			// increment the count
			$property_count++;

			// update the table
			$this->db->update('user_properties', array('count' => $property_count), array('id_user' => $userid, 'id_properties' => $property_id)); 
		}
		
	}
?>