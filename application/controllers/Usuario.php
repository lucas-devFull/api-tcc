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


                $insert_data = [
                    'login' => $_GET['login'],
                    'senha' => $_GET['senha'],
                    'tipo' => $_GET['tipo'],
                    'created_at' => time(),
                    'updated_at' => time(),
                ];

// var_dump($token);
                $return = [
                    'login' => $_GET['login'],
                    'senha' => $_GET['senha'],
                    'tipo' => $_GET['tipo'],
                    'created_at' => time(),
                    'updated_at' => time(),
                    'token' => $token 
                ];

                print_r($this->dadosSessao($return));
                // echo json_encode($return);

                // $login = $_GET['login'];
                // $senha = $_GET['senha'];
                // echo json_encode($this->usuario_model->getUsers($this->getContent()));
                
        }
    }
}
