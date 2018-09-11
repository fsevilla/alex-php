<?php

namespace Core\Database;

interface DriverInterface {
	static function connect($host, $user, $password);
	static function select_database($con, $database);
	static function insert_id($con);
	static function num_rows($records);
	static function fetch_array($records);
	static function fetch_object($records);
	static function affected_rows($con);
	static function query($con, $query);
	static function error($con);
}
