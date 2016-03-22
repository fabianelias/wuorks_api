<?php

/*******************************************************************************
 * 
 *                  Controlador para resuldos de busquedas
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Search extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model("search_model", "searchModel");
        
    }
    
    /***************************************************************************
     * @search_wuokers(), función que retorna los usuarios según criterios.
     * http://localhost/search/search_wuorkers/wuork_area/informatica/region/metropilitana/key/WBqyGRGuRHHTEIZwTuJfFvPgyhCHZ67GCmtlAxdT
     ***************************************************************************/
    
    public function search_wuorkers_get(){
        
        //Validar parametros get
        
        if(!$this->get("wuork_area")){
            $this->response(NULL, 400);
        }
        if(!$this->get("region")){
            $this->response(NULL, 400);
        }
        
        //Asignacion de variables
        
        $wuork_area = $this->get("wuork_area");
        $region     = $this->get("region");
        
        $search = $this->searchModel->search($wuork_area, $region);
        
        if($search){
            
            $this->response($search, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @search_wuokers(), función que retorna los usuarios según criterios.
     * http://localhost/search/search_wuorkers/wuork_area/informatica/region/metropilitana/key/WBqyGRGuRHHTEIZwTuJfFvPgyhCHZ67GCmtlAxdT
     ***************************************************************************/
    
    public function search_wuorkers_V2_post(){
        
        //Validar parametros get
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $parametros); //Recibir por post los datos del usuario 
	
        }
        
        //Asignacion de variables
        
        $wuork_area = $parametros["wuork_area"];
        $region     = $parametros["region"];
        
        $search = $this->searchModel->search($wuork_area, $region);
        
        if($search){
            
            $this->response($search, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
}

