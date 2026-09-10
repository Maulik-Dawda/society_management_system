<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Society.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../models/Notice.php';
require_once __DIR__ . '/../models/Vehicle.php';

class SocietyController extends Controller {
    private $societyModel;
    private $memberModel;
    private $noticeModel;
    private $vehicleModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
        $this->societyModel = new Society();
        $this->memberModel = new Member();
        $this->noticeModel = new Notice();
        $this->vehicleModel = new Vehicle();
    }

    private function getActiveSocietyId() {
        return Session::get('active_society_id') ?? 1;
    }

    public function registration() {
        $userId = Session::get('user_id');
        $isNew = isset($_GET['new']) || isset($_GET['action']);
        
        $society = null;
        if (!$isNew) {
            $society = $this->societyModel->findById($this->getActiveSocietyId());
        }

        $this->view('society/registration', [
            'society' => $society,
            'isNew' => $isNew
        ]);
    }

    public function processRegistration() {
        // Admin registers new society
        $societyName = trim($_POST['society_name'] ?? '');
        $registeredAddress = trim($_POST['registered_address'] ?? '');
        $panNumber = trim($_POST['pan_number'] ?? '');

        if (empty($societyName) || empty($registeredAddress) || empty($panNumber)) {
            Session::setFlash('error', "Society Name, Address, and PAN Number are required.");
            $this->redirect('/registration?new=1');
        }

        $societyId = $this->societyModel->create($_POST, Session::get('user_id'));
        if ($societyId) {
            Session::set('active_society_id', $societyId);
            Session::set('active_society_name', $societyName);
            Session::setFlash('success', "Society '{$societyName}' registered successfully! You can now add members to this society.");
            $this->redirect('/members');
        } else {
            Session::setFlash('error', "Failed to register society.");
            $this->redirect('/registration?new=1');
        }
    }

    public function selectActiveSociety() {
        $societyId = intval($_GET['id'] ?? 0);
        if ($societyId > 0) {
            $society = $this->societyModel->findById($societyId);
            if ($society) {
                Session::set('active_society_id', $society['id']);
                Session::set('active_society_name', $society['name']);
                Session::setFlash('success', "Active society switched to '{$society['name']}'. Showing member directory.");
            }
        }
        $this->redirect('/members');
    }

    public function members() {
        $societyId = $this->getActiveSocietyId();
        $members = $this->memberModel->getAll($societyId);
        $this->view('society/members', ['members' => $members]);
    }

    public function addMember() {
        $flatNumber = trim($_POST['flat_number'] ?? '');
        $ownerName = trim($_POST['owner_name'] ?? '');
        $ownerPhone = preg_replace('/[^0-9]/', '', $_POST['owner_phone'] ?? '');
        $ownerEmail = trim($_POST['owner_email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($flatNumber) || empty($ownerName) || empty($ownerPhone)) {
            Session::setFlash('error', "Flat Number, Owner Name, and Owner Mobile Number are required.");
            $this->redirect('/members');
        }

        require_once __DIR__ . '/../models/User.php';
        $userModel = new User();
        $existingUser = $userModel->findByMobile($ownerPhone);
        $userId = null;

        if ($existingUser) {
            // Existing user in database -> link user ID without modifying password!
            $userId = $existingUser['id'];
            Session::setFlash('info', "Mobile {$ownerPhone} is already registered as '{$existingUser['name']}'. Linked existing user account to flat {$flatNumber} without modifying password.");
        } else {
            // New user -> create user account with password
            if (empty($password)) {
                $password = 'User@1234';
            }
            $userId = $userModel->create($ownerName, $ownerPhone, $ownerEmail, $password, 0);
            Session::setFlash('success', "New user account and member record created for {$ownerName} ({$flatNumber})!");
        }

        $_POST['society_id'] = $this->getActiveSocietyId();
        $_POST['user_id'] = $userId;
        $_POST['owner_phone'] = $ownerPhone;

        $this->memberModel->create($_POST);
        $this->redirect('/members');
    }

    public function committee() {
        $societyId = $this->getActiveSocietyId();
        $allMembers = $this->memberModel->getAll($societyId);
        $committeeMembers = $this->memberModel->getCommitteeMembers($societyId);
        
        $this->view('society/committee', [
            'allMembers' => $allMembers,
            'committeeMembers' => $committeeMembers
        ]);
    }

    public function assignCommitteeRole() {
        $memberId = intval($_POST['member_id'] ?? 0);
        $role = trim($_POST['committee_role'] ?? 'Resident');
        $referer = $_SERVER['HTTP_REFERER'] ?? '/committee';

        if ($memberId <= 0) {
            Session::setFlash('error', "Please select a valid member.");
            $this->redirect($referer);
        }

        $this->memberModel->updateCommitteeRole($memberId, $role);
        Session::setFlash('success', "User role and designation updated successfully to '{$role}'!");
        $this->redirect($referer);
    }

    public function notices() {
        $societyId = $this->getActiveSocietyId();
        $notices = $this->noticeModel->getAll($societyId);
        $userRole = Session::get('user_role') ?? 'Resident';
        $isAdmin = Session::get('is_admin') ?? 0;

        $this->view('society/notices', [
            'notices' => $notices,
            'userRole' => $userRole,
            'isAdmin' => $isAdmin
        ]);
    }

    public function addNotice() {
        // Enforce CHAIRMAN ONLY rule
        $userRole = Session::get('user_role') ?? 'Resident';

        if ($userRole !== 'Chairman') {
            Session::setFlash('error', "Permission Denied: Notices can only be created by the Chairman of the society.");
            $this->redirect('/notices');
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title) || empty($content)) {
            Session::setFlash('error', "Title and Content are required for posting a notice.");
            $this->redirect('/notices');
        }

        $_POST['society_id'] = $this->getActiveSocietyId();
        $_POST['created_by_user_id'] = Session::get('user_id');

        $this->noticeModel->create($_POST);
        Session::setFlash('success', "Notice '{$title}' posted successfully!");
        $this->redirect('/notices');
    }

    public function vehicles() {
        $societyId = $this->getActiveSocietyId();
        $vehicles = $this->vehicleModel->getAll($societyId);
        $this->view('society/vehicles', ['vehicles' => $vehicles]);
    }

    public function addVehicle() {
        $flatNumber = trim($_POST['flat_number'] ?? '');
        $vehicleNumber = trim($_POST['vehicle_number'] ?? '');

        if (empty($flatNumber) || empty($vehicleNumber)) {
            Session::setFlash('error', "Flat Number and Vehicle Number are required.");
            $this->redirect('/vehicles');
        }

        $_POST['society_id'] = $this->getActiveSocietyId();
        $this->vehicleModel->create($_POST);
        Session::setFlash('success', "Vehicle {$vehicleNumber} registered for {$flatNumber}!");
        $this->redirect('/vehicles');
    }
}
