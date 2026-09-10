<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Society.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../models/Notice.php';
require_once __DIR__ . '/../models/Complaint.php';
require_once __DIR__ . '/../models/MaintenanceBill.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Expense.php';
require_once __DIR__ . '/../models/Vehicle.php';

class ApiController extends Controller {

    private function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function getJsonInput() {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return array_merge($_GET, $_POST, is_array($json) ? $json : []);
    }

    // GET /api/v1/users/check-mobile?mobile=XXXXXXXXXX
    public function checkMobile() {
        $mobile = trim($_GET['mobile'] ?? $_POST['mobile'] ?? '');
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);

        if (empty($cleanMobile) || strlen($cleanMobile) < 10) {
            return $this->jsonResponse(['exists' => false, 'message' => 'Invalid mobile number.'], 400);
        }

        $userModel = new User();
        $user = $userModel->findByMobile($cleanMobile);

        if ($user) {
            return $this->jsonResponse([
                'exists' => true,
                'user_id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'] ?? '',
                'mobile_number' => $user['mobile_number'],
                'message' => "Mobile number {$cleanMobile} is already registered as '{$user['name']}'. Account will be linked without changing password."
            ]);
        }

        return $this->jsonResponse([
            'exists' => false,
            'message' => 'New mobile number. Password required for new account setup.'
        ]);
    }

    // POST /api/v1/auth/login
    public function login() {
        $input = $this->getJsonInput();
        $loginType = strtolower(trim($input['login_type'] ?? 'user')); // 'admin' or 'user'
        $password = trim($input['password'] ?? '');

        $userModel = new User();

        if ($loginType === 'admin') {
            $email = trim($input['identifier'] ?? $input['email'] ?? '');
            if (empty($email) || empty($password)) {
                return $this->jsonResponse(['status' => 'error', 'message' => 'Email and Password are required for Admin login.'], 400);
            }

            $user = $userModel->findByEmail($email);
            if (!$user || !$user['is_admin'] || !$userModel->verifyPassword($user, $password)) {
                return $this->jsonResponse(['status' => 'error', 'message' => 'Invalid Admin email or password.'], 401);
            }

            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Admin login successful',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'is_admin' => true
                ],
                'requires_society_selection' => false,
                'redirect_url' => '/dashboard'
            ]);
        } else {
            return $this->userLogin();
        }
    }

    // POST /api/v1/auth/user-login
    public function userLogin() {
        $input = $this->getJsonInput();
        $mobile = trim($input['phone_number'] ?? $input['phone'] ?? $input['mobile_number'] ?? $input['mobile'] ?? $input['identifier'] ?? '');
        $password = trim($input['password'] ?? '');

        if (empty($mobile) || empty($password)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Mobile number and Password are required for User login.'], 400);
        }

        $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
        $userModel = new User();
        $user = $userModel->findByMobile($cleanMobile);

        if (!$user || !$userModel->verifyPassword($user, $password)) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Invalid Mobile number or password.'], 401);
        }

        // Credentials match! Check user's societies
        $societies = $userModel->getUserSocieties($cleanMobile, $user['id']);
        $societiesCount = count($societies);
        $requiresSelection = ($societiesCount > 1);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'chocolate-chimpanzee-235196.hostingersite.com';
        $baseUrl = "{$protocol}://{$host}";

        // Attach dynamic select_api_url to each society option
        $formattedSocieties = array_map(function($s) use ($baseUrl, $user) {
            $s['select_api_url'] = "{$baseUrl}/api/v1/auth/select-society?society_id={$s['id']}&user_id={$user['id']}";
            return $s;
        }, $societies);

        if (session_status() === PHP_SESSION_ACTIVE && class_exists('Session')) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_mobile', $user['mobile_number']);
            Session::set('is_admin', 0);

            if ($requiresSelection) {
                Session::set('user_societies', $societies);
            } else if ($societiesCount === 1) {
                $soc = $societies[0];
                Session::set('active_society_id', $soc['id']);
                Session::set('active_society_name', $soc['name']);
                Session::set('user_role', $soc['committee_role'] ?? 'Resident');
                Session::set('user_flat', $soc['flat_number'] ?? '');
                Session::set('user_member_id', $soc['member_id'] ?? null);
            }
        }

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Credentials verified successfully.',
            'requires_society_selection' => $requiresSelection,
            'select_society_api_url' => "{$baseUrl}/api/v1/auth/select-society?society_id={society_id}&user_id={$user['id']}",
            'redirect_url' => $requiresSelection ? '/select-society' : '/dashboard',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'mobile_number' => $user['mobile_number'],
                'is_admin' => false
            ],
            'societies_count' => $societiesCount,
            'societies' => $formattedSocieties
        ]);
    }

    // GET / POST /api/v1/auth/select-society?society_id=X&user_id=Y
    public function selectSocietyApi() {
        $input = $this->getJsonInput();
        $societyId = intval($input['society_id'] ?? $_GET['society_id'] ?? $_POST['society_id'] ?? 0);
        $userId = intval($input['user_id'] ?? $_GET['user_id'] ?? $_POST['user_id'] ?? (class_exists('Session') ? Session::get('user_id') : 0));
        $mobile = trim($input['mobile_number'] ?? $input['phone_number'] ?? $_GET['mobile'] ?? (class_exists('Session') ? Session::get('user_mobile') : ''));

        if ($societyId <= 0) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Valid society_id parameter is required in URL or request body.'], 400);
        }

        $userModel = new User();
        $societies = $userModel->getUserSocieties($mobile, $userId);

        $selectedSoc = null;
        foreach ($societies as $s) {
            if (intval($s['id']) === $societyId) {
                $selectedSoc = $s;
                break;
            }
        }

        if (!$selectedSoc) {
            $societyModel = new Society();
            $selectedSoc = $societyModel->findById($societyId);
        }

        if (!$selectedSoc) {
            return $this->jsonResponse(['status' => 'error', 'message' => "Society with ID {$societyId} not found."], 404);
        }

        if (session_status() === PHP_SESSION_ACTIVE && class_exists('Session')) {
            Session::set('active_society_id', $selectedSoc['id']);
            Session::set('active_society_name', $selectedSoc['name']);
            Session::set('user_role', $selectedSoc['committee_role'] ?? 'Resident');
            Session::set('user_flat', $selectedSoc['flat_number'] ?? '');
            Session::set('user_member_id', $selectedSoc['member_id'] ?? null);
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'chocolate-chimpanzee-235196.hostingersite.com';
        $baseUrl = "{$protocol}://{$host}";

        return $this->jsonResponse([
            'status' => 'success',
            'message' => "Successfully selected society '{$selectedSoc['name']}'",
            'active_society' => [
                'id' => $selectedSoc['id'],
                'name' => $selectedSoc['name'],
                'flat_number' => $selectedSoc['flat_number'] ?? 'N/A',
                'committee_role' => $selectedSoc['committee_role'] ?? 'Resident',
                'member_id' => $selectedSoc['member_id'] ?? null
            ],
            'dashboard_url' => "{$baseUrl}/dashboard"
        ]);
    }

    // GET /api/v1/societies
    public function getSocieties() {
        $societyModel = new Society();
        $societies = $societyModel->getAll();
        return $this->jsonResponse(['status' => 'success', 'data' => $societies]);
    }

    // POST /api/v1/societies/register
    public function registerSociety() {
        $input = $this->getJsonInput();
        $societyModel = new Society();

        if (empty($input['society_name']) || empty($input['registered_address']) || empty($input['pan_number'])) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Society Name, Registered Address, and PAN Number are required.'], 400);
        }

        $societyId = $societyModel->create($input);
        return $this->jsonResponse(['status' => 'success', 'message' => 'Society registered successfully!', 'society_id' => $societyId], 201);
    }

    // GET /api/v1/members?society_id=X
    public function getMembers() {
        $societyId = intval($_GET['society_id'] ?? 1);
        $memberModel = new Member();
        $members = $memberModel->getAll($societyId);
        return $this->jsonResponse(['status' => 'success', 'society_id' => $societyId, 'data' => $members]);
    }

    // POST /api/v1/members/add
    public function addMember() {
        $input = $this->getJsonInput();
        $memberModel = new Member();

        if (empty($input['flat_number']) || empty($input['owner_name']) || empty($input['owner_phone'])) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Flat Number, Owner Name, and Owner Phone are required.'], 400);
        }

        $memberId = $memberModel->create($input);
        return $this->jsonResponse(['status' => 'success', 'message' => 'Member added successfully!', 'member_id' => $memberId], 201);
    }

    // POST /api/v1/members/update-role
    public function updateMemberRole() {
        $input = $this->getJsonInput();
        $memberId = intval($input['member_id'] ?? 0);
        $role = trim($input['committee_role'] ?? 'Resident');

        if ($memberId <= 0) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Valid member_id is required.'], 400);
        }

        $memberModel = new Member();
        $memberModel->updateCommitteeRole($memberId, $role);
        return $this->jsonResponse(['status' => 'success', 'message' => "User role updated successfully to '{$role}'"]);
    }

    // GET /api/v1/notices?society_id=X
    public function getNotices() {
        $societyId = intval($_GET['society_id'] ?? 1);
        $noticeModel = new Notice();
        $notices = $noticeModel->getAll($societyId);
        return $this->jsonResponse(['status' => 'success', 'society_id' => $societyId, 'data' => $notices]);
    }

    // POST /api/v1/notices/add (CHAIRMAN ONLY Validation!)
    public function addNotice() {
        $input = $this->getJsonInput();
        $societyId = intval($input['society_id'] ?? 1);
        $userPhone = trim($input['user_phone'] ?? $input['mobile_number'] ?? '');
        $isAdmin = !empty($input['is_admin']);

        // Chairman Validation
        $memberModel = new Member();
        $member = $memberModel->getMemberByPhoneAndSociety($userPhone, $societyId);

        if (!$isAdmin && (!$member || $member['committee_role'] !== 'Chairman')) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Permission Denied: Notices can only be created by the Chairman of this society.'], 403);
        }

        if (empty($input['title']) || empty($input['content'])) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Title and Content are required for posting a notice.'], 400);
        }

        $noticeModel = new Notice();
        $noticeModel->create($input);
        return $this->jsonResponse(['status' => 'success', 'message' => 'Notice published successfully!'], 201);
    }

    // GET /api/v1/complaints?society_id=X
    public function getComplaints() {
        $societyId = intval($_GET['society_id'] ?? 1);
        $complaintModel = new Complaint();
        $complaints = $complaintModel->getAll($societyId);
        return $this->jsonResponse(['status' => 'success', 'society_id' => $societyId, 'data' => $complaints]);
    }

    // POST /api/v1/complaints/add
    public function addComplaint() {
        $input = $this->getJsonInput();
        $societyId = intval($input['society_id'] ?? 1);
        $userPhone = trim($input['user_phone'] ?? $input['mobile_number'] ?? '');

        $memberModel = new Member();
        $member = $memberModel->getMemberByPhoneAndSociety($userPhone, $societyId);

        if (!$member) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Member record not found for this society.'], 404);
        }

        if (empty($input['title']) || empty($input['description'])) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Title and Description are required to lodge a complaint.'], 400);
        }

        $complaintModel = new Complaint();
        $complaintId = $complaintModel->create([
            'society_id' => $societyId,
            'member_id' => $member['id'],
            'flat_number' => $member['flat_number'],
            'title' => $input['title'],
            'category' => $input['category'] ?? 'General',
            'description' => $input['description']
        ]);

        return $this->jsonResponse(['status' => 'success', 'message' => 'Complaint lodged successfully!', 'complaint_id' => $complaintId], 201);
    }

    // POST /api/v1/complaints/update-status
    public function updateComplaintStatus() {
        $input = $this->getJsonInput();
        $id = intval($input['complaint_id'] ?? 0);
        $status = trim($input['status'] ?? 'Open');

        if ($id <= 0) {
            return $this->jsonResponse(['status' => 'error', 'message' => 'Valid complaint_id is required.'], 400);
        }

        $complaintModel = new Complaint();
        $complaintModel->updateStatus($id, $status);
        return $this->jsonResponse(['status' => 'success', 'message' => "Complaint status updated to '{$status}'"]);
    }
}
