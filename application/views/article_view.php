<?php include("_site_header.php"); ?>
<div class="container article-view">
	<?php include("_content_nav.php"); ?>	
	<!-- content -->
	<div class="content">
		<table class="table table-bordered">
			<tr>
				<td>作者</td>
				<td><?=htmlspecialchars($_SESSION["user"]->Account)?></td>
			</tr>
			<tr>
				<td>標題</td>
				<td><?=htmlspecialchars($article->Title)?></td>
			</tr>
			<tr>
				<td> 內容 </td>
				<td><?=nl2br(htmlspecialchars($article->Content))?></td>
			</tr>
		</table>
	</div>
</div>
<?php include("_site_footer.php"); ?>

