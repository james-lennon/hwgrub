<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trips extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("trips_model");
	}

	public function get_all_trips() {
		check_auth();
		$trips = $this->trips_model->get_all_trips();
		echo json_encode($trips);
	}

	public function get_all_active_trips() {
		check_auth();
		$trips = $this->trips_model->get_all_active_trips();
		echo json_encode($trips);
	}

	public function get_user_trips($user_id) {
		check_auth();
		$trips = $this->trips_model->get_user_trips($user_id);
		echo json_encode($trips);
	}

	public function get_user_active_trips($user_id) {
		check_auth();
		$trips = $this->trips_model->get_user_active_trips($user_id);
		echo json_encode($trips);
	}

	public function add_trip() {
		$driver_id = check_auth();
		$expiration = $this->input->post("expiration");
		$eta = $this->input->post("eta");
		$restaurant = $this->input->post("restaurant");
		if(!($expiration && $eta && $restaurant)){
			echo json_encode(array("error"=>"No expiration, eta, or restaurant given"));
			exit();
		}
		$this->trips_model->add_trip($driver_id, $expiration, $eta, $restaurant);
	}

}