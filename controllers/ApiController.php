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
        return is_array($json) ? array_merge($_POST, $json) : $_POST;
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
                ]
            ]);
        } else {
            // Regular User Mobile Login (NO OTP!)
            $mobile = trim($input['identifier'] ?? $input['mobile_number'] ?? '');
            if (empty($mobile) || empty($password)) {
                return $this->jsonResponse(['status' => 'error', 'message' => 'Mobile number and Password are required for User login.'], 400);
            }

            $user = $userModel->findByMobile($mobile);
            if (!$user || !$userModel->verifyPassword($user, $password)) {
                return $this->jsonResponse(['status' => 'error', 'message' => 'Invalid Mobile number or password.'], 401);
            }

            $societies = $userModel->getUserSocieties($mobile, $user['id']);

            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'User login successful',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'mobile_number' => $user['mobile_number'],
                    'is_admin' => false
                ],
                'societies_count' => count($societies),
                'societies' => $societies
            ]);
        }
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
