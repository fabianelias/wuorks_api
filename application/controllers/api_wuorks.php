<?php

require(APPPATH.'/libraries/REST_Controller.php');

class Api_wuorks extends REST_Controller{
	
    public function __construct() {
        
        parent::__construct();
        //Cargar modelos
        $this->load->model('Resultado_model');
		$this->load->model('Valoracion_model');
		$this->load->model('Regiones_model');
        $this->load->model('Account_model');
        
        
        //Variables de configuracion
        $this->api_key = "VlRhWFWXk5DjfsI4mutEjzLCarjyMA9Cenx1rfJH";
    }
	//con esto limitamos las consultas y los permisos a la api
    protected $methods = array(
        'users_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'user_get' => array('level' => 0, 'limit' => 10),//user_get sólo level 0, pero máximo 10 consultas por hora
        'posts_user_get' => array('level' => 0, 'limit' => 10),//se necesita level 0 y sólo se pueden hacer 10 consultas por hora
        'new_user_post' => array('level' => 1),//se necesita level 1, no hay limite de peticiones
        'user_post' => array('level' => 1, 'limit' => 5),//se necesita level 1 y 5 peticiones
    );
    
    
    /***************************************************************************
     *@getProfesionales(), primera función de prueba.
     * http://localhost/wuorks_api/api_wuorks/getProfesiones/profesion/desarr/region/metropolitana/comuna/la-reina/x-api-key/VlRhWFWXk5DjfsI4mutEjzLCarjyMA9Cenx1rfJH
     **************************************************************************/
    public function getProfesionales_get(){
        $profesion = $this->get('profesion');
        $region    = $this->get('region');
        $comuna    = $this->get('comuna');
        
        $resultados = $this->Resultado_model->busqueda($profesion, $region, $comuna);
        
        if($resultados){
            $this->response($resultados, 200);
        }else{
            $this->response(NULL, 400);
        }
        
        
    }
}