<?php
Controller :


  $response = $this->MyModel->auth();
		        if($response['status'] == 200){
		        	$resp = $this->MyModel->book_all_data();
	    			json_output($response['status'],$resp);
		        }
///////////////////////////////////////////////////////////////////////
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {


    public function login($email,$password)
    {
        $q  = $this->db->select('password,id')->from('user')->where('email',$email)->get()->row();
        
        if(empty($q)){
            return array('status' => 401,'message' => 'Email not found.');
        } else {
            $hashed_password = $q->password;
            $id              = $q->id;
            if (password_verify($password, $hashed_password)) {
               $last_login = date('Y-m-d H:i:s');
               $token = crypt(substr(md5(rand()), 0, 7), $password);
               $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
               $this->db->trans_start();
               $this->db->where('id',$id)->update('user',array('last_login' => $last_login));
               $this->db->insert('user_authentication',array('users_id' => $id,'token' => $token,'expired_at' => $expired_at));
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','id' => $id, 'token' => $token);
               }
            } else {
               return array('status' => 401,'message' => 'Wrong password.');
            }
        }
    }

    public function logout()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $this->db->where('users_id',$users_id)->where('token',$token)->delete('user_authentication');
        return array('status' => 200,'message' => 'Successfully logout.');
    }

    public function auth()
    {
        $users_id  = $this->input->request_headers('User-ID', TRUE);
        $token     = $this->input->request_headers('Authorization', TRUE);
        $q  = $this->db->select('expired_at')->from('user_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
        if($q == ""){
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } else {
            if($q->expired_at < date('Y-m-d H:i:s')){
                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } else {
                $updated_at = date('Y-m-d H:i:s');
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                $this->db->where('users_id',$users_id)->where('token',$token)->update('user_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
                return array('status' => 200,'message' => 'Authorized.');
            }
        }
    }


}
