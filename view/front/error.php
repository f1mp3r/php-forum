<div class="alert alert-danger">
	<?php echo $message; ?>
	<?php if (isset($errors)): ?>
		<br />
		<?php foreach($errors as $error): ?>
			<?php echo $error . ((mb_substr($error, count($error) - 7) == '<br />') ? null : '<br />'); ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>