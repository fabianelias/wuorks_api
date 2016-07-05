<?php

/*******************************************************************************
 * 
 *                          Modelo para profesiones
 * 
 *******************************************************************************/

Class Profession_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        error_reporting(0);
    }
    
    /***************************************************************************
     * @create_profession(), función para crear una profesión.
     **************************************************************************/
    
    public function create_profession($name_profession,
                                      $job_description,
                                      $workplace,
                                      $key_profession,
                                      $id_user){
        
        //Crear array
        
       $profession = array(
           "name_profession"  => $name_profession,
           "job_description"  => $job_description,
           "workplace"        => $workplace,
           "key_profession"   => $key_profession,
           "id_user"          => $id_user
       );
       
       $query = $this->db->insert("ws_profession", $profession);
      
       return $query;
       
    }
    
    
    
    /***************************************************************************
     * @edit_profession(), función para editar un profesión
     **************************************************************************/
    
    public function edit_profession($name_profession,
                                    $job_description,
                                    $workplace,
                                    $key_profession,
                                    $id_user){
        
        //Crear array
        
        $profession = array(
           "name_profession"  => $name_profession,
           "job_description"  => $job_description,
           "workplace"        => $workplace
        );
         
        $this->db->where("key_profession", $key_profession);
        $this->db->where("id_user", $id_user);
        
        $query = $this->db->update("ws_profession", $profession);
        
        return $query;
       
    }
    
    
    /***************************************************************************
     * @infoProfession(), función para retornar la info una profesión y usuario
     **************************************************************************/
    public function infoProfession($wuorks_key, $key_profession){
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ui', "p.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->where('p.key_profession', $key_profession);
        $this->db->where('u.wuorks_key', $wuorks_key);
        
        $query = $this->db->get("ws_profession as p");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                /*
                $this->db->select("*");
                $this->db->where("id_profession", $row["id_profession"]);
                $this->db->order_by("id_rating","desc");
                $query2 = $this->db->get("ws_rating");
                */
                $this->db->select("r.id_rating,
                                   r.title,
                                   r.comment,
                                   r.rate_type,
                                   r.user_rating,
                                   r.id_profession,
                                   r.id_company,
                                   r.name_user,
                                   r.id_user,
                                   r.key_rating,
                                   r.create_time,
                                   ui.avatar,
                                   u.username
                                   ");
                $this->db->join("ws_user as u","u.wuorks_key = r.id_user","left");
                $this->db->join("ws_user_information as ui","ui.id_user = u.id_user","left");
                $this->db->where("id_profession", $row["id_profession"]);
                $this->db->order_by("id_rating","desc");
                $query2 = $this->db->get("ws_rating as r");
                
                $rating = $query2->result_array();
                
                $infoProfession[] = array(
                    "username"        => $row["username"],
                    "id_user"         => $row["id_user"],
                    "email"           => $row["email"],
                    "wuorks_key"      => $row["wuorks_key"],
                    "user_type"       => $row["user_type"],
                    "type_account"    => $row["type_account"],
                    "create_time"     => $row["create_time"],
                    "name_profession" => $row["name_profession"],
                    "job_description" => $row["job_description"],
                    "workplace"       => $row["workplace"],
                    "key_profession"  => $row["key_profession"],
                    "avatar"          => $row["avatar"],
                    "rating"          => $rating,
                    "address"         => $row["address"],
                    "commune"         => $row["commune"],
                    "region"          => $row["region"],
                    
                );
                
                
            }
            return $infoProfession;
        }else{
            
            return false;
            
        }
    }
    
    
    /***************************************************************************
     * @infoProfessions(), función para retonar las profesiones de un usuario.
     **************************************************************************/
    public function infoProfessions($id_user){
        
        $this->db->select('*');
        $this->db->where('id_user', $id_user);
        $query = $this->db->get("ws_profession");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                
                $this->db->select("r.id_rating,
                                   r.title,
                                   r.comment,
                                   r.rate_type,
                                   r.user_rating,
                                   r.id_profession,
                                   r.id_company,
                                   r.name_user,
                                   r.id_user,
                                   r.key_rating,
                                   r.create_time,
                                   ui.avatar,
                                   u.username
                                   ");
                $this->db->join("ws_user as u","u.wuorks_key = r.id_user","left");
                $this->db->join("ws_user_information as ui","ui.id_user = u.id_user","left");
                $this->db->where("id_profession", $row["id_profession"]);
                $this->db->order_by("id_rating","desc");
                $query2 = $this->db->get("ws_rating as r");
                
                $rating = $query2->result_array();
                
                $infoProfessions[] = array(
                    "name_profession" => $row["name_profession"],
                    "job_description" => $row["job_description"],
                    "workplace"       => $row["workplace"],
                    "key_profession"  => $row["key_profession"],
                    "rating"          => $rating
                    
                );
                
                
            }
            return $infoProfessions;
        }else{
            
            return 1;
            
        }
    }
}
 

