<?php

namespace Controllers;

class Home_Controller extends Base_Controller
{
	public function index() {
		$this->title = 'Test';
		$this->renderView('testview');
	}
}