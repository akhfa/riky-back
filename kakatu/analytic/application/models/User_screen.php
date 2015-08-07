<?php 
	class User_screen extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_user_screen(){
			$query = $this->db->get('user_screen');
			return $query->result();
		}

		function check_exist($userid,$screen_id){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('user_screen', array(//making selection
	            'id_user' => $userid, 'id_screen' => $screen_id
	        ));

	        //counting result from query
	        if ($query->num_rows() === 0) {
	            return FALSE; //  doesn't exist in table
	        } else {
	        	return TRUE; // already exist in table
	        }
		}

		function update_data($userid,$screen_id){
			$data_exist = $this->check_exist($userid,$screen_id);
			if($data_exist === FALSE){
				$this->insert_user_screen($userid,$screen_id);
			} else {
				$this->update_user_screen($userid,$screen_id);
			}
		}

		function insert_user_screen($userid,$screen_id){
			$data = array(
			   'id_user' => $userid ,
			   'id_screen' => $screen_id ,
			   'count' => 1
			);

			$this->db->insert('user_screen', $data); 
		}

		function update_user_screen($userid,$screen_id){
			//echo 'update user screen </br>';
			// get the count of screen
			$screen_count = $this->db->get_where('user_screen', array('id_user' => $userid, 'id_screen' => $screen_id));
			$screen_count = $screen_count->row()->count;
			// increment the count
			$screen_count++;

			// update the table
			$this->db->update('user_screen', array('count' => $screen_count), array('id_user' => $userid, 'id_screen' => $screen_id)); 
		}
		
	}
?>