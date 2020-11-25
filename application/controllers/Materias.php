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
                $dadosRetorno = [];
                
                if (isset($_GET['id_materia']))  {
                    $_GET['materias.id_materia'] = $_GET['id_materia'];
                    unset($_GET['id_materia']);
                }
                
                if (!isset($_GET['tipo_usuario']) || !isset($_GET['id_usuario'])) {
                    echo(json_encode(array("status" => false, "msg" => "sem tipo ou id usuario")));
                    return;
                }
                
                if($_GET['tipo_usuario'] == 2){
                    $id = array("usuario.id_usuario" =>  $_GET['id_usuario']);

                    $join[] = ['usu_aluno','usu_aluno.id_usuario_aluno = usuario.id_usuario', 'left'];
                    $join[] = ['alunos_classe','alunos_classe.id_aluno = usu_aluno.id_aluno', 'left'];
                    $join[] = ['classe','classe.id_classe = alunos_classe.id_classe', 'left'];
                    $join[] = ['materias_classe','materias_classe.id_classe = classe.id_classe', 'left'];
                    $join[] = ['materias','materias.id_materia = materias_classe.id_materia', 'left'];

                    $dadosRetorno = $this->materias_model->crudDefault(
                        array("select" => "materias.id_materia, materias.descricao_materia"),
                        "usuario",
                        "busca",
                        $id,
                        $join
                    );
                }else{
                    $id = array("materias.id_usuario" =>  $_GET['id_usuario']);
                    $dadosRetorno = $this->materias_model->crudDefault(
                        array("select" => "materias.id_materia, materias.descricao_materia"),
                        "materias",
                        "busca",
                        ($_GET['tipo_usuario'] == 0) ? false : $id,
                        $join
                    );
                }
                echo json_encode($dadosRetorno);
            break;
            case 'delete':
                $dados = $this->getContent();
                $idmateria = array("id_materia" => $dados['id_materia']);
                $this->materias_model->crudDefault("", "materias_classe", "deletar", $idmateria);
                $this->materias_model->crudDefault("", "modulos_materia", "deletar", $idmateria);
                $deleta_materia = $this->materias_model->crudDefault("", "materias", "deletar", $idmateria);
                echo json_encode($deleta_materia);
            break;
            case 'post':
                $dados = $this->getContent();

                $dadosMaterias = array("descricao_materia" => $dados['descricao_materia']);
                if ($dados['tipo_usuario'] == 1) {
                    $dadosMaterias['id_usuario'] = $dados['id_usuario'];
                }
 
                if (isset($dados['id_materia'])) {
                    $id_materia = array("id_materia" => $dados['id']);
                    $id_materia = $this->materias_model->crudDefault($dadosMaterias, "materias", "edicao", $id_materia);
                    $this->materias_model->crudDefault(array("mod_id" => $dados['mod_id'], "id_materia" => $id_materia['id']), "modulos_materia", "edicao", $id_materia);

                }else{
                    $id_materia = $this->materias_model->crudDefault($dadosMaterias, "materias", "cadastro");
                    $this->materias_model->crudDefault(array("mod_id" => $dados['mod_id'], "id_materia" => $id_materia['id']), "modulos_materia", "cadastro");
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