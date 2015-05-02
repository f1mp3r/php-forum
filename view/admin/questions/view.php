<h3 class="text-center">Question :: <i><?php echo $question['title']; ?></i></h3>
<a href="questions/delete/<?php echo $question['id']; ?>" class="btn btn-danger delete-confirm">Delete question</a>
<hr />
<p>Added by: <?php echo $user['username']; ?> on <?php echo date('d.m.Y H:i', strtotime($question['date_created'])); ?></p>
<form method="post" action="questions/edit/<?php echo $question['id']; ?>" id="prefix">
	<input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
	<p>Title: <input type="text" name="title" value="<?php echo htmlspecialchars_decode(stripslashes($question['title'])); ?>" /></p>
	<p>Category:
		<select name="category_id" id="board">
			<?php
				foreach ($categories as $category){
					echo '<option value="' . $category['id'] . '"' . (($question['category_id'] == $category['id']) ? ' selected="selected"' : null) . '>' . $category['name'] . '</option>';
				}
			?>
		</select>
	</p>
	<p>Tags: <div id="tags"></div><input type="hidden" name="tags" value="<?php echo $tags; ?>" /></p>
	<p>
		Text: <br />
		<textarea name="text" style="height: 300px" class="form-control"><?php echo htmlspecialchars_decode(stripslashes($question['text'])); ?></textarea>
	</p>
	<hr />
	<button class="btn btn-primary center-block" type="submit" name="edit">Edit question</button>
</form>
<h3 class="text-center">Answers</h3>
<?php
	if (count($answers)) {
		foreach ($answers as $answer) {
?>
	<div class="well">
		On <?php echo date('d.m.Y H:i', strtotime($answer['date_created'])); ?> <?php echo ($answer['user_id'] == 0) ? $answer['author_name'] . ' (' . $answer['author_email'] . ')' : $this->users->get($answer['user_id'])['username']; ?> said:<br />
		<?php echo nl2br(stripslashes($answer['text'])); ?>
		<hr />
		<a href="answers/delete/<?php echo $answer['id']; ?>" class="btn btn-danger btn-xs delete-confirm">Delete</a>
	</div>
<?php
		}
	} else {
		echo '<div class="alert alert-info">No answers added.'; 
	}
?>