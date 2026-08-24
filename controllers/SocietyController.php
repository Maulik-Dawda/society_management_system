<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Society.php';

class SocietyController extends Controller {
    private $societyModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
        $this->societyModel = new Society();
    }

    public function registration() {
        $userId = Session::get('user_id');
        $society = $this->societyModel->findByUserId($userId);

        $this->view('society/registration', [
            'society' => $society
        ]);
    }

    public function processRegistration() {
        $societyName = trim($_POST['society_name'] ?? '');
        $registrationNumber = trim($_POST['registration_number'] ?? '');
        $registrationDate = trim($_POST['registration_date'] ?? '');
        $registeredAddress = trim($_POST['registered_address'] ?? '');
        $panNumber = trim($_POST['pan_number'] ?? '');
        $gstin = trim($_POST['gstin'] ?? '');

        $totalWings = intval($_POST['total_wings'] ?? 4);
        $totalFlats = intval($_POST['total_flats'] ?? 84);
        $totalMembers = intval($_POST['total_members'] ?? 84);

        $bankBalance = floatval($_POST['bank_balance'] ?? 0);
        $cashInHand = floatval($_POST['cash_in_hand'] ?? 0);
        $bankName = trim($_POST['bank_name'] ?? '');
        $accountNumber = trim($_POST['account_number'] ?? '');

        // Validation Rules
        $errors = [];
        if (empty($societyName)) {
            $errors[] = "Society name is required.";
        }
        if (empty($registeredAddress)) {
            $errors[] = "Registered Address is a required field.";
        }
        if (empty($panNumber)) {
            $errors[] = "PAN Number is a required field.";
        } elseif (!preg_match('/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/', $panNumber)) {
            $errors[] = "Please enter a valid 10-character PAN number (e.g. AAAAA0000A).";
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            Session::setFlash('old', $_POST);
            $this->redirect('/registration');
        }

        // Save to Database
        $saved = $this->societyModel->saveDetails([
            'society_name' => $societyName,
            'registration_number' => $registrationNumber,
            'registration_date' => $registrationDate,
            'registered_address' => $registeredAddress,
            'pan_number' => $panNumber,
            'gstin' => $gstin,
            'total_wings' => $totalWings,
            'total_flats' => $totalFlats,
            'total_members' => $totalMembers,
            'bank_balance' => $bankBalance,
            'cash_in_hand' => $cashInHand,
            'bank_name' => $bankName,
            'account_number' => $accountNumber
        ]);

        if ($saved) {
            Session::setFlash('success', "Society details and opening balances saved successfully!");
        } else {
            Session::setFlash('error', "Failed to save society details. Please try again.");
        }

        $this->redirect('/registration');
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
