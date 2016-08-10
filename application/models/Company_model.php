<?php

/*******************************************************************************
 * 
 *                    Modelos para perfil de empresas
 *
 ******************************************************************************/


Class Company_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
        error_reporting(0);
        
    }
    
    /***************************************************************************
     * @create_company(), función para crear un perfil de empresa.
     **************************************************************************/
    
    public function create_company( $company_name,
                                    $company_description,
                                    $address,
                                    $company_category,
                                    $key_company,
                                    $id_user,
                                    $region,
                                    $commune,
                                    $lat,
                                    $lng){
        
        //Crear array
        
        $company = array(
            "company_name"        => $company_name,
            "company_description" => $company_description,
            "address"             => $address,
            "company_category"    => $company_category,
            "key_company"         => $key_company,
            "id_user"             => $id_user,
            "region"              => $region,
            "commune"             => $commune,
            "lat"                 => $lat,
            "lng"                 => $lng
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
                                  $region,
                                  $commune,
                                  $lat,
                                  $lng
                                  ){
        
        $company = array(
            "company_name"        => $company_name,
            "company_description" => $company_description,
            "address"             => $address,
            "company_category"    => $company_category,
            "commune"             => $commune,
            "region"              => $region,
            "lat"                 => $lat,
            "lng"                 => $lng
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
            
            //buscar nombre region
            $this->db->select("nombre as nombre");
            $this->db->where("id_region",$row[0]["region"]);
            $query11  = $this->db->get("regiones");
            $region = $query11->row()->nombre;
           
            //buscar nombre comuna
            $this->db->select("nombre as nombre");
            $this->db->where("id",$row[0]["commune"]);
            $query22  = $this->db->get("comunas");
            $comuna = $query22->row()->nombre;
            
            
            //$this->db->select("*");
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
            
            $this->db->where("id_company", $row[0]["id_company"]);
            $query2 = $this->db->get("ws_rating as r");
                
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
                    "rating"              => $rating,
                    "region_nom"          => $region,
                    "comuna_nom"          => $comuna,
                    "lat"                 => $row[0]["lat"],
                    "lng"                 => $row[0]["lng"]
                );
                
                return $infoCompany;
                
        }else{
            return  false;
        }
    }
    
    
    /***************************************************************************
     * @company_info(), función para retornar la info de una empresa y su dueño
     * para vista de resultados.
     **************************************************************************/
    public function company_info($wuorks_key, $key_company){
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ui', "c.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->where('c.key_company', $key_company);
        $this->db->where('u.wuorks_key', $wuorks_key);
        
        $query = $this->db->get("ws_company as c");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                //Nombres comuna y region
                 //buscar nombre region
                $this->db->select("nombre");
                $this->db->where("id_region",$row["region"]);
                $res_reg  = $this->db->get("regiones");
                $region = $res_reg->row()->nombre;

                //buscar nombre comuna
                $this->db->select("nombre as nombre");
                $this->db->where("id",$row["commune"]);
                $res_com  = $this->db->get("comunas");
                $comuna = $res_com->row()->nombre;
                
                /*$this->db->select("*");
                $this->db->where("id_company", $row["id_company"]);*/
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
                $this->db->where("id_company", $row["id_company"]);
                $this->db->order_by("id_rating","desc");
                $query2 = $this->db->get("ws_rating as r");
                
                $rating = $query2->result_array();
                
                $infoCompany[] = array(
                    "username"        => $row["username"],
                    "id_user"         => $row["id_user"],
                    "email"           => $row["email"],
                    "wuorks_key"      => $row["wuorks_key"],
                    "user_type"       => $row["user_type"],
                    "type_account"    => $row["type_account"],
                    "create_time"     => $row["create_time"],
                    "company_category" => $row["company_category"],
                    "company_description" => $row["company_description"],
                    "workplace"       => $row["workplace"],
                    "key_company"  => $row["key_company"],
                    "company_name"    => $row["company_name"],
                    "avatar"          => $row["avatar"],
                    "rating"          => $rating,
                    "address"         => $row["address"],
                    "commune"         => $comuna,//$row["commune"],
                    "region"          => $region//$row["region"],
                    
                );
                
                
            }
            return $infoCompany;
        }else{
            return false;
        }
        
    }
    
}