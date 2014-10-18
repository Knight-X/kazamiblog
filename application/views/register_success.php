<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <title>Registraion Success</title>
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.css")?>">
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.min.css")?>">
  </head>
<body>
  <div class="container">
    <div class="alert alert-success">
	Congradulation (<?=$account?>), You have achieved registration.
        <a href="<?=site_url("user/login")?>">LogIn</a>
    </div>
  </div>

</body>
</html>	
