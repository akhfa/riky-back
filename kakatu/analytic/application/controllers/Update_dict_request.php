<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class is managing POST request from admin Kakatu
 * which contains data for updating dictionary in database
 * 
 * @author Cilvia Sianora Putri
 */
class Update_dict_request extends CI_Controller {

	/**
	 * @details get json from POST request and update the data in database
	 * 			then return response based on the process of updating database
	 */
	public function get_json(){
		// read json input
		$data_back = json_decode(file_get_contents('php://input'));

		// insert into database
		$response = $this->update_dictionary($data_back);
		
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
		if($response === NULL){
			if($description === NULL){
				return array("status" => $status);
			}
			$descriptions[] = array("description" => $description);
			return array("status" => $status, "descriptions" => $descriptions);		
		}
		$response['descriptions'][] = array("description" => $description);
		return $response;
	}

	/**
	 * @brief update data of category's dictionary in database
	 * @details check whether category name input exist or not in database.
	 * 			if doesn't exist, insert category name and category id input as a new record
	 * 			if exist, return category id of the category name in database
	 * 			
	 * @param varchar $category_id The id of category
	 * @param varchar $category_name The name of category
	 * @return category id of category name input in database
	 */
	function update_category($category_id, $category_name){
		$this->load->model('Dict_category');
		return $this->Dict_category->check_and_add($category_id, $category_name);
	}

	/**
	 * @brief update data of screen's dictionary in database
	 * @details check whether screen name with category id input exist or not in database.
	 * 			if doesn't exist, insert screen name, screen id, and category id input as a new record
	 * 			if exist, return screen id of the screen name in database
	 * 			
	 * @param varchar $screen_id The id of screen
	 * @param varchar $screen_name The name of screen
	 * @return screen id of screen name input in database
	 */
	function update_screen($screen_id, $screen_name, $category_id){
		$this->load->model('Dict_screen');
		return $this->Dict_screen->check_and_add($screen_id, $screen_name, $category_id);
	}

	/**
	 * @brief update data of properties's dictionary in database
	 * @details check whether property name with screen id input exist or not in database.
	 * 			if doesn't exist, insert property name, property id, and screen id input as a new record
	 * 			if exist, return property id of the property name in database
	 * 			
	 * @param varchar $property_id The id of property
	 * @param varchar $property_name The name of property
	 * @return property id of property name input in database
	 */
	function update_properties($property_id, $property_name, $screen_id){
		$this->load->model('Dict_properties');
		return $this->Dict_properties->check_and_add($property_id, $property_name, $screen_id);
	}

	/**
	 * @brief update data of activation's dictionary in database
	 * @details check whether activation name with screen id input exist or not in database.
	 * 			if doesn't exist, insert activation name, activation id, and screen id input as a new record
	 * 			if exist, return activation id of the activation name in database
	 * 			
	 * @param varchar $activation_id The id of activation
	 * @param varchar $activation_name The name of activation
	 * @return activation id of activation name input in database
	 */
	function update_activation($activation_id, $activation_name, $screen_id){
		$this->load->model('Dict_activation');
		return $this->Dict_activation->check_and_add($activation_id, $activation_name, $screen_id);
	}

	/**
	 * @details update the dictionary in database with data input
	 * 
	 * @param $data new data dictionary in json format
	 * @return response in json format based on process of udpating database
	 */
	function update_dictionary($data){
		$response = NULL;
		$process_failed = FALSE;

		// get list of features from json
		$features = $this->get_json_data($data, "json_feature"); 
		
		if($features !== NULL){
			$i = 0;
			foreach ($features as $feature){
				// get category data from json
				$category_id = $this->get_json_data($feature, "json_feature_id");
				$category_name = $this->get_json_data($feature, "json_feature_name");
				
				// update category in database
				$category_id_res = $this->update_category($category_id, $category_name);

				if($category_id !== $category_id_res){ // category id input doesn't match with the one in database
					$process_failed = TRUE;
					$response = $this->construct_response($response, RESPONSE_FAIL, 
						"Id input " . $category_id . " doesn't matched with database " . $category_id_res . " at feature:" . $i);
				} else { // category id matched
					// get list of screens from json
					$screens = $this->get_json_data($feature, "json_screen"); 
					if ($screens !== NULL){
						$j = 0;
						foreach ($screens as $screen){
							// get screen data from json
							$screen_id = $this->get_json_data($screen, "json_screen_id");
							$screen_name = $this->get_json_data($screen, "json_screen_name");

							// update screen in database
							$screen_id_res = $this->update_screen($screen_id, $screen_name, $category_id);

							if($screen_id !== $screen_id_res){ // screen id input doesn't match with the one in database
								$process_failed = TRUE;
								$response = $this->construct_response($response, RESPONSE_FAIL,  
									"Id input " . $screen_id . " doesn't matched with database " . $screen_id_res . 
									" at feature:" . $i . " screen:" . $j);
							} else { //screen id matched

								/** Manage the properties **/

								// get list of properties from json
								$properties = $this->get_json_data($screen, "json_properties"); 

								if($properties !== NULL){
									$k = 0;
									foreach ($properties as $property){
										// get properties data from json
										$property_id = $this->get_json_data($property, "json_properties_id");
										$property_name = $this->get_json_data($property, "json_properties_name");
										
										// update properties in database
										$property_id_res = $this->update_properties($property_id, $property_name, $screen_id);

										if($property_id !== $property_id_res){
											$process_failed = TRUE;
											$response = $this->construct_response($response, RESPONSE_FAIL, 
												"Id input " . $property_id . " doesn't matched with database " . $property_id_res . 
												" at feature:" . $i . " screen:" . $j . " property:" . $k);
										}

										$k++;
									}
								}

								/** Manage the activation **/

								// get list of properties from json
								$activations = $this->get_json_data($screen, "json_activation"); 

								if($activations !== NULL){
									$k = 0;
									foreach($activations as $activation){
										// get activation data from json
										$activation_id = $this->get_json_data($activation, "json_activation_id");
										$activation_name = $this->get_json_data($activation, "json_activation_name");

										// update activation in database
										$activation_id_res = $this->update_activation($activation_id, $activation_name, $screen_id);

										if($activation_id !== $activation_id_res){
											$process_failed = TRUE;
											$response = $this->construct_response($response, RESPONSE_FAIL, 
												"Id input " . $activation_id . " doesn't matched with database " . $activation_id_res . 
												" at feature:" . $i . " screen:" . $j . " activation:" . $k);
										}

										$k++;
									}
								}
							}

							$j++;
						}
					}
				}

				$i++;
			}	
		}

		if($process_failed === FALSE){ // there is no failed process at all
			$response = $this->construct_response(NULL, RESPONSE_SUCCESS, NULL);
		}

		return $response;
	}

}

