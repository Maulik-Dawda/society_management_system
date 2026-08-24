<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Society.php';

class DashboardController extends Controller {
    private $userModel;
    private $societyModel;

    public function __construct() {
        $this->userModel = new User();
        $this->societyModel = new Society();
    }

    public function index() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access your dashboard.");
            $this->redirect('/login');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->findById($userId);

        // Enforce society registration before dashboard access
        $society = $this->societyModel->findByUserId($userId);
        if (!$society || empty($society['pan_number']) || empty($society['registered_address'])) {
            Session::setFlash('info', "Please complete your society registration first before accessing dashboard.");
            $this->redirect('/registration');
        }

        $this->view('dashboard/index', [
            'user' => $user,
            'society' => $society
        ]);
    }
}
