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
                           j.state, cj.tipo_aviso, cj.genero, cj.horario, cj.zona
                            ');
        $this->db->join('ws_config_job as cj','cj.key_aviso = j.key_aviso','left');
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
        $sqlProf = "SELECT
                    id_profession, 
                    name_profession, 
                    job_description,
                    key_profession, 
                    id_user
                    FROM ws_profession
                    ";
        $arrProf = $this->db->query($sqlProf);
        $arrProf = $arrProf->result_array();
        
        //iteración 1 sobre los usuarios
        
        $i = 0;
        $matches['users'] = array();
        while (isset($arrProf[$i]['id_profession'])){
            
            
            //Parametros para selección
            $k = 0;
            $j = 0;
            preg_match('/'.$arrProf[$i]['name_profession'].'/', $infoJob[0]['title'],$match);
            
            if (empty($match)) {
                $j++;
               //$matches['users'][] = array('id' => $profesion);
            } else {
                $k = 1;
                $matches['users'][] = array('id' => $arrProf[$i]['id_profession']);
                
            }
            
            $i++;
        }
        
        return $matches;
        
    }
}   


