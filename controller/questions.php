<?php

namespace Controllers;

class Questions_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(['questions', 'categories', 'answers', 'tags', 'users', 'questions_tags']);
	}

	public function view($id = 0, $slug = null, $page = 1) {
		$slug = urldecode($slug);
		$data = [];
		$question = $this->questions->get($id);

		// if board does not exist return error;
		if ($question == null) {
			$this->renderView('front/error', ['message' => 'No such question exists']);
			return;
		}
		$this->questions->update($question['id'], ['views' => $question['views'] + 1]);
		$category = $this->categories->get($question['category_id']);
		$author = $this->users->get($question['user_id']);
		$tags = $this->tags->find([
			'columns' => ['id', 'tag', 'slug'],
			'join' => [
				'table' => 'questions_tags',
				'key_from' => 'id',
				'key_to' => 'tag_id'
			],
			'where' => ['question_id', $id]
		]);

		$data['tags'] = $tags;
		$data['author'] = $author;
		$data['question'] = $question;
		$data['category'] = $category;
		$data['answers'] = $this->answers->find([
			'where' => [
				'question_id',
				$question['id']
			]
		]);

		$data['title'] = $question['title'];
		$this->renderView('front/questions/view', $data);
	}

	public function create($boardid = 0) {
		if (!$this->user()->is_logged_in()) {
			$this->renderView('front/error', ['message' => 'You must be logged in to post a question.']);
			return;
		}
		$data = [];

		if (isset($_POST['post'])) {
			$errors = [];
			$slugify = new \Lib\Slugify();
			$title = $_POST['title'];
			$category_id = $_POST['category_id'];
			$tags = $_POST['tags'];
			$text = clean($_POST['text']);
			$tag_ids = [];

			if (count(is_valid($title, null, 5, 255))) {
				$errors[] = implode('<br />', is_valid($title, null, 5, 255, 'The title'));
			} else {
				$slug = $slugify->slugify($title);
				$title = clean($title);
			}
			if (count(is_valid($text, null, 5))) {
				$errors[] = implode('<br />', is_valid($text, null, 5, 255, 'The text'));
			}
			if (count(is_valid($category_id, 'number', null, null))) {
				$errors[] = implode('<br />', is_valid($category_id, 'number', null, null, 'The category'));
			} else {
				$category = $this->categories->get($category_id);
				if ($category == null || $category_id == 0) {
					$errors[] = 'No such board exists';
				}
			}

			foreach (explode(',', $tags) as $tag) {
				$tag = trim($tag);
				if (empty($tag)) {
					continue;
				}
				$slugified = $slugify->slugify($tag);
				$check = $this->tags->get($slugified, 'slug');
				$tag_id = 0;
				if ($check != null) {
					$tag_id = $check['id'];
				} else {
					if (count(is_valid($tag, '/^[A-Za-z0-9_\p{Cyrillic}\d\s]+$/u', 2, 50))) {
						$errors[] = implode('<br />', is_valid($tag, '/^[A-Za-z0-9_\p{Cyrillic}\d\s]+$/u', 2, 50, 'The tag ' . clean($tag)));
					} else {
						if ($this->tags->create(['tag' => $tag,'slug' => $slugified]) == 1) {
							$tag_id = $this->tags->get($slugified, 'slug')['id'];
						} else {
							$errors[] = 'An error occured while trying to insert the tag ' . $tag;
						}
					}
				}
				if ($tag_id !== 0) {
					$tag_ids[] = $tag_id;
				}
			}

			if (count($errors)) {
				$this->renderView('front/error', ['message' => 'The following errors occured:', 'title' => 'Error', 'errors' => $errors]);
			} else {
				$insertion = $this->questions->create([
					'title' => $title,
					'slug' => $slug,
					'category_id' => $category_id,
					'text' => $text,
					'user_id' => $this->user()->get_logged_user()['user_id']
				]);
				unset($_POST['post']);
				unset($_POST['title']);
				unset($_POST['text']);
				unset($_POST['tags']);

				if ($insertion == 1) {
					$question = $this->questions->find(['orderby' => ['date_created' => 'DESC', 'id' => 'DESC'], 'limit' => 1])[0];

					foreach ($tag_ids as $tagid) {
						$insertedTag = $this->questions_tags->create(['tag_id' => $tagid, 'question_id' => $question['id']]);
						if ($insertedTag != 1) {
							$errors[] = 'Could not associate tag with question';
						}
					}
					if (count($errors)) {
						$this->renderView('front/error', ['message' => 'Error: ' . $this->questions->geterror(), 'title' => 'Error', 'errors' => $errors]);
					} else {
						$this->redirect('questions', 'view', [$question['id'], $question['slug']]);
						$this->renderView('front/success', ['message' => 'The question was submitted.', 'title' => 'Question created']);
					}
				} else {
					$this->renderView('front/error', ['message' => 'Could not insert the question: ' . $this->questions->geterror(), 'title' => 'Error']);
				}
			}
			return;
		} else {
			$data['title'] = 'New question';

			$boards = $this->categories->find();
			$data['boards'] = $boards;

			$this->load_asset([
				'bootstrap-select.min.css',
				'bootstrap-select.min.js',
				'cust/add_question.js'
				// 'bootstrap-tagsinput.css',
				// 'bootstrap3-typeahead.min.js',
				// 'typeahead.bundle.js',
				// 'bootstrap-tagsinput.js',
			]);
			$this->renderView('front/questions/new', $data);
		}
	}

	public function bytag($slug) {
		$tag = $this->tags->get($slug, 'slug');
		if ($tag == null) {
			$this->renderView('front/error', ['title' => 'No such tag', 'message' => 'Such tag does not exist']);
			return;
		}
		$data = [];

		$questions = $this->questions->find([
			'columns' => ['questions.*'],
			'join' => [
				[
					'key_from' => 'id',
					'key_to' => 'question_id',
					'table' => 'questions_tags'
				],
				[
					'key_from' => 'tag_id',
					'key_to' => 'id',
					'table' => 'tags',
					'table_from' => 'questions_tags'
				]
			],
			'where' => 'tags.slug = \'' . clean($slug) . "'"
		]);

		$data['questions'] = $questions;
		$data['title'] = 'Questions having the tag "' . $tag['tag'] . '"';
		$this->renderView('front/questions/search', $data);
	}

	public function answer($question_id) {
		$question = $this->questions->get($question_id);
		$data = [];
		if ($question == null) {
			$this->renderView('front/error', ['title' => 'No such question', 'message' => 'Such question does not exist']);
			return;
		}

		if (isset($_POST['answer'])) {
			$errors = [];
			$text = clean($_POST['text']);

			if (count(is_valid($text, null, 3, null))) {
				$errors[] = implode('<br />', is_valid($text, null, 3, null, 'The text'));
			}

			if (count($errors)) {
				$this->renderView('front/error', ['title' => 'Error', 'message' => 'Error:', 'errors' => $errors]);
			} else {
				if ($this->user()->is_logged_in()) {
					$user_id = $this->user()->get_logged_user()['user_id'];
					$answer = $this->answers->create([
						'text' => $text,
						'user_id' => $user_id,
						'question_id' => $question_id
					]);

					if ($answer == 1) {
						$this->redirect('questions', 'view', [$question_id, $question['slug']]);
					} else {
						$this->renderView('front/error', ['title' => 'Error', 'message' => 'Error: could not insert the answer: ' . $this->answers->geterror()]);
					}
				} else {
					if (!isset($_POST['name'])) {
						$errors[] = 'The name is required for guests';
					}
					if (!isset($_POST['email'])) {
						$errors[] = 'The email is required for guests';
					}

					if (count($errors)) {
						$this->renderView('front/error', ['title' => 'Error', 'message' => 'Error:', 'errors' => $errors]);
					} else {
						$name = clean($_POST['name']);
						$email = clean($_POST['email']);

						if (count(is_valid($name, '/^[A-Za-z0-9_\p{Cyrillic}\d\s]+$/u', 2, 50))) {
							$errors[] = implode('<br />', is_valid($name, '/^[A-Za-z0-9_\p{Cyrillic}\d\s]+$/u', 2, 50, 'The name'));
						}

						if (count(is_valid($email, 'email'))) {
							$errors[] = implode('<br />', is_valid($email, 'email', null, null, 'The email'));
						}

						if (count($errors)) {
							$this->renderView('front/error', ['title' => 'Error', 'message' => 'Error:', 'errors' => $errors]);
						} else {
							$answer = $this->answers->create([
								'text' => $text,
								'author_name' => $name,
								'author_email' => $email,
								'question_id' => $question_id
							]);

							if ($answer == 1) {
								$this->redirect('questions', 'view', [$question_id, $question['slug']]);
							} else {
								$this->renderView('front/error', ['title' => 'Error', 'message' => 'Error: could not insert the answer: ' . $this->answers->geterror()]);
							}
						}
					}
				}
			}
			return;
		}

		$this->renderView('front/questions/answer', $data);
	}
}