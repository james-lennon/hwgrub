<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ratings extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model("orders_model");
	}

	public function get_ratings()
	{	
		check_auth();
		$user_id = $this->input->post("user_id");
		$type = $this->input->post("type"); 

		if(!($user_id && $type)){
			echo json_encode(array("error"=>"No user id or type."));
			exit();
		}

		$this->ratings_model->get_ratings($user_id, $type); 
	}

	public function add_rating() {
		check_auth();
		$user_id = $this->input->post("user_id");
		$type = $this->input->post("type"); 
		$rating_text = $this->input->post("rating_text");
		$value = $this->input->post("rating_value");

		if(!($user_id && $type && $rating_text && $value)){
			echo json_encode(array("error"=>"No user id, type, rating text, or value."));
			exit();
		}

		$this->ratings_model->add_rating($user_id, $rating_text, $type, $value); 
	}