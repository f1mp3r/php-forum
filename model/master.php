<?php

namespace Models;

class Master_Model
{
	protected $_db;
	protected $_table;
	protected $_limit;
	protected $_result;
	protected $_has_many;
	protected $_has_one;
	protected $_many_to_many;

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
			'columns' => '*',
			'where' => null,
			'join' => null,
			'orderby' => null
		];

		$args = array_merge($defaults, $options);

		$query = 'SELECT ';

		if (is_array($args['columns'])) {
			$query .= implode(', ', $args['columns']);
		} else {
			$query .= $args['columns'];
		}

		$query .= ' FROM ' . $args['table'];

		// filter the where clause
		if (!empty($args['where'])) {
			if (is_array($args['where'])) {
				$whereArg;
				$whereArgument = $args['where'];

				if (count($whereArgument) == 3) {
					$whereArg = '`' . $whereArgument[0] . '` ' . $whereArgument[1] . " '" . $this->_db->real_escape_string($whereArgument[2]) . "'";
				} else {
					$whereArg = '`' . $whereArgument[0] . "` = '" . $this->_db->real_escape_string($whereArgument[1]) . "'";
				}

				$query .= ' WHERE ' . $whereArg;
			} else {
				$query .= ' WHERE ' . $this->_db->real_escape_string($args['where']);
			}
		}

		if (!empty($args['orderby']) && count($args['orderby'])) {
			if (is_array($args['orderby'])) {
				$orders = [];
				foreach ($args['orderby'] as $key => $order) {
					$orders[] = '`' . $key . '` ' . $order;
				}
				$query .= ' ORDER BY ' . implode(', ', $orders);
			}
		}

		if (!empty($args['limit']) && is_numeric($args['limit']) && $args['limit'] > 0) {
			$query .= ' LIMIT ' . $args['limit'];
		}
		
		$results = $this->_db->query($query);
		$this->_result = $this->process_results($results);

		return $this->_result;
	}

	public function get($key = 0, $keyName = 'id', $options = []) {
		$args = array_merge(['where' => [$keyName, $key]], $options);
		$result = $this->find($args);
		if (count($result) == 1) {
			return $result[0];
		}
		return null;
	}

	public function update($id, $data, $options = []) {
		$defaults = [
			'table' => $this->_table,
			'where' => null
		];

		$args = array_merge($defaults, $options);

		$query = 'UPDATE ' . $args['table'] . ' SET ';

		$updateFieldsArray = [];
		foreach ($data as $column => $newValue) {
			$updateFieldsArray[] = '`' . $column . '` = "' . $newValue . '"';
		}

		$query .= implode(', ', $updateFieldsArray);

		if (!empty($args['where'])) {
			if (is_array($args['where'])) {
				$whereArg;
				$whereArgument = $args['where'];

				if (count($whereArgument) == 3) {
					$whereArg = '`' . $whereArgument[0] . '` ' . $whereArgument[1] . " '" . $this->_db->real_escape_string($whereArgument[2]) . "'";
				} else {
					$whereArg = '`' . $whereArgument[0] . "` = '" . $this->_db->real_escape_string($whereArgument[1]) . "'";
				}

				$query .= ' WHERE ' . $whereArg;
			} else {
				$query .= ' WHERE ' . $this->_db->real_escape_string($args['where']);
			}
		} else {
			$query .= ' WHERE `id` = ' . (int) $id;
		}

		$this->_db->query($query);
		
		return $this->_db->affected_rows;
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