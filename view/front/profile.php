<h1 class="text-center"><?php echo $profile['username']; ?>'s profile</h1>
<div class="row">
	<div class="col-md-6">
		<h3 class="text-center">Info</h3>
		<ul class="list-group">
			<li class="list-group-item">Date of registration: <?php echo date('d.m.Y', strtotime($profile['date_created'])); ?></li>
			<li class="list-group-item">Email: <?php echo $profile['email']; ?></li>
		</ul>
	</div>
	<div class="col-md-6">
		<h3 class="text-center">10 newest questions asked</h3>
		<?php if (count($questions)): ?>
			<div class="list-group">
			<?php foreach ($questions as $question): ?>
				<a href="questions/view/<?php echo $question['id'] . '/' . $question['slug']; ?>" class="list-group-item"><?php echo $question['title']; ?></a>
			<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="alert alert-info">No asked questions.</div>
		<?php endif; ?>
	</div>
</div>