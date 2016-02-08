<?php

class Account_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function obtener_datos($id_usuario){

        $this->db->select('*');
		$this->db->join('datos_usuario','datos_usuario.id_usuario = usuarios.id_usuario ','left');
        $this->db->where('datos_usuario.id_usuario',$id_usuario);
		$query = $this->db->get('usuarios');
		return $query->row();
	}
	/*****************************************************************************/
	/*FUNCIÓN PARA OBETENER LA INFORMACIÓN DEL USUARIO
	/*****************************************************************************/
	public function obtener_informacion($id_usuario){
        //sdf
		$this->db->select('*');
		$this->db->join('datos_usuario',"datos_usuario.id_usuario = usuarios.id_usuario",'left');
        $this->db->where('datos_usuario.id_usuario',$id_usuario);
		$query = $this->db->get('usuarios');

		return $query->row();
	}//FIN PARA OBTENER_INFORMACION();
	//
	/***************************************************************************
     * @FUNCIÓN PARA OBTENER MENSAJES RECIBIDOS y ENVIADOS  
    ***************************************************************************/
    public function mensajes_recibidos(){
        $destinatartio = $this->session->userdata('nick_usuario');
        $this->db->select('*');
        $this->db->where('destinatario',$destinatartio);
        $this->db->order_by('id_mensaje','DESC');
        $select = $this->db->get('mensajes');
        
        return $select->result();
    }
    public function mensajes_recibidos_v2(){
        $destinatartio = $this->session->userdata('nick_usuario');
        $this->db->select('*');
        $this->db->where('destinatario',$destinatartio);
        $this->db->order_by('id_mensaje','DESC');
        $select = $this->db->get('mensajes');
        if($select->num_rows() > 0){
            $resu = $select->result();
            foreach ($resu as $mr){
                 switch ($mr->estado){
                        case 0: 
                            $color = "#eeeeee;"; 
                            $estado = '<p class="btn btn-danger">Sin Leer</p>';
                            break;
                        case 1:
                            $color = "#ffffff";
                            $estado = '<p class="btn btn-abi-p">Leido</p>';
                            break;
                }
                $fecha = date("d/m/Y", strtotime($mr->fecha_envio));
                $res[] = array(
                    $estado,
                    $fecha,
                    //$mr->fecha_envio,
                    $mr->asunto,
                    $mr->mensajes,
                    $mr->emisor,
                    '<a href="javascript:;" onclick="msgRead('.$mr->estado.','.$mr->id_mensaje.');" data-toggle="modal" data-target="#recibidos_'.$mr->id_mensaje.'"style="color:cecece;">Leer</a>'
                );
            }
        }else{
            $res[] = array('','','No has recibido ningun mensaje','','','');
        }
        return $res;
    }
    //wwww
    public function mensajes_enviados(){
        $emisor = $this->session->userdata('nick_usuario');
        $this->db->select('*');
        $this->db->where('emisor',$emisor);
        $this->db->order_by('id_mensaje','DESC');
        $select = $this->db->get('mensajes');
        
        return $select->result();
    }
    public function mensajes_enviados_v2(){
        $emisor = $this->session->userdata('nick_usuario');
        $this->db->select('*');
        $this->db->where('emisor',$emisor);
        $this->db->order_by('id_mensaje','DESC');
        $select = $this->db->get('mensajes');
        
        //return $select->result();
        if($select->num_rows() > 0){
            $resu = $select->result();
            foreach ($resu as $mr){
                 switch ($mr->estado){
                        case 0: 
                            $color = "#eeeeee;"; 
                            $estado = '<p class="btn btn-danger">Sin Leer</p>';
                            break;
                        case 1:
                            $color = "#ffffff";
                            $estado = '<p class="btn btn-abi-p">Leído</p>';
                            break;
                }
                $fecha = date("d/m/Y", strtotime($mr->fecha_envio));
                $res[] = array(
                    $estado,
                    $fecha,
                    //$mr->fecha_envio,
                    $mr->asunto,
                    $mr->mensajes,
                    $mr->destinatario,
                    '<a href="javascript:;"  data-toggle="modal" data-target="#enviados_'.$mr->id_mensaje.'"style="color:cecece;">Leer</a>'
                );
            }
        }else{
            $res[] = array('','','No haz enviado ningun mensaje','','','');
        }
        return $res;
    }
    /***************************************************************************
     * @FUNCIÓN PARA OBTENER LAS PROFESIONES
    ***************************************************************************/
	public function obtener_nomProfesiones(){
        
        //crear array
        $data = array();
        $data['']='--- Seleccione una profesión ---';
        $this->db->select('id, nombre_profesion');
        $this->db->from('profesiones');
        $this->db->order_by("nombre_profesion","DESC");
        $query = $this->db->get();
        
        foreach($query->result() as $row){
            $data[$row->nombre_profesion] = ucwords($row->nombre_profesion);  
        }
        return $data;
	}
    /***************************************************************************
     * FUNCIÓN PARA TRAR LOS PERFILES DEL USUARIO 
    ***************************************************************************/
	public function obtener_perfiles($email){

            $this->db->select('*');
            $this->db->from('perfil_profesional');
            $this->db->where('email',$email);
            $this->db->order_by('id_perfil','desc');
            $query = $this->db->get();

             if($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        $query->free_result();
	}//FIN PARA OBTENER_PERFILES();
    
    /***************************************************************************
     * @FUNCIÓN PARA OBTENER LAS PROFESIONES DE LA BD del usuario
    ****************************************************************************/
	public function obtener_profesion($id)
	{
		$this->db->select('*');
		 	$this->db->from('perfil_profesional');
            $this->db->where('id_perfil',$id);
            $query = $this->db->get();

        if($query->num_rows() == 1) return $query->row();

        return FALSE;
	}//FIN PARA OBTENER_PROFESION();
    
    /***************************************************************************
     * @Función para obtener los contratos hecho al usuario conectado
     */
    public function mis_contratos($id){
        
        $destinatario = $this->session->userdata('nick_usuario');
        $token  = rand(99, 9999);
        
        $this->db->select('c.id_contrato, c.emisor, p_p.nom_profesion, c.estado, c.fecha_contrato, c.comentario');
        $this->db->join('perfil_profesional as p_p','p_p.id_perfil = c.id_perfil','left');
        $this->db->join('usuarios as u','u.nick_usuario = c.destinatario','left');
        $this->db->where('u.id_usuario',$id);
        $this->db->where('c.destinatario',$destinatario);
        
        $query = $this->db->get('contratos as c');
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                switch ($row['estado']) {
                    case 0: 
                        $estado = "Sin valoración";
                        $clase  = "btn btn-danger";
                        break;
                    case 1: 
                        $estado = "Listo"; 
                        $clase = "btn btn-abi";
                        break;
                }
                $res[] = array(
                    substr($row['comentario'], 0 ,25).'...',
                    $row['emisor'],
                    $row['nom_profesion'],
                    '<p class="'.$clase.'">'.$estado.'</p>',
                    $row['fecha_contrato'],
                    '<a href="'.base_url().'account/contract_details/'.$token.'/'.$row['id_contrato'].'/contratos" class=" btn btn-info">ver detalle</a>'
                );
            }
            
            //return $res;
        }
        else{
                $res[] = array(
                    '',
                    '',
                    '<i class="fa fa-meh-o"></i>&nbsp; Todavía no te han contratado!.',
                    '',
                    '',
                    ''
                );
            }
        return $res;
        
    }
    public function mis_contratados($id){
        
        $emisor = $this->session->userdata('nick_usuario');
        $token  = rand(99, 9999);
        
        $this->db->select('c.id_contrato, c.destinatario, p_p.nom_profesion, c.estado, c.fecha_contrato, c.comentario');
        $this->db->join('perfil_profesional as p_p','p_p.id_perfil = c.id_perfil','left');
        $this->db->join('usuarios as u','u.nick_usuario = c.destinatario','left');
        //$this->db->where('u.id_usuario',$id);
        $this->db->where('c.emisor',$emisor);
        $this->db->order_by('id_contrato','DESC');
        
        $query = $this->db->get('contratos as c');
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                switch ($row['estado']) {
                    case 0: 
                        $estado = "Sin valoración";
                        $clase  = "btn btn-danger";
                        break;
                    case 1: 
                        $estado = "Listo"; 
                        $clase = "btn btn-abi";
                        break;
                }
                $res[] = array(
                    substr($row['comentario'], 0 ,65).'...',
                    $row['destinatario'],
                    $row['nom_profesion'],
                    '<p class="'.$clase.'">'.$estado.'</p>',
                    $row['fecha_contrato'],
                     '<a href="'.base_url().'account/contract_details/'.$token.'/'.$row['id_contrato'].'/contratados" class=" btn btn-info">ver detalle</a>'
                );
            }
            
            
        }else{
                $res[] = array(
                    '',
                    '',
                    '<i class="fa fa-meh-o"></i>&nbsp; Todavía no has contratado a nadie!.',
                    '',
                    '',
                    ''
                );
            }
        return $res;
    }
    public function detalle_contrato($id_contrato, $id_usuario, $tipo){
        
       if($tipo == "contratos"){
            
            //contrato hecho al usuario conectado
            $destinatario = $this->session->userdata('nick_usuario');
            
            //$this->db->select('c.id_contrato, c.emisor, p_p.nom_profesion, c.estado, c.fecha_contrato, c.comentario');
            $this->db->select('*');
            $this->db->join('perfil_profesional as p_p','p_p.id_perfil = c.id_perfil','left');
            $this->db->join('usuarios as u','u.nick_usuario = c.destinatario','left');
            $this->db->join('datos_usuario as d_u','d_u.id_usuario = u.id_usuario','left');
            //$this->db->where('u.id_usuario',$id_usuario);
            $this->db->where('c.id_contrato',$id_contrato);
            $this->db->where('c.destinatario',$destinatario);
            $query = $this->db->get('contratos as c');
            
            if($query->num_rows() > 0){
                $res['d_contract'] = $query->row();
                
                //Obtenemos los datos del usuario que has contratado
                $this->db->select('*');
                $this->db->join('datos_usuario as d_u','d_u.id_usuario = u.id_usuario','left');
                $this->db->where('u.nick_usuario',$res['d_contract']->emisor);
                $query2 = $this->db->get('usuarios as u');
            
                  $res['d_contratador'] = $query2->row();
            
                $this->db->select('*');
                $this->db->where('id_contrato',$id_contrato);
                $query3 = $this->db->get('valoraciones');
            
                 $res['d_valoracion'] = $query3->row();
                 $res['tipo'] = 1;
                  return $res; 
            }else{
                
                return false;
                
            }
       }else{
           //contrato que a hecho el usuario conectado
            $emisor = $this->session->userdata('nick_usuario');
            
            //$this->db->select('c.id_contrato, c.emisor, p_p.nom_profesion, c.estado, c.fecha_contrato, c.comentario');
            $this->db->select('*');
            $this->db->join('perfil_profesional as p_p','p_p.id_perfil = c.id_perfil','left');
            $this->db->join('usuarios as u','u.nick_usuario = c.destinatario','left');
            $this->db->join('datos_usuario as d_u','d_u.id_usuario = u.id_usuario','left');
            //$this->db->where('u.id_usuario',$id_usuario);
            $this->db->where('c.id_contrato',$id_contrato);
            $this->db->where('c.emisor',$emisor);
            $query = $this->db->get('contratos as c');
            
            if($query->num_rows() > 0){
                $res['d_contract'] = $query->row();
                
                //Obtenemos los datos del usuario contratador
                $this->db->select('*');
                $this->db->join('datos_usuario as d_u','d_u.id_usuario = u.id_usuario','left');
                $this->db->where('u.nick_usuario',$res['d_contract']->emisor);
                $query2 = $this->db->get('usuarios as u');
            
                $res['d_contratador'] = $query2->row();
            
                $this->db->select('*');
                $this->db->where('id_contrato',$id_contrato);
                $query3 = $this->db->get('valoraciones');
            
                 $res['d_valoracion'] = $query3->row();
                 $res['tipo'] = 2;
                  return $res; 
            }else{
                
                return false;
                
            }
           
       }
               
    }
    /*
     * @Funcion para verificar que el usuario tenga su perfil completo antes de ingresar una nueva profesión
     */
    public function veri_perfil(){
        
        $id_usuario = $this->session->userdata('id_usuario');
        
        $this->db->select('region, comuna, telefono_celular');
        $this->db->where('id_usuario',$id_usuario);
        $this->db->where('region!=',NULL);
        $this->db->where('comuna!=',NULL);
        $this->db->where('telefono_celular!=',NULL);
        $query = $this->db->get('datos_usuario');
        
        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    /***************************************************************************/
    /*FUNCIÓN PARA INGRESAR UNA NUEVA PROFESIÓN DE UN USUARIO
    /***************************************************************************/
    public function ingresar_profesion($data){
        $email = $this->session->userdata('user');
        $id_usuario = $this->session->userdata('id_usuario');
        $show_info = $data["show_info"];
        if($show_info != NULL){
            $show_info = 1;
        }else{
            $show_info = 0;
        }
        @date_default_timezone_get('America/Santiago');
		$fecha_ingreso = @date('Y-m-d');
        $datas = array(
            'nom_profesion' => $data["profesion"],
            'descripcion' => $data["descripcion"],
            'lugar_trabajo' => $data["lugar_trabajo"],
            'valor_hora' => $data["valor_hora"],
            'aptitudes' => $data["aptitudes"],
            'mostrar_info' => 0,// $show_info,
            'valor_positiva' => 0,
            'valor_negativa' => 0,
            'valor_neutra'   => 0,
            'email' => $email,
            'id_usuario' => $id_usuario,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => 1
        );
        
        $insert = $this->db->insert('perfil_profesional',$datas);
        
        return $insert;
        
    }//Fin ingresar_profesion()
    
    /***************************************************************************
    * @FUNCIÓN PARA MODIFICAR LOS DATOS DE LAS PROFESIONES
    ***************************************************************************/
	public function modificar_profesion($datos){
		$email = $this->session->userdata('user');
		$show_infor = $datos["show_info"];
		if ($show_infor == null) {
			$show_infor=0 ;
		}else{

		$show_infor =1;
        }
		$array = array(
			"nom_profesion" => $datos["profesion"],
			"descripcion" => $datos["descripcion"],
			"aptitudes" => $datos["aptitudes"],
			"estado" => $datos["estado"],
			"fecha_modificacion" => date("Y-m-d H:i:s"),
			"lugar_trabajo" => $datos["lugar_trabajo"],
			"valor_hora" => $datos["valor_hora"],
			"mostrar_info" => $show_infor,
			"email"=>$email
			
		);
		//$email = $this->session->userdata['user'];
		$id = $datos["id"];
		$this->db->where('id_perfil',$id);
		$this->db->update('perfil_profesional', $array);
	}//FIN PARA MODIFICAR_PROFESION();
    
    /***************************************************************************
    * @FUNCIÓN PARA MODIFICAR LOS DATOS DEL USUARIO
    ***************************************************************************/
	public function update_usuario($datos)
	{
		$datos_usuario = array(
			"comuna" => $datos["comuna"],
			"region" => $datos["region"],
			"fecha_nacimiento" => $datos["fecha_nacimiento"],
			"telefono_celular" => $datos["telefono_celular"],
			"telefono_opcional" => $datos["telefono_opcional"],
			"sexo" => $datos["sexo"]
		);

		$usuario = array("nombres" => $datos["nombres"],
			"apellidos" => $datos["apellidos"],);

		$id_usuario = $this->session->userdata['id_usuario'];

		$this->db->where('id_usuario',$id_usuario);

		$this->db->update('datos_usuario', $datos_usuario);
        $this->db->where('id_usuario',$id_usuario);
		$this->db->update('usuarios', $usuario);
	}//FIN PARA UPDATE_USUARIO()
    
    /***************************************************************************
     * @FUNCION PARA MODIFICAR LA CLAVE DE LA CUENTA
    ****************************************************************************/
	public function modificar_clave($datos)
	{
		$array = array(
			"password" => md5($datos["clave"])
		);
		$email = $this->session->userdata['user'];
		$this->db->where('email',$email);
		$this->db->update('usuarios', $array);
	}//FIN PARA MODIFICAR_CLAVE
    
    /***************************************************************************
     * @FUNCIÓN PARA MODIFICAR LA FOTO DE PERFIL DE LA CUENTA
    ***************************************************************************/
	public function foto_perfil($imagen)
	{
        
		$array = array(	"imagen" => $imagen
			);
		$email = $this->session->userdata['user'];
        $id_usuario = $this->session->userdata['id_usuario'];

		$this->db->where('id_usuario',$id_usuario);
		$this->db->update('datos_usuario', $array);
        
        //eliminamos la imagen anterior
        
	}//FIN PARA FOTO_PERFIL();
    
    /***************************************************************************
     * @FUNCIÓN PARA OBTENER MENSAJES NO LEIDOS
    ***************************************************************************/
    public function ultimos_mensajes(){
        
        $nick = $this->session->userdata('nick_usuario');
        
        $this->db->where('destinatario',$nick);
        $this->db->where('estado','0');
        $this->db->from('mensajes');
        $query = $this->db->count_all_results();
        
        return $query;
    }//FIN PARA ULTIMOS_MENSAJES]();
    
    /***************************************************************************
     * @FUNCIÓN PARA NOTIFICACIONES
    ****************************************************************************/
    public function notificaciones(){
        $id_usuario = $this->session->userdata('id_usuario');
        
        $this->db->select('*');
        $this->db->join('perfil_profesional','perfil_profesional.id_perfil = valoraciones.id_perfilp','left');
        $this->db->join('datos_usuario','perfil_profesional.id_usuario = datos_usuario.id_usuario','left');
        $this->db->where('datos_usuario.id_usuario',$id_usuario);
        $this->db->where('valoraciones.estado','0');
        $this->db->from('valoraciones');
        $res['num_notif'] = $this->db->count_all_results();
        
        
        $this->db->select('*');
        $this->db->join('perfil_profesional','perfil_profesional.id_perfil = valoraciones.id_perfilp','left');
        $this->db->join('datos_usuario','perfil_profesional.id_usuario = datos_usuario.id_usuario','left');
        $this->db->join('usuarios','valoraciones.id_usuario = usuarios.id_usuario','left');
        $this->db->where('datos_usuario.id_usuario',$id_usuario);
        $this->db->where('valoraciones.estado','0');
        $this->db->limit(5);
        $result = $this->db->get('valoraciones');
        
        if($result->num_rows() > 0){
            $res['info'] = $result->result();
        }
        return $res;
    }
    
    
  
}