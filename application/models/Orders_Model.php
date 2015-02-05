<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model{
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
		 FROM 
		 	orders 
		 WHERE 
		 	orders.order_id = ?', 
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
	"state"=> 0, 
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

public function get_trip_orders($trip_id) 
{
	$query = $this->db->$query('
		SELECT 
			orders.order_id,
			orders.order_text, 
			orders.customer_id, 
			orders.state, 
			orders.fee,  
		FROM 
			orders 
		WHERE 
			orders.trip_id = ?', 
		array($trip_id));

	if ($query->num_row() == 0)
	{
		return FALSE;
	}

	return $query->result();
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
	FROM 
		orders 
	WHERE 
		orders.customer_id = ?
		', array($customer_id));

	if ($query->num_rows() == 0)
	{
		return FALSE;
	}

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

public function get_rejected_customer_orders($customer_id)
{
	return $this->get_customer_orders_of_state($customer_id, 2); 
}

public function get_completed_customer_orders($customer_id)
{
	return $this->get_customer_orders_of_state($customer_id, 3); 
}


public function get_customer_orders_of_state($customer_id, $status)
{
	$query= $this->db->query('
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
		orders.customer_id = ?, 
		orders.status = ?,

		', array($customer_id, $stauts));

	if($query->num_rows()==0){
		return FALSE;
	}

	return $query->result(); 
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
		return FALSE;
	}

	return $query->result();
}

public function get_all_pending_orders()
{
	return $this->get_all_orders_of_state(0);
}

public function get_all_accepted_orders()
{
	return $this->get_all_orders_of_state(1);
}


