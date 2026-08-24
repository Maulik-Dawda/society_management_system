<?php

require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Model.php';

class App {
    private $routes = [];

    public function __construct() {
        Session::init();
    }

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function run() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slashes and script path prefix if running via subfolder
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        if (empty($uri)) {
            $uri = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            $controllerName = $handler[0];
            $action = $handler[1];

            require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
            $controller = new $controllerName();
            $controller->$action();
        } else {
            // Default fallback route to login if route unknown
            header("Location: /login");
            exit();
        }
    }
}
