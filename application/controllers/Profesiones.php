<?php

/*******************************************************************************
 * 
 *                Controlador para profesiones y areas(rubro)
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Profesiones extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        $this->load->model("profesiones_model","profesionesModel");
        error_reporting(0);
        
    }
    
    /***************************************************************************
     * @obtenerProfesiones()
     **************************************************************************/
    public function obtenerProfesiones_get(){
        
        $profes = $this->profesionesModel->allProf();
        
        if($profes){
            
            $this->response($profes, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
        
    }
}