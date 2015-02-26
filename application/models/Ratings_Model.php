<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ratings_model extends CI_Model{

// driver = 0, customer = 1 
// bad rating = 0, good rating = 1

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function get_all_user_ratings($user_id){
		$query = $this->db->query('
			SELECT
				*
			FROM
				ratings
			WHERE
				ratings.user_id = ?
			', array($user_id));

		return $query->result();
	}

	public function get_ratings($user_id, $type){
		$query = $this->db->query('
			SELECT
				*
			FROM
				ratings
			WHERE
				ratings.user_id = ?         AND 
				ratings.type = ?
			', array($user_id, $type));

		return $query->result();
	}

	public function get_driver_ratings($user_id)
	{	
		return $this->get_ratings($user_id, 0);
	}

	public function get_customer_ratings($user_id)
	{	
		return $this->get_ratings($user_id, 1);
	}

	public function add_rating($user_id, $rating_text, $type, $value)
	{
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}
	
		$data = array(
			"user_id"=>$user_id,
			"rating_text"=>$rating_text,
			"type"=>$type,
			"value"=>$value,
			);
		$this->db->insert('ratings', $data);
	}

	public function get_record($user_id, $type){
		$query = $this->db->query('
			SELECT
				(
					SELECT COUNT(ratings.rating_id) FROM ratings WHERE ratings.user_id = ? AND ratings.type = ? AND ratings.value = 1
	    		) as good_ratings,
	    		(
					select COUNT(ratings.rating_id) FROM ratings WHERE ratings.user_id = ? AND ratings.type = ? AND ratings.value = 0
	    		) as bad_ratings
			LIMIT
				1
			', array($user_id, $type, $user_id, $type));

		return $query->row();
	}

	public function get_driver_record($user_id)
	{
		return $this->get_record($user_id, 0);
	}

	public function get_customer_record($user_id)
	{
		return $this->get_record($user_id, 1);
	}

	public function get_bad_ratings($user_id){
		$query = $this->db->query('
			SELECT
				COUNT(ratings.rating_id) as num
			FROM
				ratings
			WHERE
				ratings.value = 0      AND
				ratings.user_id = ?
			',array($user_id));
		return $query->row()->num;
	}

	public function get_good_ratings($user_id){
		$query = $this->db->query('
			SELECT
				COUNT(ratings.rating_id) as num
			FROM
				ratings
			WHERE
				ratings.value = 1      AND
				ratings.user_id = ?
			',array($user_id));
		return $query->row()->num;
	}
}

