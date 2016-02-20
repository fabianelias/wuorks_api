<?php

class Contacto_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
        
        //solo para ingreso de email pre- lanzamiento
       
	}

	public function agregar_contacto($datos)
	{
		$code_contacto = rand(9999,999999)."wuorks.2#657#20";
		$fecha_ingreso = date("Y-m-d H:i:s");

		$array = array(
			"nombre" => $datos["nombre"],
			"email" => $datos["email"],
			"mensaje" => $datos["mensaje_contacto"],
			"code_contacto" => $code_contacto,
			"tipo_de_contacto" => $datos["tipo_contacto"],
			"fecha_ingreso" => $fecha_ingreso
			);

		$insert = $this->db->insert('contactos', $array);

		$this->load->library('email');

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

		$this->email->from('fabian.bravo93@gmail.com','WUORKS | El profesional que necesitas!.');
		$this->email->to($this->input->post('email',TRUE));
		$this->email->subject('Mensaje con exito - WUORKS -');
		$this->email->message('<h1>Hola, '.$this->input->post('nombres').' '.$this->input->post('apellidos').'</h1>
			<br>
			<p>tú mensaje nos ha llegado con exito, te contactaremos a la brevedar.</p><br>
			<p>Aqui tu codigo de ingreso:'.$code_contacto.'</p><br>
			<br> <b>Saludos, equipo de WUORKS.<b>
			');
		$this->email->send();
	}
    /************************
     * @funcion para ingresar email de vista  "pronto"
     */
    public function email_pronto($email){
        
        $data = array('email' => $email);
        
        $this->db->insert('emails_pre',$data);
        return true;
    }

		

		
}