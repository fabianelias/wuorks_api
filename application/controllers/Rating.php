<?php

/*******************************************************************************
 * 
 *                      Controlador para calificaciones
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Rating extends REST_Controller{
    
    public function __construct(){
        
        parent::__construct();
        
        $this->load->model("rating_model", "ratingModel");
        error_reporting(0);
    }
    
    /***************************************************************************
     * 
     *                  Sección uno, crear una calificación
     * 
     **************************************************************************/
    
    
    /***************************************************************************
     * @rating(), funcion para crear una calificación.
     * http://localhost/wuorks_api/rating/rating/title/titulo/comment/comentario/rate_type/1/user_rating/5/id_profession/1/id_company/null/key/WBqyGRGuRHHTEIZwTuJfFvPgyhCHZ67GCmtlAxdT
     **************************************************************************/
    
    public function rating_get(){
        
        //Validar parametros get
        
        if(!$this->get("title")){
            $this->response(NULL, 400);
        }
        if(!$this->get("comment")){
            $this->response(NULL, 400);
        }
        if(!$this->get("rate_type")){
            $this->response(NULL, 400);
        }
        if(!$this->get("id_profession")){
            $this->response(NULL, 400);
        }
        if(!$this->get("id_company")){
            $this->response(NULL, 400);
        }
        if(!$this->get("user_rating")){
            $this->response(NULL, 400);
        }
        
        //Asignació de variables, (Limpiar variables title y comment)
        
        $title   = $this->get("title");
        $title   = str_replace("_", " ", $title);
        
        $comment = $this->get("comment");
        $comment = str_replace("_", " ", $comment);
        
        $rate_type = $this->get("rate_type");
        $user_rating = $this->get("user_rating");
        $id_profession = $this->get("id_profession");
        $id_company    = $this->get("id_company");
        $key_rating    = $this->key_rating();
        
        $create = $this->ratingModel->create_rating( $title,
                                                     $comment,
                                                     $rate_type,
                                                     $user_rating,
                                                     $id_profession,
                                                     $id_company,
                                                     $key_rating
                                                    );
        
        if($create){
            
            $this->response($create, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
            
    }
    
    
    /***************************************************************************
     * @key_rating(), función para crear una key unica para la calificación.
     **************************************************************************/
    
    public function key_rating($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
        $source = 'abcdefghijklmnopqrstuvwxyz';
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if($n==1) $source .= '1234567890';
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
            if($length>0){
                    $key_rating = "";
                    $source = str_split($source,1);
                    for($i=1; $i<=$length; $i++){
                            mt_srand((double)microtime() * 1000000);
                            $num = mt_rand(1,count($source));
                            $key_rating .= $source[$num-1];
                    }
            }
            return $key_rating;
    }
}