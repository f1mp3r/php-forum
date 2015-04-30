<?php if (!$user->is_logged_in()): ?>
<div class="row">
	<div class="col-md-4 col-md-offset-1">
		<?php if (isset($wrongData)): ?>
			<div class="alert alert-danger">
				Error: wrong username / password
			</div>
		<?php endif; ?>
		<h3 class="text-center">Sign in</h3>
		<form method="post">
			<p>
				<label for="signin-username" class="sr-only">Username</label>
				<input type="text" name="username" placeholder="Username" id="signin-username" class="form-control" required="required" />
			</p>
			<p>
				<label for="signin-password" class="sr-only">Password</label>
				<input type="password" name="password" placeholder="Password" id="signin-password" class="form-control" required="required" />
			</p>
			<button type="submit" name="signin" class="btn btn-success center-block">Sign in</button>
		</form>
	</div>
	<div class="col-md-4 col-md-offset-1">
		<h3 class="text-center">Sign up</h3>
		<form method="post" action="user/signup">
			<p>
				<label for="signin-username" class="sr-only">Username</label>
				<input type="text" name="username" placeholder="Username" id="signin-username" class="form-control" required="required" />
			</p>
			<p>
				<label for="signin-password" class="sr-only">Password</label>
				<input type="password" name="password" placeholder="Password" id="signin-password" class="form-control" required="required" />
			</p>
			<p>
				<label for="signin-reppassword" class="sr-only">Repeat password</label>
				<input type="password" name="password_repeat" placeholder="Repeat password" id="signin-reppassword" class="form-control" required="required" />
			</p>
			<p>
				<label for="signin-email" class="sr-only">Email</label>
				<input type="email" name="email" placeholder="E-mail" id="signin-email" class="form-control" required="required" />
			</p>
			<button type="submit" name="signup" class="btn btn-success center-block">Sign up</button>
		</form>
	</div>
</div>
<?php else: ?>
<div class="alert alert-warning">
	You are already logged in as <?php echo $user->get_logged_user()['username']; ?>
</div>
<?php endif; ?>