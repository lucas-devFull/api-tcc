<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professor extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model("professor_model");
	}
	
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                echo json_encode($this->professor_model->crudDefault("", "usu_professor", "busca", $_GET));
            break;
            case 'delete':
                $idUsuario = array("id_professor" => $_POST['id_professor']);
                $idProfessor = array("id_usuario_professor" => $_POST['id_usuario_professor']);
                $deletarProfessor = $this->professor_model->crudDefault("","usu_professor", "deletar", $idProfessor);
                $deletarUsuario = $this->professor_model->crudDefault("","usuario", "deletar", $idUsuario);
                if ($deletarProfessor['status']) {

                }
            break;
            case 'post':
                echo json_encode($this->professor_model->crudDefault($_POST, "usu_professor", "cadastro"));
            break;
            case 'put':
                $id = array("id_professor" => $_POST['id_professor']);
                unset($_POST['id_professor']);
                echo json_encode($this->professor_model->crudDefault($_POST, "usu_professor", "editar", $id));
            break;
        }
    }
}
