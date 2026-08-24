<?php

class Controller {
    public function view($viewName, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View file [{$viewName}] not found at path: {$viewPath}");
        }
    }

    public function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
