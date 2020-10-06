<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Professor_model extends MY_Model {

	public function __construct(){
        parent::__construct();
    }
    
    public function cadastraProfessor($dados){
        $this->db->set($dados);
        $this->db->insert('usu_professor');
    }
}