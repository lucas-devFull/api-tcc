<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends MY_Model{

   public function __construct(){
       parent::__construct();
   }

   public function getUsers($dados)
   {
      if ($dados) {
         return $this->db->select("*, TO_BASE64(imagem_usuario) as imagem")
         ->where("email_usuario", $dados['login'])
         ->or_where("nick_usuario", $dados['login'])
         ->having("senha_usuario", md5($dados['senha']))
         ->get("usuario")->row_array();
      }else{
         return false;
      }
   }

   public function cadastraUsuarioDefault($dados){
      $validacaoLogin = $this->validaNickUsuario($dados);
      if (is_string($validacaoLogin)) {
         echo json_encode(array("status" => false, "msg" => $validacaoLogin));
         exit;
      }
      $dados['senha_usuario'] = md5($dados['senha_usuario']);
      return $this->crudDefault($dados, "usuario", "cadastro");
   }

   public function editaUsuario($dados){

      if ($dados['senha_usuario'] == "") {
         unset($dados['senha_usuario']);
      }else{
         $dados['senha_usuario'] = md5($dados['senha_usuario']);
      }
      $infoUsuario = $this->buscaUsuario($dados['id_usuario']);
      $dadosAlteracao = array_diff($dados, $infoUsuario);
      if (isset($dadosAlteracao['email_usuario']) || isset($dadosAlteracao['nick_usuario'])) {
         $validacaoLogin = $this->validaNickUsuario($dadosAlteracao);
         if (is_string($validacaoLogin)) {
            echo json_encode(array("status" => false, "msg" => $validacaoLogin));
            exit;
         }
      }
      if(!empty($dadosAlteracao)){
         return $this->crudDefault($dadosAlteracao, "usuario", "edicao", array("id_usuario" => $dados['id_usuario']));
      }else{
         return true;
      }
   }

   public function cadastraUsuario($dados){
      $validacaoLogin = $this->validaNickUsuario($dados);
      if (is_string($validacaoLogin)) {
         echo json_encode(array("status" => false, "msg" => $validacaoLogin));
         exit;
      }
      $dados['tipo_usuario'] = 2;
      $dados['senha_usuario'] = md5($dados['senha_usuario']);
      $id_usuario = $this->crudDefault($dados, "usuario", "cadastro");
      $dadosAluno['descricao_usu_aluno'] = $dados['descricao_usuario'];
      $dadosAluno['id_usuario_aluno'] = $id_usuario['id'];
      return $this->crudDefault($dadosAluno, "usu_aluno", "cadastro");
   }

   public function buscaUsuario($id = 0){
      if ($id != 0) {
         return $this->db->select("*")
         ->where("id_usuario", $id)
         ->get("usuario")->row_array();
      }else{
         return $this->db->select("*")
         ->get("usuario")->result_array();
      }

   }
}