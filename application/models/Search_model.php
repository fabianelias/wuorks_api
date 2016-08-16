<?php
/*******************************************************************************
 * 
 *                  Modelo para resultados de busquedas
 * 
 * Notas*: última actualización de algoritmo de busqueda :09-04-2016.
 *         -Mejoras: Retornar usuarios con orden de mejor nota.
 *         -Recojer los nombres de la región y comuna en cada script de busqueda.
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
        
        $res = array_merge($results["profession"],$results["company"]);
        
        $resu["res"] = $this->a($res,"rating");
        
        
        /*echo "<pre>";
        print_r($resu);
        echo "</pre>";exit();
        */
        return $resu;
        
    }
    
    /***************************************************************************
     * @search_profession(), retorna los usuarios con perfil de profesionales en
     * base a region y area en filtro de busqueda.
    ****************************************************************************/
    
    public function search_profession($wuork_area, $region){
        
        //Buscar el nombre de la region con $region
        $this->db->select("nombre as nombre");
        $this->db->where("id_region",$region);
        $query = $this->db->get("regiones");
        
        //$region = $query->row()->nombre;
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ui', "p.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->where('ui.region',$region);
        $this->db->like('name_profession', $wuork_area,'both');
        $this->db->or_like('job_description', $wuork_area,'both');
        
        
        $query = $this->db->get("ws_profession as p");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                
                //buscar nombre region
                $this->db->select("nombre");
                $this->db->where("id_region",$row["region"]);
                $query  = $this->db->get("regiones");
                $region = $query->row()->nombre;

                //buscar nombre comuna
                $this->db->select("nombre as nombre");
                $this->db->where("id",$row["commune"]);
                $query22  = $this->db->get("comunas");
                $comuna = $query22->row()->nombre;
            
                $this->db->select_avg("user_rating");
                $this->db->where("id_profession", $row["id_profession"]);
                $this->db->order_by('user_rating','DESC');
                $query2 = $this->db->get("ws_rating");
                
                $rating = $query2->result_array();
                
                $profession[] = array(
                    //"id"=> $row["id_user"],
                    "username"        => $row["username"],
                    "profession"      => $row["name_profession"],
                    "job_description" => $row["job_description"],
                    "wuorks_key"      => $row["wuorks_key"],
                    "key_profession"  => $row["key_profession"],
                    "avatar"          => $row["avatar"],
                    "rating"          => number_format($rating[0]["user_rating"],1),//$rating,
                    //"address"         => $row["address"],
                    "commune"         => $comuna,//$row["commune"],
                    "region"          => $region,//$row["region"],
                    "type"            => 1,
                    "lat"             => $row["lat"],
                    "lng"             => $row["lng"]
                );
                
                
            }
            $professionr = $this->a($profession,"rating");
            return $professionr;
            
        }else{
            
            //array vacio 
            $data = array();
            return $data;
            
        }
        
    }
    
    /***************************************************************************
     * @search_company(), retornar usuarios con el perfil de empresas, según 
     * región y area ingresada por el usuario.
    ***************************************************************************/
    
    public function search_company($wuork_area,$region){
        
       /* //Buscar el nombre de la region con $region
        $this->db->select("nombre as nombre");
        $this->db->where("id_region",$region);
        $res_reg = $this->db->get("regiones");*/
        
        $this->db->select('u.username, c.company_category, c.company_description, u.wuorks_key, c.key_company, ui.avatar,
                           c.address, c.commune, c.region,c.id_company,c.lat,c.lng');
        $this->db->join('ws_user_information as ui', "c.id_user = ui.id_user", "left");
        $this->db->join("ws_user as u", "ui.id_user = u.id_user", "left");
        $this->db->like('c.company_category', $wuork_area,'both');
        $this->db->or_like('c.company_description', $wuork_area,'both');
        $this->db->where('c.region',$region);
        
        $query = $this->db->get("ws_company as c");
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
               
                //buscar nombre region
                $this->db->select("nombre");
                $this->db->where("id_region",$row["region"]);
                $query  = $this->db->get("regiones");
                $region = $query->row()->nombre;

                //buscar nombre comuna
                $this->db->select("nombre as nombre");
                $this->db->where("id",$row["commune"]);
                $query22  = $this->db->get("comunas");
                $comuna = $query22->row()->nombre;
                
                
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
                    "rating"          => number_format($rating[0]["user_rating"],1),//$rating,
                    //"address"         => $row["address"],
                    "commune"         => $comuna,//$row["commune"],
                    "region"          => $region,//$row["region"],
                    "type"            => 2,
                    "lat"             => $row["lat"],
                    "lng"             => $row["lng"]
                );
                
            }
            
            $companys = $this->a($company,"rating");
            return $companys;
            
        }else{
            //array vacio 
            $data = array();
            return $data;
            
        }
    }
    

    public function a(&$array, $key) {
        
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        arsort($sorter);
        $o=0;
        foreach ($sorter as $ii => $va) {
            $ret[$o]=$array[$ii];
            $o++;
        }
        $array=$ret;
       
        return $array;
    }
}


