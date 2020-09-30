<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model{
   public function getUsers($dados)
   {
      if ($dados) {
         return $this->db->select("*")
         ->where("email_usuario", $dados['login'])
         ->or_where("nick_usuario", $dados['login'])
         ->having("senha_usuario", md5($dados['senha']))
         ->having("tipo_usuario", $dados['tipo'])
            ->get("usuario")->result_array();
      }else{
         return false;
      }
   }
}