<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Graficos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("graficos_model");
    }

    public function index(){
        if ($_POST['id_materia'] == "undefined" && $_POST['id_classe'] == "undefined" && $_POST['aula_id_materia'] == "undefined") {
            echo json_encode($this->geraPorTodos(($_POST["mod_id"] != "undefined") ? $_POST["mod_id"] : false));
        }elseif ($_POST['id_classe'] == "undefined" && $_POST['aula_id_materia'] == "undefined") {
            echo json_encode($this->geraPorMateria($_POST['id_materia']));
        }elseif($_POST['id_classe'] == "undefined"){
            echo json_encode($this->geraPorclasseOuAula($_POST['id_materia'], $_POST['aula_id_materia'] , "aula"));
        }elseif($_POST['aula_id_materia'] == "undefined"){
            echo json_encode($this->geraPorclasseOuAula($_POST['id_materia'], $_POST['id_classe'] , "classe"));
        }else{
            echo json_encode($this->geraPorclasseOuAula($_POST['id_materia'], $_POST['id_classe'] , "todos"));
        }
    }

    public function geraPorclasseOuAula($idMateria, $id, $tipo){
        if ($tipo == "todos") {
            $idArray = [];
            $id = array("materias.id_materia" => $idMateria, "classe.id_classe" => $id);
            $join[] = ["aula_resultados", "aula_resultados on aula_resultados.id_aula_resultados = aula_id", "left"];
            $join[] = ["materias", "materias.id_materia = aulas.aula_id_materia", "left"];
            $join[] = ["alunos_classe", "alunos_classe.id_aluno = aula_resultados.id_aluno_resultados", "left"];
            $join[] = ["usu_aluno", "usu_aluno.id_aluno = alunos_classe.id_aluno", "left"];
            $join[] = ["classe", "classe.id_classe = alunos_classe.id_classe", "left"];
            $retorno = $this->graficos_model->crudDefault("", "aulas", "busca", $id, $join);
            $arrayFormatado[] = ['Alunos da Classe: '. $retorno["dados"][0]["descricao_classe"], 'Tentativas de resolução dos exercícios'];
            foreach ($retorno['dados'] as $key => $value) {
                if (!in_array($value["id_aluno_resultados"],$idArray) && $value['id_aluno_resultados'] != null) {
                    $idArray[] = $value["id_aluno_resultados"];
                    
                    foreach ($idArray as $i => $valor) {
                        if ($valor == $value["id_aluno_resultados"]) {
                            $arrayFormatado[] = [$value['descricao_usu_aluno'], $value['tentativas_resultados']];
                        }
                    }
                }else{
                    foreach ($arrayFormatado as $j => $v) {
                        if ($v[0] == $value["descricao_usu_aluno"] && $value['id_aluno_resultados'] != null) {
                            $arrayFormatado[$j] = [$arrayFormatado[$j][0], ($arrayFormatado[$j][1] + $value['tentativas_resultados']) ];
                        }
                    }
                }
            }
            return array("status" => true, "dados" => $arrayFormatado);
        }else{
            $idArray = [];
            ($tipo == "aula") ? $id = array("materias.id_materia" => $idMateria, "aulas.aula_id" => $id) : $id = array("materias.id_materia" => $idMateria, "classe.id_classe" => $id);
            $join[] = ["materias", "materias.id_materia = aulas.aula_id_materia", "left"];
            $join[] = ["aula_resultados", "aula_resultados on aula_resultados.id_aula_resultados = aula_id", "left"];
            $join[] = ["classe", "classe.id_classe on aula_resultados.id_classes_resultados = classe.id_classe", "left"];
            $retorno = $this->graficos_model->crudDefault("", "aulas", "busca", $id, $join);
            $arrayFormatado[] = [($tipo == "aula") ? 'Classes da Aula: '. $retorno["dados"][0]["aula_descricao"] : 'Resumo das Aulas Por Classes: '. $retorno["dados"][0]["descricao_classe"], 'Tentativas de resolução dos exercícios'];

            foreach ($retorno['dados'] as $key => $value) {
                if($tipo == "aula"){
                    if (!in_array($value["id_classe"],$idArray) && $value['id_classe'] != null) {
                        $idArray[] = $value["id_classe"];

                        foreach ($idArray as $i => $valor) {
                            if ($valor == $value["id_classe"]) {
                                $arrayFormatado[] = [$value['descricao_classe'], $value['tentativas_resultados']];
                            }
                        }
                    }else{
                        foreach ($arrayFormatado as $j => $v) {
                            if ($v[0] == $value["descricao_classe"] && $value['id_classe'] != null) {
                                $arrayFormatado[$j] = [$arrayFormatado[$j][0], ($arrayFormatado[$j][1] + $value['tentativas_resultados']) ];
                            }
                        }
                    }
                }else{
                    if (!in_array($value["aula_id"],$idArray) && $value['aula_id'] != null) {
                        $idArray[] = $value["aula_id"];

                        foreach ($idArray as $i => $valor) {
                            if ($valor == $value["aula_id"]) {
                                $arrayFormatado[] = [$value['aula_descricao'], $value['tentativas_resultados']];
                            }
                        }
                    }else{
                        foreach ($arrayFormatado as $j => $v) {
                            if ($v[0] == $value["aula_descricao"] && $value['aula_id'] != null) {
                                $arrayFormatado[$j] = [$arrayFormatado[$j][0], ($arrayFormatado[$j][1] + $value['tentativas_resultados']) ];
                            }
                        }
                    }
                }
            }
            return array("status" => true, "dados" => $arrayFormatado);
        }
    }
    public function geraPorMateria($idMateria){
        $idArray = [];
        $id = array("materia.id_materia", $idMateria);
        // $select = ["materias.id_materia, descricao_materia, aulas_id, aula_descricao"];
        $join[] = ["materias", "materias.id_materia = aulas.aula_id_materia", "left"];
        $join[] = ["aula_resultados", "aula_resultados on aula_resultados.id_aula_resultados = aula_id", "left"];
        $retorno = $this->graficos_model->crudDefault("", "aulas", "busca", $id, $join);
        $arrayFormatado[] = ['Aulas da Materia: '. $retorno["dados"][0]["descricao_materia"], 'Tentativas de resolução dos exercícios'];
        foreach ($retorno['dados'] as $key => $value) {
            if (!in_array($value["aula_id"],$idArray)) {
                $idArray[] = $value["aula_id"];
                
                foreach ($idArray as $i => $valor) {
                    if ($valor == $value["aula_id"]) {
                        $arrayFormatado[] = [$value['aula_descricao'], ($value['id_resultados'] != null) ? $value['tentativas_resultados'] : 0];
                    }
                }
            }else{
                foreach ($arrayFormatado as $j => $v) {
                    if ($v[0] == $value["aula_descricao"]) {
                        $arrayFormatado[$j] = [$arrayFormatado[$j][0], ($arrayFormatado[$j][1] + $value['tentativas_resultados']) ];
                    }
                }
            }
        }
        return array("status" => true, "dados" => $arrayFormatado);
    }

    public function geraPorTodos($id=false){
        $idArray = [];
        $idModulo = array("modulos.mod_id" =>  $id);
        $arrayFormatado[] = ['Modulos', 'Quantidade Total de Aulas'];
        $select = ["materias.id_materia, descricao_materia, modulos.mod_id, mod_desc, aulas_id, aula_descricao"];
        $join[] = ["modulos_materia", "modulos_materia.id_materia = materias.id_materia", "left"];
        $join[] = ["modulos", "modulos.mod_id = modulos_materia.mod_id", "left"];
        $join[] = ["aulas", "aulas.aula_id_materia = materias.id_materia", "left"];
        $retorno = $this->graficos_model->crudDefault($select, "materias", "busca", ($id) ? $idModulo : "", $join);
        foreach ($retorno['dados'] as $key => $value) {
            if (!in_array($value["mod_id"],$idArray)) {
                $idArray[] = $value["mod_id"];

                foreach ($idArray as $i => $valor) {
                    if ($valor == $value["mod_id"]) {
                        $arrayFormatado[] = [$value['mod_desc'], ($value['aula_id'] != null) ? 1 : 0];
                    }
                }
            }else{
                foreach ($arrayFormatado as $j => $v) {
                    if ($v[0] == $value["mod_desc"] && $value['aula_id'] != null) {
                        $arrayFormatado[$j] = [$arrayFormatado[$j][0], ($arrayFormatado[$j][1] +1) ];
                    }
                }
            }
        }
        return array("status" => true, "dados" => $arrayFormatado);
    }
}