<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class is managing POST request from android client Kakatu
 * which contains user activity
 * 
 * @author Cilvia Sianora Putri
 */
class Client_request extends CI_Controller {

	/**
	 * @details get json from POST request and insert the data to database
	 * 			then return response based on the process of inserting to database
	 */
	public function get_json(){
		// read json input
		$data_back = json_decode(file_get_contents('php://input'));

		// insert into database
		$response = $this->insert_user_activity($data_back);
		
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
	 * @details construct response in json format
	 * 
	 * @param string const $status 	: failed/success status
	 * @param string $description 	: response description or empty if NULL
	 * 
	 * @return response in json format
	 */
	public function construct_response($response, $status, $description){
		if($response === NULL){ // there is no response created before
			if($description === NULL){ // there is no description
				return array("status" => $status);
			}
			// else: there is description
			$descriptions[] = array("description" => $description);
			return array("status" => $status, "descriptions" => $descriptions);		
		}
		// else: there is response created before
		$response['descriptions'][] = array("description" => $description);
		return $response;
	}

	/**
	 * @details get category id in database based on category name,
	 * 			 check first whether category name exist or not
	 * 
	 * @param string $category_name The name of category
	 * @return category id or NULL if not exist
	 */
	function get_category($category_name){
		$this->load->model('Dict_category');
		return $this->Dict_category->check_category_exist($category_name);
	}

	/**
	 * @details get screen id in database based on screen name and category id,
	 * 			 check first whether exist or not
	 * 
	 * @param string $screen_name The name of screen
	 * @param varchar $category_id The id of category of screen
	 * @return screen id or NULL if not exist
	 */
	function get_screen($screen_name, $category_id){
		$this->load->model('Dict_screen');
		return $this->Dict_screen->check_screen_exist($screen_name, $category_id);
	}

	/**
	 * @details get property id in database based on property name and screen id,
	 * 			 check first whether exist or not
	 * 
	 * @param string $property_name The name of property
	 * @param varchar $screen_id The id of screen of property
	 * @return property id or NULL if not exist
	 */
	function get_property($property_name, $screen_id){
		$this->load->model('Dict_properties');
		return $this->Dict_properties->check_property_exist($property_name, $screen_id);
	}

	/**
	 * @details get activation id in database based on activation name and screen id,
	 * 			 check first whether exist or not
	 * 
	 * @param string $activation_name The name of activation
	 * @param varchar $screen_id The id of screen of activation
	 * @return activation id or NULL if not exist
	 */
	function get_activation($activation_name, $screen_id){
		$this->load->model('Dict_activation');
		return $this->Dict_category->check_category_exist($activation_name, $screen_id);
	}

	/**
	 * @details check whether user id input exist or not in database.
	 * 			if doesn't exist, insert the user id as a new record
	 * 
	 * @param varchar $userid The id of user
	 */
	function update_user($userid){
		$this->load->model('User_data');
		$this->User_data->check_and_add($userid);
	}

	/**
	 * @brief update the statistic of user's screen activity in database
	 * @details check whether user id with screen id exist or not in database.
	 * 			if doesn't exist, insert the user id with screen id as a new record
	 * 			if exist, update the record
	 * 
	 * @param varchar $userid The id of user
	 * @param varchar $screen_id The id of screen
	 */
	function update_user_screen($userid, $screen_id){
		$this->load->model('User_screen');
		$this->User_screen->update_data($userid, $screen_id);
	}

	/**
	 * @brief update the statistic of user's properties activity in database
	 * @details check whether user id with property id exist or not in database.
	 * 			if doesn't exist, insert the user id with property id as a new record
	 * 			if exist, update the record
	 * 
	 * @param varchar $userid The id of user
	 * @param varchar $property_id The id of screen
	 */
	function update_user_properties($userid, $property_id){
		$this->load->model('User_properties');
		$this->User_screen->update_data($userid, $property_id);
	}

	/**
	 * @brief update the statistic of user's activation activity in database
	 * @details check whether user id with activation id exist or not in database.
	 * 			if doesn't exist, insert the user id with activation id as a new record
	 * 			if exist, update the record
	 * 
	 * @param varchar $userid The id of user
	 * @param varchar $activation_id The id of screen
	 */
	function update_user_activation($userid, $activation_id){
		$this->load->model('User_activation');
		$this->User_screen->update_data($userid, $activation_id);
	}

	/**
	 * @details put the data of user's activity from json to database
	 * 
	 * @param $data user's data activity in json format
	 * @return response in json format based on process of inserting to database
	 */
	function insert_user_activity($data){
		// get user data from json
		$userid = $this->get_json_data($data, "json_user_id");
		
		if($userid === NULL){ // there is no userid in json
			$response = $this->construct_response(NULL, RESPONSE_FAIL, "Wrong JSON format");
		} else {
			// update userid in database
			$this->update_user($userid);
			
			// get list of features from json
			$features = $this->get_json_data($data, "json_feature"); 
			
			$i = 0;
			$response = NULL;
			foreach ($features as $feature){
				// get category data from json
				$category_name = $this->get_json_data($feature, "json_feature_name");
				
				// get category id from database
				$category_id = $this->get_category($category_name);

				if($category_id === NULL){ // category doesn't exist
					$process_failed = TRUE;
					$response = $this->construct_response($response, RESPONSE_FAIL, "An error at feature:" . $i);
				} else { // category exists
					// get list of screens from json
					$screens = $this->get_json_data($feature, "json_screen"); 

					$j = 0;
					foreach ($screens as $screen){
						// get screen data from json
						$screen_name = $this->get_json_data($screen, "json_screen_name");

						// get screen id from database
						$screen_id = $this->get_screen($screen_name,$category_id);

						if($screen_id === NULL){ // screen doesn't exist
							$process_failed = TRUE;
							$response = $this->construct_response($response, RESPONSE_FAIL,  
								"An error at feature:" . $i . 
								" screen:" . $j);
						} else { //screen exist
							// update user screen activity in database
							$this->update_user_screen($userid, $screen_id);

							// get list of properties from json
							$properties = $this->get_json_data($screen, "json_properties"); 

							$k = 0;
							foreach ($properties as $property){
								// get property data from json
								$property_name = $this->get_json_data($property, "json_properties_name");

								// get property id from database
								$property_id = $this->get_property($property_name,$screen_id);

								if($property_id === NULL){ // property doesn't exist in database
									$process_failed = TRUE;
									$response = $this->construct_response($response, RESPONSE_FAIL, 
										"An error at feature:" . $i . 
										" screen:" . $j .
										" property:" . $k);
								} else { // property exist in database
									// update user properties activity in database
									$this->update_user_properties($userid, $property_id);
								}

								$k++;
							}

							// get list of properties from json
							$activations = $this->get_json_data($screen, "json_activation"); 
							
							$k = 0;
							foreach($activations as $activation){
								// get activation data from json
								$activation_name = $this->get_json_data($activation, "json_activation_name");
								
								// get activation id from database
								$activation_id = $this->get_activation($activation_name,$screen_id);

								if($activation_id === NULL){ // activation doesn't exist in database
									$process_failed = TRUE;
									$response = $this->construct_response($response, RESPONSE_FAIL, 
										"An error at feature:" . $i . 
										" screen:" . $j .
										" activation:" . $k);
								} else { // activation exist in database
									// update user properties activity in database
									$this->update_user_activation($userid, $activation_id);
								}

								$k++;
							}

						}

						$j++;
					}
				}

				$i++;
			}

			if($process_failed === FALSE){ // there is no failed process at all
				$response = $this->construct_response(NULL, RESPONSE_SUCCESS, NULL);
			}
		}

		return $response;
	}

}

