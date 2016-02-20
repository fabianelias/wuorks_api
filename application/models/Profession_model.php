<?php

/*******************************************************************************
 * 
 *                          Modelo para profesiones
 * 
 *******************************************************************************/

Class Profession_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
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
}
 

