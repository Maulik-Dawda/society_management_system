<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Otp.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../models/Society.php';
require_once __DIR__ . '/../services/NotificationService.php';

class AuthController extends Controller {
    private $userModel;
    private $otpModel;
    private $passwordResetModel;
    private $societyModel;

    public function __construct() {
        $this->userModel = new User();
        $this->otpModel = new Otp();
        $this->passwordResetModel = new PasswordReset();
        $this->societyModel = new Society();
    }

    public function register() {
        if (Session::has('user_id')) {
            $this->redirectBasedOnRegistration(Session::get('user_id'));
        }
        $this->view('auth/register');
    }

    public function processRegister() {
        $name = trim($_POST['name'] ?? '');
        $societyName = trim($_POST['society_name'] ?? '');
        $mobile = trim($_POST['mobile_number'] ?? '');

        // Validation
        $errors = [];
        if (empty($name)) {
            $errors[] = "Name is required.";
        }
        if (empty($societyName)) {
            $errors[] = "Society name is required.";
        }
        if (empty($mobile) || !preg_match('/^[0-9]{10,15}$/', $mobile)) {
            $errors[] = "Please enter a valid mobile number (10-15 digits).";
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            Session::setFlash('old', ['name' => $name, 'society_name' => $societyName, 'mobile_number' => $mobile]);
            $this->redirect('/register');
        }

        // Create or update pending user
        $userId = $this->userModel->create($name, $societyName, $mobile);

        // Generate 6 digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otpModel->createOtp($mobile, $otpCode);

        // Send OTP notification
        NotificationService::sendOtp($mobile, $otpCode);

        Session::setFlash('success', "OTP has been sent to {$mobile}. Code: {$otpCode}");
        $this->redirect('/verify-otp?mobile=' . urlencode($mobile));
    }

    public function verifyOtp() {
        $mobile = $_GET['mobile'] ?? '';
        $verified = $_GET['verified'] ?? 0;
        
        $this->view('auth/verify_otp', [
            'mobile' => $mobile,
            'verified' => $verified
        ]);
    }

    public function processVerifyOtp() {
        $mobile = trim($_POST['mobile_number'] ?? '');
        $otpCode = trim($_POST['otp_code'] ?? '');

        if (empty($mobile) || empty($otpCode)) {
            Session::setFlash('error', "Mobile number and OTP code are required.");
            $this->redirect('/verify-otp?mobile=' . urlencode($mobile));
        }

        $isValid = $this->otpModel->verifyOtp($mobile, $otpCode);

        if (!$isValid) {
            Session::setFlash('error', "Invalid or expired OTP. Please try again.");
            $this->redirect('/verify-otp?mobile=' . urlencode($mobile));
        }

        // OTP verified successfully
        $user = $this->userModel->findByMobile($mobile);
        if (!$user) {
            Session::setFlash('error', "User account not found.");
            $this->redirect('/register');
        }

        $this->userModel->updateStatus($user['id'], 'pending_password');

        // Create Password Token
        $token = $this->passwordResetModel->createToken($user['id']);

        // Dispatch WhatsApp and SMS notifications with setup link
        $dispatch = NotificationService::sendPasswordCreationLink($mobile, $token);

        Session::setFlash('success', "OTP Verified successfully! A password creation link has been sent to your WhatsApp and Text Message.");
        Session::setFlash('dispatch_info', $dispatch);

        $this->redirect('/verify-otp?mobile=' . urlencode($mobile) . '&verified=1');
    }

    public function setPassword() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            Session::setFlash('error', "Invalid password creation request.");
            $this->redirect('/login');
        }

        $tokenData = $this->passwordResetModel->findByToken($token);
        if (!$tokenData) {
            Session::setFlash('error', "Password link is invalid, used, or expired.");
            $this->redirect('/login');
        }

        $this->view('auth/set_password', [
            'token' => $token,
            'user' => $tokenData
        ]);
    }

    public function processSetPassword() {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $tokenData = $this->passwordResetModel->findByToken($token);
        if (!$tokenData) {
            Session::setFlash('error', "Invalid or expired password reset token.");
            $this->redirect('/login');
        }

        // Validation Rules: min 8 chars, 1 upper, 1 lower, 1 special char
        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = "Password must contain at least one special character (!@#$%^&* etc).";
        }
        if ($password !== $confirmPassword) {
            $errors[] = "Password and confirm password fields do not match.";
        }

        if (!empty($errors)) {
            Session::setFlash('errors', $errors);
            $this->redirect('/set-password?token=' . urlencode($token));
        }

        // Hash and update password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $this->userModel->updatePassword($tokenData['user_id'], $passwordHash);
        $this->passwordResetModel->markUsed($token);

        Session::setFlash('success', "Password created successfully! Please log in with your mobile number and new password.");
        $this->redirect('/login');
    }

    public function login() {
        if (Session::has('user_id')) {
            $this->redirectBasedOnRegistration(Session::get('user_id'));
        }
        $this->view('auth/login');
    }

    public function processLogin() {
        $mobile = trim($_POST['mobile_number'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($mobile) || empty($password)) {
            Session::setFlash('error', "Mobile number and password are required.");
            $this->redirect('/login');
        }

        $user = $this->userModel->findByMobile($mobile);

        if (!$user || empty($user['password_hash'])) {
            Session::setFlash('error', "Invalid mobile number or password.");
            $this->redirect('/login');
        }

        if ($user['status'] !== 'active') {
            Session::setFlash('error', "Account is not active. Please complete mobile verification and password creation.");
            $this->redirect('/login');
        }

        if (password_verify($password, $user['password_hash'])) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('society_name', $user['society_name']);
            Session::set('mobile_number', $user['mobile_number']);

            // Directly redirect user on first login / uncompleted registration
            $this->redirectBasedOnRegistration($user['id']);
        } else {
            Session::setFlash('error', "Invalid mobile number or password.");
            $this->redirect('/login');
        }
    }

    private function redirectBasedOnRegistration($userId) {
        $society = $this->societyModel->findByUserId($userId);
        if (!$society || empty($society['pan_number']) || empty($society['registered_address'])) {
            Session::setFlash('info', "Welcome! Please complete your society registration and opening setup first.");
            $this->redirect('/registration');
        } else {
            $this->redirect('/dashboard');
        }
    }

    public function logout() {
        Session::destroy();
        $this->redirect('/login');
    }
}
