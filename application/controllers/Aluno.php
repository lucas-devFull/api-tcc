<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aluno extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model("aluno_model");
        $this->load->model('usuario_model');
	}
	
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                echo json_encode($this->getContents());
            break;
            case 'delete':
                $idUsuario = array("id_professor" => $_POST['id_professor']);
                $idProfessor = array("id_usuario_professor" => $_POST['id_usuario_professor']);
                $deletarProfessor = $this->aluno_model->crudDefault("","usu_professor", "deletar", $idProfessor);
                $deletarUsuario = $this->aluno_model->crudDefault("","usuario", "deletar", $idUsuario);
                if ($deletarProfessor['status']) {

                }
            break;
            case 'post':
                $id_usuario = $this->usuario_model->cadastraUsuario($this->getContent());
                $dados = $this->getContent();
                $dadosAluno['descricao_usu_aluno'] = $dados['descricao_usuario'];
                $dadosAluno['id_usuario_aluno'] = $id_usuario['id'];
                echo json_encode($this->aluno_model->crudDefault($dadosAluno, "usu_aluno", "cadastro"));
            break;
            case 'put':
                $id = array("id_professor" => $_POST['id_professor']);
                unset($_POST['id_professor']);
                echo json_encode($this->aluno_model->crudDefault($_POST, "usu_professor", "editar", $id));
            break;
        }
    }
}
