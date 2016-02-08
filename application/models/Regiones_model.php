<?php

class Regiones_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	public function obtenerRegiones(){
		$this->db->select('*');
		$this->db->from('regiones');
		$query = $this->db->get();
			
			 if($query->num_rows() > 0) {
	        foreach ($query->result() as $row) {
	            $data[] = $row;
	        }
	        return $data;
	    }$query->free_result();
	}
	public function obtenerComunas(){

		$this->db->select('*');
        $this->db->order_by('nombre','ASC');
		$consulta = $this->db->get('comunas');

		return $consulta->result();
	}
    
    /***************************************************
     * @Funcion para obtener las comunas segun region
     ***************************************************/
    public function obtener_comunas($region){
        
        $this->db->select('id_region as id');
        $this->db->like('nombre',$region);
        $this->db->order_by('nombre','ASC');
        $res = $this->db->get('regiones');
        $id_region = $res->row()->id;
        
        //Seleccionamos comuna
        
        $com = $this->db->query("SELECT `comunas`.`nombre` FROM `comunas` INNER JOIN `provincias`"
                . "ON `comunas`.`provincia` =  `provincias`.`id`"
                . " INNER JOIN `regiones` ON `regiones`.`id_region` = `provincias`.`region` AND  `regiones`.`id_region` = ".$id_region." ");
        $comunas = $com->result();
        
        return $comunas;
        
    } 
	
}
