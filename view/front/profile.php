<h1 class="text-center"><?php echo $profile['username']; ?>'s profile</h1>
<div class="row">
	<div class="col-md-6">
		<h3 class="text-center">Info</h3>
		User since: <?php echo date('d.m.Y', strtotime($profile['date_created'])); ?><br />
		Email: <?php echo $profile['email']; ?>
	</div>
	<div class="col-md-6">
		<h3 class="text-center">10 newest questions asked</h3>
		<?php if (count($questions)): ?>
			<div class="list-group">
			<?php foreach ($questions as $question): ?>
				<a href="questions/view/<?php echo $question['id'] . '/' . $question['slug']; ?>" class="list-group-item"><?php echo $question['title']; ?></a>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>