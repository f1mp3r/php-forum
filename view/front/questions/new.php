<h1 class="text-center">Create a question</h1>
<form method="POST">
	<div class="row">
		<div class="col-md-2 text-right">
			<p>Title</p>
		</div>
		<div class="col-md-10"><input type="text" name="title" placeholder="Question title" class="form-control" /></div>
	</div>
	<div class="row">
		<div class="col-md-2 text-right">
			<p>Board</p>
		</div>
		<div class="col-md-10">
			<select name="category_id" id="board">
				<option value="0">Choose a board</option>
				<?php foreach ($boards as $board): ?>
				<option value="<?php echo$board['id']; ?>"><?php echo$board['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2 text-right">
			<p>Tags</p>
		</div>
		<div class="col-md-10"><input type="text" name="tags" id="tags" placeholder="Question tags" class="form-control" /></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<textarea name="text" placeholder="Question text.." class="form-control" style="height:300px;"></textarea>
		</div>
	</div>
	<div class="row" style="margin-top: 10px;">
		<div class="col-md-12">
			<button type="submit" name="post" class="btn btn-success btn-lg center-block">Post your question</button>
		</div>
	</div>
</form>