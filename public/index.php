<?php

// Enable error reporting for debugging
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/App.php';

try {
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

    // Define Society Module Routes & Actions
    $app->get('/registration', ['SocietyController', 'registration']);
    $app->post('/registration', ['SocietyController', 'processRegistration']);
    $app->get('/society-registration', ['SocietyController', 'registration']);
    $app->get('/society/registration', ['SocietyController', 'registration']);

    $app->get('/members', ['SocietyController', 'members']);
    $app->post('/members/add', ['SocietyController', 'addMember']);
    $app->get('/society/members', ['SocietyController', 'members']);

    $app->get('/notices', ['SocietyController', 'notices']);
    $app->post('/notices/add', ['SocietyController', 'addNotice']);
    $app->get('/society/notices', ['SocietyController', 'notices']);

    $app->get('/vehicles', ['SocietyController', 'vehicles']);
    $app->post('/vehicles/add', ['SocietyController', 'addVehicle']);
    $app->get('/society/vehicles', ['SocietyController', 'vehicles']);

    // Define Finance Module Routes & Actions
    $app->get('/maintenance', ['FinanceController', 'maintenance']);
    $app->post('/maintenance/generate', ['FinanceController', 'generateBills']);
    $app->get('/finance/maintenance', ['FinanceController', 'maintenance']);

    $app->get('/payments', ['FinanceController', 'payments']);
    $app->post('/payments/collect', ['FinanceController', 'collectPayment']);
    $app->get('/finance/payments', ['FinanceController', 'payments']);

    $app->get('/expenses', ['FinanceController', 'expenses']);
    $app->post('/expenses/add', ['FinanceController', 'addExpense']);
    $app->get('/finance/expenses', ['FinanceController', 'expenses']);

    $app->get('/reports', ['FinanceController', 'reports']);
    $app->get('/reports/tally-export', ['FinanceController', 'tallyExport']);
    $app->get('/finance/reports', ['FinanceController', 'reports']);

    // Dispatch Application Router
    $app->run();
} catch (Exception $e) {
    http_response_code(200);
    $errorMessage = $e->getMessage();
    require_once __DIR__ . '/../views/setup_error.php';
    exit();
} catch (Error $e) {
    http_response_code(200);
    $errorMessage = $e->getMessage();
    require_once __DIR__ . '/../views/setup_error.php';
    exit();
}
