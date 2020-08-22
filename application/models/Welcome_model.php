<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome_model extends CI_Model{
   public function teste()
   {
    //    $this->db->select("*");
       return $this->db->get("feed_post")->result_array();
   }
}