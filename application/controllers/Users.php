<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{

	public function test(){
		$this->load->model("users_model");
		$this->load->model("trips_model");
		$email = "abcde@fg.com";
		// $this->users_model->add_user($email, "James", "Lennon", "911");
		// $url = $this->users_model->forgot_password($email);
		// var_dump($url);

		// $this->load->model("users_model");
		$var = $this->users_model->check_login($email, md5("test"));
		var_dump($var);

		// $user = $this->users_model->check_login("test@gmail.com", md5("test"));

		// $this->load->model("ratings_model");
		// $this->ratings_model->add_rating($user->user_id, "Alright job!", 0, 1);
		// $ratings = $this->ratings_model->get_driver_record($user->user_id);
		// var_dump($ratings);
	}

	public function add(){
		$email = $this->input->post("email");
		$fname = $this->input->post("firstname");
		$lname = $this->input->post("lastname");
		$phone = $this->input->post("phone");

		if(!($email && $fname && $lname && $phone)){
			echo json_encode(array("error"=>"No email, firstname, lastname, or phone given."));
			exit();
		}

		$this->load->model("users_model");
		$success = $this->users_model->add_user($email, $fname, $lname, $phone);
		if(!$success){
			echo json_encode(array("error"=>"User with email already exists"));
			exit();
		}
		$url = $this->users_model->forgot_password($email);
		echo json_encode(array("url"=>$url));
	}

	public function login(){
		$email = $this->input->post("email");
		$password = $this->input->post("password");

		if(!$email || !$password){
			echo json_encode(array("error"=>"no email or password given"));
			exit();
		}

		$this->load->model(array("users_model", "ratings_model", "trips_model"));
		$user_id = $this->users_model->check_login($email, $password);

		if ($user_id != FALSE) {
			$driver_ratings = $this->ratings_model->get_driver_ratings($user_id);
			$customer_ratings = $this->ratings_model->get_customer_ratings($user_id);
			$trips = $this->trips_model->get_user_trips($user_id);
			$user = $this->users_model->get_user($user_id);

			$data = array(
				"user" => $user,
				"trips" => $trips,
				"driver_ratings" => $driver_ratings,
				"customer_ratings" => $customer_ratings,
				);

			echo json_encode($data);
		}else{
			echo json_encode(array("error"=>"invalid login"));
		}
	}

	public function send_forgot_email($email){
		$this->load->model("users_model");
		$url = $this->users_model->forgot_password($email);
		if($url){
			echo json_encode(array("url"=>$url));
		}else{
			echo json_encode(array("error"=>"No user with email or wait one day."));
		}
	}

	public function forgot($hash = FALSE){
		if(!$hash){
			show_error("Invalid forgot URL");
			exit();
		}else{
			$this->load->model('users_model');
			$user = $this->users_model->check_forgot_hash($hash);
			if($user){
				$this->load->helper(array('url','form'));
				$this->load->library('form_validation');

				$this -> form_validation -> set_rules('password', 'Password', 'required');
				$this -> form_validation -> set_rules('confirm-password', 'Password Confirmation', 'required|matches[password]');

				if ($this -> form_validation -> run() == FALSE) {
					echo validation_errors();
					$this->load->view("set_password");
				} else {
					$this->users_model->set_password($user->user_id, md5($this->input->post("password")));
					echo "Successfully set password";
				}
			}else{
				show_error("Invalid forgot URL");
			}
		}
	}

}