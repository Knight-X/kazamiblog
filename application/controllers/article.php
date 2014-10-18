<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller{

  public function author()
  {
    $this->load->view('article_author');
  }

  public function post()
  {
    session_start();
    if (!isset($_SESSION["user"])){
	redirect(site_url("/user/login"));
	return true;
    }	
    $this->load->view('article_post', array("pageTitle" => "Post Sytem- posting"));
  }

  public function posting(){
    session_start();
    if (!isset($_SESSION["user"])){
	redirect(site_url("/user/login"));
	return true; 
    }

    $title = trim($this->input->post("title"));
    $content = trim($this->input->post("content"));

    if ($title == "" || $content == ""){
        $this->load->view('article_post', array(
	    "pageTitle" => "Post System - Post",
	    "errorMessage" => "Title or Content shoun't be empty, please check!",
	    "title" => $title,
            "content" => $content
        ));
	return false;
    }

    $this->load->model("ArticleModel");
    $insertID = $this->ArticleModel->insert($_SESSION["user"]->UserID, $title, $content);
    redirect(site_url("article/postSuccess/".$insertID));
  }

  public function postSuccess($articleID){
    $this->load->view('article_success', array("pageTitle" => "POst System - post Sucess","articleID" => $articleID));
//    $this->load->view('article_post', array("pageTitle" => $articleID));

  }


  public function edit()
  {
    $this->load->view('article_edit');
  }
  
}
