<?php
/*
 * Controlador para:
 * Vista de perfil profesional de usuario. -funcion u().
 * Ingresar un mensaje privado a un perfil profesional de usuario. -funcion ingresar_mensaje().
 * Ingresar un contrato entre las partes. -funcion contract().
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wuokers extends CI_Controller {

	public 	function __construct(){
		parent::__construct();
		$this->load->model('Resultado_model');
        $this->load->model('Account_model');
		$this->load->model('Valoracion_model');
		$this->load->model('Mensajes_model');
        $this->load->model('contrato_model');
		//$this->load->library('libraries/fechas');
		$this->load->helper('fechas');
		$this->load->helper('tags');
        $this->load->library('email');
        $this->load->library('email_registro');
		
	}
    
    /***************************************************************************
     * @ACTIVA LA VISTA DEL PERFIL DEL USUARIO
    ****************************************************************************/
	public function u($campobuscador,$nick_usuario)
	{

				$email =$this->session->userdata('user');
		        $data['title'] = ' WUORKS | El profesional que necesitas! 2015';
		   		$data["datos"] = $this->Resultado_model->getProfesionales($campobuscador,$nick_usuario);
                $id_perfil = $data['datos']->id_perfilp;
                $data['valoraciones'] = $this->Resultado_model->get_valoraciones($id_perfil);
                $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
                $data['ul_noti'] = $this->Account_model->notificaciones();
		   		//$data["valoracion"] = $this->Valoracion_model->obtenerValoraciones($data["datos"]);
		   		$this->load->view('template/header', $data);
				$this->load->view('perfil-de-usuario',$data);
				$this->load->view('template/footer');
	}
	
	/********************************************************************/
	/*@FUNCIÓN QUE GUARDAR EL MENSAJE REALIZADO A UN PERFIL PROFESIONAL
	/********************************************************************/
	public function  send_mensaje(){
		//if($this->input->is_ajax_request('enviar_mensaje'))
		//{
			$email = $this->session->userdata('user');

			$this->form_validation->set_rules('asunto','asunto','trim|required');
			$this->form_validation->set_rules('texto','mensaje','trim|required');
			$this->form_validation->set_message('required',' El  %s es obligatorio');
            
            $url_last = $this->input->post('url_last');
           
			if($this->form_validation->run() != FALSE){
                
				$data = array('asunto'	 =>  $this->input->post("asunto"),
							  'destinatario' => $this->input->post("destinatario"),
							  'emisor' => $this->input->post("emisor"),
							  'texto' => $this->input->post("texto")
							  	);
                
				$guardar = $this->Mensajes_model->guardar_mensaje($data);
                if($guardar){
                    //echo "success";
                    $this->exito_mensaje($data);
                }else{
                    echo "error";
                }
			}else{
				//echo validation_errors();
               
                redirect($url_last,'refresh');
			}
		//}
		//else{
		//	redirect(base_url());
		//}
	}//FIN INGRESA_CONTACtO();
    
    /***************************************************************************
     * @FUNCIÓN PARA GUARDAR LA ACCIÓN DE CONTRATO ENTRE PARTES
    ****************************************************************************/
    public function contract(){
        if($this->input->post('ingresa_contrato')){
          
            $destinatario = $this->input->post('destinatario');
            $comentario   = $this->input->post('texto');
            $id_perfil    = $this->input->post('cod_temporal');
            
            $url_destino  = $this->input->post('url_destino');
            
            $data = array('destinatario' => $destinatario,
                          'comentario'   => $comentario,
                          'id_perfil'    => $id_perfil);
            //1-Ingresamos la contratación
            $guardar = $this->contrato_model->ingresa_contrato($data);
            
            if($guardar){
                //buscamos los datos del contratador y envio de email
                $datos = $this->contrato_model->get_datos($destinatario,$id_perfil,$comentario);
                
                
                //redirect($url_destino,'refresh');
                $this->exito_contrato($datos);
            }else{
                echo 'error';
            }
        }else{
            redirect(base_url());
        }
    }
    
    //ACTIVA VISTA DE FIN DE CONTRATO
    
    public function exito_contrato($datos){
        
            $data['title'] = 'WUORKS | El profesional que necesitas! '.date("Y");
            $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
            $data['ul_noti'] = $this->Account_model->notificaciones();
            $data['datos_1'] = $datos['contratado'][0]['nick_usuario'];
            $data['datos_2'] = $datos['contratado'][0]['email'];
   			$this->load->view('template/header', $data);
            $this->load->view('template/menu-login-registro');
            $this->load->view('exito_contrato_view');
   			$this->load->view('template/footer');
    }
    
    /***************************************************************************
     * FUNCION PARA VALORAR UN USUARIO
    ***************************************************************************/
    public function valorar_usuario(){
        
        $tipo_val    = $this->input->post('tip_val');
        $id_contrato = $this->input->post('tempc');
        $comentario  = $this->input->post('comment');
       
        $ins = $this->contrato_model->valorar_usuario($tipo_val, $id_contrato, $comentario);
        
        echo "success";
    }
    
    /***************************************************************************
     * @Función para activar vista de exito mensaje
     **************************************************************************/
    public function exito_mensaje($datos){
        
            $data['title'] = 'WUORKS | El profesional que necesitas! '.date("Y");
            $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
            $data['ul_noti'] = $this->Account_model->notificaciones();
            $data['datos_1'] = $datos['destinatario'];
   			$this->load->view('template/header', $data);
            $this->load->view('template/menu-login-registro');
            $this->load->view('exito_mensaje_view');
   			$this->load->view('template/footer');
    }
    /***************************************************************************
     * @Función para recuperar password()
     **************************************************************************/
    public function recu_password(){
        
             $data['title'] = 'WUORKS | El profesional que necesitas! '.date("Y");
   			$this->load->view('template/header', $data);
            $this->load->view('template/menu-login-registro');
            $this->load->view('login-registro/recupera_password_view');
   			$this->load->view('template/footer');
    }
    public function rpas(){
        
        $this->form_validation->set_rules('email','email','trim|required|valid_email');
        $this->form_validation->set_message('required','%s es obligario');
        $this->form_validation->set_message('valid_email','Ingresa un email valido');
        if($this->form_validation->run() == TRUE){
            $email = $this->input->post('email');
            //$change = $this->
        //echo $email;
        }else{
            $this->recu_password(validation_errors());
        }
        
    }
    public function Aleatorio($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
	{
		$source = 'abcdefghijklmnopqrstuvwxyz';
		if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($n==1) $source .= '1234567890';
		if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
		if($length>0){
			$rstr = "";
			$source = str_split($source,1);
			for($i=1; $i<=$length; $i++){
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,count($source));
				$rstr .= $source[$num-1];
			}
	
		}
		return $rstr;
	}
}