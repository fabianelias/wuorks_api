<?php

class Accounts_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function valida_usuario($datos)
	{
		$array = array("email" => $datos["email"], "clave" => md5($datos["clave"]));
		$query = $this->db->where($array)->get("usuarios");

		if($query->num_rows() == 1) return $query->row();

		return FALSE;
	}
    /***************************************************************************/
    /* FUNCIÓN PARA VALIDAR AL USUARIO Y RECUPERAR SUS DATOS.
    /***************************************************************************/
    public function verifica_usuario($datos,$type = ''){
        $array = array("email" => $datos["email"], "password" => md5($datos["clave"]), "tipo_usuario" => $type);
		$query = $this->db->where($array)->get("usuarios");

		if($query->num_rows() == 1){
			return true;
		}else{
			return false;
		}
    }//FIN PARA VALIDAR_USUARIO();
    
    /***************************************************************************/
    /*FUNCION PARA RECOGER LOS DATOS DE LA CUENTA
    /***************************************************************************/
    public function datos_usuarios(){
    	$email = $this->input->post('email');
        $this->db->select('*');
        $this->db->where('email',$email);
        $query = $this->db->get('usuarios');
        
        return $query->row();
    }
    /***************************************************************************/
    /* FUNCIÓN PARA INSERTAR UN NUEVO REGISTRO DE USUARIO
    /************************************************************************* */
    public function ingresar_registro($datos_registro){
         //CREAMOS UN CODIGO UNICO PARA VERICAR CUENTA DE EMAIL
        $cod_confirmacion = rand(999,9999).'-'.md5($datos_registro['email']);
        //CREAMOS SU NOMBRE DE USUARIO PARA EL SISTEMA
        $nick_usuario = '@'.substr($datos_registro['nombres'],0,2).'_'.substr($datos_registro['apellidos'],0,5).  rand(111, 999);
        @date_default_timezone_get('America/Santiago');
		$fecha_ingreso = @date('Y/m/d');
        $data = array(
                'nombres'       => $datos_registro['nombres'],
                'apellidos'     => $datos_registro['apellidos'],
                'email'         => $datos_registro['email'],
                'password'      => md5($datos_registro['clave']),
                'nick_usuario'  => $nick_usuario,
                'fecha_ingreso' => $fecha_ingreso,
                'code_confir'   => $cod_confirmacion,
                'tipo_usuario'  => 1,
                'estado'        => 0
                );
        //INGRESAMOS LOS DATOS A LA BD
        $insert = $this->db->insert('usuarios',$data);
        error_reporting(0);
       //TOMAMOS EL ULTIMO ID CREADO PARA, DATOS_USUARIO
        $query = $this->db->query('SELECT LAST_INSERT_ID() FROM usuarios');
        $row = $query->row_array();
        $last_id = array('id_usuario' => $row['LAST_INSERT_ID()'],
                         'tipo_cuenta' => 1,
                         'pais' => 'chile',
                         'imagen' => 'profile-wuork250x250.png');
        $this->db->insert('datos_usuario',$last_id);
        $hash = md5($nick_usuario);
        //
        $url_verificacion = base_url().'accounts/verify_email?token='.$cod_confirmacion.'&veri_code='.$hash;
        $nombre = $datos_registro['nombres'].' '.$datos_registro['apellidos'];
        $this->load->library('email');
        $this->load->library('email_registro');
        
        /*$this->email->initialize(array(
              'protocol' => 'smtp',
              'smtp_host' => 'smtp.mandrillapp.com',
              'smtp_user' => 'fabian_bravo@live.com',
              'smtp_pass' => 'v5Oh_LTqtPfoQfJhxPUn2Q',
              'smtp_port' => 587,
              'mailtype' => 'html',
              'crlf' => "\r\n",
              'newline' => "\r\n"
           ));*/
        $config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['mailtype'] = 'html';
		$config['protocol'] = 'smpt';
		$config['smtp_host'] = 'ssl://smtp.googlemail.com';
		$config['smtp_port'] = 465;
		$config['smtp_user'] = 'fabian.bravo93@gmail.com';
		$config['smtp_pass'] = 'corsario1993';
		$config['validation'] = TRUE;
		$this->email->initialize($config);
		$this->email->clear();
        

        $this->email->from('no-reply@wuorks.com', 'WUORKS | El profesional que necesitas.');
        $this->email->to($datos_registro['email']);
        $this->email->subject($nombre.' te damos la ¡Bienvenida! a Wuorks.com el portal de servicios');
        $this->email->message($this->email_registro->confirmar_registro($url_verificacion,$nombre),TRUE);//Load a view into email body
        $this->email->send();
        
        return $insert;
        
    }// FIN PARA INSERTAR_REGISTRO();
	
	public function very_email($email){

		$consulta = $this->db->get_where('usuarios',  array('email' => $email ));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
	public function very_rut($rut){

		$consulta = $this->db->get_where('user',  array('rut' => $rut ));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
	public function very_codigo($variable,$campo){
		$consulta = $this->db->get_where('usuarios',array($campo=>$variable));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
	public function update_user($code_confirm){

		$this->db->where('code_confirm',$code_confirm);
		$this->db->update('usuarios', array('estado' => '1' ));
	}
	//Funciones para iniciar session
	public function very_sesion(){
		$clave = $this->input->post('clave',TRUE);
		$clave = md5($clave);
		
		$consulta = $this->db->get_where('user', array('email' => $this->input->post('email',TRUE),
			'password' =>$clave
			));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
	public function verifica_estado(){
		$clave = $this->input->post('clave',TRUE);
		$clave = md5($clave);
		$consulta = $this->db->get_where('usuarios', array('email' => $this->input->post('email',TRUE),
			'password' =>$clave,
			'estado' => '1'
			));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
		public function obtener_regiones(){
		$this->db->select('*');
		$this->db->from('regiones');

		$query = $this->db->get();
		if ($query->num_rows() == 1) return $query->row();

			return FALSE;
		
	}
}