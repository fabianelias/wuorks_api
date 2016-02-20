<?php

/*******************************************************************************
 * 
 *                  Modelo para califaciones de usuario
 * 
 ******************************************************************************/


Class Rating extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
    }
    
    /***************************************************************************
     * @create_rating(), función para crear una calificación de usuario.
     **************************************************************************/
    
    public function create_rating(  $title,
                                    $comment,
                                    $rate_type,
                                    $user_rating,
                                    $id_profession,
                                    $id_company,
                                    $key_rating
                                   ) {
        
        //Crear array
        
        $rating = array(
            "title"         => $title,
            "comment"       => $comment,
            "rate_type"     => $rate_type,
            "user_rating"   => $user_rating,
            "id_profession" => $id_profession,
            "id_company"    => $id_company,
            "key_rating"    => $key_rating
        );
        
        $query = $this->db->insert("ws_rating", $rating);
        
        return $query;
        
    }
}
