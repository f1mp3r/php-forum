<?php

namespace Admin\Controllers;
class Questions_Controller extends Admin_Controller
{
	public function onInit() {
		$this->load_models(['questions', 'users', 'tags', 'categories', 'questions_tags', 'answers']);
	}

	public function all($page = 1) {
		$data = [];
		$questionData = $this->questions->paginate(['orderby' => ['date_created' => 'DESC', 'id' => 'DESC']], 'questions/all/', $page, DEFAULT_ITEMS_PER_PAGE);
		$questions = $questionData['data'];
		$pagination = $questionData['pagination'];
		$data['questions'] = $questions;
		$data['pagination'] = $pagination;
		$data['title'] = 'Questions Administration';

		$this->renderView('admin/questions/list', $data);
	}

	public function view($id) {
		$question = $this->questions->get($id);
		$data = [];
		if ($question == null) {
			$this->renderView('front/error', ['message' => 'Question with this id does not exist.']);
			return;
		}

		$data['title'] = 'Question: ' . $question['title'];
		$data['question'] = $question;
		$data['user'] = $this->users->get($question['user_id']);
		$data['answers'] = $this->answers->find(['where' => ['question_id', $question['id']]]);
		$data['categories'] = $this->categories->find();
		$tags = $this->tags->find([
			'columns' => ['id', 'tag', 'slug'],
			'join' => [
				'table' => 'questions_tags',
				'key_from' => 'id',
				'key_to' => 'tag_id'
			],
			'where' => ['question_id', $id]
		]);
		$tagsAsNames = [];
		foreach ($tags as $tag) {
			$tagsAsNames[] = $tag['tag'];
		}
		$data['tags'] = implode(', ', $tagsAsNames);

		$this->renderView('admin/questions/view', $data);
	}

	public function delete($id) {
		$question = $this->questions->get($id);
		if ($question == null) {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'No such question exists']);
			return;
		}
		if ($this->questions->delete($id)) {
			$this->redirect('admin/questions', 'all');
		} else {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'Couldn\'t delete the question: ' . $this->questions->geterror()]);
		}
	}

	public function edit($id) {
		$question = $this->questions->get($id);
		if ($question == null) {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'No such question exists.']);
			return;
		}

		if (isset($_POST['edit'])) {
			$errors = [];
			$slugify = new \Lib\Slugify();
			$title = isset($_POST['title']) ? $_POST['title'] : $question['title'];
			$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : $question['category_id'];
			$tags = $_POST['tags'];
			$text = isset($_POST['text']) ? clean($_POST['text']) : $question['text'];
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
				$insertion = $this->questions->update($id, [
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
			$this->redirect('admin/questions', 'view', [$id]);
		}
	}
}