<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';

class DashboardController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access your dashboard.");
            $this->redirect('/login');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->findById($userId);

        $this->view('dashboard/index', [
            'user' => $user
        ]);
    }
}
