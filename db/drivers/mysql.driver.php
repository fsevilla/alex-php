<?php

namespace Core\Database;

class DatabaseDriver implements DriverInterface {

	static function connect($host, $user, $password) {
		return mysql_connect($host, $user, $password);
	}

	static function select_database($connection, $database) {
		return mysql_select_db($database);
	}

	static function insert_id($con) {
		return mysql_insert_id();
	}

	static function num_rows($records) {
		return mysql_num_rows($records);
	}

	static function fetch_array($records) {
		return mysql_fetch_array($records);
	}

	static function fetch_object($records) {
		return mysql_fetch_object($records);
	}

	static function affected_rows($con) {
		return mysql_affected_rows();
	}

	static function query($con, $query) {
		return mysql_query($query);
	}

	static function error($con) {
		return mysql_error();
	}
	
}
