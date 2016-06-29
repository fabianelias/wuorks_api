<?php

/*******************************************************************************
 * 
 *                  Regiones, provincias y comunas
 * 
 ******************************************************************************/

Class Regiones_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        error_reporting(0);
    }
    
    public function getRegiones(){
        
        $this->db->select("*");
        $query = $this->db->get("regiones");
        
        return $query->result_array();
        
    }
    
    public function getComuna($id_region){
        
        
        //Seleccionamos comuna
        
        $com = $this->db->query("SELECT `comunas`.`nombre`, `comunas`.`id` FROM `comunas` INNER JOIN `provincias`"
                . "ON `comunas`.`provincia` =  `provincias`.`id`"
                . " INNER JOIN `regiones` ON `regiones`.`id_region` = `provincias`.`region` AND  `regiones`.`id_region` = ".$id_region." ");
        $comunas = $com->result_array();
        
        return $comunas;
        
    }
}