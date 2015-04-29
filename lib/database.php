<?php

namespace Lib;

class Database
{
	protected static $db;

	public function __construct() {
		$dbhost = DB_HOST;
		$dbuser = DB_USER;
		$dbpass = DB_PASS;
		$dbname = DB_NAME;

		$db = new \mysqli($dbhost, $dbuser, $dbpass, $dbname);
		$db->set_charset(DB_DEFAULT_CHARSET);

		self::$db = $db;
	}

	public function get_instance() {
		static $instance = null;

		if ($instance === null) {
			$instance = new static();
		}

		return $instance;
	}

	public static function get_db() {
		return self::$db;
	}
}