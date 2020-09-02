<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');  

class Usuario extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('usuario_model');
	}
	
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                $login = $_GET['login'];
                $senha = $_GET['senha'];
                echo json_encode($this->usuario_model->getUsers($this->getContent()));
        }
    }
}
