<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Classe_model extends MY_Model {

	public function __construct(){
        parent::__construct();
    }
    
    public function pegaClasse($dados){
        return $this->db->select("*")
        ->get("classe")->result_array();
    }
}