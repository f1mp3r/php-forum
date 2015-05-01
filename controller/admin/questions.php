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
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'Couldn\'t delete the question: ' . $this->answers->geterror()]);
		}
	}

	public function edit($id) {
		$question = $this->questions->get($id);
		if ($question == null) {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'No such question exists.']);
			return;
		}

		if (isset($_POST['edit'])) {
			
		} else {
			$this->redirect('admin/questions', 'view', [$id]);
		}
	}
}