<?php

/*******************************************************************************
 * 
 *              Controlador para regiones, provincias y comunas
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Regiones extends REST_Controller{
    
    public function __construct(){
        
        parent::__construct();
        
        $this->load->model("regiones_model","regionesModel");
        
    }
    
    public function obtRegiones_get(){
        
        
        $reg = $this->regionesModel->getRegiones();
        
        if($reg){
            
            $this->response($reg, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    public function obtComunas_get(){
        
        $id_region = $this->get("id_region");
        
        $comuna = $this->regionesModel->getComuna($id_region);
        
        if($comuna){
            
            $this->response($comuna, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
}