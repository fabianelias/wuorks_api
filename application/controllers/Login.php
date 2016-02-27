<?php

/*******************************************************************************
 * 
 *                  Controlador API Rest Login
 *              
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Login extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model("login_model","login");
        
    }
    
    
    /***************************************************************************
     * @login(), función para validar usuarios.
     **************************************************************************/
    public function login_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $credenciales);
	
        }
        
        //Asignar variables
        $email    = $credenciales["email"];
        $password = $credenciales["password"];
        
        $valid = $this->login->valid_user($email,$password);
        
        if($valid){
            
            $this->response($valid, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
        
    }
}