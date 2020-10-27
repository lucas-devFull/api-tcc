<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');  

class Materias extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model("materias_model");
    }
    
    public function index()
    {
        switch ($this->input->method()) {
            case 'get':
                $join = false;
                $dados = false;
                if (isset($_GET['id_materia']))  {
                    $_GET['materias.id_materia'] = $_GET['id_materia'];
                    unset($_GET['id_materia']);
                }

                if (isset($_GET['id_classe']))  {
                    $_GET['classe.id_classe'] = $_GET['id_classe'];
                    unset($_GET['id_classe']);
                }

                $join[] = ['materias_classe','materias_classe.id_materia = materias.id_materia', 'left'];
                $join[] = ['classe', 'classe.id_classe = materias_classe.id_classe'];
                $join[] = ['alunos_classe', 'alunos_classe.id_classe = classe.id_classe'];
                $join[] = ['usu_aluno', 'usu_aluno.id_aluno = alunos_classe.id_aluno'];
                $dados['select'] = 'materias.id_materia, descricao_materia, classe.id_classe, descricao_classe, usu_aluno.id_aluno, descricao_usu_aluno';
                echo json_encode($this->materias_model->crudDefault($dados, "materias", "busca", $_GET, $join));
            break;
            case 'delete':
                $dados = $this->getContent();
                $idmateria = array("id_materia" => $dados['id_materia']);
                $this->materias_model->crudDefault("", "materias_classe", "deletar", $idmateria);
                $this->materias_model->crudDefault("", "modulos_materia", "deletar", $idmateria);
                $deleta_materia = $this->materias_model->crudDefault("", "materia", "deletar", $idmateria);
                echo json_encode($deleta_materia);
            break;
            case 'post':
                $dados = $this->getContent();
                if (isset($dados['id_classe'])) {
                    $id_classe = $dados['id_classe'];
                    unset($dados['id_classe']);    
                }else{
                    $id_classe = false;
                }

                $id_materia = $this->materias_model->crudDefault($dados, "materias", "cadastro");

                if ($id_classe != false) {
                    foreach ($id_classe as $value) {
                        $insertAlunosClasse = array("id_materia" => $id_materia, "id_classe" => $value);
                        $retorno = $this->materias_model->crudDefault($insertAlunosClasse, "materias_classe", "cadastro");
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                }
                
                echo json_encode(array("status" => true, "id" => $id_materia));
            break;
            case 'put':
                $dados = $this->getContent();
                $id = array("id_materia" => $dados['id_materia']);
                unset($dados['id_materia']);
                $infoUsuario = $this->materias_model->crudDefault("", "materias_classe", "busca", $id);
                if(!$infoUsuario['status']) {
                    echo json_encode($infoUsuario);
                    exit;
                }else{
                    $classeAntigos = array_map(function($dados){
                        $array = $dados['id_classe'];
                        return $array;
                    }, $infoUsuario['dados']);
                }

                $classesRetiradas = array_diff($classeAntigos, $dados["id_classe"]);
                $classesAdicionadas = array_diff($dados["id_classe"], $classeAntigos);


                if (!empty($classesRetiradas)) {
                    foreach ($classesRetiradas as $value) {
                        $dadosDelete = $id;
                        $dadosDelete["id_classe"] = $value;
                        $retorno = $this->materias_model->crudDefault("", "materias_classe", "deletar", $dadosDelete);
                        if($retorno['status'] == false){
                            echo json_encode($retorno);
                            exit;
                        }
                    }    
                }

                if (!empty($classesAdicionadas)) {
                    foreach ($classesAdicionadas as $value) {
                        $insertAlunosClasse = $id;
                        $insertAlunosClasse['id_classe'] = $value;
                        $retorno = $this->materias_model->crudDefault($insertAlunosClasse, "materias_classe", "cadastro");
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                }
                
                unset($dados['id_classe']);
                echo json_encode($this->materias_model->crudDefault($dados, "classe", "edicao", $id));
            break;
        }
    }

    public function acoesClasse(){
        
    }
}