<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model{
// ORDER STATES: 0 = pending, 1 = accepted, 2 = rejected, 3 = completed

public function __construct(){
		parent::__construct();
		$this->load->database();
	}


public function place_Order($trip_id, $order_text, $customer_id, $fee)
{
	$data = array(
	"trip_id"=> $trip_id, 
	"order_text"=>$order_text, 
	"customer_id"=>$customer_id, 
	"state"=> 0, 
	"fee"=>$fee,);

	$this->db->insert('orders', $data);
}

public function update_Order_Status ($order_id, $state)
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

public function get_Trip_Orders($trip_id) 
{

}
