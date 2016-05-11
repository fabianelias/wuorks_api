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
        $title        = $job['title'];
        $description  = $job['description'];
        $remuneration = $job['remuneration'];
        $applicants_amount = $job['applicants_amount'];
        $tipo_aviso        = $job['tipo_aviso'];
        $genero            = $job['genero'];
        $horario           = $job['horario'];
        $zona              = $job['zona'];
        $key_job           = $this->key_job();
        $id_user           = $job["id_user"];
       
        
        $create = $this->jobModel->create_job( $title,
                                               $description,  
                                               $remuneration,
                                               $applicants_amount,
                                               $tipo_aviso,
                                               $genero,
                                               $horario,
                                               $zona,
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
    
    public function key_job($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
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
    
    
    
    /***************************************************************************
     * @getMyJob(), función para obtener info basica de los jobs de del usuario
     **************************************************************************/
    public function getMyJobs_get(){
        
        $id_user = $this->get('id_user');
        
        $jobs = $this->jobModel->getMyJobs($id_user);
        
        if($jobs){
            
            $this->response($jobs, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    
    /***************************************************************************
     * @infoJob(), funcion retorna la info un aviso en particular
     **************************************************************************/
    public function infoJob_get(){
        
        $key_aviso = $this->get('key_aviso');
        
        $info = $this->jobModel->infoJob($key_aviso);
        
        if($info){
            
            $this->response($info, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @matchesWuokers(), retorn los usuarios que calzen con el minijob
     **************************************************************************/
    public function matchesWuokers_get(){
        
        $key_aviso = $this->get('key_aviso');
        
        $matches = $this->jobModel->matches($key_aviso);
        
        if($matches){
            
            $this->response($matches, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    
    /***************************************************************************
     * @getJobs(), retorna todos los MiniJobs en estado 0.
     **************************************************************************/
    public function getJobs_get(){
        
        $jobs = $this->jobModel->getJobs();
        
        if($jobs){
            $this->response($jobs, 200);
        }else{
            $this->response(NULL, 400);
        }
        
        
    }
    
    /****************************************************************************
     * @aplicar(),
     ***************************************************************************/
    public function aplicar_get(){
        
        $key_aviso = $this->get('key_aviso');
        $key_user  = $this->get('key_user');
        
        $aplicar = $this->jobModel->aplicar($key_aviso, $key_user);
        
        if($aplicar){
            $this->response($aplicar, 200);
        }else{
            $this->response(NULL, 400);
        }
        
    }
}