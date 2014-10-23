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

    function get($articleID){
      $this->db->select("article.*,user.account");
      $this->db->from('article');
      $this->db->join('user', 'article.author = user.userID', 'left');
      $this->db->where(array("articleID" => $articleID));
      $query = $this->db->get();

      if ($query->num_rows() <= 0){
        return null;
      }

      return $query->row();
    }

    function countArticlesByUserID($userID){
      $this->db->select("count(articleID) as ArticleCount");

      $this->db->from('article');
      $this->db->where(array("author" => $userID));
      $query = $this->db->get();
      
      if ($query->num_rows() <= 0){
        return null;
      }


      return $query->row()->ArticleCount;
    }


    function getArticlesByUserID($userID, $offset = 0, &pageSize = 20){
      $this->db->select("article.*, user.Acount");
 
      $this->db->from('article');
      $this->db->join('user', 'article.author = user.userID', 'left');
      $this->db->where(array("author" => $userID));

      $this->db->limit($pageSize, $offset);

      $this->db->order_by("ArticleID", "desc");

      $query = $this->db->get();

      return $query->result();
    }
  }
