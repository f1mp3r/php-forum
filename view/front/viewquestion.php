<h1 class="text-center"><?php echo $question['title']; ?></h1>
<div class="question">
	<div class="row">
		<div class="col-md-3">
			<ul class="info">
				<li>Author: <a href="user/profile/<?php echo $author['username']; ?>"><?php echo $author['username']; ?></a></li>
				<li>
					<span title="<?php echo date('d.m.Y H:i', strtotime($question['date_created'])); ?>">
						Added <?php echo time_ago(strtotime($question['date_created'])); ?> ago
					</span>
				</li>
				<li>Views: <?php echo $question['views'] + 1; ?></li>
				<li>Answers: <?php echo count($answers); ?></li>
				<li><a href="boards/view/<?php echo $category['slug']; ?>"><?php echo $category['name']; ?></a></li>
			</ul>
			<a href="questions/answer/<?php echo $question['id']; ?>" class="btn btn-success btn-block">Answer</a>
		</div>
		<div class="col-md-9">
			<div class="text">
				<?php echo nl2br(stripslashes($question['text'])); ?>
			</div>
		</div>
	</div>
	<hr />
	Tags:
	<?php if (isset($tags)): ?>
		<?php if (count($tags)): ?>
			<?php foreach ($tags as $tag): ?>
				<a href="search/bytag/<?php echo $tag['slug']; ?>" class="label label-primary"><?php echo $tag['name']; ?></a>
			<?php endforeach; ?>
		<?php else: ?>
			no tags added
		<?php endif; ?>
	<?php endif; ?>
</div>
<hr />
<?php if (count($answers)): ?>
<div class="list-group">
	<?php foreach ($answers as $answer): ?>
		<div class="answer">
			Author:
			<?php echo ($answer['user_id'] == 0) ? 'Anonymous' : '<a href="user/view/' . $answer['user_id'] . '">' . $this->user->get($answer['user_id'])['username'] . '</a>'; ?>
			| <span title="<?php echo date('d.m.Y H:i', strtotime($answer['date_created'])); ?>">Added <?php echo time_ago(strtotime($answer['date_created'])); ?> ago
					</span><br />
			<div class="text">
				<?php echo nl2br(stripslashes($answer['text'])); ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-info">
	No added answers :/
</div>
<?php endif; ?>