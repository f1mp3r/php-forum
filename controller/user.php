<?php

namespace Controllers;

class User_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(['users', 'questions']);
	}
	public function signin() {
		$data = [];
		if (isset($_POST['signin'])) {
			// Anti csrf
			try
			{
				\Lib\NoCSRF::check('csrf_token', $_POST, true, 60 * 10, false);
			}
			catch (\Exception $e)
			{
				$this->renderView('front/error', ['message' => 'Your session has expired.', 'title' => 'Error']);
				return;
			}
			if ($this->user()->login($_POST['username'], $_POST['password'])) {
				$this->redirect('user', 'profile');
			} else {
				$data['wrongData'] = true;
			}
		}

		$data['token'] = \Lib\NoCSRF::generate('csrf_token');
		$data['title'] = 'Sign up or sign in';
		$this->renderView('front/signin', $data);
	}

	public function signup() {
		if (isset($_POST['signup'])) {
			// Anti csrf
			try
			{
				\Lib\NoCSRF::check('csrf_token', $_POST, true, 60 * 10, false);
			}
			catch (\Exception $e)
			{
				$this->renderView('front/error', ['message' => 'Your session has expired.', 'title' => 'Error']);
				return;
			}
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			$password_repeat = $_POST['password_repeat'];
			$email = $_POST['email'];

			$errors = [];
			if (count(is_valid($username, '/^[A-Za-z0-9_]+$/', 3, 50))) {
				$errors[] = implode('<br />', is_valid($username, '/^[A-Za-z0-9_]+$/', 3, 50, 'The username'));
			}
			if (count(is_valid($password, null, 8, 255))) {
				$errors[] = implode('<br />', is_valid($password, null, 8, 255, 'The password'));
			}
			if (count(is_valid($password_repeat, null, 8, 255))) {
				$errors[] = implode('<br />', is_valid($password_repeat, null, 8, 255, 'The password repeat'));
			}
			if ($password !== $password_repeat) {
				$errors[] = 'Passwords don\'t match';
			}
			if (count(is_valid($email, 'email', 8, 255))) {
				$errors[] = implode('<br />', is_valid($email, 'email', 8, 255, 'The email'));
			}
			if ($this->users->get($username, 'username') !== null) {
				$errors[] = 'The username is already taken.';
			}
			if ($this->users->get($email, 'email') !== null) {
				$errors[] = 'The email is already taken.';
			}

			if (count($errors)) {
				$this->renderView('front/signup', ['errors' => $errors, 'title' => 'Errors occurred']);
			} else {
				$password = md5($password . SALT);
				$is_created = $this->users->create([
					'username' => $username,
					'password' => $password,
					'email' => $email
				]);

				if ($is_created == 1) {
					$this->renderView('front/signup', ['title' => 'Registration successfull']);
				} else {
					$this->renderView('front/signup', ['errors' => ['Could not create a database entry.'], 'title' => 'Errors occurred']);
				}
			}
		} else {
			$this->redirect('user', 'profile');
		}
	}

	public function profile($username = null) {
		if ($username == null && !$this->user()->is_logged_in()) {
			$this->renderView('front/error', ['message' => '404 page not found.']);
			return;
		}
		$data = [];

		$user = $username ? $this->users->get($username, 'username') : $this->user()->get_logged_user(true);
		$questions = $this->questions->find([
			'where' => ['user_id', $user['id']],
			'orderby' => ['date_created' => 'DESC', 'id' => 'DESC'],
			'limit' => 10
		]);

		$data['questions'] = $questions;
		$data['profile'] = $user;
		$data['title'] = $user['username'] . '\'s profile';
		$this->renderView('front/profile', $data);
	}

	public function logout() {
		if ($this->user()->is_logged_in()) {
			$this->user()->logout();
		}
		$this->redirect('home');
	}
}