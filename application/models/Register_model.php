<?php
/*******************************************************************************
 *           
 *                               Register model
 *           
 ******************************************************************************/

class Register_model extends CI_Model {
    
    public function __construct(){
        
        parent::__construct();
        
        $this->load->database();
        
    }
    
    /***************************************************************************
     * @register_user(), función principal para registro de usuarios.
     **************************************************************************/
    public function register_user($name,
                                  $last_name_p,
                                  $last_name_m,
                                  $key_api,
                                  $username,
                                  $email,
                                  $password,
                                  $wuorks_key,
                                  $user_type,
                                  $type_account,
                                  $state){
        
        //Paso 1: Crear registro en tbl ws_user
        
        $ws_user = array(
            "username"     => $username,
            "email"        => $email,
            "password"     => $password,
            "wuorks_key"   => $wuorks_key,
            "user_type"    => $user_type,       //Profesional o empresa 
            "type_account" => $type_account,     //Premium o Freemium
            "state"        => $state
        );
        
        $this->db->insert("ws_user", $ws_user); //Registro del usuario en ws_user
        
        //Paso 2: Crear registro en tbl ws_user_information
        
        $id_user = $this->db->insert_id();
        
        $ws_user_information = array(
            "name"        => $name,
            "last_name_p" => $last_name_p,
            "last_name_m" => $last_name_m,
            "key"         => $key_api,
            "id_user"     => $id_user,
            "avatar"      => "wuorks-not-avatar.png"
        );
        
        $r = $this->db->insert("ws_user_information", $ws_user_information);
        
        //Paso 3: Crear registro de la Api-key en ws_key
        
        $ws_keys = array(
            "key"            => $key_api,
            "level"          =>  false,
            "ignore_limits"  => false,
            "is_private_key" => false,
            "ip_addresses"   => ""
        );
        
        //$this->db->insert("ws_keys", $ws_keys);
        
        //Paso 4: Crear Registro de la Api-key en ws_access
        
        $ws_access = array(
            "key"        => $key_api,
            "controller" => "/"
        );
        
        //$r = $this->db->insert("ws_access", $ws_access);
        
        
        return $r;
        
    }
    
    
    
    /***************************************************************************
     * @verifyEmail(), función para validar si existe el email ingresado en el
     * registro.
     **************************************************************************/
    public function verifyEmail($email){
        
        $this->db->select("username");
        $this->db->where("email",$email);
        
        $query = $this->db->get("ws_user");
        
        if($query->num_rows() > 0){
            
            return true;
            
        }else{
            
            return false;
            
        }
    }
}