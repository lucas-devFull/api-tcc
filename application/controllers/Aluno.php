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
                echo json_encode($this->aluno_model->crudDefault("", "usu_aluno", "busca", $this->getContent()));
            break;
            case 'delete':
                $dados = $this->getContent();
                $id = array("id_aluno" => $dados['id_aluno']);
                $infoUsuario = $this->aluno_model->crudDefault("", "usu_aluno", "busca", $id);
                $idUsuario = array("id_usuario" => $infoUsuario["dados"][0]['id_usuario_aluno']);
                $deletaraluno = $this->aluno_model->crudDefault("","usu_aluno", "deletar", $id);
                $deletarUsuario = $this->aluno_model->crudDefault("","usuario", "deletar", $idUsuario);
                echo json_encode($deletarUsuario);
            break;
            case 'post':
                $dados = $this->getContent();
                $dados['tipo_usuario'] = 2;
                $id_usuario = $this->usuario_model->cadastraUsuarioDefault($dados);
                $dadosAluno['descricao_usu_aluno'] = $dados['descricao_usuario'];
                $dadosAluno['id_usuario_aluno'] = $id_usuario['id'];
                echo json_encode($this->aluno_model->crudDefault($dadosAluno, "usu_aluno", "cadastro"));
            break;
            case 'put':
                $dados = $this->getContent();
                $dados['tipo_usuario'] = 2;
                $id = array("id_aluno" => $dados['id_aluno']);
                unset($dados['id_aluno']);
                $infoUsuario = $this->aluno_model->crudDefault("", "usu_aluno", "busca", $id);
                if(!$infoUsuario['status']) {
                    echo json_encode($infoUsuario);
                    exit;
                }
                $dados['id_usuario'] = $infoUsuario['dados'][0]["id_usuario_aluno"];
                $this->usuario_model->editaUsuario($dados);
                $dadosaluno['descricao_usu_aluno'] = $dados['descricao_usuario'];
                echo json_encode($this->aluno_model->crudDefault($dadosaluno, "usu_aluno", "edicao", $id));
            break;
        }
    }
}
