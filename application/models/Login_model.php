<?php

/* *****************************************************************************
 * 
 *                  Modelos para acceso a la app
 * 
 ******************************************************************************/

class  Login_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        error_reporting(0);
    }
    
    /***************************************************************************
     * @valid_user(), función para validar un usuario.
     **************************************************************************/
    public function valid_user($email, $password){
        
        //validar estado de la cuenta
        
        $this->db->select('*');
        $this->db->join('ws_user_information as ai',"ai.id_user = u.id_user","left");
        $this->db->where('u.email', $email);
        $this->db->where('u.password', $password);
        $sql_1 = $this->db->get("ws_user as u");
        
        if($sql_1->num_rows() > 0){
            
            $data = $sql_1->result_array();
            
            //validar el estado del email
            if($data[0]['state'] == 1){
                
                //Correcto
                $res = array(
                    'res'  => (int)3,
                    'data' => $data
                ); 
                
            }else{
                
                //No ha validado el email
                $res = array(
                    'res' => 2
                    );
                
            }
            
            
        }else{
            
            //No se encontraron resultados
            $res = array(
                'res' => "1"
                );
            
            
        }
        
        return $res;
        /*
         *  Tipo de respuestas:
         *   1 : contraseña o email no coinciden.
         *   2 : usuario no ha validado el email
         *   3 : Todo correcto valido para ingreso
         */
    }
}