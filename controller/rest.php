<?php
namespace Controllers;
class Rest_Controller extends Base_Controller
{
	public function __construct() {
		$this->load_models(['tags']);
	}
	public function tags($field = null) {
		$filter = (in_array($field, ['tag', 'id', 'slug'])) ? $field : 'tag';
		$tags = $this->tags->find();
		$return = [];
		foreach ($tags as $tag) {
			$return[] = $tag[$filter];
		}
		header('Content-Type: application/json');
		echo json_encode($return);
	}
}