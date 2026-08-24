<?php

require_once __DIR__ . '/../core/App.php';

$app = new App();

// Define Auth Routes
$app->get('/', ['AuthController', 'login']);
$app->get('/login', ['AuthController', 'login']);
$app->post('/login', ['AuthController', 'processLogin']);

$app->get('/register', ['AuthController', 'register']);
$app->post('/register', ['AuthController', 'processRegister']);

$app->get('/verify-otp', ['AuthController', 'verifyOtp']);
$app->post('/verify-otp', ['AuthController', 'processVerifyOtp']);

$app->get('/set-password', ['AuthController', 'setPassword']);
$app->post('/set-password', ['AuthController', 'processSetPassword']);

$app->get('/logout', ['AuthController', 'logout']);

// Define Dashboard Route
$app->get('/dashboard', ['DashboardController', 'index']);

// Dispatch Application Router
$app->run();
