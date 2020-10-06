<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends MY_Model{

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
      $id_usuario = $this->crudDefault($dados, "usuario", "cadastro");      
      return $id_usuario;
   }

   public function deletaUsuario($dados){

   }
}