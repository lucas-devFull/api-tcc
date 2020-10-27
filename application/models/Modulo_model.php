<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Modulo_model extends MY_Model {

	public function __construct(){
        parent::__construct();
    }

    public function buscaMateriasPorModulos($id_modulo = 0){
        $this->db->select("descricao_materia, materias.id_materia, id_modulo");
        $this->db->join("modulos_materia","modulos_materia.id_materia = materias.id_materia", "left");
        $this->db->where("modulos_materia.id_modulo IS NULL");
        if ($id_modulo != 0) {
            $this->db->or_where("modulos_materia.id_modulo", $id_modulo);
        }
        return $this->db->get("materias")->result_array();
    }
}