<?php

/*

 Define a set of Global scope methods
 to help the Application manage simple and
 repetitive executions

*/

// Get an Env variable value or the default if it does not exist
function env($varname, $default = NULL)
{
	return getenv($varname) ?: $default;
}

// Another way to put an env variable passing two params instead of an Equal string
function setenv($varname, $value)
{
	putenv($varname."=".$value);
}

function dump()
{
	echo "<pre>";
	call_user_func_array("var_dump", func_get_args());
	echo "</pre>";
}

function dd()
{
	call_user_func_array("dump", func_get_args());
	die();
}

function udd()
{
	call_user_func_array("var_dump", func_get_args());
	die();
}

function sanitize($data){
    return addslashes(strip_tags(trim($data)));
}

