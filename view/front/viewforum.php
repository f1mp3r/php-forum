<h1 class="text-center"><?php echo $category['name']; ?></h1>
<?php if (count($subcategories)): ?>
<h3>Sub-boards</h3>
<div class="list-group">
	<?php foreach ($subcategories as $subcategory): ?>
	<a href="boards/view/<?php echo $subcategory['slug']; ?>" class="list-group-item">
		<?php echo $subcategory['name']; ?>
	</a>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<hr />
<h2>Questions <a href="questions/create/<?php echo $category['id']; ?>" class="btn btn-success pull-right">New question</a></h2>
<?php if (count($questions)): ?>
<div class="list-group">
	<?php foreach ($questions as $question): ?>
	<a href="questions/view/<?php echo $question['id']; ?>/<?php echo $question['slug']; ?>" class="list-group-item">
		<?php echo $question['title']; ?>
		<span class="small text-muted">added <?php echo time_ago(strtotime($question['date_created'])); ?> ago</span>
		<span class="badge"><?php echo $question['views']; ?> views</span>
	</a>
	<?php endforeach; ?>
</div>
<?php if (isset($pagination)) { echo $pagination; } ?>
<?php else: ?>
<div class="alert alert-info">
	No added questions :/
</div>
<?php endif; ?>