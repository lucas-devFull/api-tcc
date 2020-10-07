<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aluno_model extends MY_Model{
    

    public function __construct(){
        parent::__construct();
    }

    function cadastraAluno($dados){
        $dados["tipo_usuario"] = 2;
        $dados["senha_usuario"] = md5($dados['senha']);
        unset($dados["senha"]);

        $dadosAluno = array("descricao_usu_aluno" => $dados['descricao_usuario'], "id_usuario_aluno" => $id_usuario['id']);
        $resultInsert = $this->crudDefault($dadosAluno, "usu_aluno", "cadastro");
  
    }

}