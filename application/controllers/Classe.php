<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class Classe extends MY_Controller
{

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
                $retorno = [];
                if (isset($_GET['id_classe'])) {
                    $_GET['classe.id_classe'] = $_GET['id_classe'];
                    unset($_GET['id_classe']);
                }
                $retorno = $this->classe_model->crudDefault("", "classe", "busca", $_GET, $join);
                if (isset($_GET['classe.id_classe'])) {
                    $retorno['materias'] =  $this->pegaMateriasEAlunos($_GET['classe.id_classe'])['materias'];
                    $retorno['alunos'] = $this->pegaMateriasEAlunos($_GET['classe.id_classe'])['alunos'];
                }

                echo json_encode($retorno);
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
                if (isset($dados['id_classe'])) {
                    $id = array("id_classe" => $dados['id_classe']);
                    unset($dados['id_classe']);

                    $infoUsuario = $this->classe_model->crudDefault("", "alunos_classe", "busca", $id, false);
                    if (!$infoUsuario['status']) {
                        echo json_encode($infoUsuario);
                        exit;
                    } else {
                        $alunosAntigos = array_map(function ($dados) {
                            $array = $dados['id_aluno'];
                            return $array;
                        }, $infoUsuario['dados']);
                    }
    
                    $alunosRetirados = array_diff($alunosAntigos, explode(",",$dados["id_aluno"]));
                    $alunosAdicionados = array_diff(explode(",",$dados["id_aluno"]), $alunosAntigos);
    
                    if (!empty($alunosRetirados)) {
                        foreach ($alunosRetirados as $value) {
                            $dadosDelete = $id;
                            $dadosDelete["id_aluno"] = $value;
                            $retorno = $this->classe_model->crudDefault("", "alunos_classe", "deletar", $dadosDelete);
                            if ($retorno['status'] == false) {
                                echo json_encode($retorno);
                                exit;
                            }
                        }
                    }
    
                    if (!empty($alunosAdicionados)) {
                        foreach ($alunosAdicionados as $value) {
                            $insertAlunosClasse = $id;
                            $insertAlunosClasse['id_aluno'] = $value;
                            $retorno = $this->classe_model->crudDefault($insertAlunosClasse, "alunos_classe", "cadastro");
                            if ($retorno['status'] == false) {
                                echo json_encode($retorno);
                                exit;
                            }
                        }
                    }
    
                    unset($dados['id_aluno']);


                    $infoUsuarioMateria = $this->classe_model->crudDefault("", "materias_classe", "busca", $id, false);
                    if (!$infoUsuarioMateria['status']) {
                        echo json_encode($infoUsuarioMateria);
                        exit;
                    } else {
                        $materiasAntigas = array_map(function ($dados) {
                            $array = $dados['id_materia'];
                            return $array;
                        }, $infoUsuarioMateria['dados']);
                    }
    
                    $materiasRetiradas = array_diff($materiasAntigas, explode(",", $dados["id_materia"]));
                    $materiAdicionadas = array_diff(explode(",", $dados["id_materia"]), $materiasAntigas);
    
                    if (!empty($materiasRetiradas)) {
                        foreach ($materiasRetiradas as $value) {
                            $dadosDelete = $id;
                            $dadosDelete["id_materia"] = $value;
                            $retorno = $this->classe_model->crudDefault("", "materias_classe", "deletar", $dadosDelete);
                            if ($retorno['status'] == false) {
                                echo json_encode($retorno);
                                exit;
                            }
                        }
                    }
    
                    if (!empty($materiAdicionadas)) {
                        foreach ($materiAdicionadas as $value) {
                            $insertAlunosClasse = $id;
                            $insertAlunosClasse['id_materia'] = $value;
                            $retorno = $this->classe_model->crudDefault($insertAlunosClasse, "materias_classe", "cadastro");
                            if ($retorno['status'] == false) {
                                echo json_encode($retorno);
                                exit;
                            }
                        }
                    }
                    unset($dados['id_materia']);
                    echo json_encode($this->classe_model->crudDefault($dados, "classe", "edicao", $id));
                    
                }else{
                    $id_classe = $this->classe_model->crudDefault(array("descricao_classe" => $dados['descricao_classe']), "classe", "cadastro");
                    foreach (explode(",", $dados['id_aluno']) as $value) {
                        $dadosCadastroAluno["id_classe"] = $id_classe['id'];
                        $dadosCadastroAluno['id_aluno'] = $value;
                        $retorno = $this->classe_model->crudDefault($dadosCadastroAluno, "alunos_classe", "cadastro");
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                    
                    
                    foreach (explode(",", $dados['id_materia']) as $value) {
                        $dadosCadastroMateria["id_classe"] = $id_classe['id'];
                        $dadosCadastroMateria['id_materia'] = $value;
                        $retorno = $this->classe_model->crudDefault($dadosCadastroMateria, "materias_classe", "cadastro");
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                    echo json_encode($id_classe);
                }
                break;
        }
    }

    public function pegaMateriasEAlunos($id = false)
    {
        $retorno = [];
        $join = false;
        $where = false;
        $select = array("select" => "descricao_usu_aluno, usu_aluno.id_aluno, alunos_classe.id_classe");
        if (!$id) {
            $where = array("alunos_classe.id_aluno" => NULL);
            
        }else{
            $where = "id_classe = $id or id_classe IS NULL";
        }
        
        $join[] = ['alunos_classe', 'alunos_classe.id_aluno = usu_aluno.id_aluno', 'left'];
        $retorno['alunos'] = $this->classe_model->crudDefault($select, "usu_aluno", "busca", $where, $join)['dados'];

        if (!$id) {
            $join = false;
            $select = array("select" => "materias.id_materia, materias.descricao_materia");
        }else{
            $join = [];
            $join[] = ['materias_classe', 'materias_classe.id_materia = materias.id_materia and (materias_classe.id_classe = "' . $id .'" )', 'left'];
            $select = array("select" => "materias.id_materia, materias.descricao_materia, materias_classe.id_classe");
        }
        $retorno['materias'] = $this->classe_model->crudDefault($select, "materias", "busca","", $join)['dados'];

        if (!$id) {
            echo (json_encode($retorno));
        } else {
            return $retorno;
        }
    }
}
