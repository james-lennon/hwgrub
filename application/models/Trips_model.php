<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trips_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function get_trip($trip_id){
		$query = $this->db->query('SELECT * FROM trips WHERE trips.trip_id = ?', array($trip_id));
		return $query->row();
	}

	public function add_trip($driver_id, $expiration, $eta, $restaurant){
		$data = array(
			"driver_id"=>$driver_id,
			"expiration"=>$expiration,
			"eta"=>$eta,
			"restaurant_name"=>$restaurant,
			);
		$this->db->insert('trips', $data);
	} 

	public function get_user_trips($user_id) {
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}

		$query = $this->db->query('SELECT * FROM trips WHERE trips.driver_id = ?', array($user_id));
		return $query->result();
	}

	public function get_user_active_trips($user_id) {
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}

		$query = $this->db->query('SELECT * FROM trips WHERE trips.driver_id = ? AND trips.expiration > ?', array($user_id, time()));
		return $query->result();
	}

	public function get_all_trips() {
		$query = $this->db->query('SELECT * from trips');
		return $query->result();
	}

	public function get_all_active_trips() {
		$expiration = time();
		$query = $this->db->query('SELECT * FROM trips WHERE trips.expiration > ?', array($expiration));
		return $query->result();
	}

	// public function get_user_active_trips($driver_id) {

	// 	$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
	// 	if($query->num_rows()==0){
	// 		return FALSE;
	// 	}

	// 	$expiration = time();
	// 	$query = $this->db->query('SELECT * FROM trips WHERE trips.driver_id = ? AND trips.expiration > ?', array($driver_id, $expiration));
	// 	return $query->result();
	// }
}
