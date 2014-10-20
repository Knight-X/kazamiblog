<!-- Content Header -->
<div class="content-header">
   <div class="navbar navbar-inverse">
     <div class="navbar-inner">
       <a class="brand" href="<?=site_url("/")?>">The Articles</a>
       <ul class="nav">
         <li class="active"><a href="#">Home</a></li>
         <li><a href="#">My Articles</a></li>
       </ul>
       <!-- login status -->
       <?php
       if(isset($_SESSION["user"]) && $_SESSION["user"] != null){ ?>
        <ul class="nav pull-right">
          <li><a href="#">Hi<?=$_SESSION["user"]->Account?></a></li>
          <li class="divider-vertical"></li>
          <li><a href="<?=site_url("user/logout")?>">LogOut</a></li>
          <li><a href="<?=site_url("article/post")?>">Post</a></li>
        </ul>
     <?php }else{ ?>
	<ul class="nav pull-right">
          <li><a href="<?=site_url("user/login")?>">Log In</a></li>
          <li class="divider-vertical"></li>
          <li><a href="<?=site_url("user/register")?>">Register</a></li>
        </ul>
     <?php } ?>
      </div>
    </div>
</div>
