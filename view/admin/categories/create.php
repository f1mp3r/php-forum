<h3 class="text-center">Categories - <?php echo $title; ?></h3>
<form method="post" action="categories/<?php echo (isset($edit)) ? 'edit/' . $category['id'] : 'create' ?>" class="form-inline">
	<input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
	<input type="text" class="form-control" name="name" placeholder="Category name" value="<?php echo (isset($category)) ? $category['name'] : ''; ?>" />
	<select name="parent_id" class="form-control">
		<option value="0">Choose a parent category</option>
		<?php foreach ($categories as $cat): ?>
			<option value="<?php echo $cat['id']; ?>"<?php if (isset($edit)) { echo $cat['id'] == $category['parent_id'] ? ' selected="selected"' : null; } ?>><?php echo $cat['name']; ?></option>
		<?php endforeach; ?>
	</select>
	<button class="btn btn-success" type="submit" name="submit"><?php echo isset($edit) ? 'Update' : 'Create'; ?></button>
</form>