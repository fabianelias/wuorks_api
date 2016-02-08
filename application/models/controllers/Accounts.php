<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Controller{

	function __construct(){
		parent::__construct();
        $this->load->model('Accounts_model');
		$this->load->model('Usuarios_model');
		$this->load->model('Empresas_model');
		$this->very_sesion();
        
        $this->load->library('email');
        $this->load->library('email_registro');
	}
    /***************************************************************************/
    /*FUNCIÓN PARA VERIFICAR SI EXISTE UNA SESSION CREADA
    /***************************************************************************/
	function very_sesion(){
		if($this->session->userdata('user')){
			redirect(base_url().'Welcome');
		}
	}// FIN PARA VERY_SESSIÓN();
    /***************************************************************************/
    /*FUNCIÓN PARA ACTIVAR LA VISTA DE LOGIN
    /***************************************************************************/
	public function login(){
		
    		$data['title'] = 'Entrar -WUORKS | El profesional que necesitas! '.date("Y");
            
            $data['url_login'] = base_url()."accounts/login";
            $data['url_register'] = base_url()."accounts/register";
            
            
   			$this->load->view('template/header', $data);
            $this->load->view('template/menu-login-registro',$data);
            $this->load->view('login-registro/login');
   			$this->load->view('template/footer');
   		
	}// FIN PARA LOGIN();
    /***************************************************************************/
    /*FUNCIÓN PARA ACTIVAR LA VISTA DE REGISTRO DE USUARIO 
    /***************************************************************************/
    public function register(){
        
        //DATOS PARA ENVIAR A LA VISTA
      $data['title'] = 'Registrarme en WUORKS | El profesional que necesitas. ©'.date("Y").'';
      $data['url_login'] = base_url()."accounts/login";
      $data['url_register'] = base_url()."accounts/register";
        //CARGAMOS LAS VISTAS
      $this->load->view('template/header',$data);
   		$this->load->view('template/menu-login-registro');
   		$this->load->view('login-registro/registro-usuario',$data);
   		$this->load->view('template/footer');
    }//FIN PARA REGISTER(); 
    /****************************************************************************/
    /* FUNCIÓN PARA VALIDAR EL INGRESO DE UN USUARIO MEDIANTE AJAX
    /****************************************************************************/
    public function validar_login(){
        
        if($this->input->is_ajax_request()){
            //VALIDAMOS LOS CAMPOS
            $this->form_validation->set_rules('email','E-mail','trim|required|valid_email');
            $this->form_validation->set_rules('clave','Clave','trim|required');
            //SETEAMOS LOS MENSAJES DE ERROR
            $this->form_validation->set_message('valid_email','Ingrese un e-mail valido');
            $this->form_validation->set_message('required','%s es obligatorio');
            
            if($this->form_validation->run() != FALSE){
                    $existe = $this->Accounts_model->verifica_usuario($this->input->post());
                    
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
                                echo $this->input->post('url_destino');
                            }else{
                                echo '<?php header("Location :'.base_url().'"); ?>';
                                
                                //redirect(base_url(),'location');
                            }
                            
                        }else{
                            echo 'El usuario no esta activo | confirma tu cuenta de email';
                        }
                    }else{
                        echo 'El e-mail o contraseña son incorrectos';
                    }
                
            }else{
                echo validation_errors();
            }
        }else{
            redirect(login());
        }
        
    }//FIN PARA VALIDAR_LOGIN();
	public function valida_usuario(){//
		if($this->input->post('entrar')){
            //VALIDAMOS LOS CAMPOS
            $this->form_validation->set_rules('email','E-mail','trim|required|valid_email');
            $this->form_validation->set_rules('clave','Clave','trim|required');
            //SETEAMOS LOS MENSAJES DE ERROR
            $this->form_validation->set_message('valid_email','Ingrese un e-mail valido');
            $this->form_validation->set_message('required','%s es obligatorio');
            
            if($this->form_validation->run() != FALSE){
                    $existe = $this->Accounts_model->verifica_usuario($this->input->post());
                    
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
                                redirect(base_url()."accounts/login?url_destino=".$destino,'refresh');
                            }else{
                                $this->session->set_flashdata('valid1','El e-mail o contraseña son incorrectos');
                                redirect(base_url()."accounts/login",'refresh');
                            }
                       
                    }
                
            }else{
                if ($this->input->post('url_destino') != '') {
                                //echo $this->input->post('url_destino');}
                                $destino = $this->input->post('url_destino');
                                $this->session->set_flashdata('valid1','El e-mail y contraseña son obligatorios');
                                //$this->login();
                                redirect(base_url()."accounts/login?url_destino=".$destino,'refresh');
                            }else{
                                $this->session->set_flashdata('valid1','El e-mail y contraseña son obligatorios');
                                redirect(base_url()."accounts/login",'refresh');
                            }
                //$this->login(validation_errors());
            }
        }else{
            redirect(login());
        }
		
	}//fin funcion valida_usuario
	//funcion llamar registro.php
	public function crear_cuenta(){
		   $data['title'] = 'Registro - WUORKS | El profesional que necesitas! 2015';
		   $data["regiones"] = $this->Usuarios_model->obtener_regiones();
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('registro',$data);
   			$this->load->view('template/footer');
	}
   
   
 //funcion llamar registro_empresa.php
	public function cuenta_empresa(){
		   $data['title'] = 'Registro - WUORKS | El profesional que necesitas! 2015';
		   $data["regiones"] = $this->Usuarios_model->obtener_regiones();
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('registro_empresa',$data);
   			$this->load->view('template/footer');
	}
	//muestra la view de login para empresas
	public function login_empresa(){
		
    		$data['title'] = 'Entrar -WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('loginEmpresa');
   			$this->load->view('template/footer');
   		
	}
	//Función que valida que exista la empresa
		public function valida_empresa(){//
		
		if($this->input->post('entrarEmpresa')){

			$this->form_validation->set_rules('email','Email','required|valid_email');
			$this->form_validation->set_rules('clave','Clave','required');
			$this->form_validation->set_message('required',' El campo %s es obligatorio');
			$this->form_validation->set_message('very_email',' El %s ya existe');

			if($this->form_validation->run() != FALSE){//

			$variable = $this->Empresas_model->very_sesion();

			if($variable == true){

				$estado= $this->Empresas_model->very_estado();

				if($estado == true){

					$variables  = array('user' => $this->input->post('email'), 'tipoUsuario' => 'empresa');
					$this->session->set_userdata($variables);
					if ($this->input->post('url_destino') != '') {
						redirect($this->input->post('url_destino'));
					}else{
						redirect(base_url().'empresas/Panel');
					}
					
				}else{
					$msj  = array('mensaje' => 'El usuario no esta activo | confirma tu cuenta de email' );
					$data['title'] = 'Entrar -WUORKS | El profesional que necesitas! 2015';
   					$this->load->view('template/header', $data);
   					$this->load->view('template/up-header');
   					//$this->load->view('template/inc-menu');
   					$this->load->view('loginEmpresa',$msj);
   					$this->load->view('template/footer');

				}
			}
			else{
				$msj  = array('mensaje' => 'El E-mail o contraseña son incorrectos' );
				$data['title'] = 'Entrar -WUORKS | El profesional que necesitas! 2015';
   				$this->load->view('template/header', $data);
   				$this->load->view('template/up-header');
   				//$this->load->view('template/inc-menu');
   				$this->load->view('loginEmpresa',$msj);
   				$this->load->view('template/footer');
			}
		}else{
			$data['title'] = 'Entrar -WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('loginEmpresa');
   			$this->load->view('template/footer');
		}
	  }//fin entrar
	}//fin funcion valida_usuario
	//funcion para guardar la cuenta creada
    /***************************************************************************/
    /*FUNCIÓN QUE INSERTA UN NUEVO REGISTRO A LA BD MEDIANTE AJAX
    /***************************************************************************/
    public function ingresar_registro(){
        
        if($this->input->is_ajax_request()){
            //VALIDAMOS LOS CAMPOS DE LA VISTA REGISTRO
            $this->form_validation->set_rules('email','e-mail','trim|required|callback_very_email');
            $this->form_validation->set_rules('nombres','nombre','trim|required');
            $this->form_validation->set_rules('apellidos','apellidos','trim|required');
            $this->form_validation->set_rules('clave','clave','trim|required|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('clave2','repetir clave','trim|required|matches[clave]');
            //SETIAMOS EL MENSAJE DE ERROR
            $this->form_validation->set_message('required','%s es obligatorio');
            $this->form_validation->set_message('matches','Las claves no coinciden');
            $this->form_validation->set_message('very_email',' El %s ya existe');
            $this->form_validation->set_message('valid_email','El %s ingresado debe ser valido');
		    $this->form_validation->set_message('matches','Las contraseñas no coinciden');
		    $this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
		    $this->form_validation->set_message('max_length','El %s debe tener un máximo de 12 caracteres');
            
            if($this->form_validation->run() != FALSE){
                //TODOS LOS CAMPOS CORRECTOS, CREAMOS UN ARRAY CON LOS DATOS OBTENIDOS
                
                $data_registro = array(
                                'email'     => $this->input->post('email'),
                                'nombres'   => $this->input->post('nombres'),
                                'apellidos' => $this->input->post('apellidos'),
                                'clave'     => $this->input->post('clave')
                                );
                $registro = $this->Accounts_model->ingresar_registro($data_registro);
                
                
                if($registro){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }else{
                echo validation_errors();
            }
        }else{
            redirect(base_url());
        }
        
    }//FIN PARA INSERTA_REGISTRO(); 
     function send_message($message, $email_dest, $subject){
        //Cargar libreria email
        $this->load->library('email');
        //Configurar mensaje
        $this->email->from('no-reply@wuorks.com', 'WUORKS');
        $this->email->to($email_dest);
        $this->email->subject($subject);
        //Solicitud validación precio de toma ID #
        $this->email->message($message);
        if($this->email->send()){
            
            return true;
            
        } else {
            
            return false;
        }    
    }
 public function guardar_cuenta(){

		if($this->input->post('registrar'))
		{
			$this->form_validation->set_rules('email','Email','required|valid_email|callback_very_email');
		$this->form_validation->set_rules('nombres','Nombre','trim|required');
		$this->form_validation->set_rules('apellidos','apellidos','trim|required');
		$this->form_validation->set_rules('rut','Rut','trim|required|callback_very_rut|min_length[8]|max_length[12]');
		$this->form_validation->set_rules('clave','Clave','trim|required|min_length[6]|max_length[12]');
		$this->form_validation->set_rules('clave2','Repite Clave','trim|required|matches[clave]');
		$this->form_validation->set_message('required','  %s es obligatorio');
		$this->form_validation->set_message('very_email',' El %s ya existe');
		$this->form_validation->set_message('very_rut',' El %s ya existe');
		$this->form_validation->set_message('valid_email','El %s ingresado debe ser valido');
		$this->form_validation->set_message('matches','Las contraseñas no coinciden');
		$this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
		$this->form_validation->set_message('max_length','El %s debe tener un máximo de 12 caracteres');

		if($this->form_validation->run() != FALSE){

			$email = $this->Usuarios_model->agregar_usuario($this->input->post());
			$msj  = array('mensaje' => "Se ha creado la cuenta con exito, se ha enviado un correo de confirmación al Email" );
			$data['title'] = 'Registro - WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('registro',$msj);
   			$this->load->view('template/footer');
				
		}else{
			$data['title'] = 'Registro - WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('registro');
   			$this->load->view('template/footer');	

			}	
		  }

		}
		public function guardar_cuenta_empresa(){

		if($this->input->post('registrar_empresa'))
		{
			$this->form_validation->set_rules('email','Email','required|valid_email|callback_very_email_empresa');
			$this->form_validation->set_rules('nombre','Nombre','trim|required');
			$this->form_validation->set_rules('giro','Giro','trim|required');
			$this->form_validation->set_rules('rutEmpresa','Rut de Empresa','trim|required|callback_very_rut_empresa|min_length[8]|max_length[10]');
			$this->form_validation->set_rules('clave','Clave','trim|required|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('clave2','Repite Clave','trim|required|matches[clave]');
			$this->form_validation->set_message('required',' El campo %s es obligatorio');
			$this->form_validation->set_message('very_email_empresa',' El %s ya existe');
			$this->form_validation->set_message('very_rut_empresa',' El %s ya existe');
			$this->form_validation->set_message('valid_email','El %s ingresado debe ser valido');
			$this->form_validation->set_message('matches','Las contraseñas no coinciden');
			$this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
			$this->form_validation->set_message('max_length','El %s debe tener un máximo de 12 caracteres');

		if($this->form_validation->run() != FALSE){

			$email = $this->Empresas_model->agregar_empresa($this->input->post());
			$msj  = array('mensaje' => "Se ha creado la cuenta con exito, se ha enviado un correo de confirmación al Email" );
			$data['title'] = 'Registro - WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('registro_empresa',$msj);
   			$this->load->view('template/footer');
				
		}else{
			$data['title'] = 'Registro - WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			//$this->load->view('template/inc-menu');
   			$this->load->view('registro_empresa');
   			$this->load->view('template/footer');	

			}	
		  }

		}
		//uncion que verifica que  no exista el email.
		public function very_email($email){
			$variable = $this->Accounts_model->very_email($email);
			if($variable == true){
				return false;
			}else{
				return true;
			}
		}
		//funcion que verifica quer el rut no exista
		public function very_rut($rut){
			$variable = $this->Usuarios_model->very_rut($rut);
			if($variable == true){
				return false;
			}else{
				return true;
			}
		}
        /***********************************************************************
         * @FUNCIÓN PARA VERIFICAR EMAIL DE CUENTA CREADA
        ************************************************************************/
		public function verify_email(){
            $code = $_GET['token'];
			$msj  = array('mensaje' => "Se ha validado con exito tu email, ahora puedes comenzar" );
            $msj_2  = array('mensaje2' => "Error de validación, email no existe" );
			$up = $this->Usuarios_model->update_user($code);
            if($up){
				$data['title'] = 'Entrar -WUORKS | El profesional que necesitas! '.date("Y");
                $this->load->view('template/header', $data);
                $this->load->view('template/menu-login-registro');
                //$this->load->view('login');
                $this->load->view('login-registro/login',$msj);
                $this->load->view('template/footer');
            }else{
                $data['title'] = 'Entrar -WUORKS | El profesional que necesitas! '.date("Y");
                $this->load->view('template/header', $data);
                $this->load->view('template/menu-login-registro');
                //$this->load->view('login');
                $this->load->view('login-registro/login',$msj);
                $this->load->view('template/footer');
            }
			
		}


		//funciones que verifica quer el rut, email y codigo  de la empresa 
	  //funcion que verifica que  no exista el email.
		public function very_email_empresa($email){
			$variable = $this->Empresas_model->very_email_empresa($email);
			if($variable== true){
				return false;
			}else{
				return true;
			}
		}
		//funcion que verifica quer el rut no exista
		public function very_rut_empresa($rutEmpresa){
			$variable = $this->Empresas_model->very_rut($rutEmpresa);
			if($variable == true){
				return false;
			}else{
				return true;
			}
		}
		public function confirmar_emailEmpresas($codeConfirm){
			$res = $ $this->Usuarios_model->very_codigo($codeConfirm,'codeConfirm');
			if($res == false){
				echo"Este usuario no existe";
			}else{

				$this->Empresas_model->update_empresa($codConfirm);
				echo"usuario confirmado con exito.<br>
				<a href='".base_url()."accounts/login'>Iniciar Sesion</a>
				";
			}
		}

	

}// end ci_controller