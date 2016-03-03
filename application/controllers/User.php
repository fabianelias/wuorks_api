<?php


/*******************************************************************************
 * 
 *                       Controlador para usuarios
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class User extends REST_Controller{
    
    public function __construct(){
        
        parent::__construct();
        
        $this->load->model("user_model", "userModel");
        
    }
    
    
    /***************************************************************************
     * 
     *              Sección uno, edición de info. usuarios.
     * 
     **************************************************************************/
    
    public function edit_user_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $user); //Recibir por post los datos del usuario 
	
        }
        
        //Asignación de variables
        
        $name = $user["name"];
        $last_name_p = $user["last_name_p"];
        $last_name_m = $user["last_name_m"];
        $rut         = $user["rut"];
        $telephone_number = $user["telephone_number"];
        $cell_phone_number = $user["cell_phone_number"];
        $address           = $user["address"];
        $commune           = $user["commune"];
        $region            = $user["region"];
        $birth_date        = $user["birth_date"];
        $gender            = $user["gender"];
        //$avatar            = $user["avatar"];
        $id_user           = $user["id_user"];
        
        
        $edit = $this->userModel->edit_user( $name,
                                             $last_name_p,  
                                             $last_name_m,
                                             $rut,
                                             $telephone_number,
                                             $cell_phone_number,
                                             $address,
                                             $commune,
                                             $region,
                                             $birth_date,
                                             $gender,
                                             //$avatar,
                                             $id_user);
        
        if($edit){
            
            $this->response($edit, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @change_pass(), función para editar la contraseña
     **************************************************************************/
    public function change_pass_get(){
        
        $password = $this->get("password");
        $id_user  = $this->get("id_user");
        
        $change   = $this->userModel->change_pass($password, $id_user);
        
        if($change){
            
            $this->response($change, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @change_avatar(); función para cambiar la imagen de perfil.
     **************************************************************************/
    public function change_avatar_get(){
        
        $imagen  = $this->get("image");
        $id_user = $this->get("id_user");
        
        $change  = $this->userModel->change_avatar($imagen, $id_user);
        
        if($change){
            
            $this->response($change, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * 
     *                  Sección dos, retorna info de usuario
     * 
     **************************************************************************/
    
    public function infoUser_get(){
        
        $id_user = $this->get("id_user");
        
        $info = $this->userModel->infoUser($id_user);
        
        if($info){
            
            $this->response($info, 200);
            
        }else{
            
            $this->response(NULL, 400);
        }
    }
    
    
}