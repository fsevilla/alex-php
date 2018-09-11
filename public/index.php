<?php

/**
 * ALEx - An App Lightweight EXpeditor
 * @package ALEx
 * @author Francisco Sevilla <fsevilla@gmail.com>
**/

// Load all required initial files
require_once __DIR__.'/../core/bootstrap.php';

// Initialize application
$app = new Core\Application();

// Respond with path controller method
// or welcome page as default
$app->load($uri);