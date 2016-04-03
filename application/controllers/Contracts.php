<?php
/*******************************************************************************
 * 
 *                Controlador para metodos de contratos 
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Contracts extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model("contract_model", "contractModel");
        
        error_reporting(0);
    }
    
    
    /***************************************************************************
     * @create_contract(), funciòn para crear un contracto entre usuarios.
     **************************************************************************/
    public function create_contract_get(){
       $key_employee = $this->get("key_employee");
       $key_employer = $this->get("key_employer");
       $key_service  = $this->get("key_service");
       $key_contract = $this->key_contract();
       
       $contract     = $this->contractModel->create_contract($key_employee,
                                                             $key_employer,
                                                             $key_service,
                                                             $key_contract
                                                             );
       if($contract){
           
           $this->response($contract, 200);
           
       }else{
           
           $this->response(NULL, 400);
           
       }
       
    }
    
    
    /***************************************************************************
     * @obt_contracts(), función para obtener los contratos hechos por y hacia el
     ***************************************************************************/
    public function obt_contracts_get(){
      
        $id_user  = $this->get("id_user");
        $key_user = $this->get("key_user");
        
        $contract = $this->contractModel->obt_contracts($id_user, $key_user);
        
        if($contract){
            
            $this->response($contract, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @rating(), función para guardar una calificación
     **************************************************************************/
    public function rating_post(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $rating); 
	
       }
       
        $data = array(
                "title"       => $rating["title"],
                "comment"     => $rating["comment"],
                "rate_type"   => $rating["rate_type"],
                "user_rating" => $rating["user_rating"],
                "name_user"   => $rating["name_user"],
                "id_profession" => $rating["id_profession"],
                "id_company"    => $rating["id_company"]
        );
        $key_contract = $rating["id_contract"];
        
       $ratings = $this->contractModel->rating($data, $key_contract);
       
       if($ratings){
           
           $this->response($ratings, 200);
           
       }else{
           
           $this->response(NULL, 400);
           
       }
    }
    
    /***************************************************************************
     * @key_contract(), función para crear una key unica para el empleo.
     **************************************************************************/
    
    public function key_contract($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
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
}