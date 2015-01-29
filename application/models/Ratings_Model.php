<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model{

// driver = 0, customer = 1 
// bad rating = 0, good rating = 1

public function __construct(){
		parent::__construct();
		$this->load->database();
	}

public function get_Driver_Ratings($user_id)
{	
	$query = $this->db->query('
			SELECT
				*
			FROM
				ratings
			WHERE
				ratings.user_id = ?
			AND 
				ratings.type = ?
			', array($user_id, 0);

	if($query->num_rows()==0){
			return FALSE;
	
	$ratings = $query->result_array(); 
	return $ratings; 
}

public function get_Customer_Ratings($user_id)
{	
	$query = $this->db->query('
			SELECT
				*
			FROM
				ratings
			WHERE
				ratings.user_id = ?
			AND 
				ratings.type = ?
			', array($user_id, 1);

	if($query->num_rows()==0){
			return FALSE;
	}
	
	$ratings = $query->result_array(); 
	return $ratings; 
}

public function add_Rating($user_id, $rating_text, $type, $value)
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

public function get_Driver_Record($user_id)
{
	$query1 = $this->db->query('
		SELECT 
			* 
		FROM 
			ratings
		WHERE 
			ratings.user_id = ? 
		AND 
			ratings.type = ?
		AND 
			ratings.value = ?
			', 
			array($user_id, 0, 0));

		$negative = $query1->num_rows(); 

		$query2 = $this->db->query('
		SELECT 
			* 
		FROM 
			ratings
		WHERE 
			ratings.user_id = ? 
		AND 
			ratings.type = ?
		AND 
			ratings.value = ?
			', 
			array($user_id, 0, 1));
		$positive = $query2->num_rows(); 

		$data = array(
			"positive"=>$positive, 
			"negative"=>$negative,
			);

		return $data; 
}

public function get_Customer_Record($user_id)
{
	$query1 = $this->db->query('
		SELECT 
			* 
		FROM 
			ratings
		WHERE 
			ratings.user_id = ? 
		AND 
			ratings.type = ?
		AND 
			ratings.value = ?
			', 
			array($user_id, 1, 0));

		$negative = $query1->num_rows(); 

		$query2 = $this->db->query('
		SELECT 
			* 
		FROM 
			ratings
		WHERE 
			ratings.user_id = ? 
		AND 
			ratings.type = ?
		AND 
			ratings.value = ?
			', 
			array($user_id, 1, 1));
		$positive = $query2->num_rows(); 

		$data = array(
			"positive"=>$positive, 
			"negative"=>$negative,
			);

		return $data; 
}

