<?php
namespace Admin\Controllers;

class Categories_Controller extends Admin_Controller
{
	public function onInit() {
		$this->load_models(['categories']);
		$this->thisPage = 'categories';
	}

	public function all() {
		$data = [];
		$categories = $this->categories->find(['where' => ['parent_id', '0']]);
		foreach ($categories as &$category) {
			$category['children'] = $this->get_children($category['id']);
		}
		$data['categories'] = $categories;
		$data['title'] = 'Categories administration';
		$data['thisPage'] = $this->thisPage;

		$this->renderView('admin/categories/list', $data);
	}

	public function create() {

		$data = [];
		$data['thisPage'] = $this->thisPage;

		if (isset($_POST['submit'])) {
			// Anti csrf
			try
			{
				\Lib\NoCSRF::check('csrf_token', $_POST, true, 60 * 10, false);
			}
			catch (\Exception $e)
			{
				$this->renderView('front/error', ['message' => 'Your session has expired.', 'title' => 'Error']);
				return;
			}
			$slugify = new \Lib\Slugify();
			$errors = [];

			$name = $_POST['name'];
			$slug = $slugify->slugify($name);
			$parent_id = (int) $_POST['parent_id'];

			if ($this->categories->get($slug, 'slug') != null || $this->categories->get($name, 'name') != null) {
				$errors[] = 'The category name is taken.';
			}

			if ($parent_id < 0 || ($this->categories->get($parent_id) == null && $parent_id > 0)) {
				$errors[] = 'Invalid value for parent.';
			}

			if (count(is_valid($name, null, 3, 50))) {
				$errors[] = implode('<br />', is_valid($name, null, 3, 50, 'The name'));
			}

			if (count($errors)) {
				$this->renderView('front/error', ['message' => 'Error', 'message' => 'The following errors occured', 'errors' => $errors]);
			} else {
				if ($this->categories->create(['name' => $name, 'slug' => $slug, 'parent_id' => $parent_id]) == 1) {
					$this->redirect('admin/categories', 'all');
				} else {
					$this->renderView('front/error', ['message' => 'Error', 'message' => 'Couldn\'t create the entry: ' . $this->categories->geterror()]);
				}
			}

			return;
		}

		$data['token'] = \Lib\NoCSRF::generate('csrf_token');
		$data['categories'] = $this->categories->find();
		$data['title'] = 'Create a category';
		
		$this->renderView('admin/categories/create', $data);
	}

	public function edit($id) {
		$data = [];
		$data['thisPage'] = $this->thisPage;
		$data['edit'] = true;
		$category = $this->categories->get($id);
		if ($category == null) {
			$this->renderView('front/error', ['message' => 'Category with this id does not exist.']);
			return;
		}

		if (isset($_POST['submit'])) {
			// Anti csrf
			try
			{
				\Lib\NoCSRF::check('csrf_token', $_POST, true, 60 * 10, false);
			}
			catch (\Exception $e)
			{
				$this->renderView('front/error', ['message' => 'Your session has expired.', 'title' => 'Error']);
				return;
			}
			$slugify = new \Lib\Slugify();
			$errors = [];

			$name = $_POST['name'];
			$slug = $slugify->slugify($name);
			$parent_id = (int) $_POST['parent_id'];

			if ($this->categories->get($slug, 'slug', ['where' => ['id', '!=', $id]]) != null) {
				$errors[] = 'The category name is taken.';
			}

			if ($parent_id < 0 || ($this->categories->get($parent_id) == null && $parent_id > 0) || $parent_id == $id) {
				$errors[] = 'Invalid value for parent.';
			}

			if (count(is_valid($name, null, 3, 50))) {
				$errors[] = implode('<br />', is_valid($name, null, 3, 50, 'The name'));
			}

			if (count($errors)) {
				$this->renderView('front/error', ['message' => 'Error', 'message' => 'The following errors occured', 'errors' => $errors]);
			} else {
				if ($this->categories->update($id, ['name' => $name, 'slug' => $slug, 'parent_id' => $parent_id]) == 1) {
					$this->redirect('admin/categories', 'all');
				} else {
					$this->renderView('front/error', ['message' => 'Error', 'message' => 'Couldn\'t update the entry: ' . $this->categories->geterror()]);
				}
			}

			return;
		}

		$data['token'] = \Lib\NoCSRF::generate('csrf_token');
		$data['category'] = $category;
		$data['categories'] = $this->categories->find(['where' => ['id', '!=', $id]]);
		$data['title'] = 'Edit ' . $category['name'];
		
		$this->renderView('admin/categories/create', $data);
	}

	public function delete($id) {
		$category = $this->categories->get($id);
		if ($category == null) {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'No such category exists']);
			return;
		}
		if ($this->categories->delete($id)) {
			$this->redirect('admin/categories', 'all');
		} else {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'Couldn\'t delete the category: ' . $this->categories->geterror()]);
		}
	}

	private function get_children($parent_id = 0) {
		$children = [];
		if ($parent_id == 0) {
			return [];
		}

		$has_children = $this->categories->count(['where' => ['parent_id', $parent_id]]);
		if ($has_children) {
			$subcats = $this->categories->find(['where' => ['parent_id', $parent_id]]);
			foreach ($subcats as &$subcategory) {
				$subcategory['children'] = $this->get_children($subcategory['id']);
			}
			return $subcats;
		}
		return [];
	}
}