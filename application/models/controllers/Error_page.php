<?php
if (!defined('BASEPATH'))
   exit('No direct script access allowed');
class Error_page extends CI_Controller { 
   public function index(){
      
   }
  public function no_found(){
     $this->load->view("error_page/404.shtml");
  }
}