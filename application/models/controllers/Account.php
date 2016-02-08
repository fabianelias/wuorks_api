<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {


	function __construct(){
		parent::__construct();
		$this->load->model('Account_model');
		$this->load->model('Regiones_model');
        $this->load->model('Mensajes_model');
		$this->load->helper('fechas');
		$this->very_sesion();
	}
	function very_sesion(){
		if(!$this->session->userdata('user')){
			redirect(base_url().'accounts/login');
		}
	}
    /****************************************************************************/
    /*FUNCIÓN PARA ACTIVAR LA VISTA DE PERFIL
    /****************************************************************************/
    public function profile(){
            $email =$this->session->userdata('user');
            $id_usuario = $this->session->userdata('id_usuario');
            $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
            $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
			$data['regiones'] = $this->Regiones_model->obtenerRegiones();
            $data["datosPerfil"] = $this->Account_model->obtener_perfiles($email);
			$data['title'] = 'Mi perfil -WUORKS | El profesional que necesitas! 2015';
            
            
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			$this->load->view('template/inc-menu-lateral');
   			$this->load->view('cuentas/profile',$data);
   			$this->load->view('template/footer');
    }//FIN PROFILE();
	
    /***************************************************************************/
    /*FUNCIÓN PARA MOSTRAR LA VISTA DE PERFILES PROFESIONALES
    /***************************************************************************/
    public function professionalProfile(){
        
        $email = $this->session->userdata('user');
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data['ul_noti'] = $this->Account_model->notificaciones();
        $id_usuario = $this->session->userdata('id_usuario');
        $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
		$data["datosPerfil"] = $this->Account_model->obtener_perfiles($email);
        $data["nombre_profesiones"] = $this->Account_model->obtener_nomProfesiones();
		$data['regiones'] = $this->Regiones_model->obtenerRegiones();
		$data["title"] = 'Perfil profesional -WUORKS | El profesional que necesitas! 2015';	

		$this->load->view('template/header', $data);
   		$this->load->view('template/up-header');
   		$this->load->view('template/inc-menu-lateral');
   		$this->load->view('cuentas/mis-profesiones',$data);
   		$this->load->view('template/footer');
    }//FIN PROFESSIONALPROFILE();
     /**************************************************************************
      * @FUNCION PARA VISTA DE MENSAJES
    ***************************************************************************/
    public function messages(){
       
        $email = $this->session->userdata('user');
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data['ul_noti'] = $this->Account_model->notificaciones();
        $id_usuario = $this->session->userdata('id_usuario');
        //traemos los mensajes
        
        $data['msj_enviados'] = $this->Account_model->mensajes_enviados();
        $data['msj_enviados_v2'] = $this->Account_model->mensajes_enviados_v2();
        
        $data['msj_recibidos'] = $this->Account_model->mensajes_recibidos();
        $data['msj_recibidos_v2'] = $this->Account_model->mensajes_recibidos_v2();
        $data['nombre_profesiones'] = $this->Account_model->obtener_nomProfesiones();
        $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
        $data['regiones'] = $this->Regiones_model->obtenerRegiones();
        $data['title'] = 'Bandeja de mensajes - WUORKS | El profesional que necesitas ';
        
        //CArgamos las vistas
        $this->load->view('template/header',$data);
        $this->load->view('template/up-header');
        $this->load->view('template/inc-menu-lateral');
        $this->load->view('cuentas/mensajes',$data);
        $this->load->view('template/footer');
    }
    public function msg_read(){
        
        $id_mensaje = $this->input->get('msg');
        $act = $this->Mensajes_model->act_msg_read($id_mensaje);
        
    }
    /***************************************************************************
     * @Función para vista de contratos
     **************************************************************************/
    public function contract(){
        $email = $this->session->userdata('user');
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data['ul_noti'] = $this->Account_model->notificaciones();
        $id_usuario = $this->session->userdata('id_usuario');
        //traemos los mensajes
        
        
        $data['nombre_profesiones'] = $this->Account_model->obtener_nomProfesiones();
        $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
        $data['regiones'] = $this->Regiones_model->obtenerRegiones();
        $data['title'] = 'Contratos - WUORKS | El profesional que necesitas ';
        $data['contract'] = $this->Account_model->mis_contratos($id_usuario);
        $data['contract_v2'] = $this->Account_model->mis_contratados ($id_usuario);
        
        //CArgamos las vistas
        $this->load->view('template/header',$data);
        $this->load->view('template/up-header');
        $this->load->view('template/inc-menu-lateral');
        $this->load->view('cuentas/contratos_view',$data);
        $this->load->view('template/footer');
    }
    public function contract_details($token, $id_contrato, $tipo){
        $email = $this->session->userdata('user');
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data['ul_noti'] = $this->Account_model->notificaciones();
        $id_usuario = $this->session->userdata('id_usuario');
        //traemos los mensajes
        
        
        $data['nombre_profesiones'] = $this->Account_model->obtener_nomProfesiones();
        $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
        $data['regiones'] = $this->Regiones_model->obtenerRegiones();
        $data['title'] = 'Contratos - WUORKS | El profesional que necesitas ';
        $data['details_contract'] = $this->Account_model->detalle_contrato($id_contrato, $id_usuario, $tipo);
        
        if($data['details_contract'] == false){
            
            redirect(base_url().'account/contract');
        }else{
            //CArgamos las vistas
            $this->load->view('template/header',$data);
            $this->load->view('template/up-header');
            $this->load->view('template/inc-menu-lateral');
            $this->load->view('cuentas/detalle_contrato_view',$data);
            $this->load->view('template/footer');
        }
    }
    
    /***************************************************************************/
    /*FUNCIÓN PARA INGRESAR UNA NUEVA PROFESIONA VÍA AJAX
    /***************************************************************************/
    public function ingresar_profesion(){
        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('profesion','profesión','trim|required');
            $this->form_validation->set_rules('aptitudes','aptitudes','trim|required');
            $this->form_validation->set_rules('descripcion','descripción','trim|required');
            $this->form_validation->set_rules('valor_hora','valor hora','trim|required');
            $this->form_validation->set_rules('lugar_trabajo','lugar de trabajo','trim|required');
            $this->form_validation->set_message('required',' %s es obliglatorio');
            
            //Perfil del usuario completo?
            $veri_perfil =  $this->Account_model->veri_perfil();
            if($veri_perfil){
                
                if ($this->form_validation->run() != FALSE){
                        $dataProf = array(
                                    'profesion' => $this->input->post("profesion"),
                                    'aptitudes' => $this->input->post("aptitudes"),
                                    'descripcion' => $this->input->post("descripcion"),
                                    'valor_hora' => $this->input->post("valor_hora"),
                                    'lugar_trabajo' => $this->input->post("lugar_trabajo"),
                                    'show_info' => $this->input->post("show_info")  
                                    );
                        $guardar = $this->Account_model->ingresar_profesion($dataProf);
                        if($guardar){
                            echo "success";
                        }else{
                            echo "error";
                        }
                }
                else{
                    echo validation_errors();
                }
            }else{
                    echo 'error_3';
                }
        }else{
            redirect(base_url());
        }
    }//FIN INGRESAR_PROFESION();
	public function actualizar_datos()
	{
			$email =$this->session->userdata('user');
			//$data["datos"] = $this->Account_model->obtener_datos($email);
            $id_usuario = $this->session->userdata('id_usuario');
            $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
			$data['regiones'] = $this->Regiones_model->obtenerRegiones();
			$data['comunas'] = $this->Regiones_model->obtenerComunas();
			$data['title'] = 'Actualizar mis datos Personales -WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			$this->load->view('template/inc-menu-lateral');
   			$this->load->view('cuentas/actualizar-datos',$data);
   			$this->load->view('template/footer');
	}
	public function editar_profesion($id){

		    $email =$this->session->userdata('user');
		    $data["datos"] = $this->Account_model->obtener_datos($email);
			$data["datos_profesion"] = $this->Account_model->obtener_profesion($id);
			$data["nombre_profesiones"] = $this->Account_model->obtener_nomProfesiones();
			$data['regiones'] = $this->Regiones_model->obtenerRegiones();
			$data['title'] = 'Actualizar mis datos de Profesión -WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			$this->load->view('template/up-header');
   			$this->load->view('template/inc-menu-lateral');
   			$this->load->view('cuentas/editar-profesion',$data);
   			$this->load->view('template/footer');

	}
	public function actualizar_profesion($id){
		if($this->input->post('editar_prof'))
		{
		
		$this->form_validation->set_rules('profesion','Profesión','trim|required');
		$this->form_validation->set_rules('descripcion','Descripcion','trim|required');
		$this->form_validation->set_rules('aptitudes','Aptitudes','trim|required');
		$this->form_validation->set_rules('valor_hora','Valor de hora','trim|required');
		$this->form_validation->set_rules('lugar_trabajo','Lugar de trabajo','trim|required');
		//$this->form_validation->set_rules('show_info','Mostrar informacion','trim|required');
		$this->form_validation->set_message('required','%s es obligatorio');

		if($this->form_validation->run() != FALSE){

			$email = $this->Account_model->modificar_profesion($this->input->post());
   			$this->session->set_flashdata('mensaje', 'Se ha modificado tu profesión correctamente');
   			    redirect('account/professionalProfile','refresh');
				
		}else{
			$email =$this->session->userdata('user');
		    $data["datos"] = $this->Account_model->obtener_datos($email);
			$data["datos_profesion"] = $this->Account_model->obtener_profesion($id);
			$data['title'] = 'Actualizar mis datos Personales -WUORKS | El profesional que necesitas! 2015';
   			$this->session->set_flashdata('mensaje', 'Se han modificado tus datos correctamente');
   			    redirect('account/professionalProfile','refresh',$data);

			}	
		  }

		}

	
	public function actualizar($type = '')
	{
		error_reporting(0);
		if($this->input->post('actualizar'))
		{
		
		$this->form_validation->set_rules('nombres','Nombres','trim|required');
		$this->form_validation->set_rules('apellidos','apellidos','trim|required');
		$this->form_validation->set_rules('region','Region','trim|required');
		$this->form_validation->set_rules('comuna','Comuna','trim|required');
		$this->form_validation->set_rules('fecha_nacimiento','Fecha de nacimiento','trim|required');
		$this->form_validation->set_rules('telefono_celular','Nro. de celular','trim|required');
		$this->form_validation->set_message('required','%s es obligatorio');
		$this->form_validation->set_message('matches','Las contraseñas no coinciden');
		$this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
		$this->form_validation->set_message('max_length','El %s debe tener un máximo de caracteres');

		if($this->form_validation->run() != FALSE){

			$email = $this->Account_model->update_usuario($this->input->post());
   			$this->session->set_flashdata('mensaje', 'Se han modificado tus datos correctamente');
            if($type == 2){
                redirect(base_url().'company/account/u','refresh');
            }else{
   			    redirect('account/profile','refresh');
            }
				
		}else{
            if($type == 2){
                //cargamos data para la vista
                $this->session->set_flashdata("mensaje","Todos los campos son obligatorios");
                redirect(base_url()."company/account/u","refresh");
            }else{

                $email =$this->session->userdata('user');
                $data["datos"] = $this->Account_model->obtener_datos($email);
                $data['title'] = 'Mi perfil - WUORKS | El profesional que necesitas! 2015';
                $this->load->view('template/header', $data);
                $this->load->view('template/up-header');
                $this->load->view('template/inc-menu-lateral');
                $this->load->view('cuentas/actualizar-datos');
                $this->load->view('template/footer');	
              }
			}	
		  }

		}
		public function cambiar_clave(){
			if($this->input->post('actualizar_clave'))
		{
		
		$this->form_validation->set_rules('clave','Clave','trim|required|min_length[6]|max_length[35]');
		$this->form_validation->set_rules('clave2','Repite Clave','trim|required|matches[clave]');
		$this->form_validation->set_message('required','%s es obligatorio');
		$this->form_validation->set_message('matches','Las contraseñas no coinciden');
		$this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
		$this->form_validation->set_message('max_length','El %s debe tener un máximo de caracteres');

		if($this->form_validation->run() != FALSE){

			$email = $this->Account_model->modificar_clave($this->input->post());
   			$this->session->set_flashdata('mensaje', 'La clave se ha cambiado correctamente');
   			    redirect('account/profile','refresh');
				
		}else{
			/*$email =$this->session->userdata('user');
			$data["datos"] = $this->Account_model->obtener_datos($email);
			$data['title'] = 'Mi perfil - WUORKS | El profesional que necesitas! 2015';
   			$this->load->view('template/header', $data);
   			//$this->load->view('template/up-header');
   			$this->load->view('template/inc-menu-lateral');
   			$this->load->view('cuentas/actualizar-datos');
   			$this->load->view('template/footer');	*/
            $this->actualizar_datos(validation_errors());

			}	
		  }

		}
		public function subir_imagen($type=''){
			if($this->input->post('foto_perfil'))
		{

		   if ($_FILES['userfile']['name'] != '') {

		   		$respuesta = $this->upload_image();
		   		if (!is_array($respuesta)) {

		   			$this->Account_model->foto_perfil($respuesta);
   					$this->session->set_flashdata('mensaje', 'La imagen se ha cambiado correctamente');
                    if($type == 2){
                        redirect(base_url().'company/account/u','refresh');
                    }else{
                        redirect('account/profile','refresh');
                    }
		   		}else{
		   			
				$this->session->set_flashdata('mensaje', 'Error al cambiar la imagen');
                    if($type == 2){
                            redirect(base_url().'company/account/u','refresh');
                    }else{
                        redirect('account/actualizar_datos','refresh');
                    }
   			    

		   		}

		   
			}else{
				$this->session->set_flashdata('mensaje', 'Selecciona una imagen');
                if($type == 2){
                            redirect(base_url().'company/account/u','refresh');
                    }else{
                        redirect('account/actualizar_datos','refresh');
                    }
   			    
			}

			
		  }

		}
		function upload_image(){
			$file_name = md5($this->session->userdata('user')).'profile';
			$config['upload_path'] = './asset/images/users/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '100';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
			$config['file_name'] = '_'.$file_name.'_';
			

			$this->load->library('upload', $config);
            $this->upload->initialize($config);
			if (!$this->upload->do_upload()) {
				
				$error  = array('error' => $this->upload->display_errors());
				//$error = $config['file_name'];
				return $error;
			}else{
               // unlink(base_url().'asset/images/users/_'.$file_name.'_');
				//$data = array('upload_data' => $this->upload->data());
				$data = $this->upload->data();
				$this->create_thumb($data['file_name']);
				return $data['file_name'];
			}
		}
		function create_thumb($imagen){
		$config['image_library'] = 'gd2';
		$config['source_image'] = './asset/images/users/'.$imagen;
		$config['new_image'] = './asset/images/thumbs_user/';
		$config['thumb_marker'] = '_thumb';
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 100;
		$config['height'] = 100;

		$this->load->library('image_lib',$config);
		$this->image_lib->resize();
		}
		function get_profesiones(){
    if (isset($_GET['term'])){
      $q = strtolower($_GET['term']);
      $this->Account_model->get_profesiones();
    }
  }

	

}