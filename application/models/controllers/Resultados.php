<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resultados extends CI_Controller {

		function __construct(){
		parent::__construct();
		$this->load->model('Resultado_model');
		$this->load->model('Valoracion_model');
		$this->load->model('Regiones_model');
        $this->load->model('Account_model');
		$this->load->helper('fechas');
		ini_set('default_charset', 'UTF-8');
	}
	public function index()
	{
				// variable por si esta conectado
				/*$email =$this->session->userdata('user');
                $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
				$data['regiones'] = $this->Regiones_model->obtenerRegiones();
				//definimos las variables entragadas por GET.
				$profesion = trim($this->input->post('de', TRUE));
				$region = trim($this->input->post('region',TRUE));


				$data['ul_noti'] = $this->Account_model->notificaciones();
                
                //Paginacion
				$data['profesion'] = $profesion;
				$data['region'] =  $region;
                $pages=2; //Número de registros mostrados por páginas
				$this->load->library('pagination'); //Cargamos la librería de paginación
				$config['base_url'] = base_url().'resultados/'.$profesion.'/'.$region.'/page'; // parametro base de la aplicación, si tenemos un .htaccess nos evitamos el index.php
				$config['total_rows'] = $this->Resultado_model->filas();//calcula el número de filas  
				$config['per_page'] = $pages; //Número de registros mostrados por páginas
		        $config['num_links'] = 20; //Número de links mostrados en la paginación
		 		$config['first_link'] = 'Primera';//primer link
				$config['last_link'] = 'Última';//último link
		        $config["uri_segment"] = 3;//el segmento de la paginación
				$config['next_link'] = 'Siguiente';//siguiente link
				$config['prev_link'] = 'Anterior';//anterior link
				$config['full_tag_open'] = '<div id="paginacion">';//el div que debemos maquetar
				$config['full_tag_close'] = '</div>';//el cierre del div de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				//$data["provincias"] = $this->provincia_model->total_paginados($config['per_page'],$this->uri->segment(3));	

				$data['datos'] = $this->Resultado_model->busqueda($profesion,$region,$config['per_page'],$this->uri->segment(3));

		        $data['title'] = 'Busqueda WUORKS | El profesional que necesitas! 2015';
		   		$this->load->view('template/header', $data);
				$this->load->view('resultados',$data);
				$this->load->view('template/footer');*/
				$profesion =  utf8_decode(trim(str_replace('%', '-', $this->input->get('de', TRUE))));
				$region =  utf8_decode(trim(str_replace('%', '-', $this->input->get('region', TRUE))));

				//$this->result($profesion,$region);
                redirect(base_url().'resultados/cl/'.$profesion.'/'.$region);
	
	}

	/******************************************************************************************************
	*@FUNCIÓN QUE ACTIVA LA VISTA DE RESULTADOS
	/*********************************************************************************************************/
	public function cl($profesion = '', $region = '' ,$comuna = ''){
               
                $email =$this->session->userdata('user');
                $data['ul_msj'] = $this->Account_model->ultimos_mensajes();
				$data['regiones'] = $this->Regiones_model->obtenerRegiones();
				//definimos las variables entragadas por GET.
				//asasd
				
                
                
               
               $comuna = $this->limpiar($comuna);
                
                //$comuna = $this->limpiar($comuna);
                //$comuna = utf8_decode(utf8_encode($comuna));
				$data['ul_noti'] = $this->Account_model->notificaciones();
                
                //Paginacion
				$data['profesion'] = $profesion;
				$data['region'] =  $region;
                $data['comuna'] = $comuna;
                
                $data['comunas'] = $this->Regiones_model->obtener_comunas($region);
				$data['datos'] = $this->Resultado_model->busqueda(utf8_decode($profesion),  utf8_encode($region), $comuna);

		        $data['title'] = 'WUORKS | El profesional que necesitas! 2015';
		   		$this->load->view('template/header', $data);
				$this->load->view('resultados',$data);
				$this->load->view('template/footer');


	}
    
    public  function limpiar($String){
        $String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
        $String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
        $String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
        $String = str_replace(array('í','ì','î','ï'),"i",$String);
        $String = str_replace(array('é','è','ê','ë'),"e",$String);
        $String = str_replace(array('É','È','Ê','Ë'),"E",$String);
        $String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
        $String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
        $String = str_replace(array('ú','ù','û','ü'),"u",$String);
        $String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
        $String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
        $String = str_replace("ç","c",$String);
        $String = str_replace("Ç","C",$String);
        $String = str_replace("ñ","n",$String);
        $String = str_replace("Ñ","N",$String);
        $String = str_replace("Ý","Y",$String);
        $String = str_replace("ý","y",$String);

        $String = str_replace("&aacute;","a",$String);
        $String = str_replace("&Aacute;","A",$String);
        $String = str_replace("&eacute;","e",$String);
        $String = str_replace("&Eacute;","E",$String);
        $String = str_replace("&iacute;","i",$String);
        $String = str_replace("&Iacute;","I",$String);
        $String = str_replace("&oacute;","o",$String);
        $String = str_replace("&Oacute;","O",$String);
        $String = str_replace("&uacute;","u",$String);
        $String = str_replace("&Uacute;","U",$String);
        $String = str_replace('%C3%B1', 'ñ', $String);
        $String = str_replace('%C3%B3', 'ó', $String);
        $String = str_replace('%C3%AD', 'í', $String);
        $String = str_replace('%C3%BA', 'ú', $String);
        $String = str_replace('%C3%A9', 'é', $String);
        
        //return $String;
        return utf8_encode(utf8_decode($String));
    }

}