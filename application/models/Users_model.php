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
				users.user_id, 
				users.first_name, 
				users.last_name, 
				users.email, 
				users.phone, 
				users.img_url,
				users.password
			FROM
				users
			WHERE
				users.user_id = ?
			', array($id));
		
		return $query->row();
	}

	public function add_user($email, $fname, $lname, $phone){
		$query = $this->db->query('SELECT * FROM users WHERE users.email = ?', array($email));
		if($query->num_rows()!=0){
			return FALSE;
		}

		$password_hash = password_hash(openssl_random_pseudo_bytes(20), PASSWORD_DEFAULT);
		$forgot_hash = hash("sha256", openssl_random_pseudo_bytes(10));
		$data = array(
			"email"=>$email,
			"first_name"=>$fname,
			"last_name"=>$lname,
			"phone"=>$phone,
			"password"=>$password_hash,
			);
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function forgot_password($email){
		$this->load->helper("url");

		$query = $this->db->get_where("users", array("email"=>$email));
		if($query->num_rows()==0){
			return FALSE;
		}
		$elapsed_time = time() - $query->row()->forgot_time;
		$days = $elapsed_time / (3600*24);
		if($days < 1){
			return FALSE;
		}

		$bytes = openssl_random_pseudo_bytes(100);
		$token = hash("sha256", $bytes);

		$this->db->query('
			UPDATE
				users
			SET
				forgot_hash=?,
				forgot_time=?
			WHERE
				email = ?
			', array($token, time(), $email));

		$url = base_url("pages/forgot/$token");
		return $url;
	}

	public function check_forgot_hash($hash){
		$query = $this->db->query('
			SELECT
				*
			FROM
				users
			WHERE
				users.forgot_hash = ?
			', array($hash));
		return $query->row();
	}

	public function set_password($user_id, $password){
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$this->db->query('
			UPDATE
				users
			SET
				users.password = ?,
				users.forgot_hash = NULL
			WHERE
				users.user_id = ?
			', array($hash, $user_id));
	}

	public function set_phone($user_id, $phone)
	{
		$this->db->query(' 
			UPDATE 
				users
			SET 
				users.phone = ?
			WHERE 
				users.user_id = ?
			', array($phone, $user_id)); 
	}

	public function set_email ($user_id, $new_email)
	{
		$this->db->query('
			UPDATE 
				users
			SET
				users.email = ?
			WHERE 
				users.user_id = ?
			', array($new_email, $user_id)); 
	}

	public function set_img($user_id, $img_url){
		$query = $this->db->query('SELECT * from users WHERE users.user_id = ?', array($user_id));
		if($query->num_rows()==0){
			return FALSE;
		}
		$this->db->query('
			UPDATE 
				users 
			SET 
				users.img_url = ? 
			WHERE 
				users.user_id = ?
			', array($img_url, $user_id));
	}

	public function check_login($email, $password){
		$query = $this->db->query('
			SELECT 
				* 
			FROM 
				users
			WHERE
				users.email = ?       AND
				users.forgot_hash is NULL
			', array($email));
		if($query->num_rows()==0){
			return FALSE;
		}
		$hash = $query->row()->password;
		if(password_verify($password, $hash)){
			return $query->row()->user_id;
		}else{
			return FALSE;
		}
	}

}