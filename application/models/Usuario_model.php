<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model{
   public function getUsers($dados)
   {
      if ($dados) {
         return $this->db->select("*")
            ->where("usu_email", $dados['login'])
            ->or_where("usu_usuario", $dados['login'])
            ->where("usu_senha", md5($dados['senha']))
            ->get("usuario")->result_array();
      }
   }
}