<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller{

  public function register()
    {
      $this->load->view('register');
    }

  public function registering()
  {
    $account = $this->input->post("account");
   // var_dump($this->input->post("account"));
    $password = $this->input->post("password");
   // var_dump($this->input->post("password"));

    if (trim($password) == "" || trim($account) == ""){
	$this->load->view('register', Array("errorMessage" => "Account or Password shoundn't be empty, please check!",
                                            "account" => $account));
        return false;
    }

    $this->load->model("UserModel");
    if ($this->UserModel->checkUserExist(trim($account))){
	$this->load->view('register', Array(
		"errorMessage" => "This account is already in used.",
		"account" => $account
	));
	return false;
    }
    $q = $this->UserModel->insert(trim($account), trim($password));
    
    if ($q === true){
      $this->load->view('register_success', Array("account" => $account));
    }else{
      $this->load->view('register_failed', Array("account" => $account));
    }
  }

  public function login()
  {
    session_start();
    if (isset($_SESSION["user"]) && $_SESSION["user"] != null){
      redirect(site_url("/"));
      return true;
    }
    $this->load->view(
      "login",
      Array("pageTitle" => "Post System - Member Login"));
  }

  public function logining()
  {
    session_start();
    if(isset($_SESSION["user"]) && $_SESSION["user"] != null){
      redirect(site_url("/"));
      return true;
    }
    
    $account = trim($this->input->post("account"));
    $password = trim($this->input->post("password"));

    $this->load->model("UserModel");
    $user = $this->UserModel->getUser($account, $password);

    if($user == null){
      $this->load->view(
        "login",
        Array("pageTitle" => "Post System - Member Login",
              "account" => $account,
              "errorMessage" => "account or password wrong"
        )
      );
      return true;
    }

    $_SESSION["user"] = $user;
    redirect(site_url("/"));
  }


  public function logout()
  {
    session_start();
    session_destroy();
    redirect(site_url("/user/login"));
  }
}  
