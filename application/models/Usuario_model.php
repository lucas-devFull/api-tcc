<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model{

   public function __construct(){
       parent::__construct();
   }

   public function getUsers($dados)
   {
      if ($dados) {
         return $this->db->select("*")
         ->where("email_usuario", $dados['login'])
         ->or_where("nick_usuario", $dados['login'])
         ->having("senha_usuario", md5($dados['senha']))
         ->get("usuario")->row_array();
      }else{
         return false;
      }
   }

   public function cadastraUsuario($dados){
      $validacaoLogin = $this->validaNickUsuario($dados);
      if (is_string($validacaoLogin)) {
         return $validacaoLogin;
      }
      $this->db->set("email_usuario", $dados['email_usuario']);
      $this->db->set("nick_usuario", $dados['nick_usuario']);
      $this->db->set("senha_usuario", md5($dados['senha']));
      $this->db->set("descricao_usuario", $dados['senha']);
      $this->db->set("tipo_usuario", 2);
      $this->db->insert('usuario');
      $id_usuario = $this->db->insert_id();
      
      $dadosAluno = array("descricao_usu_aluno" => $dados['descricao_usuario'], "id_usuario_aluno" => $id_usuario);
      $this->aluno_model->cadastraAluno($dadosAluno);

      return array("status" => true);
   }
}