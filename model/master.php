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

	public function find($options = [], $count = false) {
		$defaults = [
			'limit' => $this->_limit,
			'table' => $this->_table,
			'columns' => '*',
			'where' => null,
			'join' => null,
			'orderby' => null,
			'groupby' => null
		];
		$join_type_default = 'INNER';

		$args = array_merge($defaults, $options);

		$query = 'SELECT ';

		if (is_array($args['columns'])) {
			$query .= implode(', ', $args['columns']);
		} else {
			$query .= $args['columns'];
		}

		$query .= ' FROM ' . $args['table'];

		// build the joins
		if (!empty($args['join'])) {
			if (!isset($args['join']['key_to'])) {
				foreach ($args['join'] as $join) {
					$query .= ' ' . ((!isset($join['type'])) ? $join_type_default : $join['type']) . ' JOIN ';
					$query .= $join['table'] . ' ON `' . ((!isset($join['table_from'])) ? $this->_table : $join['table_from']) . '`.' . $join['key_from'];
					$query .= ' = `' . $join['table'] . '`.' . $join['key_to'];
				}
			} else {
				$join = $args['join'];
				$query .= ' ' . ((!isset($join['type'])) ? $join_type_default : $join['type']) . ' JOIN ';
				$query .= $join['table'] . ' ON `' . ((!isset($join['table_from'])) ? $this->_table : $join['table_from']) . '`.' . $join['key_from'];
				$query .= ' = `' . $join['table'] . '`.' . $join['key_to'];
			}
		}

		// filter the where clause
		if (!empty($args['where'])) {
			if (is_array($args['where'])) {
				$whereArg;
				$whereArgument = $args['where'];

				if (count($whereArgument) == 3) {
					$whereArg = '`' . $whereArgument[0] . '` ' . $whereArgument[1] . " '" . clean($whereArgument[2]) . "'";
				} else {
					$whereArg = '`' . $whereArgument[0] . "` = '" . clean($whereArgument[1]) . "'";
				}

				$query .= ' WHERE ' . $whereArg;
			} else {
				//relying that you clean your where clause
				$query .= ' WHERE ' . $args['where'];
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

		if (!empty($args['groupby'])) {
			if (is_array($args['groupby'])) {
				$group_fields = [];
				foreach ($args['groupby'] as $field) {
					$group_fields[] = $field;
				}
				$query .= ' GROUP BY ' . implode(', ', $group_fields);
			} else {
				$query .= ' GROUP BY ' . $args['groupby'];
			}
		}

		if (!empty($args['limit'])) {
			if (is_array($args['limit'])) {
				if (count($args['limit']) == 2) {
					$offset = (int) $args['limit'][0];
					$count = (int) $args['limit'][1];

					if ($offset >= 0 && $count > 0) {
						$query .= ' LIMIT ' . $offset . ', ' . $count;
					}
				}
			} else {
				if (is_numeric($args['limit']) && $args['limit'] > 0) {
					$query .= ' LIMIT ' . $args['limit'];
				}
			}
		}
		
		// echo $query . '<br />';

		$results = $this->_db->query($query);
		if ($count) {
			return $results;
		}

		$this->_result = $this->process_results($results);

		return $this->_result;
	}

	public function count($options) {
		$result = $this->find($options, true);
		return $result->num_rows;
	}

	public function get($key = 0, $keyName = 'id', $options = []) {
		$args = array_merge(['where' => [$keyName, $key]], $options);
		$result = $this->find($args);
		if (count($result) == 1) {
			return $result[0];
		}
		return null;
	}

	public function create($data) {
		if (!count($data)) {
			die('No data to add.');
		}

		$keys = [];
		$vals = [];

		foreach ($data as $key => $value) {
			$keys[] = '`' . $key . '`';
			$vals[] = "'" . clean($value) . "'";
		}

		$colums = implode(', ', $keys);
		$values = implode(', ', $vals);

		$query = "INSERT INTO `" . $this->_table . "` (" . $colums . ") VALUES (" . $values . ")";
		$this->_db->query($query);

		return $this->_db->affected_rows;
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

	public function delete($id) {
		if (!is_numeric($id) || $id < 0) {
			return -2;
		}

		$result = $this->_db->query("DELETE FROM `" . $this->_table . "` WHERE `id` = '" . $id . "'");
		return $result;
	}

	public function paginate($data_args, $url = '?page=', $current_page = 0, $row_per_page = DEFAULT_ITEMS_PER_PAGE) {
		if (isset($data_args['limit'])) {
			unset($data_args['limit']);
		}
		$output = ['data', 'pagination' => null];

		$data_count = $this->count($data_args);

		if ($data_count <= $row_per_page) {
			$output['data'] = $this->find($data_args);
			return $output;
		} else {
			$total_pages = ceil($data_count / $row_per_page);
			if ($current_page < 1) {
				$current_page = 1;
			}

			$output['pagination'] .= '<nav><ul class="pagination">';
			// make pages
			$output['pagination'] .= '<li' . (($current_page == 1) ? ' class="disabled"' : null) . '><a href="' . $url . '1">First</a></li>';
			$output['pagination'] .= '<li' . (($current_page == 1) ? ' class="disabled"' : null) . '><a href="' . $url . max([$current_page - 1, 1]) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			foreach(range(1, $total_pages) as $page){
				if($page == 1 || $page == $total_pages || ($page >= $current_page - 2 && $page <= $current_page + 2)){
					$output['pagination'] .= '<li' . (($page == $current_page) ? ' class="active"' : null) . '><a href="' . $url . (int)$page . '">' . $page . '</a></li>';
				}
			}
			$output['pagination'] .= '<li' . (($current_page == $total_pages) ? ' class="disabled"' : null) . '><a href="' . $url . min([$current_page + 1, $total_pages]) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			$output['pagination'] .= '<li' . (($current_page == $total_pages) ? ' class="disabled"' : null) . '><a href="' . $url . $total_pages . '">Last</a></li>';
			$output['pagination'] .= '</ul></nav>';

			$offset = ($current_page - 1) * $row_per_page;
			$data_args['limit'] = [$offset, $row_per_page];
			$data = $this->find($data_args);
			$output['data'] = $data;

			return $output;
		}
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

	public function autocommit($do = TRUE) {
		$this->_db->autocommit($do);
	}

	public function commit() {
		$this->_db->commit();
	}

	public function geterror() {
		return $this->_db->error;
	}
}