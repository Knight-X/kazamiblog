<?php include("_site_header.php"); ?>
<div class="container post">
  <?php include("_content_nav.php"); ?>
  <!-- content-->
  <div class="content">
    <form action="<?=site_url("article/posting")?>" method="post">
      <?php if(isset($errorMessage)){ ?>
      <div class="alert alert-error"><?=$errorMessage?></div>
      <?php } ?>
      <table>
	  <tr>
	    <td>Title</td>
            <?php if (isset($title)){ ?>
	      <td><input type="text" name="title" value="<?=htmlspecialchars($title)?>" /></td>
            <?php }else{ ?>
              <td><input type="text" name="title" /></td>
            <?php } ?>
          </tr>
          <tr>
            <td>CONTENT</td>
            <td><textarea name="content" rows="10" cols="60"><?php
              if (isset($content)){
                echo $content;
              }
            ?></textarea></td>
          </tr>
          <tr>
            <td colspan="2">
              <a class="btn" href="<?=site_url("/")?>">Cancel</a>
              <input type="submit" class="btn" value="Submit" />
            </td>
          </tr>
        </table>
      </form>
      <form action="<?=site_url("article/upload")?>" method="post" enctype="multipart/form-data">
        <?php echo "Please CHoose a file:"?> <input type="file" name="uploadFile"><br>
        <input type="submit" value="Upload File">
      </form>
    </div>
  </div>
<?php include("_site_footer.php"); ?>
