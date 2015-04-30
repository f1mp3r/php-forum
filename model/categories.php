<?php

namespace Models;

class Categories_Model extends Master_Model
{
	protected $_has_many = [
		'subcategories' => [
			'model' => 'categories',
			'key_from' => 'id',
			'key_to' => 'parent_id'
		]
	];
}