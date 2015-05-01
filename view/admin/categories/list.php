<h3 class="text-center">Categories</h3>
<a href="categories/create" class="btn btn-success">Create new category</a>
<hr />
<ul class="categories">
<?php display_categories($categories); ?>
</ul>
<?php
function display_categories($categories) {
	foreach ($categories as $category) {
		echo '<li>' . $category['name'] . ' - <span class="btn-group"><a href="categories/edit/' . $category['id'] . '" class="btn btn-xs btn-info">Edit</a><a href="categories/delete/' . $category['id'] . '" class="btn btn-xs btn-danger delete-confirm">Delete</a></span>';
		if (count($category['children'])) {
			echo '<ul>';
			display_categories($category['children']);
			echo '</ul>';
		}
	}
}
?>