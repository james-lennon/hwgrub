<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trips extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("trips_model");
	}

	public function get_all_trips() {
		$trips = $this->trips_model->get_all_trips();
		echo json_encode($trips);
	}

	public function get_active_trips() {
		$trips = $this->trips_model->get_active_trips();
		echo json_encode($trips);
	}

	public function get_user_trips($user_id) {
		$trips = $this->trips_model->get_user_trips($driver_id);
		echo json_encode($trips);
	}

	public function add_trip() {}

	}

}