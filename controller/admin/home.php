<?php

namespace Admin\Controllers;
class Home_Controller extends Admin_Controller
{
	public function index(){
		$this->renderView('admin/dashboard', []);
	}
}