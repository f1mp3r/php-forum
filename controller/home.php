<?php

namespace Controllers;

class Home_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(array('categories'));
	}

	public function index() {
		$data = [];
		$data['title'] = 'Home';
		$data['categories'] = $this->categories->find();
		$this->renderView('testview', $data);
	}
}