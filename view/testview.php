<h1 class="text-center">PHP forum</h1>
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
	No added categories :/
</div>
<?php endif; ?>