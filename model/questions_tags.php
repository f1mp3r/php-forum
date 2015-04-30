<?php

namespace Models;

class Questions_tags_Model extends Master_Model
{
	public function __construct() {
		parent::__construct(['table' => 'questions_tags']);
	}
}