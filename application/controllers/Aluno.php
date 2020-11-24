<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aluno extends MY_Controller
{

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
                $join = false;
                $retorno = [];
                $select['select'] = "descricao_usuario, descricao_usu_aluno, usu_aluno.id_aluno, nick_usuario, email_usuario, TO_BASE64(imagem_usuario) as imagem";
                $join[] = ['usuario', 'id_usuario = id_usuario_aluno'];
                $retorno = $this->aluno_model->crudDefault($select, "usu_aluno", "busca", $this->getContent(), $join);
                (isset($_GET['id_aluno'])) ? $retorno['classes'] = $this->buscaClasses($_GET['id_aluno']) : "";
                echo json_encode($retorno);
                break;
            case 'delete':
                $dados = $this->getContent();
                $id = array("id_aluno" => $dados['id_aluno']);
                $infoUsuario = $this->aluno_model->crudDefault("", "usu_aluno", "busca", $id);
                $idUsuario = array("id_usuario" => $infoUsuario["dados"][0]['id_usuario_aluno']);
                $this->aluno_model->crudDefault("", "alunos_classe", "deletar", $id);
                $this->aluno_model->crudDefault("", "usu_aluno", "deletar", $id);
                $deletarUsuario = $this->aluno_model->crudDefault("", "usuario", "deletar", $idUsuario);
                echo json_encode($deletarUsuario);
                break;
            case 'post':
                $dados = $this->getContent();
                if (isset($dados['id_aluno'])) {
                    $id = array("id_aluno" => $dados['id_aluno']);
                    unset($dados['id_aluno']);

                    $infoUsuarioClasse = $this->aluno_model->crudDefault("", "alunos_classe", "busca", $id, false);
                    if (!$infoUsuarioClasse['status']) {
                        echo json_encode($infoUsuarioClasse);
                        exit;
                    }
                    if ($infoUsuarioClasse['dados'][0]["id_classe"] != $dados["id_classe"] && $dados["id_classe"] !== "undefined") {
                        $dadosUpdate["id_classe"] = $dados['id_classe'];
                        $retorno = $this->aluno_model->crudDefault($dadosUpdate, "alunos_classe", "edicao", $id);
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                    unset($dados["id_classe"]);

                    $infoUsuario = $this->aluno_model->crudDefault("", "usu_aluno", "busca", $id);
                    if (!$infoUsuario['status']) {
                        echo json_encode($infoUsuario);
                        exit;
                    }
                    $dados['id_usuario'] = $infoUsuario['dados'][0]["id_usuario_aluno"];
                    $this->usuario_model->editaUsuario($dados);
                    $dadosaluno['descricao_usu_aluno'] = $dados['descricao_usuario'];
                    echo(json_encode($this->aluno_model->crudDefault($dadosaluno, "usu_aluno", "edicao", $id)));

                } else {
                    $insertAlunosClasse = array("id_classe" => $dados["id_classe"]);
                    unset($dados["id_classe"]);

                    $dados['tipo_usuario'] = 2;
                    $id_usuario = $this->usuario_model->cadastraUsuarioDefault($dados);

                    $dadosAluno['descricao_usu_aluno'] = $dados['descricao_usuario'];
                    $dadosAluno['id_usuario_aluno'] = $id_usuario['id'];
                    $id_aluno = $this->aluno_model->crudDefault($dadosAluno, "usu_aluno", "cadastro");

                    $insertAlunosClasse['id_aluno'] = $id_aluno['id'];
                    $this->aluno_model->crudDefault($insertAlunosClasse, "alunos_classe", "cadastro");

                    echo json_encode($id_aluno);
                }
                break;
        }
    }

    public function buscaClasses($id = false)
    {
        $join = false;
        $where = false;
        if ($id) {
            $select = array("select" => "classe.id_classe, classe.descricao_classe, id_alunos_classe");
            $join[] = ['alunos_classe', "alunos_classe.id_classe = classe.id_classe  and (id_aluno = $id or id_aluno IS NULL)", 'left'];
            $dados =  $this->aluno_model->crudDefault($select, "classe", "busca", $where, $join)['dados'];
            return $dados;
        } else {
            echo (json_encode($this->aluno_model->crudDefault("", "classe", "busca", $where, $join)));
        }
    }
}
