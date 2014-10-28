<!-- Content Header -->
<div class="nav">
   <div class="container">
       <a class="brand" href="<?=site_url("/")?>">The Articles</a>
       <ul class="pull-left">
         <li class="active"><a href="<?=site_url("/")?>">Home</a></li>
         <?php if (isset($_SESSION["user"]) && $_SESSION["user"] != null) { ?>
         <li><a href="<?=site_url("article/author/".$_SESSION["user"]->Account) ?>">My Articles</a></li>
         <?php } ?>
       </ul>
       <!-- login status -->
       <ul class="pull-right">
       <?php
       if(isset($_SESSION["user"]) && $_SESSION["user"] != null){ ?>
          <li><a href="#">Hi<?=$_SESSION["user"]->Account?></a></li>
          <li><a href="<?=site_url("user/logout")?>">LogOut</a></li>
          <li><a href="<?=site_url("article/post")?>">Post</a></li>
     <?php }else{ ?>
          <li><a href="<?=site_url("user/login")?>">Log In</a></li>
          <li><a href="<?=site_url("user/register")?>">Register</a></li>
        </ul>
     <?php } ?>
    </div>
  </div>


  <div class="jumbotron">
    <div class="container">
      <h1>Kazami</h1>
    </div>
  </div>
