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
        
        $this->load->library('email');
        $this->load->library('email_templates');
        
        $this->url_base = "https://www.wuorks.cl/";
        error_reporting(0);
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
    
    
    /***************************************************************************
     * @recu_pass(), funcion para recuperar la password
     ***************************************************************************/
    public function recu_pass_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $email);
	
        }
        $email1 = $email["email"];// $this->get("email");
        
        $recu = $this->userModel->recuperar_pass($email1);
        
        if($recu){
            
            $this->email->initialize(array(
            'charset'  => 'utf-8',
            'protocol' => 'smtp',
            'smtp_host' => 'smtp-relay.sendinblue.com',
            'smtp_user' => 'contacto@wuorks.com',
            'smtp_pass' => 'VvNS9bGj1pfxXDQg',
            'smtp_port' => 587,
            'mailtype' => 'html',
            'crlf' => "\r\n",
            'newline' => "\r\n"
            ));
            
            $url_verificacion = $this->url_base."wuokers/new_password/".$recu["token"]."/".$recu["pass"]."/".$recu["email"];

            $this->email->from('noreply@wuorks.com', 'WUORKS | El profesional que necesitas.');
            $this->email->to($email1);
            $this->email->subject('Solicitud cambio contraseña');
            $this->email->message($this->email_templates->pass($url_verificacion,""),TRUE);
            $this->email->send();
            
            $this->response($recu, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @verify_token()
     ***************************************************************************/
    public function verify_token_get(){
        
        $token = $this->get("token");
        
        $existe = $this->userModel->verify_token($token);
        
        if($existe){
            
            $this->response($existe, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @tutoOk; cambia el estado a 1, el tuto fue visto
     **************************************************************************/
    public function tuto_off_get(){
        
        $id_user = $this->get("id_user");
        
        $res = $this->userModel->tuto_off($id_user);
        
        if($res){
            
            $this->response($res, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
        
    }
    /***************************************************************************
     * @tutoOk; actualiza info importante del perfil en el tutorial
     **************************************************************************/
    public function edit_user_tuto_post(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $data);
	
        }
        
        $datas = array(
            "address" => $data['address'],
            "region"  => $data['region'],
            "commune" => $data['commune'],
            "cell_phone_number" => $data['telefono'],
        );
        $id_user = $data["id_user"];
        
        $res = $this->userModel->edit_user_tuto($datas,$id_user);
        
        if($res){
            
            $this->response($res, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
}