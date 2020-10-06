<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');  

class Usuario extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->library("Authorization_Token");
	}
	
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                $data = [
                    'login' => $_GET['login'],
                    'time' => time(),
                ];
                $resultado = $this->usuario_model->getUsers($_GET);
                if($resultado != false || !empty($resultado)){
                    $data['id'] = $resultado["id_usuario"];
                    $token = $this->authorization_token->generateToken($data);
                    $data['tipo'] = $resultado["tipo_usuario"];
                    $data['token'] = $token;
                    echo json_encode(array('status' => true, 'dados' => $data));
                }else{
                    echo json_encode(array('status' => false));
                }
            break;
            case 'post':
                echo json_encode($this->usuario_model->cadastraUsuario($_POST));
            break;
        }
    }
}
