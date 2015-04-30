<?php if (!$user->is_logged_in()): ?>
	<?php if (isset($errors)): ?>
		<div class="alert alert-danger">
			We found errors while trying to register you: <br />
			<?php foreach ($errors as $error): ?>
				- <?php echo $error; ?><br />
			<?php endforeach; ?>
			<a href="javascript:history.go(-1)" class="alert-link">Back to the registration form</a>
		</div>
	<?php else: ?>
		<div class="alert alert-success">
			Your registration was successfull. You can now sign in with your account.
		</div>
	<?php endif; ?>
<?php else: ?>
<div class="alert alert-warning">
	You are already logged in as <?php echo $user->get_logged_user()['username']; ?>
</div>
<?php endif; ?>