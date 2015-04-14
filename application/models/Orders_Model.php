<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_Model extends CI_Model{
	// ORDER STATES: 0 = pending, 1 = accepted, 2 = rejected, 3 = completed

	public function __construct(){
			parent::__construct();
			$this->load->database();
		}

	public function get_order($order_id)
	{
		$query = $this->db->query('
			SELECT 
				orders.trip_id, 
				orders.order_text, 
				orders.customer_id, 
				orders.state, 
				orders.fee,
				users.first_name,
				users.last_name,
				users.img_url
			 FROM 
			 	orders,
			 	users
			 WHERE 
			 	orders.order_id = ?      AND
			 	users.user_id = orders.customer_id', 
			 	array($order_id));

		if ($query->num_rows() == 0)
		{
			return FALSE;
		}

		return $query->row(); 
	}

	public function place_order($trip_id, $order_text, $customer_id, $fee)
	{
		$data = array(
		"trip_id"=> $trip_id, 
		"order_text"=>$order_text, 
		"customer_id"=>$customer_id, 
		"state"=> '0', 
		"fee"=>$fee,);

		$this->db->insert('orders', $data);
	}

	public function update_order_status ($order_id, $state)
	{
		
		$query = $this->db->query('SELECT * FROM orders WHERE orders.order_id =?', array($order_id));
		if ($query->num_rows() == 0){
			return FALSE;
		}
		
		$this->db->query('
		UPDATE
			 orders 
		SET 
			orders.state = ? 
		WHERE 
			orders.order_id = ?

			', array($state, $order_id));
	}

	public function get_pending_trip_orders($trip_id) 
	{
		$query = $this->db->query('
			SELECT 
				orders.order_id,
				orders.order_text, 
				orders.customer_id, 
				orders.state, 
				orders.fee,  
				users.first_name,
				users.last_name,
				users.img_url
			FROM 
				orders,
				users
			WHERE 
				orders.trip_id = ?   AND
				users.user_id = orders.customer_id AND
				orders.state = ?
			ORDER BY
				orders.fee DESC'
				, 
			array($trip_id, 0));

		return $query->result();
	}

	public function get_accepted_trip_orders($trip_id) 
	{
		$query = $this->db->query('
			SELECT 
				orders.order_id,
				orders.order_text, 
				orders.customer_id, 
				orders.state, 
				orders.fee,  
				users.first_name,
				users.last_name,
				users.img_url
			FROM 
				orders,
				users
			WHERE 
				orders.trip_id = ?   AND
				users.user_id = orders.customer_id AND
				orders.state = ?
			ORDER BY
				orders.fee DESC'
				, 
			array($trip_id, 1));

		return $query->result();
	}


	public function get_rejected_trip_orders($trip_id) 
	{
		$query = $this->db->query('
			SELECT 
				orders.order_id,
				orders.order_text, 
				orders.customer_id, 
				orders.state, 
				orders.fee,  
				users.first_name,
				users.last_name,
				users.img_url
			FROM 
				orders,
				users
			WHERE 
				orders.trip_id = ?   AND
				users.user_id = orders.customer_id AND
				orders.state = ?
			ORDER BY
				orders.fee DESC'
				, 
			array($trip_id, 2));

		return $query->result();
	}

	public function get_num_trip_orders($trip_id)
	{
		$orders = $this->get_trip_orders($trip_id); 
		$numOrders = count($orders); 
		return $numOrders; 
	}

	public function get_all_customer_orders($customer_id)
	{
		$query = $this->db->query('
		SELECT
			orders.order_id,
			orders.trip_id, 
			orders.customer_id,
			orders.order_text,  
			orders.state,
			orders.fee, 
			trips.restaurant_name,
			users.first_name,
			users.last_name,
			users.img_url
		FROM 
			orders,
			users
		WHERE 
			orders.customer_id = ?    AND
			users.user_id = orders.customer_id AND
			trips.trip_id = orders.trip_id

			', array($customer_id));

		return $query->result(); 
	}

	public function get_accepted_customer_orders($customer_id)
	{
	 	return $this->get_customer_orders_of_state($customer_id, 1); 
	}

	public function get_pending_customer_orders($customer_id)
	{
		return $this->get_customer_orders_of_state($customer_id, 0); 
	}

	//Purposely combined completed and rejectd so that they're one section in the app, and it sorts them

/*
	public function get_rejected_customer_orders($customer_id)
	{
		return $this->get_customer_orders_of_state($customer_id, 2); 
	}

*/

	public function get_completed_customer_orders($customer_id)
	{
		$completedAccepted = $this->get_customer_orders_of_state($customer_id, 3); 
		$rejected = $this->get_customer_orders_of_state($customer_id, 2); 

		$final = array_merge($completedAccepted, $rejected);
		usort($final, function($a,$b) 
		{
			return $a['eta'] > $b['eta'];
		});

		return $final;
	}


	public function get_active_customer_orders($customer_id)
	{
		//$arr = array(0, 1);
		//var_dump($arr);
		$query= $this->db->query('
		SELECT
			orders.order_id,
			orders.trip_id, 
			orders.customer_id,
			orders.order_text,  
			orders.state,
			orders.fee, 
			trips.restaurant_name,
			trips.eta
		FROM 
			orders, 
			trips
		WHERE 
			orders.customer_id = ? AND 
			(orders.state = ? OR orders.state = ?) AND 
			trips.trip_id = orders.trip_id
		ORDER BY trips.eta ASC'
			,array($customer_id, '0', '1'));

		if($query->num_rows()==0){
			return array();
		}

		return $query->result(); 
	}

	public function get_inactive_customer_orders($customer_id)
	{
		//$arr = array('2', '3');
		//var_dump($arr);
		$query= $this->db->query('
		SELECT
			orders.order_id,
			orders.trip_id, 
			orders.customer_id,
			orders.order_text,  
			orders.state,
			orders.fee, 
			trips.restaurant_name, 
			trips.eta
		FROM 
			orders, 
			trips
		WHERE 
			orders.customer_id = ? AND 
			(orders.state = ? OR orders.state = ?) AND  
			trips.trip_id = orders.trip_id
		ORDER BY trips.eta DESC', 
			array($customer_id, '2', '3'));

		if($query->num_rows()==0){
			return array();
		}

		return $query->result(); 
	}

	public function get_customer_orders_of_state($customer_id, $state)
	{
		$queryOrder;
		if ($state == 0 || $state == 1) {
			$queryOrder = "ASC";
		}
		else {
			$queryOrder = "DESC";
		}
		$query= $this->db->query('
		SELECT
			orders.order_id,
			orders.trip_id, 
			orders.customer_id,
			orders.order_text,  
			orders.state,
			orders.fee, 
			trips.restaurant_name,
			trips.eta
		FROM 
			orders,
			trips
		WHERE 
			orders.customer_id = ? AND
			orders.state = ? AND
			trips.trip_id = orders.trip_id
		ORDER BY trips.eta ' . $queryOrder,
		 array($customer_id, $state));

		if($query->num_rows()==0){
			return array();
		}

		return $query->result_array(); 
	}

	public function get_all_orders_of_state($state)
	{
		$query = $this->db->query('
		SELECT 
			orders.order_id,
			orders.trip_id, 
			orders.customer_id,
			orders.order_text,  
			orders.state,
			orders.fee, 
		FROM 
			orders
		WHERE 
			orders.state = ? 
			', array($state));

		if ($query->num_rows() == 0)
		{
			return array();
		}

		return $query->result_array();
	}

	public function get_all_pending_orders()
	{
		return $this->get_all_orders_of_state(0);
	}

	public function get_all_accepted_orders()
	{
		return $this->get_all_orders_of_state(1);
	}

}