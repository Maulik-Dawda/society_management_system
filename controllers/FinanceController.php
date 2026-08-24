<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Society.php';

class FinanceController extends Controller {
    private $societyModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
        $this->societyModel = new Society();
    }

    private function enforceRegistration() {
        $userId = Session::get('user_id');
        $society = $this->societyModel->findByUserId($userId);
        if (!$society || empty($society['pan_number']) || empty($society['registered_address'])) {
            Session::setFlash('info', "Please complete your society registration first.");
            $this->redirect('/registration');
        }
    }

    public function maintenance() {
        $this->enforceRegistration();
        $this->view('finance/maintenance');
    }

    public function payments() {
        $this->enforceRegistration();
        $this->view('finance/payments');
    }

    public function expenses() {
        $this->enforceRegistration();
        $this->view('finance/expenses');
    }

    public function reports() {
        $this->enforceRegistration();
        $this->view('finance/reports');
    }
}
