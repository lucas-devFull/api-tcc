<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('welcome_model');
	}
	
	public function index()
	{
		var_dump($this->welcome_model->teste());
	}
}
