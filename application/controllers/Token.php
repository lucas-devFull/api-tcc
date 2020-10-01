<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');  

class Token extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library("Authorization_Token");

	}
	
    public function index(){
        return $this->authorization_token->generateToken($_GET);
    }

    public function refreshToken(){
        $this->input->post();
    }


    public function dadosSessao(){
        return $this->authorization_token->userData($token);
    }
}
