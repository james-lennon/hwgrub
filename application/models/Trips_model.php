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

		$query = $this->db->query('
			SELECT 
				trips.*,
				users.img_url,
				users.first_name,
				users.last_name,
				trips.expiration < ? as expired
			FROM 
				trips,
				users
			WHERE
				trips.driver_id = ?     AND
				trips.expiration > ?    AND
				trips.driver_id = users.user_id
			ORDER BY 
				trips.expiration ASC', array(time(), $user_id, strtotime("midnight")));
		return $query->result();
	}

	public function get_user_active_trips($user_id) {
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}

		$query = $this->db->query('
			SELECT trips.*,
			(SELECT COUNT(*) FROM orders WHERE orders.trip_id = trips.trip_id) as order_count
			 FROM trips 
			WHERE trips.driver_id = ? 
			AND trips.expiration > ? 
			ORDER BY trips.expiration ASC', array($user_id, time()));
		return $query->result();
	}

	public function get_user_inactive_trips($user_id) {
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}

		$query = $this->db->query('SELECT * FROM trips WHERE trips.driver_id = ? AND trips.expiration < ? ORDER BY trips.expiration ASC', array($user_id, time()));
		return $query->result();
	}



	public function get_all_trips() {
		$query = $this->db->query('SELECT * from trips');
		return $query->result();
	}

	public function get_all_active_trips() {
		$expiration = time();
		$query = $this->db->query('
			SELECT 
				trips.*,
				users.first_name,
				users.last_name,
				users.img_url
			FROM 
				trips,
				users
			WHERE 
				trips.expiration > ?      AND
				trips.driver_id = users.user_id
			ORDER BY
				trips.expiration ASC', array($expiration));
		return $query->result();
	}

	public function delete_trip($trip_id)
	{
		$query = $this->db->query('
			UPDATE 
				trips 
			SET 
				trips.expiration = ? 
			WHERE 
				trips.trip_id = ?
			', array(0, $trip_id));
	}
}
