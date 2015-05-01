<?php

namespace Admin\Controllers;
class Questions_Controller extends Admin_Controller
{
	public function onInit() {
		$this->load_models(['questions', 'users', 'tags', 'categories', 'questions_tags', 'answers']);
		$this->thisPage = 'questions';
	}

	public function all($page = 1) {
		$data = [];
		$data['thisPage'] = $this->thisPage;
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
		$data['thisPage'] = $this->thisPage;
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
		$data['token'] = \Lib\NoCSRF::generate('csrf_token');
		$this->load_asset([
			'bootstrap-select.min.css',
			'bootstrap-tags.css',
			'bootstrap-select.min.js',
			'bootstrap-tags.min.js',
			'cust/add_question.js'
		]);

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

			//get current tags
			$currentTags = $this->tags->find([
				'columns' => ['id', 'tag', 'slug'],
				'join' => [
					'table' => 'questions_tags',
					'key_from' => 'id',
					'key_to' => 'tag_id'
				],
				'where' => ['question_id', $id]
			]);
			$tagsAsNames = [];
			foreach ($currentTags as $tag) {
				$tagsAsNames[] = $tag['tag'];
			}
			$tagsAsString = implode(', ', $tagsAsNames);

			if ($tags != $tagsAsString) {
				foreach (explode(',', $tags) as $tag) {
					$tag = trim($tag);
					if (empty($tag)) {
						continue;
					}
					if (in_array($tag, $tagsAsNames)) {
						continue;
					}

					$slugified = $slugify->slugify($tag);
					$check = $this->tags->get($slugified, 'slug');

					if ($check != null) {
						$tag_id = $check['id'];
						if (count($this->questions_tags->find(['where' => [['tag_id', $tag_id], 'and', ['question_id', $id]]])) == 1) {
							// tag is already connected with the question
							continue;
						} else {
							$connect_tag = $this->questions_tags->create(['tag_id' => $tag_id, 'question_id' => $id]);
							if ($connect_tag != 1) {
								$errors[] = 'Could not associate tag with question';
							} else {
							}
						}
					} else {
						if (count(is_valid($tag, '/^[A-Za-z0-9_\p{Cyrillic}\d\s]+$/u', 2, 50))) {
							$errors[] = implode('<br />', is_valid($tag, '/^[A-Za-z0-9_\p{Cyrillic}\d\s]+$/u', 2, 50, 'The tag ' . clean($tag)));
						} else {
							if ($this->tags->create(['tag' => $tag,'slug' => $slugified]) == 1) {
								$tag_id = $this->tags->get($slugified, 'slug')['id'];
								$connect_tag = $this->questions_tags->create(['tag_id' => $tag_id, 'question_id' => $id]);
								if ($connect_tag != 1) {
									$errors[] = 'Could not associate tag with question';
								} else {
								}
							} else {
								$errors[] = 'An error occured while trying to insert the tag ' . $tag;
							}
						}
					}
				}
			}

			if (count($errors)) {
				$this->renderView('front/error', ['message' => 'The following errors occured:', 'title' => 'Error', 'errors' => $errors]);
			} else {
				$update = $this->questions->update($id, [
					'title' => $title,
					'slug' => $slug,
					'category_id' => $category_id,
					'text' => $text
				]);

				if ($update == 1 || $update == 0) {
					$newTags = array_map('trim', array_filter(explode(',', $tags)));
					echo $tags;
					$diff_tags = array_diff($tagsAsNames, $newTags);
					echo '<pre>';
					print_r($tagsAsNames);
					print_r($newTags);
					print_r($diff_tags);
					echo '</pre>';
					foreach ($diff_tags as $tag) {
						$slugged = $slugify->slugify($tag);
						$get_tag = $this->tags->get($tag, 'tag');
						$deletion = $this->questions_tags->delete(0, "`tag_id` = '" . $get_tag['id'] . "' AND `question_id` = '" . $id . "'");
						if ($deletion != 1) {
							$errors[] = 'Could\'t delete the tag (' . $tag . '): ' . $this->questions_tags->geterror();
						}
					}
					if (count($errors)) {
						$this->renderView('front/error', ['message' => 'Error', 'title' => 'Error', 'errors' => $errors]);
					} else {
						// $this->redirect('admin/questions', 'view', [$id]);
						$this->renderView('front/success', ['message' => 'Question successfully updated', 'title' => 'Good job']);
					}
				} else {
					$this->renderView('front/error', ['message' => 'Error:' . $this->questions->geterror(), 'title' => 'Error', 'errors' => $errors]);
				}
				return;
			} 
		} else {
			$this->redirect('admin/questions', 'view', [$id]);
		}
	}
}