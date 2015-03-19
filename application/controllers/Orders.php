<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model("orders_model");
	}

	public function place_order() {
		$customer_id = check_auth();
		$order_text = $this->input->post("order_text");
		$fee = $this->input->post("fee");
		$trip_id = $this->input->post("trip_id");

		if(!$order_text) {
			echo json_encode(array("error"=>"No order text given"));
			exit();
		}
		else if (!(($fee!==FALSE) && $trip_id)) {
			echo json_encode(array("error"=>"No fee or trip id given"));
			exit();
		}
		
		$this->orders_model->place_order($trip_id, $order_text, $customer_id, $fee);
		echo json_encode(array("success"=>1));
	}

	public function accept_order () {
		check_auth();
		$order_id = $this->input->post("order_id");
		$state = 1;
		if (!$order_id) {
			echo json_encode(array("error"=>"No order id given"));
			exit();
		}
		$this->orders_model->update_order_status($order_id, $state);
		echo json_encode(array("success"=>1));
	}

	public function reject_order () {
		check_auth();
		$order_id = $this->input->post("order_id");
		$state = 2;
		if (!$order_id) {
			echo json_encode(array("error"=>"No order id given"));
			exit();
		}

		$this->orders_model->update_order_status($order_id, $state);
		echo json_encode(array("success"=>1));
	}

	public function get_trip_orders() {
		check_auth();
		$trip_id = $this->input->post("trip_id");
		if (!$trip_id) {
			echo json_encode(array("error"=>"No trip id given"));
			exit();
		}

		$orders = $this->orders_model->get_trip_orders($trip_id);
		echo json_encode(array("orders"=>$orders));
	}

	public function get_num_trip_orders()
	{
		check_auth(); 
		$trip_id = $this->input->post("trip_id");
		if (!$trip_id) {
			echo json_encode(array("error"=>"No trip id given"));
			exit();
		}
		$numOrders = $this->orders_model->get_num_trip_orders($trip_id); 
		echo json_encode($numOrders); 
	}

	public function get_order() {
		check_auth();
		$order_id = $this->input->post("order_id");
		if (!$order) {
			echo json_encode(array("error"=>"No order id given"));
			exit();
		}

		$order = $this->order_model->get_order($order_id);
		echo json_encode($order);
	}

	public function get_orders_content(){
		$user_id = check_auth();

		$orders = $this->orders_model->get_all_customer_orders($user_id);
		$this->load->view("content/trip_orders", array("orders"=>$orders));
	}

	public function get_all_active_orders() {
		check_auth();

		$orders = $this->orders_model->get_all_active_orders();
		echo json_encode($orders);
	}

	public function get_all_pending_orders() {
		check_auth();

		$orders = $this->orders_model->get_all_pending_orders();
		echo json_encode($orders);
	}

	public function get_active_customer_orders() {
		check_auth();
		$customer_id = $this->input->post("customer_id");
		if (!$customer_id) {
			echo json_encode(array("error"=>"No customer id given"));
			exit();
		}

		$orders = $this->orders_model->get_active_customer_orders($customer_id);
		echo json_encode($orders);
	}

	public function get_all_customer_orders()
	{
		$customer_id = check_auth(); 
		if (!$customer_id){
			echo json_encode(array("error"=>"No customer id given."));
			exit();
		}
		$active = $this->orders_model->get_active_customer_orders($customer_id); 
		$inactive = $this->orders_model->get_inactive_customer_orders($customer_id); 
		echo json_encode(array("active_orders"=>$active, "inactive_orders"=>$inactive));
	}

}
