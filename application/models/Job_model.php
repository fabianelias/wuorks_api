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
        
        $this->db->select('j.key_aviso, j.title, j.description, 
                           j.remuneration, j.created_at, j.delete_at, j.id_user,
                           j.state
                            ');
        $this->db->join('ws_config_job as cj','cj.key_aviso = j.key_aviso','left');
        $this->db->where('j.id_user',$id_user);
        $this->db->where('j.state',0);//0 = activo; 1 = desactivo
        $this->db->order_by('j.id','DESC');
        
        $query = $this->db->get('ws_job as j');
        
        return $query->result_array();
        
        
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
}   


