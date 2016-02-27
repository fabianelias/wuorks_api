<?php

/*******************************************************************************
 * 
 *                Controlador para metodos de empresas
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Company extends REST_Controller{
    
    public function __construct(){
        
        parent::__construct();
        
        $this->load->model("company_model", "companyModel");
        
    }
    
    /***************************************************************************
     * 
     *                  Sección uno, crear perfil empresas
     * 
     **************************************************************************/
    
    
    /***************************************************************************
     * @create_company(), función para crear un perfil de empresa.
     **************************************************************************/
    
    public function create_company_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $company); //Recibir por post los datos de la empresa  
	
        }
        
        //Asignación de variables
        
        $company_name        = $company["company_name"];
        $company_description = $company["company_description"];
        $address             = $company["address"];
        $company_category    = $company["company_category"];
        $key_company         = $this->key_company();
        $id_user             = $company["id_user"];
        
        $create = $this->companyModel->create_company($company_name,
                                                      $company_description,
                                                      $address,
                                                      $company_category,
                                                      $key_company,
                                                      $id_user);
        
        if($create){
            
            $this->response($create, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @key_company(), función para crear una key unica para la empresa.
     **************************************************************************/
    
    public function key_company($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
        $source = 'abcdefghijklmnopqrstuvwxyz';
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if($n==1) $source .= '1234567890';
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
            if($length>0){
                    $key_company = "";
                    $source = str_split($source,1);
                    for($i=1; $i<=$length; $i++){
                            mt_srand((double)microtime() * 1000000);
                            $num = mt_rand(1,count($source));
                            $key_company .= $source[$num-1];
                    }
            }
            return $key_company;
    }
    
    /***************************************************************************
     * 
     *              Sección dos , editar un perfil de empresa
     * 
     **************************************************************************/
    
    
    /***************************************************************************
     * @edit_company(), función para editar un perfil de empresa.
     **************************************************************************/
    
    public function edit_company_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $company); //Recibir por post los datos de la empresa  
	
        }
        
        //Asignación de variables
        
        $company_name        = $company["company_name"];
        $company_description = $company["company_description"];
        $address             = $company["address"];
        $company_category    = $company["company_category"];
        //$key_company         = $company["key_company"];
        $id_user             = $company["id_user"];
        $region              = $company["region"];
        $commune             = $company["commune"];
        
        $edit = $this->companyModel->edit_company( $company_name,
                                                   $company_description,
                                                   $address,
                                                   $company_category,
                                                   //$key_company,
                                                   $id_user,
                                                   $region,
                                                   $commune);
        
        if($edit){
            
            $this->response($edit, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    
    /***************************************************************************
     * 
     *              Sección dos , obtener un perfil de empresa
     * 
     **************************************************************************/
    
    
    /***************************************************************************
     * @info_company(), función para editar un perfil de empresa.
     **************************************************************************/
    public function company_get(){
        
        $id_user = $this->get("id_user");
        
        $info    = $this->companyModel->myCompany($id_user);
        
        if($info){
            
            $this->response($info, 200);
            
        }else{
            
            $this->response(NULL, 400);
        }
    }
    
}
