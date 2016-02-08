<?php

   class Empresas extends CI_Controller{

      function __construct(){
      parent::__construct();
      $this->load->model('Empresas_model');
      $this->load->model('Regiones_model');
      $this->very_sesion();
   }
   function very_sesion(){
      if(!$this->session->userdata('user')){
         redirect(base_url().'Welcome');
      }
   }

      //panel de administracion de empresas
      public function panel(){

         $email =$this->session->userdata('user');
         $data["datos"] = $this->Empresas_model->obtener_datos($email);
         $data['regiones'] = $this->Regiones_model->obtenerRegiones();
          $data['title'] = 'Mi Panel. WUORKS | El profesional que necesitas!';

            $this->load->view('template/header', $data);
            $this->load->view('template/up-header');
            $this->load->view('template/inc-menu-empresa');
            $this->load->view('empresas/panel');
            $this->load->view('template/footer');
      } 
      //vista de mis servicios
      public function mis_servicios(){
         $email = $this->session->userdata('user');

         $data["datos"] = $this->Empresas_model->obtener_datos($email);
         $data['regiones'] = $this->Regiones_model->obtenerRegiones();
         $data['title'] = 'Mis servicios. WUORKS | El profesional que necesitas!';

         $this->load->view('template/header',$data);
         $this->load->view('template/up-header');
         $this->load->view('template/inc-menu-empresa');
         $this->load->view('empresas/mis-servicios',$data);
          $this->load->view('template/footer');
      }
         public function mensajes(){


         $email =$this->session->userdata('user');

         $data["datos"] = $this->Empresas_model->obtener_datos($email);
         $data["mensajes_enviados"] = $this->Empresas_model->obtener_mensajes_enviados($email);
         $data["mensajes_recibidos"] = $this->Empresas_model->obtener_mensajes_recibidos($data["datos"]->nombre);
         $data['regiones'] = $this->Regiones_model->obtenerRegiones();
         $data['title'] = 'Inbox -WUORKS | El profesional que necesitas! 2015';
            $this->load->view('template/header', $data);
            $this->load->view('template/up-header');
            $this->load->view('template/inc-menu-empresa');
            $this->load->view('empresas/mensajes',$data);
            $this->load->view('template/footer');
   }

      public function actualizar_datos()
   {
         $email =$this->session->userdata('user');
         $data["datos"] = $this->Empresas_model->obtener_datos($email);
         $data['regiones'] = $this->Regiones_model->obtenerRegiones();

         $data['title'] = 'Actualizar mis datos  -WUORKS | El profesional que necesitas! 2015';
            $this->load->view('template/header', $data);
            $this->load->view('template/up-header');
            //$this->load->view('template/inc-menu-lateral');
            $this->load->view('empresas/actualizarEmpresa',$data);
            $this->load->view('template/footer');
   }  
      public function actualizar()
   {
      if($this->input->post('actualizar'))
      {
      
      $this->form_validation->set_rules('nombre','Nombre','trim|required');
      $this->form_validation->set_rules('giro','Giro','trim|required');
      $this->form_validation->set_rules('region','Region','trim|required');
      $this->form_validation->set_rules('comuna','Comuna','trim|required');
      $this->form_validation->set_rules('direccion','direccion','trim|required');
      $this->form_validation->set_rules('telefonoCelular','Nro. de celular','trim|required');
      $this->form_validation->set_message('required','%s es obligatorio');
      $this->form_validation->set_message('matches','Las contraseñas no coinciden');
      $this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
      $this->form_validation->set_message('max_length','El %s debe tener un máximo de caracteres');

      if($this->form_validation->run() != FALSE){

         $email = $this->Empresas_model->modificar_empresas($this->input->post());
            $this->session->set_flashdata('mensaje', 'Se han modificado tus datos correctamente');
                redirect('empresas/panel','refresh');
            
      }else{
         $email =$this->session->userdata('user');
         $data["datos"] = $this->Empresas_model->obtener_datos($email);
         $data['title'] = 'Mi perfil - WUORKS | El profesional que necesitas! 2015';
            $this->load->view('template/header', $data);
            $this->load->view('template/up-header');
            //$this->load->view('template/inc-menu-empresa');
            $this->load->view('empresas/actualizarEmpresa');
            $this->load->view('template/footer');  

         }  
        }

      }
      public function cambiar_clave(){
         if($this->input->post('actualizar_clave'))
      {
      
      $this->form_validation->set_rules('clave','Clave','trim|required|min_length[6]|max_length[35]');
      $this->form_validation->set_rules('clave2','Repite Clave','trim|required|matches[clave]');
      $this->form_validation->set_message('required','%s es obligatorio');
      $this->form_validation->set_message('matches','Las contraseñas no coinciden');
      $this->form_validation->set_message('min_length','El %s debe tener al menos 6 caracteres');
      $this->form_validation->set_message('max_length','El %s debe tener un máximo de caracteres');

      if($this->form_validation->run() != FALSE){

         $email = $this->Empresas_model->modificar_clave($this->input->post());
         //$msj  = array('mensaje' => "Se han modificado con exito tus datos." );
         //$data['title'] = 'Actualizar Datos - WUORKS | El profesional que necesitas! 2015';
            //$this->load->view('template/header', $data);
            //$this->load->view('template/up-header');
            //$this->load->view('template/inc-menu');
            //$this->load->view('cuentas/mi_perfil',$msj);
            //$this->load->view('template/footer');
            $this->session->set_flashdata('mensaje', 'La clave se ha cambiado correctamente');
                redirect('empresas/panel','refresh');
            
      }else{
         $email =$this->session->userdata('user');
         $data["datos"] = $this->Account_model->obtener_datos($email);
         $data['title'] = 'Mi perfil - WUORKS | El profesional que necesitas! 2015';
            $this->load->view('template/header', $data);
            $this->load->view('template/up-header');
            $this->load->view('template/inc-menu-empresa');
            $this->load->view('cuentas/actualizar-datos');
            $this->load->view('template/footer');  

         }  
        }

      }
      public function subir_imagen(){
         if($this->input->post('foto_perfil'))
      {

         if ($_FILES['userfile']['name'] != '') {

               $respuesta = $this->upload_image();
               if (!is_array($respuesta)) {

                  $this->Empresas_model->foto_perfil($respuesta);
                  $this->session->set_flashdata('mensaje', 'La imagen se ha cambiado correctamente');
               redirect('empresas/panel','refresh');
               }else{
                  
            $this->session->set_flashdata('mensaje', 'Error al cambiar la imagen');
                redirect('empresas/actualizar_datos','refresh');

               }

         
         }else{
            $this->session->set_flashdata('mensaje', 'No se selecciono una imagen');
                redirect('empresas/actualizar_datos','refresh');
         }

         
        }

      }
      function upload_image(){
         $file_name = md5($this->session->userdata('user')).'profile';
         $config['upload_path'] = 'asset/images/users/';
         $config['allowed_types'] = 'gif|jpg|png|jpeg';
         $config['max_size'] = 2*1024;
         $config['max_width'] = '1024';
         $config['max_height'] = '1024';
         $config['file_name'] = $file_name;
         

         $this->load->library('upload', $config);

         if ( ! $this->upload->do_upload()) {
            
            $error  = array('error' => $this->upload->display_errors());
            //$error = $config['file_name'];
            return $error;
         }else{
            //$data = array('upload_data' => $this->upload->data());
            $data = $this->upload->data();
            $this->create_thumb($data['file_name']);
            return $data['file_name'];
         }
      }
      function create_thumb($imagen){
      $config['image_library'] = 'gd2';
      $config['source_image'] = 'asset/images/users/'.$imagen;
      $config['new_image'] = 'asset/images/thumbs_user/';
      $config['thumb_marker'] = '_thumb';
      $config['create_thumb'] = TRUE;
      $config['maintain_ratio'] = TRUE;
      $config['width'] = 190;
      $config['height'] = 190;

      $this->load->library('image_lib',$config);
      $this->image_lib->resize();
      }
      function get_profesiones(){
    if (isset($_GET['term'])){
      $q = strtolower($_GET['term']);
      $this->Account_model->get_profesiones();
    }
  }

   } 
 ?>