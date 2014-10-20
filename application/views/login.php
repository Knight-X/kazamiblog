<?php include("_site_header.php"); ?>
<div class="container">
    <?php include("_content_nav.php") ?>
    <div class="content">
      <form action="<?=site_url("user/logining")?>" method="post">
        <?php if(isset($errorMessage)){ ?>
	<div class="alert alert-error"><?=$errorMessage ?></div>
        <?php } ?>
        <table>
          <tr>
	    <td>Account</td>
            <?php if(isset($account)){ ?>
	      <td><input type="text" name="account"
		  value="<?=htmlspecialchars($account)?>" /></td>
              <?php }else{ ?>
                <td><input type="text" name="account" /></td>
              <?php } ?>
          </tr>
	  <tr>
	    <td>Password</td>
	    <td><input type="password" name="password" /></td>
          </tr>
	  <tr>
	    <td colspan="2">
	      <a href="<?=site_url("/")?>">Cancel</a>
              <input type="submit" class="btn" value="submit" />
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>

