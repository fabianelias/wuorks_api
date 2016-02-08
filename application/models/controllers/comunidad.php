<?php

   class Comunidad extends CI_Controller{

   	function __construct(){
		parent::__construct();
		$this->load->model('Principal_model');
		//$this->load->library('libraries/fechas');
		$this->load->helper('fechas');
		
	}
   	public function Index() {

   			$email =$this->session->userdata('user');
   		    $data['title'] = 'Comunidad WUORKS | El profesional que necesitas! 2015';
   		    $data["datos"] = $this->Principal_model->get_profesionales();
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu-lateral');
   			$this->load->view('comunidad/comunidad',$data);
   			$this->load->view('template/footer');
   	}

   } 
 ?>