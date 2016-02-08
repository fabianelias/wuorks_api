<?php

Class Account extends CI_Controller{
    
    public function __construct() {
        
        parent::__construct();
        $this->very_sesion();
        $this->load->model("Account_model");
        $this->load->model("Regiones_model");
        
        //modelos solo de compañias
        $this->load->model("company/Account_company_model");
        
        $this->id_usuario = $this->session->userdata('id_usuario');
        
    }
    function very_sesion(){
		if(!$this->session->userdata('user')){
			redirect(base_url().'company/accounts?');
		}
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
     * @Función para vista profile user
    ***************************************************************************/
    public function u($name = ''){
        //cargamos data para la vista
        $data['active'] = 'profile';
        $data['titulo'] = 'WUORKS | Empresas ';
        $data['title'] = ucfirst($this->session->userdata('nombre'));
        //datos del perfil
        $id_usuario = $this->session->userdata('id_usuario');
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data["datos"] = $this->Account_model->obtener_informacion($id_usuario);
        $data['regiones'] = $this->Regiones_model->obtenerRegiones();
		$data['comunas'] = $this->Regiones_model->obtenerComunas();
        
        $this->load->view('company/template/header_inc',$data);
        $this->load->view('company/template/menu_inc',$data);
        $this->load->view('company/profile/profile_user_view',$data);
        $this->load->view('company/template/footer_inc');
    }
    
    /***************************************************************************
     * @Función para registros
    ***************************************************************************/
    public function c($name_company = ''){
        
        //cargamos data para la vista
        $data['active'] = 'company';
        $data['titulo'] = 'WUORKS | Empresas ';
        
        $data["datos"] = $this->Account_model->obtener_informacion($this->id_usuario);
        $data['company'] = $this->Account_company_model->getCompany();
        
        if(!empty($data['company'])){
            $data['title'] = $data['company'][0]['name_company'];
        }else{
            $data['title'] = 'Mi empresa';
        }
        
        
        $this->load->view('company/template/header_inc',$data);
        $this->load->view('company/template/menu_inc',$data);
        $this->load->view('company/profile/profile_company_view',$data);
        $this->load->view('company/template/footer_inc');
    }
    
    /***************************************************************************
     * @Función para vista de mensajes
     **************************************************************************/
    public function mensajes($type_mensaje = ""){
        //cargamos data para la vista
        $data['active'] = 'mensajes';
        $data['titulo'] = 'WUORKS | Empresas ';
        
        $data["datos"] = $this->Account_model->obtener_informacion($this->id_usuario);
        $data['company'] = $this->Account_company_model->getCompany();
        
        if($type_mensaje == "send"){
            $data['title'] = "Mensajes enviados";
        }else if($type_mensaje == "recibido"){
            $data['title'] = 'Mensajes recibidos';
        }else{
            $data['title'] = 'Bandeja';
        }
        
        
        $this->load->view('company/template/header_inc',$data);
        $this->load->view('company/template/menu_inc',$data);
        $this->load->view('company/profile/profile_mensajes_view',$data);
        $this->load->view('company/template/footer_inc');
    }
    /***************************************************************************
     * @Función para vista de creacion de mini web
     **************************************************************************/
    public function create_mini_web($type = '',$company_id){
        //cargamos data para la vista
        $data['active'] = 'company';
        $data['titulo'] = 'WUORKS | Empresas ';
        if($type == 1){ //creacion
            $title = "Configuración mini web";
        }else{ //editar
            $title = "Editar mini web";
        }
        $data['title']  = $title;
        //datos del perfil
        $data["datos"] = $this->Account_model->obtener_informacion($this->id_usuario);
        $data['company'] = $this->Account_company_model->getCompany();
        $data['type'] = $type;
        $data['regiones'] = $this->Regiones_model->obtenerRegiones();
		$data['comunas'] = $this->Regiones_model->obtenerComunas();
        
        $this->load->view('company/template/header_inc',$data);
        $this->load->view('company/template/menu_inc',$data);
        $this->load->view('company/mini_webs/mini_web_view',$data);
        $this->load->view('company/template/footer_inc');
    }
    /***************************************************************************
     * @create_company función para crear la empresa
     **************************************************************************/
    public function create_company(){
        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('rubro','rubro','trim|required');
            $this->form_validation->set_rules('nombre','nombre','trim|required');
            $this->form_validation->set_rules('rut_company','RUT','trim|required|numeric|callback_veri_rut');
            $this->form_validation->set_rules('state_company','estado','trim|required');
            $this->form_validation->set_rules('telefono','telefono','trim|required|numeric');
            $this->form_validation->set_rules('descripcion','descripcion hora','trim|required');
            $this->form_validation->set_rules('lugar_trabajo','lugar de trabajo','trim|required');
            $this->form_validation->set_message('required',' %s es obliglatorio');
            $this->form_validation->set_message('numeric','%s invalido');
            
            //Perfil del usuario completo?
            $veri_perfil =  $this->Account_model->veri_perfil();
            if($veri_perfil){
                if ($this->form_validation->run() != FALSE){
                        //variables adicionales
                        $id_company = $this->aleatorio();
                        $id_usuario = $this->session->userdata('id_usuario');
                        
                        
                        $descripcion = $this->input->post("descripcion");
                        $descripcion = $this->veri_desc($descripcion); //Limpiamos la descripcion de email o numero de telefono
                        $dataCom= array(
                                    'rubro'         => ucfirst($this->input->post("rubro")),
                                    'name_company'  => ucfirst($this->input->post("nombre")),
                                    'desc_company'  => ucfirst($descripcion),
                                    'contact_phone' => $this->input->post("telefono"),
                                    'place_job'     => $this->input->post("lugar_trabajo"),
                                    'company_id'    => $id_company,
                                    'id_usuario'    => $id_usuario,
                                    'rut_company'   => $this->input->post("rut_company"),
                                    'state_company'   => $this->input->post("state_company"),
                                    );
                        $guardar = $this->Account_company_model->createCompany($dataCom);
                        
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
                echo "error_3";
            }
        }else{
            redirect(base_url());
        }
    }
    /***************************************************************************
     * @edit_company función para editar la información de la empresa
    ***************************************************************************/
    public function edit_company(){
        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('rubro','rubro','trim|required');
            $this->form_validation->set_rules('nombre','nombre','trim|required');
            $this->form_validation->set_rules('rut_company','RUT','trim|required|numeric');
            $this->form_validation->set_rules('state_company','estado','trim|required');
            $this->form_validation->set_rules('telefono','telefono','trim|required|numeric');
            $this->form_validation->set_rules('descripcion','descripcion hora','trim|required');
            $this->form_validation->set_rules('lugar_trabajo','lugar de trabajo','trim|required');
            $this->form_validation->set_message('required',' %s es obliglatorio');
            $this->form_validation->set_message('numeric','%s invalido');
            $this->form_validation->set_message('veri_desc',' No puedes incluir email en la descripción');
            $this->form_validation->set_message('veri_num',' No puedes incluir un numero de telefono');
            
                if ($this->form_validation->run() != FALSE){
                        //variables adicionales
                        $descripcion = $this->input->post("descripcion");
                        $descripcion = $this->veri_desc($descripcion);
                        $dataCom = array(
                                    'rubro'         => ucfirst($this->input->post("rubro")),
                                    'name_company'  => ucfirst($this->input->post("nombre")),
                                    'desc_company'  => ucfirst($descripcion),
                                    'contact_phone' => $this->input->post("telefono"),
                                    'place_job'     => $this->input->post("lugar_trabajo"),
                                    'rut_company'   => $this->input->post("rut_company"),
                                    'state_company'   => $this->input->post("state_company"),
                                    );
                        $editar = $this->Account_company_model->editCompany($dataCom);
                        
                        if($editar){
                            echo "success";
                        }else{
                            echo "error";
                        }
                }
                else{
                    echo validation_errors();
                }
        }else{
            redirect(base_url());
        }
    }
    /***************************************************************************
     * @mini_web Funcion para guardar o editar o previsualizar la mini web
     **************************************************************************/
    public function mini_web($t, $id){
        if($this->input->post('save') == "save"){
            $this->form_validation->set_rules('quienes_somos','Quienes somos','trim|required');
            $this->form_validation->set_rules('mision','Misión','trim|required');
            $this->form_validation->set_rules('vision',"Visión",'trim|required');
            $this->form_validation->set_rules('phone_company','Telefono','trim|required|numeric');
            $this->form_validation->set_rules('email_company','Email','trim|required');
            $this->form_validation->set_message('required','%s es obligatorio');
            $this->form_validation->set_message('numeric','%s invalido');
            
            if($this->form_validation->run() != FALSE){
                    //echo "todo ok";
                $datos   = $this->Account_model->obtener_informacion($this->id_usuario);
                $company = $this->Account_company_model->getCompany();
                
                    $data = array(
                        'desc_company' => $this->input->post('quienes_somos'),
                        'mision'        => $this->input->post('mision'),
                        'vision'        => $this->input->post('vision'),
                        'phone_company' => $this->input->post('phone_company'),
                        'email_company' => $this->input->post('email_company'),
                        'direcc_company'=> $this->input->post('direcc_company'),
                        //'otro_con'      => $this->input->post('otro_con'),
                        'facebook'      => $this->input->post('facebook'),
                        'twiter'          => $this->input->post('twit'),
                        'linkedin'      => $this->input->post('linkedin'),
                        'otro'          => $this->input->post('otro'),
                        'active'        => (int)1
                    );
                   $miniWeb = $this->Account_company_model->mini_web($data,$this->id_usuario);
                   if($miniWeb){
                       $this->session->set_flashdata("mensaje","tu mini web se ha creado con exito, pueder verla <a href='".base_url()."company/comp/".$id."'>aqui</a>");
                        redirect(base_url()."company/account/create_mini_web/".$t."/".$id."","refresh");
                   }else{
                       $this->session->set_flashdata("mensaje", "Ha ocurrido un error, intentalo más tarde");
                       redirect(base_url()."company/account/create_mini_web/".$t."/".$id."","refresh");
                   }
            }else{
                $this->session->set_flashdata("mensaje", validation_errors());
                redirect(base_url()."company/account/create_mini_web/".$t."/".$id."","refresh");
            }
            
        }else{
            
            $datos   = $this->Account_model->obtener_informacion($this->id_usuario);
            $company = $this->Account_company_model->getCompany();
            //echo "previsualizar";
            
            $data = array(
                'quienes_somos' => $this->input->post('quienes_somos'),
                'mision'        => $this->input->post('mision'),
                'vision'        => $this->input->post('vision'),
                'phone_company' => $this->input->post('phone_company'),
                'email_company' => $this->input->post('email_company'),
                'direcc_company'=> $this->input->post('direcc_company'),
                'otro_con'      => $this->input->post('otro_con'),
                'facebook'      => $this->input->post('facebook'),
                'twit'          => $this->input->post('twit'),
                'linkedin'      => $this->input->post('linkedin'),
                'otro'          => $this->input->post('otro')
            );
            $this->preview_mini_web($company,$datos,$data);
        }
    }
    public function preview_mini_web($company,$usuario,$datos){
        
		$data['title'] = ' WUORKS | El profesional que necesitas! 2015';
		
       
        //$data['valoraciones'] = $this->Resultado_model->get_valoraciones($id_perfil);
        $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
        $data['ul_noti'] = $this->Account_model->notificaciones();
        
        $data['company'] = $company;
        $data['datos']   = $usuario;
        $data['pre']     = $datos;
		   		//$data["valoracion"] = $this->Valoracion_model->obtenerValoraciones($data["datos"]);
		$this->load->view('template/header', $data);
        //$this->load->view('template/inc-menu-dos');
		$this->load->view('company/profile/profile_web_view',$data);
		//$this->load->view('template/footer');
    }
    /***************************************************************************
     * @veri_desc función que permite verificar que en la descripción de la 
     * empresa no valla un numero de telefono o un email
     **************************************************************************/
    public function veri_desc($descripcion){
        
        $patron    = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/';
        $er_numero = '/\+?\d{1,3}?[- .]?\(?(?:\d{2,3})\)?[- .]?\d\d\d[- .]?\d\d\d\d/';
        $er_num_2  = '/[0-9]{0,9}/';
        
        $desc = preg_replace($patron, "", $descripcion);
        $desc = preg_replace($er_numero, "", $desc);
        $desc = preg_replace($er_num_2, "", $desc);
        
        return $desc;
		
    }
    public function veri_rut($RUT){
        $veri = $this->Account_company_model->veriRUT($RUT);
        
        if($veri){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    /***************************************************************************
     * @Aleatorio función para crear cadenas de caratactes aleatorias
     **************************************************************************/
    public function aleatorio($length=25,$uc=TRUE,$n=TRUE,$sc=FALSE){
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
