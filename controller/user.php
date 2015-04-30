<?php

namespace Controllers;

class User_Controller extends Base_Controller
{
	public function signin() {
		$data = [];
		$data['title'] = 'Sign up or sign in';
		$this->renderView('front/signin', $data);
	}
}