<?php 
	class User_activation extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_user_activation(){
			$query = $this->db->get('user_activation');
			return $query->result();	
		}

		function check_user_exist($userid){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('user_activation', array(//making selection
	            'id_user' => $userid
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return NULL; //  doesn't exist in table
	        } else {
	        	return $query->result(); // already exist in table
	        }
		}

		function check_exist($userid,$activation_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('user_activation', array(//making selection
	            'id_user' => $userid, 'id_activation' => $activation_id
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return FALSE; //  doesn't exist in table
	        } else {
	        	return TRUE; // already exist in table
	        }
		}

		function update_data($userid,$activation_id){
			$data_exist = $this->check_exist($userid,$activation_id);
			if($data_exist === FALSE){
				$this->insert_user_activation($userid,$activation_id);
			} else {
				$this->update_user_activation($userid,$activation_id);
			}
		}

		function insert_user_activation($userid,$activation_id){
			$data = array(
			   'id_user' => $userid ,
			   'id_activation' => $activation_id ,
			   'count' => 1
			);

			$this->db->insert('user_activation', $data); 
		}

		function update_user_activation($userid,$activation_id){
			// get the count of activation
			//$this->db->select('count');
			$activation_count = $this->db->get_where('user_activation', array('id_user' => $userid, 'id_activation' => $activation_id));
			$activation_count = $activation_count->row()->count;
			// increment the count
			$activation_count++;

			// update the table
			$this->db->update('user_activation', array('count' => $activation_count), array('id_user' => $userid, 'id_activation' => $activation_id)); 
		}
		
	}
?>