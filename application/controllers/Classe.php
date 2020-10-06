<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');  

class Classe extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model("classe_model");
	}
	
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                $resultado = $this->classe_model->pegaClasse($_GET);
                echo json_encode(array('status' => true, 'dados' => $resultado));
            break;
            case 'post':
                // echo json_encode($this->usuario_model->cadastraUsuario($_POST));
            break;
        }
    }
}
