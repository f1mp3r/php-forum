<?php

namespace Admin\Controllers;

class Admin_Controller extends \Controllers\Base_Controller
{
	public function __construct() {
		if ($this->user()->is_logged_in()) {
			$userinfo = $this->user()->get_logged_user(true);
			if ($userinfo['is_admin'] != 1) {
				$this->renderView('front/error', ['title' => '404', 'message' => '404 page not found']);
			}
		} else {
			$this->renderView('front/error', ['title' => '404', 'message' => '404 page not found']);
		}
		$this->layout = 'admin';
		$this->onInit();
	}
}