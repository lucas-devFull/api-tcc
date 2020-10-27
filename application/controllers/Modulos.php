<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');  

class Modulos extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model("modulo_model");
    }
    
    public function index(){
        switch ($this->input->method()) {
            case 'get':
                $join = false;
                $dados = false;

                $retorno = $this->modulo_model->crudDefault($dados, "modulos", "busca", $_GET, $join);
                $retorno['materias'] = $this->modulo_model->buscaMateriasPorModulos(isset($_GET['id_modulo']) ? $_GET['id_modulo'] : "");
                echo json_encode($retorno);
            break;
            case 'delete':
                $dados = $this->getContent();
                $idmodulo = array("id_modulo" => $dados['id_modulo']);
                $this->modulos_model->crudDefault("", "modulos_materia", "deletar", $idmodulo);
                $deleta_modulo = $this->modulos_model->crudDefault("", "modulos", "deletar", $idmodulo); // sp deixar deletar um modulo se n houver aulas relacionadas a ele 
                echo json_encode($deleta_modulo);
            break;
            case 'post':
                $dados = $this->getContent();
                if (isset($dados['id_materia'])) {
                    $id_materia = $dados['id_materia'];
                    unset($dados['id_materia']);    
                }else{
                    $id_materia = false;
                }

                $id_modulo = $this->modulos_model->crudDefault($dados, "modulos", "cadastro");

                if ($id_materia != false) {
                    foreach ($id_materia as $value) {
                        $insertModulosMateria = array("id_modulo" => $id_modulo, "id_materia" => $value);
                        $retorno = $this->modulos_model->crudDefault($insertModulosMateria, "modulos_materia", "cadastro");
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                }
                
                echo json_encode(array("status" => true, "id" => $id_modulo));
            break;
            case 'put':
                $dados = $this->getContent();
                $id = array("id_modulo" => $dados['id_modulo']);
                unset($dados['id_modulo']);
                $infoUsuario = $this->modulos_model->crudDefault("", "modulos_materia", "busca", $id);
                if(!$infoUsuario['status']) {
                    echo json_encode($infoUsuario);
                    exit;
                }else{
                    $materiasAntigas = array_map(function($dados){
                        $array = $dados['id_materia'];
                        return $array;
                    }, $infoUsuario['dados']);
                }

                $materiasRetiradas = array_diff($materiasAntigas, $dados["id_materia"]);
                $materiasAdicionadas = array_diff($dados["id_materia"], $materiasAntigas);


                if (!empty($materiasRetiradas)) {
                    foreach ($materiasRetiradas as $value) {
                        $dadosDelete = $id;
                        $dadosDelete["id_materia"] = $value;
                        $retorno = $this->modulos_model->crudDefault("", "modulos_materia", "deletar", $dadosDelete);
                        if($retorno['status'] == false){
                            echo json_encode($retorno);
                            exit;
                        }
                    }    
                }

                if (!empty($materiasAdicionadas)) {
                    foreach ($materiasAdicionadas as $value) {
                        $insertModulosMateria = $id;
                        $insertModulosMateria['id_materia'] = $value;
                        $retorno = $this->modulos_model->crudDefault($insertModulosMateria, "modulos_materia", "cadastro");
                        if ($retorno['status'] == false) {
                            echo json_encode($retorno);
                            exit;
                        }
                    }
                }
                
                unset($dados['id_materia']);
                echo json_encode($this->modulos_model->crudDefault($dados, "modulos", "edicao", $id));
            break;
        }
    }
}