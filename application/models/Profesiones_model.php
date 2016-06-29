<?php

Class Profesiones_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        error_reporting(0);
    }
    
    public function allProf(){
        
        $this->db->select("*");
        $this->db->order_by("nombre_profesion","ASC");
        $q = $this->db->get("profesiones");
        
        return $q->result_array();
    }
}