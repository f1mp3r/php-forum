<?php

namespace Admin\Controllers;
class Home_Controller extends Admin_Controller
{
	public function onInit() {
		$this->thisPage = 'home';
		$this->load_models(['tags', 'users', 'questions', 'answers']);
	}

	public function index(){
		$data = [];
		$data['thisPage'] = $this->thisPage;
		$data['tags'] = $this->tags->count();
		$data['questions'] = $this->questions->count();
		$data['answers'] = $this->answers->count();
		$data['users'] = $this->users->count();

		$this->renderView('admin/dashboard', $data);
	}
}