<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> 
---------------------------------------------------------------------------------- 
Date : June 2016
Author: Mr. Jayanath Liyanage   jayanathl@icta.lk

Programme Manager: Shriyananda Rathnayake
URL: http://www.govforge.icta.lk/gf/project/hhims/
__________________________________________________________________________________
Private Practice configuration :

Date : July 2015		ICT Agency of Sri Lanka (www.icta.lk), Colombo
Author : Laura Lucas
Programme Manager: Shriyananda Rathnayake
Supervisors : Jayanath Liyanage, Erandi Hettiarachchi
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/
class User extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->checkLogin();
        $this->load->library('session');
    }
    
    	public function change(){
		$ug = $_POST["ug"];
		if ($ug=="") {
			echo -1;
			return;
		}
		if ($ug > 0) {
			$this->load->model("mpersistent");
			$data["usergroup_info"] = $this->mpersistent->open_id( $ug,'user_group','UGID');
		}
		if (!$data["usergroup_info"]){
			echo -1;
			return;
		}
		if( ($this->session->userdata("UserGroup") != 'Programmer')) {
			if ($this->session->userdata('TEST_MODE')){
				$this->session->set_userdata('UserGroup', $data["usergroup_info"]["Name"]);
				$this->session->set_userdata('Scan_Redirect', $data['usergroup_info']['Scan_Redirect']);
				$this->session->set_userdata('TEST_MODE', TRUE);
				echo 1; return;
			}
			else{
				echo -1;
				return;
			}
		}
		$this->session->set_userdata('UserGroup', $data["usergroup_info"]["Name"]);
		$this->session->set_userdata('Scan_Redirect', $data['usergroup_info']['Scan_Redirect']);
		$this->session->set_userdata('TEST_MODE', TRUE);
		echo 1;
	}
    
	public function reset(){
		$uid = $this->session->userdata("reset_suer");
		if ($uid){
			$this->load->model("mpersistent");
			$save_data=array('Password'=>md5("123"));
			$status = $this->mpersistent->update('user', 'UID', $uid, $save_data);
			$this->session->set_flashdata(
				'msg', 'Password reseted.'
			);
			$this->session->set_userdata("reset_suer",null);
			header("Status: 200");
			header("Location: ".site_url("form/edit/user/".$uid)); 
			
		}
		exit;
	}
	function current_user($uid){
		$this->session->set_userdata("reset_suer",$uid);
	}
    public function index()
    {
        //$this->load->view('patient');
		echo "nothing here";
    }

    public function create()
    {
        echo Modules::run('form/create', 'user');
    }
    
	public function profile(){
		$uid = $this->session->userdata("UID");
		$this->load->model("mpersistent");
		$data["user_info"] = $this->mpersistent->open_id( $uid,'user', 'UID');
		if (empty($data["user_info"])){
			$data["error"] ="Profile not found, Login again and try";
			$this->load->vars($data);
			$this->load->view('user_error');
		}
                      $this->load->model('muser');
                      $data["drug_list"] = $this->muser->get_my_favour($uid);
        if ($data["drug_list"] ){
            for ($i=0; $i<count($data["drug_list"]); $i++){
                   $data["drug_list"][$i]["drug_items"] = $this->muser->get_my_favour_drug_list_items($data["drug_list"][$i]["user_favour_drug_id"],null);
            }
        }
        if (isset($_GET["ret"])){
            $this->session->set_userdata("RET_PAGE",$_GET["ret"]);
        }
        else{
              $this->session->set_userdata(site_url()); 
        }
		$this->load->vars($data);
		$this->load->view("user_profile");
	}
        
        public function get_my_favour(){
		$uid = $this->session->userdata("UID");
		$this->load->model('muser');
		$data["list"] = $this->muser->get_my_favour($uid);
		$json = json_encode($data["list"]);
		echo $json ;
	}
	public function get_my_favour_drug_list($favour_id,$Stock_id=null){
		$this->load->model('muser');
		$data["list"] = $this->muser->get_my_favour_drug_list($favour_id,$Stock_id);
		$json = json_encode($data["list"]);
		echo $json ;
	}
    public function save()
    {
    	
        $frm = 'user';
        if (!file_exists('application/forms/' . $frm . '.php')) {
            die("Form " . $frm . "  not found");
        }
        include 'application/forms/' . $frm . '.php';
        $data["form"] = $form;

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->load->model("muser");
	$id = $this->input->post($form["OBJID"]);
	$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
        for ($i = 0; $i < count($form["FLD"]); ++$i) {
            $this->form_validation->set_rules(
                $form["FLD"][$i]["name"], '"' . $form["FLD"][$i]["label"] . '"', $form["FLD"][$i]["rules"]
            );
        }
        $this->form_validation->set_rules($form["OBJID"]);
		if ($id>0){
			$this->form_validation->set_rules("Password", "Password", "");
			$this->form_validation->set_rules("Password_check", "Password confirmation", "");
        }
		else{
			$this->form_validation->set_rules("Password", "Password", "required|matches[Password_check]");
			$this->form_validation->set_rules("Password_check", "Password confirmation", "required");
		}
        if ($this->form_validation->run() == FALSE) {
            $this->load->vars($data);
            echo Modules::run('form/create', 'user');
        } else {
             $sve_data = array(
                'FirstName'  => ucfirst(strtolower($this->input->post("FirstName"))),
                'OtherName'    => strtoupper($this->input->post("OtherName")),
                'Gender'                => $this->input->post("Gender"),
                'DateOfBirth'           => $this->input->post("DateOfBirth"),
                'Post'                   => $this->input->post("Post"),
                'UserName'             => $this->input->post("UserName"),
                'UserGroup'             => $this->input->post("UserGroup"),
                'Title'             => $this->input->post("Title"),
                'Address_Street'        => $this->input->post("Address_Street"),
                'Address_Village'       => $this->input->post("Address_Village"),
                'Address_DSDivision'       => $this->input->post("Address_DSDivision"),
                'Address_District'       => $this->input->post("Address_District"),
		'Active'       => $this->input->post("Active")
            );
           
            $status = false;
			
            if ($id > 0) {
            	
                $status = $this->mpersistent->update($frm, $form["OBJID"], $id, $sve_data);
                
                $this->session->set_flashdata(
                    'msg', 'REC: ' . ucfirst(strtolower($this->input->post("UserName"))) . ' Updated'
                );
				if ( $status){
					header("Status: 200");
					if (isset($_POST["CONTINUE"])){
						header("Location: ".site_url($_POST["CONTINUE"])); 
						return;
					}
					else{
						header("Location: ".site_url($form["NEXT"].'/'.$status));
						return;
					}
				}
            } else {
				$sve_data['Password']= md5($this->input->post("Password"));
                                $r = $this->muser->create_user($sve_data);
				if ($r){
                                    
                                    if($this->input->post("UserGroup")=='Doctor' || $this->input->post("UserGroup")=='Programmer'){
                                    $this->load->model('mdrug_stock');
                                    $data['insert_drug'] = $this->mdrug_stock->insert_drug_list($r);
                                    }
                                    
					$this->session->set_flashdata(
						'msg', 'REC: ' . ucfirst(strtolower($this->input->post("UserName"))). ' created'
					);
					header("Status: 200");
					if (isset($_POST["CONTINUE"])){
						header("Location: ".site_url($_POST["CONTINUE"])); 
						return;
					}
					else{
						header("Location: ".site_url($form["NEXT"].'/'.$status));
						return;
					}

				}
				else{
					$this->form_validation->set_message('UserName','User exist');
					$this->load->vars($data);
					echo Modules::run('form/create', 'user');
					return;
				}
            }
            echo "ERROR in saving";
        }
    }
    
    public function save_changepwd(){
                  	
        $frm = 'user_changepwd';
        if (!file_exists('application/forms/' . $frm . '.php')) {
            die("Form " . $frm . "  not found");
        }
        include 'application/forms/' . $frm . '.php';
        $data["form"] = $form;

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->load->model("muser");
	$id = $this->input->post($form["OBJID"]);
	$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
          for ($i = 0; $i < count($form["FLD"]); ++$i) {
            $this->form_validation->set_rules(
                $form["FLD"][$i]["name"], '"' . $form["FLD"][$i]["label"] . '"', $form["FLD"][$i]["rules"]
            );
        }

		if ($id>0){
			$this->form_validation->set_rules("Passwrd", "Passwrd", "required|matches[Password_check]");
			$this->form_validation->set_rules("Password_check", "Password confirmation", "required");
                         if ($this->form_validation->run() == FALSE) {
                     header("Location: ".site_url ('form/edit/user_changepwd/'.$id.' ')); 
            
        } else {
                $sve_data['Password']= md5($this->input->post("Passwrd"));                 
                $status = $this->mpersistent->update("user", $form["OBJID"], $id, $sve_data);
                
                $this->session->set_flashdata(
                    'msg', 'Password Updated'
                );
				if ( $status){
					header("Status: 200");
					if (isset($_POST["CONTINUE"])){
						header("Location: ".site_url($_POST["CONTINUE"])); 
						return;
					}
					else{
						header("Location: ".site_url());
						return;
					}
				}
        }
        }

        
    }

    public function visits($vdate=null){
		if ($vdate==null){
			$vdate= date("Y-m-d");
			$data["vdate"] = $vdate;
		}
		else{
			$data["vdate"] = $vdate;
		}
		$this->load->model('muser');
		$data["visit_list"] = $this->muser->get_opd_visit_list($this->session->userdata('UID'),$vdate);
		$this->load->vars($data);
		$this->load->view("visits_view");
	}
    
    public function get_unique_id($dob)
    {
        $yyyy = substr($dob, 0, 4);
        $mm = substr($dob, 5, 2);
        $dd = substr($dob, 8, 2);
        //echo $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,14);
        //echo $yyyy.$mm.$dd.time();
        //echo $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,8);
        return
            $yyyy . $mm . $dd . substr(number_format(str_replace(".", "", microtime(true) * rand()), 0, '', ''), 0, 8);
    }
    
// PP configuration
    // Change status to production in PP Mode if Admin or Programmer Usergroup   
   public function start_production(){ 
   		$this->load->model('muser');
		$this->muser->start_production();
   }
    
    
}

//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */