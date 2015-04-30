<?php

namespace Controllers;

class Questions_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(['questions', 'categories', 'answers', 'tags', 'users']);
	}

	public function view($id, $slug, $page = 1) {
		$slug = urldecode($slug);
		$data = [];
		$question = $this->questions->get($slug, 'slug');

		// if board does not exist return error;
		if ($question == null) {
			$this->renderView('front/error', ['message' => 'No such question exists']);
			return;
		}
		$this->questions->update($question['id'], ['views' => $question['views'] + 1]);
		$category = $this->categories->get($question['category_id']);
		$author = $this->users->get($question['user_id']);

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
		$this->renderView('front/viewquestion', $data);
	}
}