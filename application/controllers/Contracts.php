<?php
/*******************************************************************************
 * 
 *                Controlador para metodos de contratos 
 * 
 ******************************************************************************/

require(APPPATH.'/libraries/REST_Controller.php');

Class Contracts extends REST_Controller{
    
    public function __construct() {
        
        parent::__construct();
        
        $this->load->model("contract_model", "contractModel");
        
        error_reporting(0);
    }
    
    
    /***************************************************************************
     * @create_contract(), funciòn para crear un contracto entre usuarios.
     **************************************************************************/
    public function create_contract_post(){
        
       if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $contract); 
	
       }
       
       $key_employee = $contract["key_employee"];//$this->get("key_employee");
       $key_employer = $contract["key_employer"];//$this->get("key_employer");
       $key_service  = $contract["key_service"];//$this->get("key_service");
       $key_contract = $this->key_contract();
       
       $nomProf = $contract["nomProf"];//$this->get('nomProf');
       
       $contract     = $this->contractModel->create_contract($key_employee,
                                                             $key_employer,
                                                             $key_service,
                                                             $key_contract
                                                             );
       
       if($contract){
            
            $pro_info_user = $this->contractModel->infoUser($key_employee); // info del contratado.
            
            $info_contratador = $this->contractModel->infoUser($key_employer); // info del contratador.
            
            $this->email->initialize(array(
            'charset'  => 'utf-8',
            'protocol' => 'smtp',
            'smtp_host' => 'smtp-relay.sendinblue.com',
            'smtp_user' => 'contacto@wuorks.com',
            'smtp_pass' => 'VvNS9bGj1pfxXDQg',
            'smtp_port' => 587,
            'mailtype' => 'html',
            'crlf' => "\r\n",
            'newline' => "\r\n"
            ));
            
            // mail al contratador
            $this->email->from('noreply@wuorks.com', 'WUORKS | El profesional que necesitas.');
            $this->email->to($info_contratador['data'][0]['email']);
            $this->email->subject('WUORKS, Haz contrato a '.$pro_info_user["data"][0]["username"]);
            echo $this->email->message("   
                    <h4>Hola <p style='color:#666';>".$info_contratador["data"][0]["username"].",</h4><br/>
                        Haz solicitado la profesión de ".$pro_info_user["data"][0]["username"].", se le ha enviado tu email y número telefonico
                        para contactarte.<br/>
                        <hr/>
                        <br>
                        Suerte,<br/>
                        Equipo Wuorks
                    ");
            $this->email->send();
              
            //Email al contratado
            $this->email->from('noreply@wuorks.com', 'WUORKS | El profesional que necesitas.');
            $this->email->to($pro_info_user["data"][0]["email"]);
            $this->email->subject('WUORKS, Felicidades! te han contratado.');
            $this->email->message("   
                    <h4>Hola <p style='color:#666';>".$pro_info_user["data"][0]["username"].",</h4><br/>
                        ".$info_contratador["data"][0]["username"].", ha solicitado tu profesión '".$nomProf."'., a continuación los datos para contactarlo.<br/>
                        Email: ".$info_contratador["data"][0]['email'].".<br/>
                        Numero: ".$info_contratador["data"][0]['cell_phone_number']."<br/>
                        Nombre: ".$info_contratador["data"][0]['name']." ".$info_contratador["data"][0]['last_name_p']."  
                        <hr/>
                        <br>
                        Suerte,<br/>
                        Equipo Wuorks
                    ");
            $this->email->send();
             
           $this->response($contract, 200);
           
       }else{
           
           $this->response(NULL, 400);
           
       }
       
    }
    
    
    /***************************************************************************
     * @obt_contracts(), función para obtener los contratos hechos por y hacia el
     ***************************************************************************/
    public function obt_contracts_get(){
      
        $id_user  = $this->get("id_user");
        $key_user = $this->get("key_user");
        
        $contract = $this->contractModel->obt_contracts($id_user, $key_user);
        
        if($contract){
            
            $this->response($contract, 200);
            
        }else{
            
            $this->response(NULL, 400);
            
        }
    }
    
    /***************************************************************************
     * @rating(), función para guardar una calificación
     **************************************************************************/
    public function rating_post(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	parse_str(file_get_contents('php://input'), $rating); 
	
       }
       
        $data = array(
                "title"       => $rating["title"],
                "comment"     => $rating["comment"],
                "rate_type"   => $rating["rate_type"],
                "user_rating" => $rating["user_rating"],
                "name_user"   => $rating["name_user"],
                "id_profession" => $rating["id_profession"],
                "id_company"    => $rating["id_company"],
                "id_user"       => $rating["id_user"]
        );
        $key_contract = $rating["id_contract"];
        
       $ratings = $this->contractModel->rating($data, $key_contract);
       
       if($ratings){
           
           $this->response($ratings, 200);
           
       }else{
           
           $this->response(NULL, 400);
           
       }
    }
    
    /***************************************************************************
     * @key_contract(), función para crear una key unica para el empleo.
     **************************************************************************/
    
    public function key_contract($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE){
        
        $source = 'abcdefghijklmnopqrstuvwxyz';
            if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if($n==1) $source .= '1234567890';
            if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
            if($length>0){
                    $key_job = "";
                    $source = str_split($source,1);
                    for($i=1; $i<=$length; $i++){
                            mt_srand((double)microtime() * 1000000);
                            $num = mt_rand(1,count($source));
                            $key_job .= $source[$num-1];
                    }
            }
            return $key_job;
    }
}