<?php
   
  class Upload extends CI_Controller{

    function __construct()
    {
      parent::__construct();
      $this->load->helper(array('form', 'url'));
    }

    function index()
    {
      $this->load->view('upload_form', array('error' => ''));

    }


    function do_upload()
    {
      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = '*';
      $config['max_size'] = '10000000';

      $this->load->library('upload', $config);


      if (!$this->upload->do_upload()){
        $error = array('error' => $this->upload->display_errors());

        $this->load->view('upload_form', $error);
      }else{
        $data = array('upload_data' => $this->upload->data());
 
        $this->load->view('upload_success', $data);
      }


      $dir = new DirectoryIterator('/usr/share/nginx/html/codeigniter/uploads');
      $x = 0;
      foreach ($dir as $file){
        $x += ($file->isFile()) ? 1 : 0;
      }

      if ($x >= 4){
        echo exec("/home/kazami/bin/ffmpeg -f concat -i /usr/share/nginx/html/codeigniter/uploads/list.txt -c copy /usr/share/nginx/html/codeigniter/uploads/demo2.mp4", $output);
      }

    }
}
?>
