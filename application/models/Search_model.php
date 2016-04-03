<?php
/*******************************************************************************
 * 
 *                  Modelo para resuldos de busquedas
 * 
 ******************************************************************************/

Class Search_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        error_reporting(0);
    }
    
    /***************************************************************************
     * @search(), retorna usuarios según criterios del usuario.
     **************************************************************************/
    
    public function search($wuork_area, $region){
        
        $results["profession"] = $this->search_profession($wuork_area, $region);
        
        $results["company"]    = $this->search_company($wuork_area, $region);
        
        return $results;
        
    }
    
    public function search_profession($wuork_area, $region){
        
        //Buscar el nombre de la region con $region
        $this->db->select("nombre as nombre");
        $this->db->where("id_region",$region);
        $query = $this->db->get("regiones");
        
        //$region = $query->row()->nombre;
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ui', "p.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->like('name_profession', $wuork_area,'both');
        $this->db->where('ui.region',$region);
        
        $query = $this->db->get("ws_profession as p");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                
                $this->db->select_avg("user_rating");
                $this->db->where("id_profession", $row["id_ profession"]);
                $query2 = $this->db->get("ws_rating");
                
                $rating = $query2->result_array();
                
                $profession[] = array(
                    "username"        => $row["username"],
                    "profession"      => $row["name_profession"],
                    "job_description" => $row["job_description"],
                    "wuorks_key"      => $row["wuorks_key"],
                    "key_profession"  => $row["key_profession"],
                    "avatar"          => $row["avatar"],
                    "rating"          => $rating,
                    "address"         => $row["address"],
                    "commune"         => $row["commune"],
                    "region"          => $row["region"],
                    "type"            => 1
                );
                
                
            }
            return $profession;
        }else{
            
            return false;
            
        }
        
    }
    public function search_company($wuork_area,$region){
        
       /* //Buscar el nombre de la region con $region
        $this->db->select("nombre as nombre");
        $this->db->where("id_region",$region);
        $res_reg = $this->db->get("regiones");*/
        
        $this->db->select('u.username, c.company_category, c.company_description, u.wuorks_key, c.key_company, ui.avatar,
                           c.address, c.commune, c.region');
        $this->db->join('ws_user_information as ui', "c.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->like('c.company_category', $wuork_area,'both');
        $this->db->where('c.region',$region);
        
        $query = $this->db->get("ws_company as c");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
               
                $this->db->select_avg("user_rating");
                $this->db->where("id_company", $row["id_company"]);
                $query2 = $this->db->get("ws_rating");
                
                $rating = $query2->result_array();
                
                $company[] = array(
                    "username"        => $row["username"],
                    "profession"      => $row["company_category"],
                    "job_description" => $row["company_description"],
                    "wuorks_key"      => $row["wuorks_key"],
                    "key_profession"  => $row["key_company"],
                    "avatar"          => $row["avatar"],
                    "rating"          => $rating,
                    "address"         => $row["address"],
                    "commune"         => $row["commune"],
                    "region"          => $row["region"],
                    "type"            => 2
                );
                
            }
            
            return $company;
            
        }else{
            
            return false;
            
        }
    }
}
