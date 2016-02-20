<?php

Class Accounts extends CI_Controller{
    
    public function __construct() {
        
        parent::__construct();
        $this->load->model("Accounts_model");
        
    }
    
    public function index(){
        
        $data['title'] = "Wuorks.com | Empresas";
        $data['url_login']    = base_url()."company/accounts?type=log";
        $data['url_register'] = base_url()."company/accounts?type=reg";
        
        $this->load->view("template/header",$data);
        $this->load->view("template/menu-login-registro",$data);
        $this->load->view("company/accesos/acceso_view");
    }
    
    /***************************************************************************
     * @Función para verificar ingreso
    ***************************************************************************/
    public function signin(){
       if($this->input->post('entrar')){
            //VALIDAMOS LOS CAMPOS
            $this->form_validation->set_rules('email','E-mail','trim|required|valid_email');
            $this->form_validation->set_rules('clave','Clave','trim|required');
            //SETEAMOS LOS MENSAJES DE ERROR
            $this->form_validation->set_message('valid_email','Ingrese un e-mail valido');
            $this->form_validation->set_message('required','%s es obligatorio');
            $type = 2;
            if($this->form_validation->run() != FALSE){
                    $existe = $this->Accounts_model->verifica_usuario($this->input->post(),$type);
                    
                    if($existe){
                        $estado = $this->Accounts_model->verifica_estado();
                        if($estado){
                            //CONSEGUIMOS LOS DATOS PARA VARIABLES DE SESSION
                            $data['datos_usuarios'] = $this->Accounts_model->datos_usuarios();
                            
                            $session = array(
                                            'id_usuario'   => $data['datos_usuarios']->id_usuario,
                                            'nick_usuario' => $data['datos_usuarios']->nick_usuario,
                                            'email'        => $data['datos_usuarios']->email,
                                            'nombre'       => $data['datos_usuarios']->nombres.' '.$data['datos_usuarios']->apellidos,
                                            'tipo_usuario' => $data['datos_usuarios']->tipo_usuario,
                                            'user'         => $data['datos_usuarios']->email
                                            );
                            $this->session->set_userdata($session);
                            
                            if ($this->input->post('url_destino') != '') {
                                //echo $this->input->post('url_destino');}
                                $destino = $this->input->post('url_destino');
                                redirect($destino,'refresh');
                            }else{
                                
                                
                                redirect(base_url(),'refresh');
                            }
                            
                        }else{
                           // echo 'El usuario no esta activo | confirma tu cuenta de email';
                            $this->session->set_flashdata('valid1','El usuario no esta activo | confirma tu cuenta de email');
                            redirect(base_url()."accounts/login?url_destino=".$destino,'refresh');
                        }
                    }else{
                        if ($this->input->post('url_destino') != '') {
                                //echo $this->input->post('url_destino');}
                                $destino = $this->input->post('url_destino');
                                $this->session->set_flashdata('valid1','El e-mail o contraseña son incorrectos');
                                //$this->login();
                                redirect(base_url()."company/accounts?url_destino=".$destino,'refresh');
                            }else{
                                $this->session->set_flashdata('valid1','El e-mail o contraseña son incorrectos');
                                redirect(base_url()."company/accounts",'refresh');
                            }
                       
                    }
                
            }else{
                if ($this->input->post('url_destino') != '') {
                                //echo $this->input->post('url_destino');}
                                $destino = $this->input->post('url_destino');
                                $this->session->set_flashdata('valid1','El e-mail y contraseña son obligatorios');
                                //$this->login();
                                redirect(base_url()."company/accounts?url_destino=".$destino,'refresh');
                            }else{
                                $this->session->set_flashdata('valid1','El e-mail y contraseña son obligatorios');
                                redirect(base_url()."company/accounts",'refresh');
                            }
                //$this->login(validation_errors());
            }
        }else{
            redirect(login());
        }
    }
    
    /***************************************************************************
     * @Función para registros
    ***************************************************************************/
    public function register(){
        
        echo "function para registrar";
    }
}