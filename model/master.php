<?php

namespace Models;

class Master_Model
{
	protected $_db;
	protected $_table;
	protected $_limit;
	protected $_result;

	public function __construct($args = []) {
		$defaults = [
			'limit' => null
		];
		$args = array_merge($defaults, $args);

		extract($args);

		if (isset($table)) {
			$this->_table = $table;
		}

		if (isset($limit)) {
			$this->_limit = $limit;
		}

		if ($this->_table == null) {
			// manually selecting db
			$caller = get_called_class();
			$caller = explode('\\', $caller);
			$caller = end($caller);
			$caller = strtolower(explode('_', $caller)[0]);
			$this->_table = $caller;
		}

		$db_object = \Lib\Database::get_instance();
		$this->_db = $db_object::get_db();
	}

	public function find($options = []) {
		$defaults = [
			'limit' => $this->_limit,
			'table' => $this->_table,
			'where' => '',
			'columns' => '*'
		];

		$args = array_merge($defaults, $options);

		$query = 'SELECT ';

		if (is_array($args['columns'])) {
			$query .= implode(', ', $args['columns']);
		} else {
			$query .= $args['columns'];
		}

		$query .= ' FROM ' . $args['table'];

		if (!empty($args['where'])) {
			$query .= ' WHERE ' . $args['where'];
		}

		if (!empty($args['limit']) && is_numeric($args['limit']) && $args['limit'] > 0) {
			$query .= ' LIMIT ' . $args['limit'];
		}

		$results = $this->_db->query($query);
		$results = $this->process_results($results);

		return $results;
	}

	protected function process_results($results_set) {
		$results = [];
		if (!empty($results_set) && $results_set->num_rows > 0) {
			while ($row = $results_set->fetch_assoc()) {
				$results[] = $row;
			}
		}

		return $results;
	}
}