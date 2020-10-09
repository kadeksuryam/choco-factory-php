<?php
class Framework
{
    private static $request;
    private static $controller;

    public static function run()
    {
        self::init();
        self::load_controller();
        self::dispatch();
    }
    // Initialization
    private static function init()
    {
        // Define path constants
        define("DS", DIRECTORY_SEPARATOR);
        define("ROOT", dirname(getcwd()) . DS);
        define("APP_PATH", ROOT . 'application' . DS);
        define("FRAMEWORK_PATH", ROOT . "framework" . DS);
        define("PUBLIC_PATH", ROOT . "public" . DS);
        define("CONFIG_PATH", APP_PATH . "config" . DS);
        define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
        define("MODEL_PATH", APP_PATH . "models" . DS);
        define("VIEW_PATH", APP_PATH . "views" . DS);
        define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
        define('DB_PATH', FRAMEWORK_PATH . "database" . DS);
        define("LIB_PATH", FRAMEWORK_PATH . "libraries" . DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);
        define("TEMPLATE_PATH", PUBLIC_PATH . "templates" . DS);
        define("CSS_PATH", PUBLIC_PATH . "css" . DS);
        define("JS_PATH", PUBLIC_PATH . "js" . DS);
        define("IMAGE_PATH", PUBLIC_PATH . "images" . DS);
        define("UPLOAD_PATH", PUBLIC_PATH . "uploads" . DS);
        define("HTML_PATH", PUBLIC_PATH . "html" . DS);
        define("CHOCOLATE_PATH", HTML_PATH . "chocolate" . DS);
        define("USER_PATH", HTML_PATH . "user" . DS);

        // Load core classes
        foreach (array(CORE_PATH, DB_PATH, CONFIG_PATH) as $dir) {
            foreach (scandir($dir) as $filename) {
                $path = $dir . $filename;
                if ($path != __FILE__) {
                    if (is_file($path)) {
                        require $path;
                    }
                }
            }
        }

        // Load configuration file
        $GLOBALS['config'] = include CONFIG_PATH . "config.php";

        // Start session
        session_start();
    }

    // Autoloading
    private static function load_controller()
    {
        self::$request = new Request();
        Router::parse(self::$request->url, self::$request);
        $name = self::$request->controller . "Controller";
        $file = CONTROLLER_PATH . $name . '.class.php';
        require $file;
        self::$controller = new $name();
    }

    // Routing and dispatching
    private static function dispatch()
    {
        call_user_func_array([self::$controller, self::$request->action], self::$request->params);
    }
}