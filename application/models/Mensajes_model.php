<?php

class Mensajes_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
        
	}

	public function guardar_mensaje($data){
        
		@date_default_timezone_get('America/Santiago');
		$fecha_ingreso = @date('Y-m-d');
        $id_usuario = $this->session->userdata('id_usuario');
        $emisor = $this->session->userdata('nick_usuario');
        $destinatario = $data['destinatario'];
        
       $email = $this->busca_email($destinatario);
        
		$datas = array('asunto'       => $data["asunto"],
					   'emisor'       => $emisor,
					   'destinatario' => $data["destinatario"],
					   'mensajes'      => $data["texto"],
					   'fecha_envio'  => $fecha_ingreso,
                       'estado'       => 0,
                       'id_usuario'   => $id_usuario
				 );
		//$insert = $this->db->insert('mensajes',$datas);
        error_reporting(0);
        
        
        
        $url_envio = base_url().'account/messages?';
        $nombre = $data['destinatario'];
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
		$this->email->clear();//
        

        $this->email->from('no-reply@wuorks.com', 'WUORKS | Necesito tu profesión');
        $this->email->to($email);
        $this->email->subject($nombre.', '.$data['asunto']);
        $this->email->message($this->email_registro->email_mensajes($nombre,$nom_profesion,$url_envio),TRUE);//Load a view into email body
        $this->email->send();
        
		return  $this->db->insert('mensajes',$datas);;

	}
    public function busca_email($destinatario){
        
        $this->db->select('email');
        $this->db->where('nick_usuario',$destinatario);
        $query = $this->db->get('usuarios');

        foreach ($query->result() as $row)
        {
            return $row->email;
        }
    }
    
    public function act_msg_read($id_mensaje){
        
        $data = array("estado" => 1);
        $this->db->where('id_mensaje',$id_mensaje);
        return $this->db->update('mensajes',$data);
    }

}


