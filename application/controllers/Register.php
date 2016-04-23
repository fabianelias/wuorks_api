<?php

/*******************************************************************************
 * 
 *                  Controlador para registro de usuarios
 * 
 ******************************************************************************/


require(APPPATH.'/libraries/REST_Controller.php');
class Register extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model("register_model", "registerModel");
        $this->load->library('email');
        $this->load->library('email_templates');
        
        $this->url_base = "http://beta.wuorks.com/";
        
    }
    protected $methods = array(
        
    );
    /***************************************************************************
     * @register_get(), función 1 de registro.
     * http://localhost/wuorks_api/Register/register/name/".$name."/last_name_p/".$last_name_p."/last_name_m/".$last_name_m."/email/".$email."/password/".$password."./type_account/".$type_account."/key/WBqyGRGuRHHTEIZwTuJfFvPgyhCHZ67GCmtlAxdT"
     **************************************************************************/
    public function registerUser_post(){
        /*
        //Validar parametros get
        if(!$this->get("name")){
            $this->response(NULL, 400);
        }
        if(!$this->get("last_name_p")){
            $this->response(NULL, 400);
        }
        if(!$this->get("last_name_m")){
            $this->response(NULL, 400);
        }
        if(!$this->get("email")){
            $this->response(NULL, 400);
        }
        if(!$this->get("password")){
            $this->response(NULL, 400);
        }
        if(!$this->get("type_account")){
            $this->response(NULL, 400);
        }*/
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $newUser); //Recibir por post los datos de la empresa  
	
        }
        
        //Asignación de variables
        
        //Parametros para tbl ws_user_information
        $name         = $newUser["name"];//$this->get("name");
        $last_name_p  = $newUser["last_name_p"];//$this->get("last_name_p");
        $last_name_m  = $newUser["last_name_m"];//$this->get("last_name_m");
        $key_api      = $this->generate_token();
        
        //Parametros para tbl ws_user
        $username     = $this->username($name, $last_name_p);
        $email        = $newUser["email"];//$this->get("email");
        $password     = $newUser["password"];//$this->get("password");
        $wuorks_key   = $this->wuorks_key();
        $user_type    = $newUser["user_type"]; //Usuario freemium
        $type_account = 0;//$this->get("type_account");
        $state        = $newUser["state"];
        $newletter    = $newUser["newletter"];
        $gender       = $newUser["gender"];
        
        $register = $this->registerModel->register_user($name,
                                                        $last_name_p,
                                                        $last_name_m,
                                                        $key_api,
                                                        $username,
                                                        $email,
                                                        $password,
                                                        $wuorks_key,
                                                        $user_type,
                                                        $type_account,
                                                        $state,
                                                        $newletter,
                                                        $gender
                                                        );
        
        if($register){
            
        
            
            $this->email->initialize(array(
            'charset'  => 'utf-8',
            'protocol' => 'smtp',
            'smtp_host' => 'smtp-relay.sendinblue.com',
            'smtp_user' => 'contacto@wuorks.com',
            'smtp_pass' => 'VvNS9bGj1pfxXDQg',//ecadc75f6235396dee1e4b89e68d69c43c08a876
            'smtp_port' => 587,
            'mailtype' => 'html',
            'crlf' => "\r\n",
            'newline' => "\r\n"
            ));
            
            $token = md5($email);
            $rand  = rand(999999, 9999999);
            $url_verificacion = $this->url_base."oauth/verify_account/".$token."/".$rand;

            $this->email->from('noreply@wuorks.com', 'WUORKS | El profesional que necesitas.');
            $this->email->to($email);
            $this->email->subject($name.' te damos la ¡Bienvenida! a Wuorks.com el portal de servicios');
            $this->email->message($this->email_templates->email_confirm($url_verificacion,$name),TRUE);
            $this->email->send();

            
            $this->response($register, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
 
    }
    
    /***************************************************************************
     * @username(), función para crear un username único.
     ***************************************************************************/
    public function username($name, $last_name_p = ""){
        
        $n = strtolower($name);
        $n = str_replace(" ", "", $n);
        $l = strtoupper($last_name_p);
        $l = substr($l, 0,1);
        $id = rand(999, 9999);
        $username = "@".$n.$l."_".$id;
        
        return $username;
    }
    
    /***************************************************************************
     * @wuorks_key(), función para generar una key unica para uso.
     **************************************************************************/
    public function wuorks_key($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
            $source = 'abcdefghijklmnopqrstuvwxyz';
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if($n==1) $source .= '1234567890';
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
            if($length>0){
                    $wuorks_key = "";
                    $source = str_split($source,1);
                    for($i=1; $i<=$length; $i++){
                            mt_srand((double)microtime() * 1000000);
                            $num = mt_rand(1,count($source));
                            $wuorks_key .= $source[$num-1];
                    }
            }
            return $wuorks_key;
    }
    
    /***************************************************************************
     * @generate_token(), función para generar un token (api key) para el usuario
     **************************************************************************/
    private function generate_token($len = 40)
    {
        //un array perfecto para crear claves
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        );
        //desordenamos el array chars
        shuffle($chars);
        $num_chars = count($chars) - 1;
        $token = '';

        //creamos una key de 40 carácteres
        for ($i = 0; $i < $len; $i++)
        {
            $token .= $chars[mt_rand(0, $num_chars)];
        }
        
        //Api key de uso
        $api_key = "WBqyGRGuRHHTEIZwTuJfFvPgyhCHZ67GCmtlAxdT";
        return $api_key;//$token;
    }
    
    
    /***************************************************************************
     * 
     *             Sección validación de usuarios para registro
     * 
     **************************************************************************/
    
    
    /***************************************************************************
     * @verify_email(), función para verificar si existe el email ingresado en
     * el registro.
     * http://localhost/wuorks_api/register/verify_email/email/".$email"/key/
     **************************************************************************/
    
    public function verify_email_get(){
        
        //Validar parametros GET
        if(!$this->get("email")){
            $this->response(NULL, 400);
        }
        
        //Asignación de variables
        $email  = $this->get("email");
        
        $verify = $this->registerModel->verifyEmail($email);
        
        if($verify){
            
            $this->response($verify, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    } 
    
    
    
    /***************************************************************************
     * @verify_account(), función para verificar el email par activar la cuenta
     ***************************************************************************/
    public function verify_account_get(){
        //Validar parametros GET
        if(!$this->get("email")){
            $this->response(NULL, 400);
        }
        
        //Asignación de variables
        $email  = $this->get("email");
        
        $verify = $this->registerModel->verify_account($email);
        
        if($verify){
            
            $this->response($verify, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
}

