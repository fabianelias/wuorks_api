<?php

/*******************************************************************************
 * 
 *                           Modelo para usuarios
 *
 ******************************************************************************/

Class User_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
        error_reporting(0);
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
                               // $avatar,
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
           // "avatar"            => $avatar
        );
        
        $this->db->where("id_user", $id_user);
        
        $query = $this->db->update("ws_user_information", $user);
        
        return $query;
        
    }
    
    /***************************************************************************
     * @change_pass(), función para cambiar la contraseña
     **************************************************************************/
    public function change_pass($password, $id_user){
        
        $pass = array("password" => $password);
        
        $this->db->where("id_user",$id_user);
        
        return $this->db->update("ws_user",$pass);
        
    }
    
    /***************************************************************************
     *@change_avatar(),
     **************************************************************************/
    public function change_avatar($imagen, $id_user){
        
        $avatar = array("avatar" => $imagen);
        
        $this->db->where("id_user",$id_user);
        
        return $this->db->update("ws_user_information",$avatar);
        
    }
    /***************************************************************************
     * @infoUser(), función que retorna la info del usuario
     **************************************************************************/
    public function infoUser($id_user){
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ai',"ai.id_user = u.id_user","left");
        $this->db->where('u.id_user', $id_user);
        $sql_1 = $this->db->get("ws_user as u");
        
        if($sql_1->num_rows() > 0){
            
            $data = $sql_1->result_array();
            
            //buscar nombre region
            $this->db->select("nombre");
            $this->db->where("id_region",$data[0]["region"]);
            $query  = $this->db->get("regiones");
            $region = $query->row()->nombre;
           
            //buscar nombre comuna
            $this->db->select("nombre as nombre");
            $this->db->where("id",$data[0]["commune"]);
            $query2  = $this->db->get("comunas");
            $comuna = $query2->row()->nombre;
            $data2 = array(
                "region_nom" => $region,
                "comuna_nom" => $comuna
            );
            //$data = array_merge($data, $data2);
                //Correcto
                $res = array(
                    'res'        => (int)1,
                    'data'       => $data,
                    'region_nom' => $region,
                    'comuna_nom' => $comuna
                ); 
                
            
        }else{
            
            //No se encontraron resultados
            $res = array(
                'res' => "1"
                );
            
            
        }
        
        return $res;
    }
}