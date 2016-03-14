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
        
        //$results["company"]    = $this->search_company($wuork_area, $region);
        
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
        $this->db->like('name_profession', $wuork_area);
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
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ui', "c.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->like('company_category', $wuork_area);
        $this->db->where('ui.region',$region);
        
        $query = $this->db->get("ws_ company as c");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                
                $this->db->select_avg("user_rating");
                $this->db->where("id_company", $row["id_company"]);
                $query2 = $this->db->get("ws_rating");
                
                $rating = $query2->result_array();
                
                $profession = array(
                    "username"        => $row["username"],
                    "profession"      => $row["name_profession"],
                    "job_description" => $row["job_description"],
                    "wuorks_key"      => $row["wuorks_key"],
                    "key_profession"  => $row["key_profession"],
                    "avatar"          => $row["avatar"],
                    "rating"          => $query2,
                    "address"         => $row["address"]
                );
                
            }
            
        }else{
            
            return false;
            
        }
    }
}
