<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php
    if (isset($pageTitle)){
	echo $pageTitle;
    }else{
	echo "System";
    }
    ?></title>
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap-responsive.css")?>">
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.min.css")?>">
  </head>
  <body data-target=".bs-docs-sidebar" data-spy="scroll" data-twttr-rendered="true">
