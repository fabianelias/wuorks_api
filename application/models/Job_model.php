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
            "type_job"          => $type_job,
            "key_job"           => $key_job,
            "id_user"           => $id_user
        );
        
        $query = $this->db->insert("ws_jobs", $job);
        
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
}

