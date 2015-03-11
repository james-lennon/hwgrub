<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function index(){
		$this->load->view("pages/home");
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
					$this->load->view("pages/forgot");
				} else {
					$this->users_model->set_password($user->user_id, md5($this->input->post("password")));
					redirect("pages/index");
				}
			}else{
				show_error("Invalid forgot URL");
			}
		}
	}

}
