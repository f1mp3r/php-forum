<?php
namespace Admin\Controllers;

class Categories_Controller extends Admin_Controller
{
	public function onInit() {
		$this->load_models(['categories']);
	}

	public function all() {
		$data = [];
		$categories = $this->categories->find(['where' => ['parent_id', '0']]);
		foreach ($categories as &$category) {
			$category['children'] = $this->get_children($category['id']);
		}
		$data['categories'] = $categories;
		$data['title'] = 'Categories administration';

		$this->renderView('admin/categories/list', $data);
	}

	public function create() {
		
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