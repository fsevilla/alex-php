<?php

/**
 * ALEx - An App Lightweight EXpeditor
 * @package ALEx
 * @author Francisco Sevilla <fsevilla@gmail.com>
**/


$root = str_replace($_SERVER['DOCUMENT_ROOT'], "", __DIR__);

$path = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

$uri = str_replace($root, "", $path);

// If it is trying to load a file that actually exists in the server
// requests the file and avoids loading the app through public/index.php
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
	require_once __DIR__.'/public'.$uri;
    return false;
}

require_once __DIR__.'/public/index.php';
