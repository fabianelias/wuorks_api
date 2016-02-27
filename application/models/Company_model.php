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
                                  //$key_company,
                                  $id_user,
                                  $commune,
                                  $region){
        
        $company = array(
            "company_name"        => $company_name,
            "company_description" => $company_description,
            "address"             => $address,
            "company_category"    => $company_category,
            "region"              => $region,
            "commune"             => $commune
        );
        
        
        $this->db->where("id_user", $id_user);
        
        $query = $this->db->update("ws_company", $company);
        
        return $query;
        
    }
    
    
    /***************************************************************************
     * @infoCompany(), función para obtener info de la empresa
     **************************************************************************/
    public function myCompany($id_user){
        
        $this->db->select('*');
        $this->db->where('id_user',$id_user);
        
        $query = $this->db->get('ws_company');
        
        if($query->num_rows() > 0){
            
            $row = $query->result_array();
            
                $this->db->select("*");
                $this->db->where("id_company", $row[0]["id_company"]);
                $query2 = $this->db->get("ws_rating");
                
                $rating = $query2->result_array();
                
                $infoCompany[] = array(
                    "company_name"        => $row[0]["company_name"],
                    "company_description" => $row[0]["company_description"],
                    "address"             => $row[0]["address"],
                    "company_category"    => $row[0]["company_category"],
                    "key_company"         => $row[0]["key_company"],
                    "create_time"         => $row[0]["create_time"],
                    "region"              => $row[0]["region"],
                    "commune"             => $row[0]["commune"],
                    "rating"              => $rating
                );
                return $infoCompany;
        }else{
            
            return  false;
        }
    }
}