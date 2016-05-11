<?php

/*******************************************************************************
 * 
 *                          Modelos para empleos
 * 
 ******************************************************************************/

Class Job_model extends CI_Model{
    
    public function __construct() {
        
        parent::__construct();
        
    }
    
    /***************************************************************************
     * @create_job(), función para crear un empleo.
     **************************************************************************/
    
    public function create_job( $title,
                                $description,  
                                $remuneration,
                                $applicants_amount,
                                $tipo_aviso,
                                $genero,
                                $horario,
                                $zona,
                                $key_job,
                                $id_user
                                 ){
        
        //Crear Array para ws_job
        $fecha_actual  = date('Y-m-d');
        $fecha_termino = date('Y-m-d',  strtotime('+1 week'));
        $miniJob = array(
                "key_aviso"         => $key_job,
                "title"             => $title,
                "description"       => $description,
                "remuneration"      => $remuneration,
                "applicants_amount" => $applicants_amount,
                "created_at"        => $fecha_actual,
                "delete_at"         => $fecha_termino,
                "id_user"           => $id_user
            );
        
        $query = $this->db->insert("ws_job", $miniJob);
        
        if($query){
            
            $configJob = array(
                "key_aviso"   => $key_job,
                "tipo_aviso"  => $tipo_aviso,
                "genero"      => $genero,
                "horario"     => $horario,
                "zona"        => $zona,
            );
            
            $query2 = $this->db->insert("ws_config_job",$configJob);
            
        }
        
        return $query;
        
    }
    
    /***************************************************************************
     * @edit_job(), función para editar un empleo.
     **************************************************************************/
    
    public function edit_job( $title,
                              $job_description,   
                              $workplace   ,
                              $remuneration,
                              $workday,
                              $applicants_amount,
                              $tags_work_area,
                              $type_job,
                              $key_job,
                              $id_user
                              ){
        
        //Crear Array
        
        $job = array(
            "title"             => $title,
            "job_description"   => $job_description,
            "workplace"         => $workplace,
            "remuneration"      => $remuneration,
            "workday"           => $workday,
            "applicants_amount" => $applicants_amount,
            "tags_works_area"   => serialize($tags_work_area),
            "type_job"          => $type_job
        );
        
        $this->db->where("key_job", $key_job);
        $this->db->where("id_user", $id_user);
        $query = $this->db->update("ws_jobs", $job);
        
        return $query;
        
    }
    
    /***************************************************************************
     * @getMyJobs(), retorna lo jobs creados por el usuario
     **************************************************************************/
    public function getMyJobs($id_user){
        
        //VERIFICO LA FECHA DE TODOS LOS AVISOS SI ESTAN EN LA MISMA FECHA ACTUAL 
        //LOS TERMINO
        $this->verify_job();
        
        
        //SELECCIONO LA INFOR BASICA DEL AVISO
        $this->db->select('j.key_aviso, j.title, j.description, 
                           j.remuneration, j.created_at, j.delete_at, j.id_user,
                           j.state
                            ');
        $this->db->join('ws_config_job as cj','cj.key_aviso = j.key_aviso','left');
        $this->db->where('j.id_user',$id_user);
        //$this->db->where('j.state',0);//0 = activo; 1 = desactivo
        $this->db->order_by('j.id','DESC');
        
        $query = $this->db->get('ws_job as j');
        
        if($query->num_rows() > 0){
            
            //RECORRO LOS AVISOS
            foreach ($query->result_array() as $row){
                
                //SELECCIONO LAS POSTULACIONES ASOCIADAS AL AVISO
                $this->db->select("*");
                $this->db->where("key_job",$row['key_aviso']);
                $query_2 = $this->db->get("ws_postulantes");
                
                if($query_2->num_rows() > 0){
                    
                    //RECORRO LAS POSTULACIONES ASOCIADAS AL AVISO Y BUSCA LA INFO GENERAL DEL USUARIO
                    //QUE POSTULO.
                    foreach ($query_2->result_array() as $row2){
                            $this->db->select("
                                    u.username,
                                    u.email,
                                    u.wuorks_key,
                                    u.type_account,
                                    iu.name,
                                    iu.last_name_p,
                                    iu.telephone_number,
                                    iu.cell_phone_number,
                                    iu.address,
                                    iu.commune,
                                    iu.region,
                                    iu.avatar,
                                    iu.gender
                                   ");
                            $this->db->join("ws_user_information as iu","iu.id_user = u.id_user","left");
                            $this->db->where("u.wuorks_key",$row2['key_user']);
                            $this->db->where("u.state",1);
                            $sqlUser = $this->db->get("ws_user as u");
                            $arrUser = $sqlUser->result_array();
                    }
                }else{
                    $arrUser = array();
                }
                
                $res[]  = array(
                    "key_aviso" => $row['key_aviso'],
                    "title"     => $row['title'],
                    "description" => $row['description'],
                    "remuneration" => $row['remuneration'],
                    "created_at"    => $row['created_at'],
                    "delete_at"     => $row['delete_at'],
                    "id_user"       => $row['id_user'],
                    "state"         => $row['state'],
                    "postulantes"   => $arrUser
                );
            }
            
        }
        return $res;//$query->result_array();
        
        
    }
    
    /***************************************************************************
     * @getMyJobs(), retorna la info de un aviso.
     **************************************************************************/
    public function infoJob($key_aviso){
        
        $this->db->select('j.key_aviso, j.title, j.description, 
                           j.remuneration, j.created_at, j.delete_at, j.id_user,
                           j.state, cj.tipo_aviso, cj.genero, cj.horario, cj.zona,
                           c.company_name, c.company_description,c.address,c.company_category,
                           c.key_company, c.commune, c.region,
                           u.username, u.email, u.wuorks_key,u.type_account, u.create_time
                            ');
        $this->db->join('ws_config_job as cj','cj.key_aviso = j.key_aviso','left');
        $this->db->join('ws_user as u','u.id_user = j.id_user','left');
        $this->db->join('ws_company as c','c.id_user = u.id_user','left');
        $this->db->where('j.key_aviso',$key_aviso);
        $this->db->where('j.state',0);//0 = activo; 1 = desactivo
        $this->db->order_by('j.id','DESC');
        
        $query = $this->db->get('ws_job as j');
        
        return $query->result_array();
        
    }
    
    
    /***************************************************************************
     * @matches($key_aviso), 
     **************************************************************************/
    public function matches($key_aviso){
        
        //Obtener el detalle del aviso
        $infoJob = $this->infoJob($key_aviso);
        
        //Parametros para selección
       
        //obtener todas las profesiones creadas.
        
        $this->db->select("p.id_profession,
                           p.name_profession,
                           p.job_description,
                           p.key_profession,
                           p.id_user,
                           u.username,
                           u.email,
                           u.wuorks_key,
                           u.type_account,
                           iu.name,
                           iu.last_name_p,
                           iu.telephone_number,
                           iu.cell_phone_number,
                           iu.address,
                           iu.commune,
                           iu.region,
                           iu.avatar,
                           iu.gender
                          ");
        $this->db->join("ws_profession as p","p.id_user = u.id_user","left");
        $this->db->join("ws_user_information as iu","iu.id_user = u.id_user","left");
        $this->db->where("u.state",1);
        $sqlUser = $this->db->get("ws_user as u");
        $arrUser = $sqlUser->result_array();
        //iteración 1 sobre los usuarios
        
        $i = 0;
        $matches['users'] = array();
        //while (isset($arrUser['id_profession'])){
        //Parametros para selección
        $k = 0;
        $j = 0;
        foreach ($arrUser as $arr){
           
           //buscar nombre comuna
            $this->db->select("nombre");
            $this->db->where("id",$arr['commune']);
            $query2  = $this->db->get("comunas");
            $comuna = $query2->result_array();
            
            //echo$infoJob[0]['commune'];exit();
            //por profesiòn
            preg_match('/'.strtolower($arr['name_profession']).'/', strtolower($infoJob[0]['title']), $match);
            
            //Por genero
            if($arr['gender'] == $infoJob[0]['genero']){ $gender = "ok";}else{$gender ="";}
            
            
            //Por comuna
            if($arr['commune'] == $infoJob[0]['commune']){$comuna = "Ok";}else{$comuna ="";}
            
            
            if(!empty($match) && !empty($gender) && !empty($comuna)){
                if($arr['id_profession'] != null){
                   
                    $this->db->select_avg('user_rating');
                    $this->db->where('id_profession',$arr['id_profession']);
                    $rating = $this->db->get('ws_rating');
                    
                    $matches['users'][] = array(
                        'id'       => $arr['id_profession'],
                        'username' => $arr['username'],
                        'name_prof'=> $arr['name_profession'],
                        'avatar'   => $arr['avatar'],
                        'rating'   => $rating->row()->user_rating,
                        'key_prof' => $arr['key_profession'],
                        'wuorks_key' => $arr['wuorks_key']
                        );
                }
                $k++;
            }else{
                $j++;
            }
            
            $i++;
        }
       return array_filter($matches); 
    }
    
    
    /***************************************************************************
     * @getJobs(), 
     **************************************************************************/
    public function getJobs(){
        
        //VERIFICO LA FECHA DE TODOS LOS AVISOS SI ESTAN EN LA MISMA FECHA ACTUAL 
        //LOS TERMINO
        $this->verify_job();
        
         $this->db->select('j.key_aviso, j.title, j.description, 
                           j.remuneration, j.created_at, j.delete_at, j.id_user,
                           j.state, cj.tipo_aviso, cj.genero, cj.horario, cj.zona,
                           c.company_name, c.company_description,c.address,c.company_category,
                           c.key_company, c.commune, c.region,ui.avatar,
                           u.username, u.email, u.wuorks_key,u.type_account, u.create_time,
                           co.nombre as nombre_comuna, re.nombre as region_nombre
                            ');
        $this->db->join('ws_config_job as cj','cj.key_aviso = j.key_aviso','left');
        $this->db->join('ws_user as u','u.id_user = j.id_user','left');
        $this->db->join('ws_company as c','c.id_user = u.id_user','left');
        $this->db->join('ws_user_information as ui','ui.id_user = u.id_user','left');
        $this->db->join('comunas as co','co.id = c.commune','left');
        $this->db->join('regiones as re','re.id_region = c.region','left');
        $this->db->where('j.state',0);//0 = activo; 1 = desactivo
        $this->db->order_by('j.id','DESC');
        
        $query = $this->db->get('ws_job as j');
        
        return $query->result_array();
        
    }
    
    /***************************************************************************
     * @aplicar(); guarda una relacion aviso , usuario
     **************************************************************************/
    public function aplicar($key_aviso, $key_user){
        
        $data = array("key_user" => $key_user, "key_job" => $key_aviso,"created_at" => date('Y-m-d'));
        
        $this->db->where('key_user',$key_user);
        $this->db->where('key_job',$key_aviso);
        $query = $this->db->get('ws_postulantes');
        
        if($query->num_rows() > 0){
            return false;
        }else{
            return $this->db->insert('ws_postulantes', $data);
        }
        
    }
    
    /***************************************************************************
     * @verify_job(), termina los avisos que esten en la fecha de termino.
     **************************************************************************/
    public function verify_job(){
        
       $this->db->select('j.key_aviso, j.title, j.description, 
                           j.remuneration, j.created_at, j.delete_at, j.id_user,
                           j.state
                            ');
        $this->db->join('ws_config_job as cj','cj.key_aviso = j.key_aviso','left');
        $this->db->where('j.state',0);//0 = activo; 1 = desactivo
        $this->db->order_by('j.id','DESC');
        
        $query = $this->db->get('ws_job as j');
        
        if($query->num_rows() > 0){
            
            foreach ($query->result_array() as $row){
                
                $fecha_actual = date('Y-m-d');
                if($row['delete_at'] == $fecha_actual){
                    
                    $update = array('state' => 1);
                    $this->db->where('key_aviso',$row['key_aviso']);
                    $this->db->update('ws_job',$update);
                    
                }else{
                    echo "";
                }
                
            }
        }
        
        return true;
    }
}   


