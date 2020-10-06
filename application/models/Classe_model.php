<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Classe_model extends CI_Model {

	public function __construct(){
        parent::__construct();
    }
    
    public function pegaClasse($dados){
        return $this->db->select("*")
        ->get("usuario")->result_array();
    }
}