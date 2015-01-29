<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function get_user($id){
		$query = $this->db->query('
			SELECT
				*
			FROM
				users
			WHERE
				users.user_id = ?
			', array($id));
		return $query->row();
	}

	public function add_user($email, $fname, $lname, $phone){
		$data = array(
			"email"=>$email,
			"first_name"=>$fname,
			"last_name"=>$lname,
			"phone"=>$phone,
			);
		$this->db->insert('users', $data);
	}

	public function set_password($user_id, $password){
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$this->db->query('
			UPDATE
				users
			SET
				users.password = ?
			WHERE
				users.user_id = ?
				', array($hash, $user_id));
	}

	public function set_img($user_id, $img_url){
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}
		$this->db->query('UPDATE users SET users.img_url=? WHERE users.user_id = ?', array($img_url, $user_id));
	}

	public function check_login($email, $password){
		$query = $this->db->query('SELECT * FROM users WHERE users.email = ?', array($email));
		if($query->num_rows()==0){
			return FALSE;
		}
		$hash = $query->row()->password;
		if(password_verify($password, $hash)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

}