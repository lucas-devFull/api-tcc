<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aluno_model extends CI_Model{
    

    public function __construct(){
        parent::__construct();
    }

    public function cadastraAluno($dados){
        $this->db->set($dados);
        $this->db->insert('usu_aluno');
    }
}