<?php 
	class User_data extends CI_Model {

		function __construct(){
			parent::__construct();
		}

		function get_user_data(){
			$query = $this->db->query('SELECT * from user_data');
			return $query->result();	
		}		

		function check_and_add($userid){
			// check whether user already exist or not in database
			$user_exist = $this->check_user_exist($userid);
			
			// insert userid to database if not exist
			if($user_exist === FALSE){
				$this->insert_user($userid);	
			}
		}

		function insert_user($userid){
			$data = array(
			        'id_user' => $userid,
			);

			$this->db->insert('user_data', $data);
		}
		
		function check_user_exist($userid){
			$query = null; //emptying in case 

	        $query = $this->db->get_where('user_data', array(//making selection
	            'id_user' => $userid
	        ));

	        if ($query->num_rows() === 0) {
	            return FALSE; // screen is not exist in table
	        } else {
	        	return TRUE; // screen is already exist in table
	        }
		}
	}
?>