<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{

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
		$user_id = $this->users_model->add_user($email, $fname, $lname, $phone);
		if(!$user_id){
			echo json_encode(array("error"=>"User with email already exists"));
			exit();
		}

		$default_img_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=identicon";
		$this->users_model->set_img($user_id, $default_img_url);

		$url = $this->users_model->forgot_password($email);
		echo json_encode(array("url"=>$url));
	}

	public function login(){
		$this->load->model(array("users_model"));
		$user_id = check_auth();

		if ($user_id != FALSE) {
			if(!check_sess_auth()){
				$this->load->library("session");
				$credentials = array(
					"email"=>$this->input->post("email"),
					"password"=>$this->input->post("password"),
					"no_hash"=>$this->input->post("no_hash")
					);
				$this->session->set_userdata($credentials);
			}

			echo json_encode(array("user_id"=>$user_id));
		}else{
			echo json_encode(array("error"=>"invalid login"));
		}
	}

	public function logout(){
		$this->load->library("session");
		$this->session->sess_destroy();
		echo json_encode(array("success"=>1));
	}

	public function send_forgot_email($email){
		$this->load->model("users_model");
		$url = $this->users_model->forgot_password($email);
		if($url){
			//Send Email Here
			$this->load->library("email");
			$this->email->subject("Set Password for WolverEats");
			$this->email->to($email);

			echo json_encode(array("url"=>$url));
		}else{
			echo json_encode(array("error"=>"No user with email or wait one day."));
		}
	}

	public function get_current_user_content()
	{
		$user_id = check_auth();

		if(!$user_id){
			exit(json_encode(array("No user_id given")));
		}
		else 
		{
			$this->load->model(array("users_model","ratings_model", "trips_model"));
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
		}
	}
	
	public function get(){
		check_auth();
		$user_id = $this->input->post("user_id");

		if ($user_id != FALSE) {
			$this->load->model(array("users_model","ratings_model", "trips_model"));
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
			echo json_encode(array("error"=>"no user id given"));
		}
	}

	public function get_user_content(){
		check_auth();
		$user_id = $this->input->post("user_id");

		if(!$user_id){
			exit(json_encode(array("No user_id given")));
		}

		$this->load->model(array("users_model", "ratings_model"));
		$data["user"] = $this->users_model->get_user($user_id);
		$data["ratings"] = $this->ratings_model->get_all_user_ratings($user_id);
		$data["good_ratings"] = $this->ratings_model->get_good_ratings($user_id);
		$data["bad_ratings"] = $this->ratings_model->get_bad_ratings($user_id);

		$this->load->view("content/user_info", $data);
	}



}