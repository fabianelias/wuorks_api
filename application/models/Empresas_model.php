<?php

class Empresas_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	//obtener datos
	public function obtener_datos($email){

			$this->db->select('*');
		 	$this->db->from('empresa');
            $this->db->where('email',$email);
            $query = $this->db->get();

        if($query->num_rows() == 1) return $query->row();

        return FALSE;
	}
		//
	public function obtener_mensajes_enviados($email){

		$this->db->select('*');
		$this->db->where('emisor',$email);
		$this->db->order_by('fecha_ingreso',"DESC");
		$consulta = $this->db->get('mensaje_contacto');

		return $consulta->result();


	}
	public function obtener_mensajes_recibidos($datos){

		$this->db->select('*');
		$this->db->where('destinatario',$datos);
		
		$this->db->order_by('fecha_ingreso',"DESC");
		$consulta = $this->db->get('mensaje_contacto');

		return $consulta->result();

	}
	//fin
	public function valida_usuario($datos)
	{
		$array = array("email" => $datos["email"], "clave" => md5($datos["clave"]));
		$query = $this->db->where($array)->get("empresa");

		if($query->num_rows() == 1) return $query->row();

		return FALSE;
	}

	public function agregar_empresa($datos)
	{
		$code_confirm = rand(9999,999999)."wuorks_confirmacion_empresa";
		$ale = rand(111,999);
		$fecha_ingreso = date("Y-m-d H:i:s");
		$fecha_nacimiento = date("0000-00-0");
		$array = array(
			"nombre" => $datos["nombre"],
			"giro" => $datos["giro"],
			"rutEmpresa" => $datos["rutEmpresa"],
			"email" => $datos["email"],
			"password" => md5($datos["clave"]),
			"comuna" => " ",
			"region" => " ",
			"fechaIngreso" => $fecha_ingreso,
			"telefonoCelular" => "",
			"telefonoFijo" => "",
			"tipoCuenta" => "normal",
			"tipoUsuario" => "empresa",
			"estado" => "0",
			"codeConfirm" => $code_confirm,
			"direccion" => " ",
			"imagen" =>"profile-wuork250x250.png",
			"nombreRepresentante" => " ",
			"rutRepresentante" => " ",
			"estado" => 0,

		);
		$insert = $this->db->insert('empresa', $array);

		$this->load->library('email');

		$config['charset'] = 'utf-8';
		$config['newline'] = '\r\n';
		$config['mailtype'] = 'html';
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.googlemail.com';
		$config['smtp_port'] = 465;
		$config['smtp_user'] = 'fabian.bravo93@gmail.com';
		$config['smtp_pass'] = 'corsario1993';
		$config['validation'] = TRUE;
		$this->email->initialize($config);
		$this->email->clear();

		$this->email->from('fabian.bravo93@gmail.com','WUORKS | El profesional que necesitas!.');
		$this->email->to($this->input->post('email',TRUE));
		$this->email->subject('Confirma tu cuenta de usuario - WUORKS -');
		$this->email->message('<h1>Bienvenido: '.$this->input->post('nombres').' '.$this->input->post('apellidos').',</h1>
			<br>
			<p>Para confirmar tu registro ingresa al siguiente link</p><br>
			<a href="'.base_url().'accounts/confirmar_email/'.$code_confirm.'">Confirmar Email</a>
			<br> <b>Gracias por entrar a la comunidad WUORKS.<b>
			');
		$this->email->send();
	}

	public function very_email_empresa($email){

		$consulta = $this->db->get_where('empresa',  array('email' => $email ));
		if($consulta->num_rows() != 0){
			return true;
		}else{
			return false;
		}
	}
	public function very_rut($rutEmpresa){

		$consulta = $this->db->get_where('empresa',  array('rutEmpresa' => $rutEmpresa ));
		if($consulta->num_rows() != 0){
			return true;
		}else{
			return false;
		}
	}
	public function very_codigo($variable,$campo){
		$consulta = $this->db->get_where('user',array($campo=>$variable));
		if($consulta->num_rows() !=0){
			return true;
		}else{
			return false;
		}
	}
	public function update_user($code_confirm){

		$this->db->where('code_confirm',$code_confirm);
		$this->db->update('user', array('estado' => '1' ));
	}
	//Funciones para iniciar session
	public function very_sesion(){
		$clave = $this->input->post('clave',TRUE);
		$clave = md5($clave);
		
		$consulta = $this->db->get_where('empresa', array('email' => $this->input->post('email',TRUE),
			'password' =>$clave
			));
		if($consulta->num_rows() != 0){
			return true;
		}else{
			return false;
		}
	}
	public function very_estado(){
		$clave = $this->input->post('clave',TRUE);
		$clave = md5($clave);
		$consulta = $this->db->get_where('empresa', array('email' => $this->input->post('email',TRUE),
			'password' =>$clave,
			'estado' => '1'
			));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
		public function modificar_empresas($datos)
	{
		$array = array(
			"nombre" => $datos["nombre"],
			"giro" => $datos["giro"],
			"comuna" => $datos["comuna"],
			"region" => $datos["region"],
			"direccion" => $datos["direccion"],
			"telefonoCelular" => $datos["telefonoCelular"],
			"telefonoFijo" => $datos["telefonoFijo"]
			
		);
		$email = $this->session->userdata['user'];
		$this->db->where('email',$email);
		$this->db->update('empresa', $array);
		//$update = $this->db->update('user', $array);
	}
	public function modificar_clave($datos)
	{
		$array = array(
			"password" => md5($datos["clave"])
		);
		$email = $this->session->userdata['user'];
		$this->db->where('email',$email);
		$this->db->update('empresa', $array);
		//$update = $this->db->update('user', $array);
	}
	public function foto_perfil($imagen)
	{
		$array = array(	"imagen" => $imagen
			);
		$email = $this->session->userdata['user'];
		$this->db->where('email',$email);
		$this->db->update('empresa', $array);
	}
}