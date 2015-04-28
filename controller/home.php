<?php

namespace Controllers;

class Home_Controller extends Master_Controller
{
	public function __construct() {
		parent::__construct(get_class(), null, '/view/home/');
	}

	public function index() {
		\View::forge('template', ['title' => 'test']);
		echo 'Az sme index';
	}
}