<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model{
   public function getUsers($login="devBack", $senha="admin")
   {
       return $this->db->select("*")
            ->where("usu_email", $login)
            ->or_where("usu_usuario", $login)
            ->where("usu_senha", md5($senha))
            ->get("usuario")->result_array();
      //  return "teste end point";
   }
}