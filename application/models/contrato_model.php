<?php

Class Contrato_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
        error_reporting(0);
        
    }
    
    public function ingresa_contrato($datos){
        
        $id_usuario = $this->session->userdata('id_usuario');
        $emisor     = $this->session->userdata('nick_usuario');
        
        $data = array('id_usuario'   => $id_usuario,
                      'destinatario' => $datos['destinatario'],
                      'emisor'       => $emisor,
                      'comentario'   => $datos['comentario'],
                      'estado'       => 0,
                      'id_perfil'    => $datos['id_perfil']);
        
        return $this->db->insert('contratos',$data);
        
    }
    
    /***********************************************************************************
     * @FUNCIÓN PARA RESCATAR LOS DATOS DEL CONTRATADOR, PARA ENVIARSELOS AL CONTRATADO
    ************************************************************************************/
    public function get_datos($desti, $id_p, $com){
        
        $id_usuario = $this->session->userdata('id_usuario');//id del usuario conectado
        $destinatario = $desti;//Nombre usuario contratado
        $id_perfil = $id_p; //ID del perfil del contratado
        
        
        //Obtenemos los datos del contratador
        $this->db->select('*');
        $this->db->join('datos_usuario as du','du.id_usuario = u.id_usuario','left');
        $this->db->where('u.id_usuario',$id_usuario);
        $query = $this->db->get('usuarios as u');
        
        if($query->num_rows() > 0){
            $contratador = $query->result_array();
        }else{
            return false;
        } 
        
        //Obtenemos los datos del contratado
        $this->db->select('*');
        $this->db->join('perfil_profesional as pf','pf.id_usuario = u.id_usuario','left');
        $this->db->where('pf.id_perfil',$id_perfil);
        $query1 = $this->db->get('usuarios as u');
        if($query1->num_rows() > 0){
            $contratado = $query1->result_array();
        }
        
        $query = $this->db->query('SELECT LAST_INSERT_ID() FROM contratos');
        $row = $query->row_array();
        //creamos la valoracion con valores nullos
        $valoracion = array('id_perfilp' => $id_perfil,
                            'id_usuario' => $id_usuario,
                            'tipo_valoracion' => (int)-1,
                            'resena' => '',
                            'fecha_valoracion' => date('Y-m-d'),
                            'estado'  => 0,
                            'usuario' => $contratador[0]['nick_usuario'],
                            'id_contrato' => $row['LAST_INSERT_ID()']
            );
        $this->db->insert('valoraciones',$valoracion);
        //
        $this->load->library('email');
        //Armamos el email
       /* $this->email->initialize(array(
              'protocol' => 'smtp',
              'smtp_host' => 'smtp.mandrillapp.com',
              'smtp_user' => 'fabian_bravo@live.com',
              'smtp_pass' => 'v5Oh_LTqtPfoQfJhxPUn2Q',
              'smtp_port' => 587,
              'mailtype' => 'html',
              'crlf' => "\r\n",
              'newline' => "\r\n"
           ));*/
        $config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['mailtype'] = 'html';
		$config['protocol'] = 'smpt';
		$config['smtp_host'] = 'ssl://smtp.googlemail.com';
		$config['smtp_port'] = 465;
		$config['smtp_user'] = 'fabian.bravo93@gmail.com';
		$config['smtp_pass'] = 'corsario1993';
		$config['validation'] = TRUE;
		$this->email->initialize($config);
		$this->email->clear();
        //cargamos las librarias necesarias
        
        $this->load->library('email_registro');

        $this->email->from('no-reply@wuorks.com', 'WUORKS | Necesito tu profesión');
        $this->email->to($contratado[0]['email']);
        $this->email->subject(utf8_decode($destinatario.', Contrate tu profesión '.$contratado[0]['nom_profesion']));
        $this->email->message($this->email_registro->email_contrato($destinatario,$contratado,$contratador,$com),TRUE);//Load a view into email body
        $this->email->send();
        
        //array de vuelta
        $data['contratado'] = $contratado;
        $data['contratador'] = $contratador;
        
        return $data;
       
    }
    /***************************************************************************
     * FUNCION PARA VALORAR UN USUARIO
     ***************************************************************************/
    public function valorar_usuario($tipo_val, $id_contrato, $comentario){
        
        //echo $id_contrato; exit();
        //actualizamos el registro de la tabla valoraciones
        $data = array(
            'resena'          => $comentario,
            'tipo_valoracion' => $tipo_val,
            'fecha_valoracion'=> date('Y-m-d'),
            'estado'          => 1
        );
        $this->db->where('id_contrato',$id_contrato);
        $this->db->update('valoraciones',$data);
        
        //Actualizamos el estado del registro contrato
        $data_contrato = array('estado' => 1);
        $this->db->where('id_contrato',$id_contrato);
        $this->db->update('contratos',$data_contrato);
        
        
        //actualizamos la valoracion del perfil_profesional
        //seleccionamos el id del perfil
        $this->db->select('*');
        $this->db->where('id_contrato',$id_contrato);
        $query = $this->db->get('contratos');
        $perfil = $query->row_array();
        $id_perfil = $perfil['id_perfil'];
        
        
        
        $this->db->select('*');
        $this->db->where('id_perfil',$id_perfil);
        $query2 = $this->db->get('perfil_profesional');
        $q_2 = $query2->row_array();
        
        $val_pos = $q_2['valor_positiva'];
        $val_neu = $q_2['valor_neutra'];
        $val_neg = $q_2['valor_negativa'];
        
       
        
        if($tipo_val == 1){
                $tipo_valo = (int)$val_pos + 1;
                $data_2 = array(
                    'valor_positiva'=> $tipo_valo
                );
        }elseif ($tipo_val == 2) {
                $tipo_valo = (int)$val_neu + 1;
                $data_2 = array(
                    'valor_neutra' => $tipo_valo
                );
        }else{
                $tipo_valo = (int)$val_neg + 1;
                $data_2 = array(
                    'valor_negativa' => $tipo_valo
                );
        }
        //actualizamos
        $this->db->where('id_perfil',$id_perfil);
        $this->db->update('perfil_profesional',$data_2);
        
        return true;
        
    }
}