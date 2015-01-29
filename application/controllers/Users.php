<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{

	public function test(){
		$this->load->model("users_model");
		$email = "test@gmail.com";
		$this->users_model->add_user($email, "James", "Lennon", "911");
		echo $this->users_model->forgot_password($email);
	}

	public function check(){
		$this->load->model("users_model");
		$var = $this->users_model->check_login("test@gmail.com", md5("test"));
		var_dump($var);
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