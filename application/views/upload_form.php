<?php include("_site_header.php"); ?>
 <div class="container">
 <?php include("_content_nav.php"); ?>
 <!-- content -->
   <div class="content">

    <?php echo $error; ?>
    
    <?php echo form_open_multipart('upload/do_upload'); ?>

    <input type="file" name="userfile" size="20" />
    <br /><br />

    <input type="submit" value="upload" />

    </form>
   </div>
 </div>
  <?php include("_site_footer.php"); ?>

