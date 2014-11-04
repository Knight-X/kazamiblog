<?php include("_site_header.php"); ?>
 <div class="container">
 <?php include("_content_nav.php"); ?>
 <!-- content -->
   <div class="content">

    <?php echo $error; ?>

      <form action="<?=site_url("upload/set_script"); ?>" method="post">
        <table>
          <tr>
             <td>First movie</td>
               <td><input type="text" name="first" /></td>
          </tr>
          <tr>
             <td>Second movie</td>
             <td><input type="text" name="second" /></td>
          </tr>
          <tr>
             <td>Third movie</td>
             <td><input type="text" name="third" /></td>
          </tr>
        </table>
       <input type="submit" value="setting" />
      </form>
    
      <div class="uploadform">
      <?php echo form_open_multipart('upload/do_upload'); ?>

      <input type="file" name="userfile" size="20" />
      <br /><br />

      <input type="submit" value="upload" />

      </form>
    </div>
   </div>
 </div>
  <?php include("_site_footer.php"); ?>

