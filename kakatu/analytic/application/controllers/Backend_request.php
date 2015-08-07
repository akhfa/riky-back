<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class is managing POST request from backend server
 * which contains user id and analyze the user's activity
 * 
 * @author Cilvia Sianora Putri
 */
class Backend_request extends CI_Controller {

	/**
	 * @details get json from POST request and analyze the user's activity
	 * 			then return response which contains user's analytic result
	 */
	public function get_json(){
		// read json input
		$data_back = json_decode(file_get_contents('php://input'));

		// analysis the user's activity
		$response = $this->analysis($data_back);
		 // $response = $this->stubBuatIndam();
		
		// set header as json
		header("Content-type: application/json");
		 
		// send response
		echo json_encode($response);
	}

	public function get_json_data($json_data, $key_var){
		// retrieve data
		$key = $this->get_key($key_var);
		return $json_data->{$key};
	}

	public function get_key($key_var){
		$ini_array = parse_ini_file(JSON_DATA_FILE);
		return $ini_array[$key_var];
	}

	/**
	 * @details get all categories from database
	 * 
	 * @return array of categories
	 */
	function get_all_categories(){
		$this->load->model('Dict_category');
		return $this->Dict_category->get_dict_category();
	}

	/**
	 * @details get all record of user's activation activity from database
	 * 
	 * @param varchar $userid The id of user
	 * @return array of user's activation activity record
	 */
	function get_user_activations($userid){
		$this->load->model('User_activation');
		return $this->User_activation->check_user_exist($userid);
	}

	/**
	 * @details get category id of screen
	 * 
	 * @param varchar $screen_id The id of screen
	 * @return category id
	 */
	function get_category_id($screen_id){
		$this->load->model('Dict_screen');
		return $this->Dict_screen->get_category($screen_id);
	}

	function get_screen_id($activation_id){
		$this->load->model('Dict_activation');
		return $this->Dict_activation->get_screen_id($activation_id);
	}

	function get_category_id_from_activation($activation_id){
		// get screen id of activation
		$screen_id = $this->get_screen_id($activation_id);

		// get category id of screen
		return $this->get_category_id($screen_id);
	}

	/**
	 * @details get number of activations which has the same category
	 * @return number of activations
	 */
	function get_number_activations($category_id){
		// get list of screens which has the same category
		$this->load->model('Dict_screen');
		$screens = $this->Dict_screen->get_screens_same_category($category_id);

		$num_activations = 0;

		// count number of activations for each screen
		$this->load->model('Dict_activation');
		foreach ($screens as $screen) {
			$num_activations += $this->Dict_activation->get_num_same_screen($screen->id);
		}
		
		return $num_activations;
	}

	/**
	 * @brief analyze the user's activity
	 * 
	 * @param $data json containing user id
	 * @return user's analytic result in json format
	 */
	function analysis($data){
		// get the user id from json
		$userid = $this->get_json_data($data, "json_user_id");

		if($userid === NULL){ // there is no user id in json
			$response = array("status" => RESPONSE_FAIL, "description" => "Wrong json format");
		} else { // there is user id in json
			// prepare array of categories
			$dict_categories = $this->get_all_categories();
			
			// check whether user has activation activity before or not
			$user_activations = $this->get_user_activations($userid); // get all record

			if($user_activations === NULL){ // user doesn't have activity before
				foreach ($dict_categories as $category) {
					$result[] = array("category" => $category->kategori, "level" => "beginner");
				}
			} else { // user has activity before
				// initialization count of activation to 0
				foreach ($dict_categories as $category) {
					$categories[] = array("activation_count" => 0);
				}
				
				// counting data to be used in analysis
				foreach ($user_activations as $data_activation) {
					// get activation id
					$activation_id = $data_activation->id_activation;
					
					// get category id of activation
					$category_id = $this->get_category_id_from_activation($activation_id);
					
					// get number of activation who has the same screen
					$num_activations = $this->get_number_activations($category_id);
					
					// search category array id
					$array_id = $this->search_category($dict_categories,$category_id);
					
					// insert into array categories
					$categories[$array_id]['activation_count'] = $data_activation->count + $categories[$array_id]['activation_count'];
					$categories[$array_id]['num_activations'] = $num_activations;
				}

				// analysis for each category
				$i = 0;
				foreach($categories as $category){
					// analysis
					if ($category['activation_count'] === 0){ // there is no activation activity
						$result_level = "beginner";
					} else {
						$result_level = $this->analysis_rule($category['activation_count'], $category['num_activations']);
					}
					
					// get the category name
					$category_name = $dict_categories[$i]->kategori;
					
					// put the result
					$result[] = array("category" => $category_name, "level" => $result_level);	

					$i++;			
				}

			}

			$response = array("status" => RESPONSE_SUCCESS, "userid" => $userid, "categories" => $result);
		}
		return $response;
	}

	/**
	 * @details search the array id of category is array of categories 
	 * 			based on category id
	 * 
	 * @param $categories Array of categories
	 * @param $category_id The id of category to be searched
	 * @return The array id of category
	 */
	function search_category($categories, $category_id){
		$i = 0;
		$found = FALSE;
		$num_category = count($categories);
		
		while($found === FALSE && $i < $num_category){
	       if ($categories[$i]->id === $category_id) {
	           $found = TRUE;
	       } else {
	       		$i++;
	       }
	   }

	   if($found === TRUE){
	   		return $i;
	   } else {
	   		return NULL;
	   }
	}

	/**
	 * @details analyze the user's behaviour based on activation activity
	 * 
	 * @param activation_count: count of activation used in user's activity
	 * @param num_activation: number of activation in the same screen
	 * @return intermediate if count of activation is 1 to threshold inclusive for each activation in the same screen
	 * 			advanced if count of activation is greater than threshold for each activation in the same screen
	 */
	function analysis_rule($activation_count,$num_activations){
		if($activation_count <= ($num_activations * THRESHOLD_ANALYSIS)){
			return "intermediate";
		}
		return "advanced";
	}
}
