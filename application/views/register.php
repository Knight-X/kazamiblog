<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.min.css")?>">
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.css")?>">
  </head>

  <body>

<div class="container">
  <form action="<?=site_url("/user/registering")?>" method="post">
	<?php if (isset($errorMessage)){?>
        <div class="alert alert-error">
               <?=$errorMessage?>
        </div>
        <?php }?>
      <table class="table table-bordered">
         <tr>
           <td>
             Account
           </td>
           <td>
                <?php if (isset($account)){?>
                <input type="text" name="account" value="<?=htmlspecialchars($account)?>" />
                <?php }else{ ?>
                <input type="text" name="account" />
                <?php } ?>
           </td>
         </tr>
         <tr>
            <td>
             Password
           </td>
           <td>
             <input type="password" name="password" />
           </td>
        </tr>
       
        <tr>
           <td colspan="2">
           <input class="btn" type="submit" value="送出" />
           </td>
       </tr>
      </table>
  </form>
 </div>
  </body>
</html>


