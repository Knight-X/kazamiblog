<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller{

  public function author($author = null, $offset = 0)
  {
    if ($author == null){
      show_404("Author not found");
      return true;
    }

    $this->load->model("UserModel");
    $this->load->model("ArticleModel");

    $user = $this->UserModel->getUserByAccount($author);
    if ($user == null){
      show_404("Author not found");
    }

    $pageSize = 20;

    $this->load->library('pagination');
    $config['uri_segment'] = 4;

    $config['base_url'] = site_url('/article/author/'.$author.'/');

    $config['total_rows'] = $this->ArticleModel->countArticlesByUserID($user->UserID);

    $config['per_page'] = $pageSize;

    $this->pagination->initialize($config);

    $results = $this->ArticleModel->getArticlesByUserID($user->UserID, $offset, $pageSize);

    $this->load->view('article_author',
      array("pageTitle" => "Post System -" . $user->Account . " Article List",
            "results" => $results,
            "user" => $user,
            "pageLists" => $this->pagination->create_links()
      ));
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

  public function view($articleID = null){
    session_start();
    if ($articleID == null){
      show_404("Article not found!");
      return true;
    }

    $this->load->model("ArticleModel");

    $article = $this->ArticleModel->get($articleID);

    if ($article == null){
      show_404("Article not found!");
      return true;
    }

    $this->load->view('article_view', array(
     "pageTitle" => "Post System - Artilce [". $article->Title."] ",
     "article" => $article
    ));
  }
  public function edit()
  {
    $this->load->view('article_edit');
  }
 
  public function gg()
  {
    $this->load->view('info');
  }

  public function compiler()
  {
    $this->load->view('compiler');
  }

  public function myscanner()
  {
    $this->load->view('scanner2');
  }

  public function myscanner2()
  {
    $this->load->view('myscanner');
  }
}
