<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');


class ACL{

	/*  */
	protected $lbl_table 		= 	'tbl_admin';
	protected $lbl_status 		= 	'status';
	protected $lbl_email 		= 	'email';
	protected $lbl_password 	= 	'password';
	protected $lbl_id 			= 	'id';
	protected $lbl_added_by 	= 	'added_by';
	protected $lbl_permission 	= 	'permission';
	protected $lbl_token 		= 	'token';
	protected $lbl_logintype 	= 	'logintype';
	
	/*  */

	public function fetchAdmin($email=null,$password=null){
		$CI = & get_instance(); 
		$CI->db->select('*');
		$CI->db->where($this->lbl_status,1);
		$CI->db->where($this->lbl_email,$email);
		$CI->db->where($this->lbl_password,md5($password));
		$res = $CI->db->get($this->lbl_table)->row_array();
		//echo $CI->db->last_query();
		return $res;
	}
	public function getdata($id=null,$addedid=null){
		$CI = & get_instance(); 
		$CI->db->select('*');
		//$CI->db->where('status',1);
		if(!empty($id) || !empty($addedid)){
			if(!empty($id)){
				$CI->db->where($this->lbl_id,$id);
			}
			if(!empty($addedid)){
				$CI->db->where($this->lbl_added_by,$addedid);
			}
		}
			$res = $CI->db->get($this->lbl_table)->result_array();
		//echo $CI->db->last_query();
		return $res;
	}

	public function permission($id=null){
		$CI = & get_instance(); 
		$CI->db->select($this->lbl_permission);
		//$CI->db->where('status',1);
		if(!empty($id)){
			$CI->db->where($this->lbl_id,$id);
		}
		$res = $CI->db->get($this->lbl_table)->row_array();
		return $res;
	}

	public function auth_permission($token,$type=null){
		$CI = & get_instance();
		$CI->load->library('ACl','acl');
		$permission = $CI->acl->permission($token);
		if(!empty($permission)){
			$auth = json_decode($permission['permission']);
			
			foreach($auth as $key=> $a){
					
				if($key == $type){
					if(!empty($a)){
						return true;
					}else{
						return false;
					}
				}
			}
		}else{
			return false;
		}
	}

	

	public function checkloggedin(){
		$CI = & get_instance();
		$CI->load->library('ACl','acl');
		if(!empty($CI->session->userdata($this->lbl_token)) && !empty($CI->session->userdata($this->lbl_email)) && !empty($CI->session->userdata($this->lbl_logintype))){
			$data[$this->lbl_token] 		= 	$CI->session->userdata($this->lbl_token);
			$data[$this->lbl_email] 		= 	$CI->session->userdata($this->lbl_email);
			$data[$this->lbl_logintype] 	= 	$CI->session->userdata($this->lbl_logintype);
			if(!empty($data)){
				return $data;
			}else{
				return 0;
			}
		}
	}
}

?>