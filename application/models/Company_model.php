<?php

/*******************************************************************************
 * 
 *                    Modelos para perfil de empresas
 *
 ******************************************************************************/


Class Company_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
    }
    
    /***************************************************************************
     * @create_company(), función para crear un perfil de empresa.
     **************************************************************************/
    
    public function create_company( $company_name,
                                    $company_description,
                                    $address,
                                    $company_category,
                                    $key_company,
                                    $id_user){
        
        //Crear array
        
        $company = array(
            "company_name"        => $company_name,
            "company_description" => $company_description,
            "address"             => $address,
            "company_category"    => $company_category,
            "key_company"         => $key_company,
            "id_user"             => $id_user
        );
        
        $query = $this->db->insert("ws_company", $company);
        
        return $query;
        
    }
    
    
    /***************************************************************************
     * @edit_company(), función para editar un perfil de empresa.
     **************************************************************************/
    
    public function edit_company( $company_name,
                                  $company_description,
                                  $address,
                                  $company_category,
                                  $key_company,
                                  $id_user){
        
        $company = array(
            "company_name"        => $company_name,
            "company_description" => $company_description,
            "address"             => $address,
            "company_category"    => $company_category,
        );
        
        $this->db->where("key_company", $key_company);
        $this->db->where("id_user", $id_user);
        
        $query = $this->db->update("ws_company", $company);
        
        return $query;
        
    }
}