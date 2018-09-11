<?php

namespace Core\Database;

class DatabaseDriver implements DriverInterface {

	static function connect($host, $user, $password) {
		return mysqli_connect($host, $user, $password);
	}

	static function select_database($connection, $database) {
		return mysqli_select_db($connection, $database);
	}

	static function insert_id($con) {
		return mysqli_insert_id($con);
	}

	static function num_rows($records) {
		return mysqli_num_rows($records);
	}

	static function fetch_array($records) {
		return mysqli_fetch_array($records);
	}

	static function fetch_object($records) {
		return mysqli_fetch_object($records);
	}

	static function affected_rows($con) {
		return mysqli_affected_rows($con);
	}

	static function query($con, $query) {
		return mysqli_query($con, $query);
	}

	static function error($con) {
		return mysqli_error($con);
	}

}