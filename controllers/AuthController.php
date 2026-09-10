<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../models/Society.php';

class AuthController extends Controller {
    private $userModel;
    private $memberModel;
    private $societyModel;

    public function __construct() {
        $this->userModel = new User();
        $this->memberModel = new Member();
        $this->societyModel = new Society();
    }

    public function login() {
        if (Session::has('user_id')) {
            $this->redirectBasedOnSession();
        }
        $this->view('auth/login');
    }

    public function processLogin() {
        $loginType = strtolower(trim($_POST['login_type'] ?? 'user'));
        $password = trim($_POST['password'] ?? '');

        if ($loginType === 'admin') {
            $email = trim($_POST['email'] ?? '');
            if (empty($email) || empty($password)) {
                Session::setFlash('error', "Admin Email and Password are required.");
                $this->redirect('/login');
            }

            $user = $this->userModel->findByEmail($email);
            if (!$user || !$user['is_admin'] || !$this->userModel->verifyPassword($user, $password)) {
                Session::setFlash('error', "Invalid Admin email or password.");
                $this->redirect('/login');
            }

            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('is_admin', 1);
            Session::set('user_role', 'Admin');

            $allSocieties = $this->societyModel->getAll();
            if (count($allSocieties) > 1) {
                Session::set('user_societies', $allSocieties);
                Session::setFlash('success', "Welcome Admin {$user['name']}! Please select a society to proceed.");
                $this->redirect('/select-society');
            } else {
                $firstSoc = $allSocieties[0] ?? null;
                Session::set('active_society_id', $firstSoc['id'] ?? 1);
                Session::set('active_society_name', $firstSoc['name'] ?? 'Meridian Heights');
                Session::setFlash('success', "Welcome Admin {$user['name']}!");
                $this->redirect('/dashboard');
            }
        } else {
            // User Mobile Login (NO OTP!)
            $mobile = trim($_POST['mobile_number'] ?? '');
            if (empty($mobile) || empty($password)) {
                Session::setFlash('error', "Mobile number and Password are required.");
                $this->redirect('/login');
            }

            $user = $this->userModel->findByMobile($mobile);
            if (!$user || !$this->userModel->verifyPassword($user, $password)) {
                Session::setFlash('error', "Invalid Mobile number or password.");
                $this->redirect('/login');
            }

            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_mobile', $user['mobile_number']);
            Session::set('is_admin', 0);

            // Fetch societies associated with this user
            $societies = $this->userModel->getUserSocieties($user['mobile_number'], $user['id']);
            $allSocieties = $this->societyModel->getAll();

            if (count($societies) > 1 || count($allSocieties) > 1) {
                // Multiple Societies exist: Ask user to select society after login!
                $displaySocieties = count($societies) > 1 ? $societies : $allSocieties;
                Session::set('user_societies', $displaySocieties);
                Session::setFlash('info', "Please select which society you would like to enter.");
                $this->redirect('/select-society');
            } elseif (count($societies) === 1) {
                // Only 1 Society exists: Direct redirect to dashboard
                $soc = $societies[0];
                Session::set('active_society_id', $soc['id']);
                Session::set('active_society_name', $soc['name']);
                Session::set('user_role', $soc['committee_role'] ?? 'Resident');
                Session::set('user_flat', $soc['flat_number'] ?? '');
                Session::set('user_member_id', $soc['member_id'] ?? null);

                Session::setFlash('success', "Welcome back, {$user['name']}!");
                $this->redirect('/dashboard');
            } else {
                Session::set('active_society_id', 1);
                Session::set('user_role', 'Resident');
                $this->redirect('/dashboard');
            }
        }
    }

    public function selectSocietyPage() {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $userId = Session::get('user_id');
        $mobile = Session::get('user_mobile');
        $societies = $this->userModel->getUserSocieties($mobile, $userId);

        $this->view('auth/select_society', ['societies' => $societies]);
    }

    public function chooseSociety() {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $societyId = intval($_POST['society_id'] ?? 0);
        $userId = Session::get('user_id');
        $mobile = Session::get('user_mobile');

        $societies = $this->userModel->getUserSocieties($mobile, $userId);
        $selectedSoc = null;
        foreach ($societies as $s) {
            if (intval($s['id']) === $societyId) {
                $selectedSoc = $s;
                break;
            }
        }

        if (!$selectedSoc) {
            Session::setFlash('error', "Invalid society selected.");
            $this->redirect('/select-society');
        }

        Session::set('active_society_id', $selectedSoc['id']);
        Session::set('active_society_name', $selectedSoc['name']);
        Session::set('user_role', $selectedSoc['committee_role'] ?? 'Resident');
        Session::set('user_flat', $selectedSoc['flat_number'] ?? '');
        Session::set('user_member_id', $selectedSoc['member_id'] ?? null);

        Session::setFlash('success', "Entered society: " . htmlspecialchars($selectedSoc['name']));
        $this->redirect('/dashboard');
    }

    public function register() {
        $this->view('auth/register');
    }

    public function processRegister() {
        $name = trim($_POST['name'] ?? '');
        $mobile = trim($_POST['mobile_number'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($name) || (empty($mobile) && empty($email)) || empty($password)) {
            Session::setFlash('error', "Please fill in all required fields.");
            $this->redirect('/register');
        }

        $userId = $this->userModel->create($name, $mobile, $email, $password, 0);
        Session::setFlash('success', "Registration successful! You can now log in with your credentials.");
        $this->redirect('/login');
    }

    public function logout() {
        Session::destroy();
        $this->redirect('/login');
    }

    private function redirectBasedOnSession() {
        $this->redirect('/dashboard');
    }
}
