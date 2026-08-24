<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';

class SocietyController extends Controller {

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
    }

    public function registration() {
        $this->view('society/registration');
    }

    public function members() {
        $this->view('society/members');
    }

    public function notices() {
        $this->view('society/notices');
    }

    public function vehicles() {
        $this->view('society/vehicles');
    }
}
