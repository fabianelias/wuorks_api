<?php

/*******************************************************************************
 * 
 *                      Controlador para empleos 
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Job extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model("job_model", "jobModel");
        
    }
    
    /***************************************************************************
     * @create_job(), función para crear un empleo.
     **************************************************************************/
    
    public function create_job_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $job); //Recibir por post los datos del empleo 
	
        }
        
        //Asignación de variables
        
        $title             = $job["title"];
        $job_description   = $job["job_description"];
        $workplace         = $job["workplace"];
        $remuneration      = $job["remuneration"];
        $workday           = $job["workday"];
        $applicants_amount = $job["applicants_amount"];
        $tags_work_area    = $job["tags_work_area"];
        $type_job          = $job["type_job"];
        $key_job           = $this->key_job();
        $id_user           = $job["id_user"];
        
        $create = $this->jobModel->create_job( $title,
                                               $job_description,   
                                               $workplace   ,
                                               $remuneration,
                                               $workday,
                                               $applicants_amount,
                                               $tags_work_area,
                                               $type_job,
                                               $key_job,
                                               $id_user
                                                );
        
        if($create){
            
            $this->response($create, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
        
        
    }
    
    
    /***************************************************************************
     * @key_job(), función para crear una key unica para el empleo.
     **************************************************************************/
    
    public function key_job($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
        $source = 'abcdefghijklmnopqrstuvwxyz';
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if($n==1) $source .= '1234567890';
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
            if($length>0){
                    $key_job = "";
                    $source = str_split($source,1);
                    for($i=1; $i<=$length; $i++){
                            mt_srand((double)microtime() * 1000000);
                            $num = mt_rand(1,count($source));
                            $key_job .= $source[$num-1];
                    }
            }
            return $key_job;
    }
    
    
    /***************************************************************************
     * 
     *              Sección dos, editar un empleo
     * 
     **************************************************************************/
    
    public function edit_job_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $job); //Recibir por post los datos del empleo 
	
        }
        
        //Asignación de variables
        
        $title             = $job["title"];
        $job_description   = $job["job_description"];
        $workplace         = $job["workplace"];
        $remuneration      = $job["remuneration"];
        $workday           = $job["workday"];
        $applicants_amount = $job["applicants_amount"];
        $tags_work_area    = $job["tags_work_area"];
        $type_job          = $job["type_job"];
        $key_job           = $job["key_job"];
        $id_user           = $job["id_user"];
        
        $edit = $this->jobModel->edit_job( $title,
                                               $job_description,   
                                               $workplace   ,
                                               $remuneration,
                                               $workday,
                                               $applicants_amount,
                                               $tags_work_area,
                                               $type_job,
                                               $key_job,
                                               $id_user
                                                );
        
        if($edit){
            
            $this->response($edit, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
        
        
    }
}