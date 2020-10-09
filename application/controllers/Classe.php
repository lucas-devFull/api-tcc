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
                 $join = false;
                 if (isset($_GET['id_classe'])) {
                     $join[] = ['alunos_classe','alunos_classe.id_classe = classe.id_classe'];
                     $join[] = ['usu_aluno', 'usu_aluno.id_aluno = alunos_classe.id_aluno'];
                    $_GET['classe.id_classe'] = $_GET['id_classe'];
                    unset($_GET['id_classe']);
                }
                echo json_encode($this->classe_model->crudDefault("", "classe", "busca", $_GET, $join));
            break;
            case 'delete':
                $dados = $this->getContent();
                $idclasse = array("id_classe" => $dados['id_classe']);
                $this->classe_model->crudDefault("", "alunos_classe", "deletar", $idclasse);
                $this->classe_model->crudDefault("", "materias_classe", "deletar", $idclasse);
                $deleta_classe = $this->classe_model->crudDefault("", "classe", "deletar", $idclasse);
                echo json_encode($deleta_classe);
            break;
            case 'post':
                $dados = $this->getContent();
                $id_classe = $this->classe_model->crudDefault($dados, "classe", "cadastro");
                foreach ($dados['id_aluno'] as $value) {
                    $insertAlunosClasse = array("id_classe" => $id_classe, "id_aluno" => $value);
                    $retorno = $this->classe_model->crudDefault($insertAlunosClasse, "alunos_classe", "cadastro");
                    if ($retorno['status'] == false) {
                        echo json_encode($retorno);
                        exit;
                    }
                }
                echo json_encode(array("status" => true, "id" => $id_classe));
            break;
            case 'put':
                $join = false;
                $dados = $this->getContent();
                $id = array("id_classe" => $dados['id_classe']);
                unset($dados['id_classe']);
                $infoUsuario = $this->classe_model->crudDefault("", "alunos_classe", "busca", $id, $join);
                if(!$infoUsuario['status']) {
                    echo json_encode($infoUsuario);
                    exit;
                }else{
                    $alunosAntigos = array_map(function($dados){
                        $array = $dados['id_aluno'];
                        return $array;
                    }, $infoUsuario['dados']);
                }

                $alunosRetirados = array_diff($alunosAntigos, $dados["id_aluno"]);
                $alunosAdicionados = array_diff($dados["id_aluno"], $alunosAntigos);

                foreach ($alunosRetirados as $value) {
                    $dadosDelete = $id;
                    $dadosDelete["id_aluno"] = $value;
                    $retorno = $this->classe_model->crudDefault("", "alunos_classe", "deletar", $dadosDelete);
                    if($retorno['status'] == false){
                        echo json_encode($retorno);
                        exit;
                    }
                }

                foreach ($alunosAdicionados as $value) {
                    $insertAlunosClasse = $id;
                    $insertAlunosClasse['id_aluno'] = $value;
                    $retorno = $this->classe_model->crudDefault($insertAlunosClasse, "alunos_classe", "cadastro");
                    if ($retorno['status'] == false) {
                        echo json_encode($retorno);
                        exit;
                    }
                }
                unset($dados['id_aluno']);
                echo json_encode($this->classe_model->crudDefault($dados, "classe", "edicao", $id));
            break;
        }
    }
}
