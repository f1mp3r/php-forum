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
<?php if (count($questions)): ?>
<h3>Questions</h3>
<div class="list-group">
	<?php foreach ($questions as $question): ?>
	<a href="questions/view/<?php echo $question['id']; ?>/<?php echo $question['slug']; ?>" class="list-group-item">
		<?php echo $question['title']; ?>
		<span class="small text-muted">added <?php echo time_ago(strtotime($question['date_created'])); ?> ago</span>
		<span class="badge"><?php echo $question['views']; ?> views</span>
	</a>
	<?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-info">
	No added questions :/
</div>
<?php endif; ?>