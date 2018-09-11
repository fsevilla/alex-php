<?php
namespace Core;

class Application {

    private $config;

    public function __construct()
    {
        $this->loadAppConfig();
        $this->loadCorsConfig();
    }

    protected function loadAppConfig()
    {
        $this->config = require_once __DIR__.'/../config/app.php';
        $this->defineVars();
        $this->setDebugMode();
    }

    protected function loadCorsConfig()
    {
        require_once __DIR__.'/../config/cors.php';
    }

    private function defineVars()
    {
        // Define path of the app folder
        define('__APP_PATH__', __DIR__.'/../', 1);

        $prefix = $this->config['app_prefix'] ?: 'APP';

        foreach ($this->config as $key => $value) {
            $dKey = strtoupper($prefix.'_'.$key);
            define($dKey, $value);
        }
    }

    private function setDebugMode()
    {
        $mode = $this->config['error_reporting'] ?: 'none';
        $mode = strtoupper($mode);

        if($mode === 'NONE') {
            ini_set('display_errors', 0);   
        } else {
            ini_set('display_errors', 1);

            switch ($mode) {
                case 'ALL':
                    error_reporting(E_ALL);
                    break;
                case 'WARNING':
                    error_reporting(E_WARNING);
                    break;
                case 'PARSE':
                    error_reporting(E_PARSE);
                    break;
                case 'ERROR':
                    error_reporting(E_ERROR);
                    break;
                case 'NOTICE':
                    error_reporting(E_NOTICE);
                    break;
                default:
                    error_reporting(E_ALL);
                    break;
            }
        }
    }

    public function load($uri)
    {
        Request::handle($uri);
    }

}