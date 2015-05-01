<?php

namespace Admin\Controllers;
class Answers_Controller extends Admin_Controller
{
	public function onInit() {
		$this->load_models(['answers', 'questions']);
	}

	public function delete($id) {
		$answer = $this->answers->get($id);
		if ($answer == null) {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'No such answer exists']);
			return;
		}
		if ($this->answers->delete($id)) {
			$this->redirect('admin/questions', 'view', [$answer['question_id']]);
		} else {
			$this->renderView('front/error', ['title' => 'Error', 'message' => 'Couldn\'t delete the answer: ' . $this->answers->geterror()]);
		}
	}
}