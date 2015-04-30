<?php

namespace Controllers;

class Home_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(['categories', 'questions']);
	}

	public function index() {
		$data = [];
		$data['title'] = 'Home';
		$data['categories'] = $this->categories->find([
			'where' => ['parent_id', '=', '0'],
			'columns' => ['name', 'slug']
		]);

		$data['questions'] = $this->questions->find([
			'columns' => ['title', 'slug', 'id', 'views', 'date_created'],
			'orderby' => [
				'date_created' => 'DESC',
				'id' => 'DESC'
			],
			'limit' => 10
		]);
		
		$this->renderView('front/home', $data);
	}
}