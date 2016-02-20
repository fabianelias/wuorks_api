<?php

class Valoracion_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	public function obtenerValoraciones($datos){
		
		$this->db->where('valoraciones.idProfesion', $datos->idProfesion);
		$this->db->select('valoracionPositiva');
		$this->db->select('valoracionNeutra');
		$this->db->select('valoracionNegativa');
		$query = $this->db->get('valoraciones');
			
		return $query->result();
	}
	public function ObValoracionPositiva(){
		
		$this->db->select('idProfesion');
		$this->db->select('valoracionPositiva');
		$this->db->select('valoracionNeutra');
		$this->db->select('valoracionNegativa');
		$query = $this->db->get('valoraciones');
			
			 if($query->num_rows() >= 0) {
	        foreach ($query->result() as $row) {
	            $data[] = $row;
	        }
	        return $data;
	    }$query->free_result();
	}
	
	public function obtenerNumerValoraciones($idProfesion){
		$this->db->where('idProfesion', $idProfesion);

		$this->db->select('valoracionPositiva');
		$consulta = $this->db->get('valoraciones');
		return $consulta->row();
	}

	public function obtener_valoracion_positiva($idProfesion,$orderby = null){

		$this->db->where('idProfesion',$idProfesion);

		$this->db->select_avg('valoracionPositiva');


		$this->db->order_by("valoracionPositiva", "DESC");

		$consulta = $this->db->get('valoraciones');

		return $consulta->row();
	}
	
}

