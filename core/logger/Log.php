<?php

class Log {

	private static function write($type, $message)
	{
		$dateTime = date("Y-m-d h:i:sa");
		var_dump(APP_ENV);	
		echo "$type: ($dateTime) $message <br />";
	}

	public static function info($message)
	{
		return self::write('INFO', $message);
	}

	public static function warn($message)
	{
		return self::write('WARNING', $message);
	}

	public static function error($message)
	{
		return self::write('ERROR', $message);
	}
}
