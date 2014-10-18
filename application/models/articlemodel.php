<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ArticleModel extends CI_Model{
    function __construct()
    {
      parent::__construct();
    }


    function insert($author, $title, $content){
      $this->db->insert("article",
        array("Author" => $author,
              "Title" => $title,
              "Content" => $content,
              "Views" => 0
        ));
      return $this->db->insert_id();
     }
  }
