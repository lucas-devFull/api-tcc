<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professor extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model("professor_model");
        $this->load->model('usuario_model');
	}
	
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                echo json_encode($this->professor_model->crudDefault("", "usu_professor", "busca", $_GET));
            break;
            case 'delete':
                $dados = $this->getContent();
                $idProfessor = array("id_professor" => $dados['id_professor']);
                $infoUsuario = $this->professor_model->crudDefault("", "usu_professor", "busca", $idProfessor);
                $idUsuario = array("id_usuario" => $infoUsuario["dados"][0]['id_usuario_professor']);
                $deletarProfessor = $this->professor_model->crudDefault("","usu_professor", "deletar", $idProfessor);
                $deletarUsuario = $this->professor_model->crudDefault("","usuario", "deletar", $idUsuario);
                echo json_encode($deletarUsuario);
            break;
            case 'post':
                $dados = $this->getContent();
                $dados['tipo_usuario'] = 1;
                $id_usuario = $this->usuario_model->cadastraUsuarioDefault($dados);
                $dadosProfessor['descricao_professor'] = $dados['descricao_usuario'];
                $dadosProfessor['id_usuario_professor'] = $id_usuario['id'];
                echo json_encode($this->professor_model->crudDefault($dadosProfessor, "usu_professor", "cadastro"));
            break;
            case 'put':
                $dados = $this->getContent();
                $dados['tipo_usuario'] = 1;
                $id = array("id_professor" => $dados['id_professor']);
                unset($dados['id_professor']);
                $infoUsuario = $this->professor_model->crudDefault("", "usu_professor", "busca", $id);
                if(!$infoUsuario['status']) {
                    echo json_encode($infoUsuario);
                    exit;
                }
                $dados['id_usuario'] = $infoUsuario['dados'][0]["id_usuario_professor"];
                $this->usuario_model->editaUsuario($dados);
                $dadosProfessor['descricao_professor'] = $dados['descricao_usuario'];
                echo json_encode($this->professor_model->crudDefault($dadosProfessor, "usu_professor", "edicao", $id));
            break;
        }
    }
}
