<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Pronto extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("Contacto_model");
    }
    
    public function index(){
        
        
        
        $this->load->view('p/pronto_view');
    
    }
    public function p(){
        
            $email = $this->input->post('email');

            $e = $this->Contacto_model->email_pronto($email);

            if($e == true){
                echo "success";
            }else{
                echo "error";
            }
    }
}