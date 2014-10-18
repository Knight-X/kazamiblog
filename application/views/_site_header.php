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
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.min.css")?>">
    <link rel="stylesheet" href="<?=base_url("/css/bootstrp.css")?>">
  </head>
  <body>
