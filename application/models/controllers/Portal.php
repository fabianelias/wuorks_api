<?php

class Portal extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Contacto_model');
        $this->load->model('Account_model');
	}
	public function contacto()
	{
		$data['title'] = 'WUORKS | El profesional que necesitas! 2015';
            $data['ul_msj'] = $this->Account_model->ultimos_mensajes(); 
            $data['ul_noti'] = $this->Account_model->notificaciones();
   			$this->load->view('template/header', $data);
   			//$this->load->view('template/inc-menu');
   			$this->load->view('contacto');
   			$this->load->view('template/footer');
	
	}
		public function terminos() {


   		 $data['title'] = 'Terminos y condiciones - WUORKS | El profesional que necesitas! 2015';

   			$this->load->view('template/header', $data);
   			//$this->load->view('template/inc-menu');
   			$this->load->view('terminos-condiciones');
   			$this->load->view('template/footer');
   		}
   			public function politicas() {

   		    $data['title'] = 'Politicas de Seguridad - WUORKS | El profesional que necesitas! 2015';

   			$this->load->view('template/header', $data);
   			//$this->load->view('template/inc-menu');
   			$this->load->view('politicas-de-seguridad');
   			$this->load->view('template/footer');
   		}
   	public function que_es_wuorks()
	{
		$data['title'] = 'Qué es WUORKS | El profesional que necesitas! 2015';

   		$this->load->view('template/header', $data);
		$this->load->view('que-es-wuorks');
		$this->load->view('template/footer');
	
	}
		public function enviar_contacto(){
		if($this->input->post('enviar_cont')){

			$this->form_validation->set_rules('nombre','Nombres','required');
			$this->form_validation->set_rules('email','E-mail','required|valid_email');
			$this->form_validation->set_rules('tipo_contacto','Motivo de contacto','required');
			$this->form_validation->set_rules('mensaje_contacto','Mensaje','required');

			$this->form_validation->set_message('required','%s es obligatorio');
			$this->form_validation->set_message('valid_email','El %s ingresado debe ser valido');

			if($this->form_validation->run() != FALSE){
					
				$email = $this->Contacto_model->agregar_contacto($this->input->post());	

				//$data['title'] = 'WUORKS | El profesional que necesitas! 2015';
				//$msg   = array('mensaje' => "¡Se ha enviado el mensaje con exito!." );
   			    //$this->load->view('template/header', $data);
   			    //$this->load->view('template/inc-menu');
   			    //$this->load->view('contacto',$msj);
   			    //$this->load->view('template/footer');
   			    //$this->session->set_flashdata('men', $msg);
   			    
   			     $this->session->set_flashdata('mensaje', 'Mensaje enviado correctamente!');
   			    redirect('Welcome','refresh');
			}else{
				$data['title'] = 'WUORKS | El profesional que necesitas! 2015';
   			    $this->load->view('template/header', $data);
   			    //$this->load->view('template/inc-menu');
   			    $this->load->view('contacto');
   			    $this->load->view('template/footer');
   			    
			}
		}

	}
	public function empresas() {

   		    $data['title'] = 'WUORKS | El profesional que necesitas! 2015';

   			$this->load->view('template/header', $data);
   			//$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('empresas/empresas');
   			$this->load->view('template/footer');
   		}
   

    
}