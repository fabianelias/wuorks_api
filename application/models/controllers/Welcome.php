<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Regiones_model');
        $this->load->model('Account_model');
	}

	public function index()
	{
		$data['title'] = 'WUORKS | El profesional que necesitas! 2015';
		$data['regiones'] = $this->Regiones_model->obtenerRegiones();
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data['ul_noti'] = $this->Account_model->notificaciones();
   		$this->load->view('template/header', $data);
		$this->load->view('index',$data);
		$this->load->view('template/footer');
	}
    public function envio(){
        $this->load->library('email');
        $this->email->initialize(array(
              'protocol' => 'smtp',
              'smtp_host' => 'smtp.mandrillapp.com',
              'smtp_user' => 'fabian_bravo@live.com',
              'smtp_pass' => 'v5Oh_LTqtPfoQfJhxPUn2Q',
              'smtp_port' => 587,
              'mailtype' => 'html',
              'crlf' => "\r\n",
              'newline' => "\r\n"
           ));

        $this->email->from('no-reply@wuorks.com', 'from name');
        $this->email->to('fabian_bravo@live.com');
        $this->email->subject('The Subject');
        $this->email->message('email_view',TRUE);//Load a view into email body
        $this->email->send();
        echo $this->email->print_debugger(); //For Debugging Purpose
    }
    
   
}
