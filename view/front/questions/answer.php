<h1 class="text-center">Answer a question</h1>
<form method="post">
	<div class="row">
		<?php if ($user->is_logged_in()): ?>
		<div class="col-md-12">
		<?php else: ?>
		<div class="col-md-4">
			<p>
				<label class="sr-only" for="name">Name:</label>
				<input type="text" name="name" placeholder="Your name" class="form-control" id="name" />
			</p>
			<p>
				<label class="sr-only" for="email">Email:</label>
				<input type="text" name="email" placeholder="Your e-mail" class="form-control" id="email" />
			</p>
		</div>
		<div class="col-md-8">
		<?php endif; ?>
			<textarea name="text" style="height:300px" class="form-control" placeholder="Answer text"></textarea>
		</div>
	</div>
	<button type="submit" name="answer" class="btn btn-success center-block btn-lg" style="margin-top: 10px;">Answer</button>
</form>