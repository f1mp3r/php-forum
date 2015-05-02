<h1 class="text-center"><?php echo $title; ?></h1>
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
	No questions matching this criteria
</div>
<?php endif; ?>