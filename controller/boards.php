<?php

namespace Controllers;

class Boards_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(['questions', 'categories']);
	}

	public function index() {
		$this->redirect('home');
	}

	public function view($slug, $page = 1) {
		$slug = urldecode($slug);
		$data = [];
		$currentCategory = $this->categories->get($slug, 'slug', ['columns' => ['name', 'id']]);

		// if board does not exist return error;
		if (count($currentCategory) == 0 || $currentCategory == null) {
			$this->renderView('front/error', ['message' => 'No such board exists']);
			return;
		}
		$questionsData = $this->questions->paginate([
			'where' => ['category_id', $currentCategory['id']],
			'columns' => ['title', 'slug', 'views', 'id', 'date_created']
		], 'boards/view/' . $slug . '/', $page);

		$data['category'] = $currentCategory;
		$data['title'] = $currentCategory['name'];
		$data['questions'] = $questionsData['data'];
		$data['pagination'] = $questionsData['pagination'];
		$data['subcategories'] = $this->categories->find([
			'where' => [
				'parent_id',
				$currentCategory['id']
			]
		]);
		$this->renderView('front/viewforum', $data);
	}
}