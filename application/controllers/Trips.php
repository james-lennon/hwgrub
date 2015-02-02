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
		$trips = $this->trips_model->get_user_trips($driver_id);
		echo json_encode($trips);
	}

	public function get_user_active_trips($user_id) {
		check_auth();
		$trips = $this->trips_model->get_user_active_trips($driver_id);
		echo json_encode($trips);
	}

	public function add_trip($driver_id, $expiration, $eta, $restaurant) {
		check_auth();
		$this->trips_model->add_trip($driver_id, $expiration, $eta, $restaurant);
	}

}