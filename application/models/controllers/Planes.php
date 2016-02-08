<?php
Class Planes extends CI_Controller{
    
    public function __construct() {
        
        parent::__construct();
        $this->load->library('encrypt');
        //carga modelos
        //$this->load->model('planes_model);
        $this->load->model('Account_model');
    }
    public function very_sesion(){
		if(!$this->session->userdata('user')){
			redirect(base_url().'accounts/login');
		}
	}
    
    public function index(){
        
            $data['title'] = 'Elige tu plan -WUORKS | El profesional que necesitas! '.date("Y");
            $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
            $data['ul_noti'] = $this->Account_model->notificaciones();
   			$this->load->view('template/header', $data);
            $this->load->view('template/menu-login-registro');
            $this->load->view('wuorks-pro/planes_view');
   			$this->load->view('template/footer');
    }
    
    public function p($nom_p,$id){
            
            $this->very_sesion();
            $email_1 = $this->session->userdata('user');
            $key = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $email_e = $this->encrypt->encode($email_1,$key);
            $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
            $data['ul_noti'] = $this->Account_model->notificaciones();
            $this->load->library('encrypt');
            $plan = array();
            
            if($id == 2){
                $plan['tipo_plan']  = 'Plan WUORKS-PRO';
                $plan['valor_plan'] = 9590;
                $plan['id_plan']    = 2;
                $plan['duracion_plan']='6 meses';
                
                //datos encriptados
                $plan['email_e'] = $email_e;
                
            }elseif ($id == 3) {
                $plan['tipo_plan'] = 'Plan WUORKS-PLATA';
                $plan['valor_plan'] = 6990;
                $plan['id_plan']    = 3;
                $plan['duracion_plan']='3 meses';
                //datos encriptados
                $plan['email_e'] = $email_e;
            }else{
                $plan['tipo_plan'] = 'Plan WUORKS-BRONCE';
                $plan['valor_plan'] = 2990;
                $plan['id_plan']    = 4;
                $plan['duracion_plan']='1 mes';
                //datos encriptados
                $plan['email_e'] = $email_e;
            }
            
            $data['title'] = 'Elige tu plan 2 -WUORKS | El profesional que necesitas! '.date("Y");
            $data['plan'] = $plan;
            
   			$this->load->view('template/header', $data);
            $this->load->view('template/menu-login-registro');
            $this->load->view('wuorks-pro/planes_2_view');
   			$this->load->view('template/footer');
    }
    public function verify_plan(){
        $this->very_sesion();
        echo 'verificando pago';
    }
    public function confirm_plan(){
        $this->very_sesion();
        $token = $_GET['token'];
        $email_1 = $this->session->userdata('user');
        //$email = utf8_decode($email);
        echo 'compra correcta, realizar procedimiento email '.$email_1;
        echo $token;
    }
    public function error_plan(){
        $this->very_sesion();
        echo 'error de pago plan';
    }
}