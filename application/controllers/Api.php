<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct() {
	
        parent::__construct();
        $this->load->model('api_model');
        $this->load->library( 'form_validation','session','email','authorization_token');
        header('Access-Control-Allow-Origin: * ');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: * ");
    }
    function base64url_encode($str) {

        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
  
     public function generate_jwt($headers,$payload,$secret = 'secret')
     {
     $headers_encoded = $this->base64url_encode(json_encode($headers));
     $payload_encoded = $this->base64url_encode(json_encode($payload));
     
     $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
     $signature_encoded = $this->base64url_encode($signature);
     $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
     return $jwt;
     }



	public function getUserlogin() {
        $data = array(
            'status' => '',
            'reason' => '',
            'data' => '',
        );
        $json = file_get_contents('php://input');
        $data = json_decode($json);
     
        //$email = $this->input->post('email');
        //$password = $this->input->post('password');
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array(
               'admin_email' => $data->admin_email,
               'admin_password' => md5($data->admin_password),
               'logintime' => date("Y-m-d H:i:s"),
        );
        //$id=$data->id;
     
        if ($data->admin_email != '' && $data->admin_password != '') {
            $rec = $this->api_model->getUserlogin($data->admin_email, $data->admin_password);
           // print_r($rec->num_rows());
           // die;
           $jwt = $this->generate_jwt($headers, $payload, $secret = 'secret');
            if($rec->num_rows()>0){
               $arr = array(
                    'token_no' => $jwt,
                    'token_exp' => date("Y-m-d H:i:s"),
                    
               );
               $this->db->where('id',$rec->row()->id);
               $this->db->update('admin_login',$arr);
               $rec->row()->token=$jwt;
               //$this->db->insert('users',$arr);
              // echo $this->db->last_query(); die;
                 $response = array(
                'status' => 'success',
                'reason' => 'Login successful',
                 'data' => $rec->row(),
               
            );
            }else{
                $response = array(
                'status' => 'error',
                'reason' => 'Incorrect Email and Password',
                
               
            );
            }
           
            
        } else {
           $response = array(
                'status' => 'error',
                'reason' => 'Email And Password can not be blank',
            );
        }
     
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_header($jwt)
                        ->set_output(json_encode($response));
     }

public function getAllCustomer() 
{

      $this->db->select('id,first_name,last_name,father_name,image,email, phone, status');
      $this->db->from('customers');
     
      $res=$this->db->get()->result();
      $response = array(
                'result' => 'success',
                'reason' => '',
                'data' => $res,
            );
    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response));  
                            
                            
        
    }

public function getAllProduct()
 {

        $this->db->select('products.id,products.name,company.name');
        $this->db->from('products');
        $this->db->join("company","products.company=company.id");
       
        $res=$this->db->get()->result();
        $response = array(
                  'result' => 'success',
                  'reason' => '',
                  'data' => $res,
              );
      return $this->output
                      ->set_content_type('application/json')
                      ->set_status_header(200)
                      ->set_output(json_encode($response));  
                              
                              
          
      }

}
