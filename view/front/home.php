<h1 class="text-center">Boards</h1>
<?php if (count($categories)): ?>
<div class="list-group">
	<?php foreach ($categories as $category): ?>
	<a href="boards/view/<?php echo $category['slug']; ?>" class="list-group-item">
		<?php echo $category['name']; ?>
	</a>
	<?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-info">
	No added categories.
</div>
<?php endif; ?>
<?php if (count($questions)): ?>
<hr />
<h2>10 newest questions <a href="questions/create" class="btn btn-success pull-right">New question</a></h2>
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