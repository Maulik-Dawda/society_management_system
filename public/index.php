<?php

// Enable error reporting for debugging
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/App.php';

try {
    $app = new App();

    // REST API Routes (for WhatsApp Chatbot & External Integrations)
    $app->get('/api/v1/auth/login', ['ApiController', 'login']);
    $app->post('/api/v1/auth/login', ['ApiController', 'login']);
    $app->get('/api/v1/auth/user-login', ['ApiController', 'userLogin']);
    $app->post('/api/v1/auth/user-login', ['ApiController', 'userLogin']);
    $app->get('/api/v1/auth/select-society', ['ApiController', 'selectSocietyApi']);
    $app->post('/api/v1/auth/select-society', ['ApiController', 'selectSocietyApi']);
    $app->get('/api/v1/users/check-mobile', ['ApiController', 'checkMobile']);
    $app->get('/api/v1/societies', ['ApiController', 'getSocieties']);
    $app->post('/api/v1/societies/register', ['ApiController', 'registerSociety']);
    $app->get('/api/v1/members', ['ApiController', 'getMembers']);
    $app->post('/api/v1/members/add', ['ApiController', 'addMember']);
    $app->post('/api/v1/members/update-role', ['ApiController', 'updateMemberRole']);
    $app->get('/api/v1/notices', ['ApiController', 'getNotices']);
    $app->post('/api/v1/notices/add', ['ApiController', 'addNotice']);
    $app->get('/api/v1/complaints', ['ApiController', 'getComplaints']);
    $app->post('/api/v1/complaints/add', ['ApiController', 'addComplaint']);
    $app->post('/api/v1/complaints/update-status', ['ApiController', 'updateComplaintStatus']);

    // Define Web Auth Routes
    $app->get('/', ['AuthController', 'login']);
    $app->get('/login', ['AuthController', 'login']);
    $app->post('/login', ['AuthController', 'processLogin']);

    $app->get('/select-society', ['AuthController', 'selectSocietyPage']);
    $app->post('/select-society', ['AuthController', 'chooseSociety']);
    $app->get('/select-active-society', ['SocietyController', 'selectActiveSociety']);

    $app->get('/register', ['AuthController', 'register']);
    $app->post('/register', ['AuthController', 'processRegister']);
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

    $app->get('/committee', ['SocietyController', 'committee']);
    $app->post('/committee/assign', ['SocietyController', 'assignCommitteeRole']);
    $app->get('/society/committee', ['SocietyController', 'committee']);

    $app->get('/complaints', ['ComplaintController', 'index']);
    $app->post('/complaints/add', ['ComplaintController', 'add']);
    $app->post('/complaints/update-status', ['ComplaintController', 'updateStatus']);
    $app->get('/society/complaints', ['ComplaintController', 'index']);

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
