<?php

/*******************************************************************************
 * 
 *                           Modelo para usuarios
 *
 ******************************************************************************/

Class User_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
    }
    
    /***************************************************************************
     * @edit_user(), función para editar la info basica del usuario.
     **************************************************************************/
    public function edit_user(  $name,
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
                                $avatar,
                                $id_user){
        
        //Crear array
        
        $user = array(
            "name"  => $name,
            "last_name_p" => $last_name_p,
            "last_name_m" => $last_name_m,
            "rut"         => $rut,
            "telephone_number" => $telephone_number,
            "cell_phone_number" => $cell_phone_number,
            "address"           => $address,
            "commune"           => $commune,
            "region"            => $region,
            "birth_date"        => $birth_date,
            "gender"            => $gender,
            "avatar"            => $avatar
        );
        
        $this->db->where("id_user", $id_user);
        
        $query = $this->db->update("ws_user_information", $user);
        
        return $query;
        
    }
}