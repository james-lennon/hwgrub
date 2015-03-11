<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trips extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("trips_model");
		date_default_timezone_set('America/Los_Angeles');
	}

	public function get_all_trips() {
		check_auth();
		$trips = $this->trips_model->get_all_trips();
		echo json_encode(array("trips"=>$trips));
	}

	public function get_all_active_trips() {
		check_auth();
		$trips = $this->trips_model->get_all_active_trips();
		echo json_encode(array("trips"=>$trips));
	}

	public function get_active_trips_content(){
		$user_id = check_auth();
		$this->load->model("orders_model");

		date_default_timezone_set('America/Los_Angeles');
		$trips = $this->trips_model->get_all_active_trips();
		$orders = array();
		for ($i=0; $i<count($trips); $i++) {
			$orders[$i] = $this->orders_model->get_trip_orders($trips[$i]->trip_id);
		}
		$this->load->view("content/trips_list", array("trips"=>$trips, "orders"=>$orders, "user_id"=>$user_id));
	}

	public function get_user_trips() {
		$user_id = check_auth();
		$trips = $this->trips_model->get_user_trips($user_id);
		echo json_encode(array("trips"=>$trips));
	}

	public function get_user_active_trips() {
		$user_id = check_auth();
		$trips = $this->trips_model->get_user_active_trips($user_id);
		echo json_encode(array("trips"=>$trips));
	}

	public function get_user_trips_content(){
		$user_id = check_auth();
		$this->load->model("orders_model");

		$trips = $this->trips_model->get_user_trips($user_id);
		$orders = array();
		for ($i=0; $i<count($trips); $i++) {
			$orders[$i] = $this->orders_model->get_trip_orders($trips[$i]->trip_id);
		}
		$this->load->view("content/trips_list", array("trips"=>$trips, "orders"=>$orders, "my_trip"=>TRUE));
	}

	public function delete_trip() {
		check_auth();
		$trip_id = $this->input->post("trip_id");
		if(!$trip_id){
			echo json_encode(array("error"=>"No trip id given"));
			exit();
		}
		$this->trips_model->delete_trip($trip_id);
		echo json_encode(array("success"=>1));
	}

	private function filter_time($time){
		$elapsed_time = $time - strtotime("midnight");
		$hours = $elapsed_time / (3600);
		if($hours < 7){
			return $time + 12*3600;
		}
		return $time;
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
		$this->trips_model->add_trip($driver_id, $this->filter_time($expiration), $this->filter_time($eta), $restaurant);
		echo json_encode(array("success"=>1));
	}
}