<?php include("_site_header.php"); ?>
  <div class="container">
  <?php include("_content_nav.php"); ?>
  <!-- content -->
  <div class="content">
    <h1><?=$user->Account ?></h1>
    
    <?php foreach($results as $article){ ?>
      <table class="table table-bordered">
        <tr>
          <td width="50">Title</td>
          <td>
            <a href="<?=site_url("article/view/".$article->ArticleID) ?>">
            <?=htmlspecialchars($article->Title) ?></a>
          </td>
        </tr>
        <tr>
          <td>Content</td>
          <td><?=htmlspecialchars($article->Content) ?></td>
        </tr>
      </table>
      <?php } ?>

      <p>
        <?=$pageLists?>
      </p>

    </div>
  </div>
  <?php include("_site_footer.php"); ?>




























