<?php

/*******************************************************************************
 *  
 *                      Controlador para profesiones
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Profession extends REST_Controller{
    
    public function __construct() {
        
         parent::__construct();
         
         $this->load->model("profession_model", "professionModel");
         error_reporting(0);
    }
    
    /***************************************************************************
     * 
     *              Sección uno, crear profesión 
     * 
     **************************************************************************/
    
    /***************************************************************************
     * @create_profession(), función para crear un profesión para un usuario.
     **************************************************************************/
    
    public function create_profession_post(){
        
       if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $profession); //Recibir por post los datos de la profesión  
	
       }
       
       //Asignación de variables
       
       $name_profession = $profession["name_profession"];
       $job_description = $profession["job_description"];
       $workplace       = $profession["workplace"];
       $key_profession  = $this->key_profession();
       $id_user         = $profession["id_user"];
       
       
       $create = $this->professionModel->create_profession($name_profession,
                                                           $job_description,
                                                           $workplace,
                                                           $key_profession,
                                                           $id_user);
       
       if($create){
           
           $this->response($create, 200);
           
       }else{
           
           $this->response(NULL, 400);
           
       }
        
    }
    
    
    /***************************************************************************
     * @key_profession(), función para crear un key única para la profesión.
     **************************************************************************/
    
    public function key_profession($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
        $source = 'abcdefghijklmnopqrstuvwxyz';
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if($n==1) $source .= '1234567890';
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
            if($length>0){
                    $key_profession = "";
                    $source = str_split($source,1);
                    for($i=1; $i<=$length; $i++){
                            mt_srand((double)microtime() * 1000000);
                            $num = mt_rand(1,count($source));
                            $key_profession .= $source[$num-1];
                    }
            }
            return $key_profession;
    }
    
    
    /***************************************************************************
     * 
     *                  Sección dos, editar una profesión
     * 
     **************************************************************************/
    
    /***************************************************************************
     * @edit_profession(), función para editar una profesión
     **************************************************************************/
    
    public function edit_profession_post(){
        
       if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $profession); //Recibir por post los datos de la profesión  
	
       }
       
       //Asignación de variables
       
       $name_profession = $profession["name_profession"];
       $job_description = $profession["job_description"];
       $workplace       = $profession["workplace"];
       $key_profession  = $profession["key_profession"];
       $id_user         = $profession["id_user"];
       
       
       $edit = $this->professionModel->edit_profession($name_profession,
                                                       $job_description,
                                                       $workplace,
                                                       $key_profession,
                                                       $id_user);
       
       if($edit){
           
           $this->response($edit, 200);
           
       }else{
           
           $this->response(NULL, 400);
           
       }
    }
    
    
    /***************************************************************************
     * 
     *                  Sección tres, retorno de info,etc.
     * 
     **************************************************************************/
    
    /***************************************************************************
     * @infoProfession(), función que retorna la info de la profession elegida
     * y la información del usuario.
     **************************************************************************/
    
    public function infoProfession_get(){
        
        //Validar parametros get
        if(!$this->get("wuorks_key")){
            $this->response(NULL, 400);
        }
        if(!$this->get("key_profession")){
            $this->response(NULL, 400);
        }
        
        //Asignación de variables
        $wuorks_key     = $this->get("wuorks_key");
        $key_profession = $this->get("key_profession");
        
        $info = $this->professionModel->infoProfession($wuorks_key, $key_profession);
        
        if($info){
            
            $this->response($info, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    
    /***************************************************************************
     * 
     *        Sección cuatro, retorno de info para perfil del usuario
     * 
     **************************************************************************/
    
    public function professions_get(){
        
        //Validar parametros get
        if(!$this->get("id_user")){
            $this->response(NULL, 400);
        }
        
        $id_user = $this->get("id_user");
        
        $info    = $this->professionModel->infoProfessions($id_user);
        
        if($info){
            
            $this->response($info, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
}

