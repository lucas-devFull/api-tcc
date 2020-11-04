<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model{

    public function __construct(){
        parent::__construct();
    }

    public function crudDefault($dados, $tabela, $tipo, $where = false, $join = false){
        switch ($tipo) {
            case 'busca' :
                (isset($dados['select'])) ? $this->db->select($dados['select']) : ""; 
                if ($join != false) {
                    foreach ($join as $value) {
                        $this->db->join($value[0], $value[1], (count($value) < 3) ? "inner" : $value[2]);
                    }
                }
                ($where != false && !empty($where) && !is_null($where)) ? $this->db->where($where) : "";
                $resultado = $this->db->get($tabela);
                if ($resultado->num_rows() > 0) {
                    return array("status" => true, "dados" => $resultado->result_array());
                }else{
                    return array("status" => false, "dados" => "não tem dados");
                }
            break;
            case 'cadastro':
                $this->db->set($dados);
                $this->db->insert($tabela);
                if ($this->db->insert_id()) {
                    return array("status" => true, "id" => $this->db->insert_id());
                }else{
                    return array("status" => false, "msg" => "erro ao cadastrar registro");
                }
            break;
            case 'edicao':
                if ($where != false && !empty($where) && !is_null($where)) {
                    $this->db->set($dados);
                    $this->db->where($where);
                    $this->db->update($tabela);
                    return array("status" => true, "msg" => "registro editado com sucesso");
                }else{
                    return array("status" => false, "msg" => "precisa de pelo menos um id dpara atualizar esse registro");
                }
                break;
            case 'deletar':
                if ($where != false && !empty($where) && !is_null($where)) {
                    $this->db->where($where);
                    $this->db->delete($tabela);
                    return array("status" => true, "msg" => "deletado com sucesso");
                }else{
                    return array("status" => false, "msg" => "precisa de pelo menos um id dpara atualizar esse registro");
                }
                break;
            default:
                return array("status" => false, "msg" => "sem tipo de ação definida");
            break;
        }
    }

    public function validaNickUsuario($dados){
        if (isset($dados['email_usuario'])) {
            $this->db->where("email_usuario", $dados['email_usuario']);
            if($this->db->get("usuario")->result_array()){
                return "ja existe este email cadastrado -> " . $dados['email_usuario'];
            }
        }

        if (isset($dados['nick_usuario'])) {
            $this->db->where("nick_usuario", $dados['nick_usuario']);
            if($this->db->get("usuario")->result_array()){
                return "ja existe este nick cadastrado -> " . $dados['nick_usuario'];
            }
        }

        return true;
    }

    // public function crudArray($dados, $key, $array, $tipo, $tabela){
    //     foreach ($array as $value) {
    //         $dados[$key] = $array;
    //         $retorno = $this->materias_model->crudDefault($dados, $tabela, $tipo);
    //         if ($retorno['status'] == false) {
    //             echo json_encode($retorno);
    //             exit;
    //         }
    //     }
    // }
}