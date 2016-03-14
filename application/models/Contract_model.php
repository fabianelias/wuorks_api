<?php

/*******************************************************************************
 * 
 *                      Modelo para funciones de contrato
 * 
 *******************************************************************************/

Class Contract_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
    }
    /***************************************************************************
     * @create_contract(), funcion para crear un contrato entre los usuarios.
     **************************************************************************/
    public function create_contract($key_employee,
                                    $key_employer,
                                    $key_service,
                                    $key_contract
                                    ){
        
        $data = array(
              "key_employee" => $key_employee,
              "key_employer" => $key_employer,
              "key_service"  => $key_service,
              "state"        => 0,
              "key_contract" => $key_contract
        );
        
        return $this->db->insert("ws_contract", $data);
        
    }
    /***************************************************************************
     * @obt_contract(), funcion que retorna los contratos de usuario.
     **************************************************************************/
    public function obt_contracts($id_user, $key_user){
        
        $informacion["employee"] = array();
        $informacion["employer"] = array();
        
        //Contratos hechos por el usuario.
        
        $this->db->select("*");
        $this->db->where("c.key_employer",$key_user);
        $this->db->order_by("c.id_contract","DESC");
        $query1 = $this->db->get("ws_contract as c");
        
        if($query1->num_rows() > 0 ){
            
            $info1 = $query1->result_array();
           
            foreach($info1 as $row){
                
                $this->db->select("*");
                $this->db->join("ws_user_information ui ","ui.id_user = u.id_user","left");
                $this->db->where("u.wuorks_key",$row["key_employee"]);
                $query2 = $this->db->get("ws_user as u");
                
                $info = $query2->result_array();
                
                //informaciòn de la profesion o la empresa
                $this->db->select("*");
                if($info[0]["user_type"] == 1){
                    $this->db->where("key_profession",$row["key_service"]);   
                    $q = $this->db->get("ws_profession");
                    $service    = $q->row()->name_profession;
                    $id_service = $q->row()->id_profession;
                  
                }else{
                   $this->db->where("key_company",$row["key_service"]);
                   $q = $this->db->get("ws_company");
                   $service    = $q->row()->company_name;
                   $id_service = $q->row()->id_company;
                }
                
                $informacion["employee"][] = array(
                              "full_name"        => $info[0]["name"]." ".$info[0]["last_name_p"]." ".$info[0]["last_name_m"],
                              "username"         => $info[0]["username"],
                              "telephono_number" => $info[0]["telephone_number"],
                              "t_number_2"       => $info[0]["cell_phone_number"],
                              "email"            => $info[0]["email"],
                              "name_service"     => $service,
                              "type_user"        => $info[0]["user_type"],
                              "wuorks_key"       => $info[0]["wuorks_key"],
                              "service_key"      => $row["key_service"],
                              "state"            => $row["state"],
                              "id"               => $row["id_contract"],
                              "key_contract"     => $row["key_contract"],
                              "id_service"       => $id_service 
                );
            }
        }
        
        //Contratos hechos al usuario.
        $this->db->select("*");
        $this->db->where("c.key_employee",$key_user);
        $this->db->order_by("c.id_contract","DESC");
        $employee = $this->db->get("ws_contract as c");
        
        if($employee->num_rows() > 0 ){
            
            $info2 = $employee->result_array();
           
            foreach($info2 as $row){
                
                $this->db->select("*");
                $this->db->join("ws_user_information ui ","ui.id_user = u.id_user","left");
                $this->db->where("u.wuorks_key",$row["key_employer"]);
                $query3 = $this->db->get("ws_user as u");
                
                $info = $query3->result_array();
                
                //informaciòn de la profesion o la empresa
                $this->db->select("*");
                if($info[0]["user_type"] == 1){
                    $this->db->where("key_profession",$row["key_service"]);   
                    $q = $this->db->get("ws_profession");
                    $service    = $q->row()->name_profession;
                    $id_service = $q->row()->id_profession;
                }else{
                   $this->db->where("key_company",$row["key_service"]);
                   $q = $this->db->get("ws_company");
                   $service    = $q->row()->company_name;
                   $id_service = $q->row()->id_company;
                }
                
                $informacion["employer"][] = array(
                              "full_name"        => $info[0]["name"]." ".$info[0]["last_name_p"]." ".$info[0]["last_name_m"],
                              "username"         => $info[0]["username"],
                              "telephono_number" => $info[0]["telephone_number"],
                              "t_number_2"       => $info[0]["cell_phone_number"],
                              "email"            => $info[0]["email"],
                              "name_service"     => $service,
                              "type_user"        => $info[0]["user_type"],
                              "wuorks_key"      => $info[0]["wuorks_key"],
                              "service_key"      => $row["key_service"],
                              "state"            => $row["state"],
                              "id"               => $row["id_contract"],
                              "key_contract"     => $row["key_contract"],
                              "id_service"       => $id_service 
                );
            }
        }
        return $informacion;
        
    }
    
    /***************************************************************************
     * @rating(), funcion para grabar un calificaciones
     **************************************************************************/
    public function rating($data, $key_contract){
        
        //grabamos la nueva calificaciones
        
        $this->db->insert("ws_rating",$data);
        
        //Actualizamos el estado del copntrato a 1
        
        $dataC = array("state" => 1);
        
        $this->db->where("key_contract", $key_contract);
        
        return $this->db->update("ws_contract",$dataC);
    }
}
