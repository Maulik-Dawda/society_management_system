<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';

class FinanceController extends Controller {

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
    }

    public function maintenance() {
        $this->view('finance/maintenance');
    }

    public function payments() {
        $this->view('finance/payments');
    }

    public function expenses() {
        $this->view('finance/expenses');
    }

    public function reports() {
        $this->view('finance/reports');
    }
}
