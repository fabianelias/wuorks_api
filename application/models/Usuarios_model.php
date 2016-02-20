<?php

class Usuarios_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function valida_usuario($datos)
	{
		$array = array("email" => $datos["email"], "clave" => md5($datos["clave"]));
		$query = $this->db->where($array)->get("user");

		if($query->num_rows() == 1) return $query->row();

		return FALSE;
	}

	public function agregar_usuario($datos)
	{
		$code_confirm = rand(9999,999999)."wuorks_confirmacion_cuenta";
		$nom = substr($datos["nombres"], 0, 2);  // devuelve "ab"
		$ape = substr($datos["apellidos"], 0, 5);  // devuelve "ab"
		$ale = rand(111,999);
		$nick = '@'.$nom.'_'.$ape.$ale;
		$fecha_ingreso = date("Y-m-d H:i:s");
		$fecha_nacimiento = date("0000-00-0");
		$array = array(
			"nombres" => $datos["nombres"],
			"apellidos" => $datos["apellidos"],
			"rut" => $datos["rut"],
			"email" => $datos["email"],
			"password" => md5($datos["clave"]),
			"comuna" => "No especificada",
			"region" => "No especificada",
			"fecha_ingreso" => $fecha_ingreso,
			"fecha_nacimiento" => $fecha_nacimiento,
			"telefono_celular" => "",
			"telefono_casa" => "",
			"tipo_cuenta" => "normal",
			"tipo_usuario" => "profesional",
			"estado" => "0",
			"code_corfim" => $code_confirm,
			"nick_usuario" =>$nick,
			"imagen" =>"profile-wuork250x250.png",
		);
		$insert = $this->db->insert('user', $array);

		$this->load->library('email');

		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
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

	public function very_email($email){

		$consulta = $this->db->get_where('user',  array('email' => $email ));
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
		$consulta = $this->db->get_where('user',array($campo=>$variable));
		if($consulta->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}
	public function update_user($code_confirm){

		$this->db->where('code_confir',$code_confirm);
	 $up = $this->db->update('usuarios', array('estado' => '1' ));
     return $up;
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
	public function very_estado(){
		$clave = $this->input->post('clave',TRUE);
		$clave = md5($clave);
		$consulta = $this->db->get_where('user', array('email' => $this->input->post('email',TRUE),
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