<?php

class Resultado_model extends CI_Model
{
	 var $config_dba_new;

	function __construct()
	{
		parent::__construct();

		 $this->config_dba_new = $this->config->item('config_dba_new');
         
	}
	public function getProfesionales($campoBuscador,$nick_usuario){

            //$this->output->enable_profiler(TRUE); 
		 	$this->db->select('*');
		 	$this->db->join('usuarios','perfil_profesional.email = usuarios.email','left');
            $this->db->join('datos_usuario','datos_usuario.id_usuario = usuarios.id_usuario','left');
            $this->db->join('valoraciones','valoraciones.id_perfilp = perfil_profesional.id_perfil','left');
		 	$this->db->where('perfil_profesional.estado','1');
		 	$this->db->where('LOWER(usuarios.nick_usuario)', strtolower($nick_usuario));
		 	$this->db->like('perfil_profesional.nom_profesion',$campoBuscador);
            $query = $this->db->get('perfil_profesional');

       		if($query->num_rows() > 0){
                return $query->row();
            }else{
                return FALSE;
            }

	}
    public function get_valoraciones($id_perfil){
        $this->db->select('*');
        $this->db->where('estado','1');
        $this->db->where('id_perfilp',$id_perfil);
        $query = $this->db->get('valoraciones');
        
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }
	function filas()
	{
		$consulta = $this->db->get('perfil_profesional');
		return  $consulta->num_rows() ;
	}
	public function obtenerPerfiles($profesion,$region){
		
		$this->db->join('usuarios','usuarios.email = perfil_profesional.email');
		
		//$this->db->join('valoraciones ','valoraciones.idProfesion = perfil_profesional.idProfesion ','left');

		if ($profesion) {
			$this->db->like('LOWER(profesion)', strtolower($profesion));
		}
		if ($region) {
			$this->db->like('LOWER(region)', strtolower($region));
		}

		$this->db->where('perfil_profesional.estado','1');

		//$this->db->order_by("valoraciones.valoracionPositiva", "DESC");

		$query = $this->db->get('perfil_profesional');

		 
         
	    return $query->result(); 

	}
	public function obtenerValoracionPos($idProfesion){
		$this->db->select('idProfesion');
		$this->db->select('valoracionPositiva');
		$this->db->where('valoraciones.idProfesion',$idProfesion);

		//$this->db->group_by('idProfesion');
		//$ = $this->db->get('valoraciones');
		
		
		$consulta = $this->db->count_all('valoraciones');
		return $consulta;
	}
	//Funci
	public function obtenerProfesiones($abuscar){

		$this->db->like('nombre_profesion',$abuscar);

		$consulta->db->get('profesiones');

		return $consulta->result();

	}
	/*************************************************************************
	/*FUNCIÓN PARA OBTENER PERFILES PROFESIONALES SEGÚN PARAMETROS INGRESADOS
	/*************************************************************************/
    public function busqueda($profesion,$region ,$comuna){
        
        if($region == ''){
            $region = 13;
        }
       // $comuna = utf8_decode(trim(str_replace('%', '-', $comuna)));
        //$comuna = $this->normaliza($comuna);
        $comuna  = str_replace('-', ' ', $comuna);
        //echo $comuna; exit();
        $this->db->select('*');
        $this->db->join('usuarios','usuarios.id_usuario = perfil_profesional.id_usuario','left');
        $this->db->join('datos_usuario','datos_usuario.id_usuario = usuarios.id_usuario','left');
        $this->db->where('perfil_profesional.estado ','1');
        $this->db->like('datos_usuario.region',$region);
        $this->db->like('perfil_profesional.nom_profesion',$profesion);
        $this->db->or_like('perfil_profesional.descripcion',$profesion);
        $this->db->like('datos_usuario.comuna',$comuna);
        
        $this->db->order_by('datos_usuario.tipo_cuenta','DESC');
        $this->db->order_by('perfil_profesional.valor_positiva','DESC');
        $consulta = $this->db->get('perfil_profesional');
        
        //print_r($consulta->result()); exit();
        // return $consulta->result_array()
        $res["profesionales"] = $consulta->result_array();
        
        return $res;
                 
         
       // return "hola";aa
    }
	
}