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
        $mobile = trim($input['mobile_number'] ?? $input['phone_number'] ?? $input['phone'] ?? $_GET['mobile'] ?? (class_exists('Session') ? Session::get('user_mobile') : ''));

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

        $role = $selectedSoc['committee_role'] ?? 'Resident';
        $isChairman = ($role === 'Chairman');

        $noticeUrlTemplate = "{$baseUrl}/api/v1/notices/add?society_id={$selectedSoc['id']}&phone_number={$mobile}&title={title}&content={content}&category=General&is_urgent=0";
        $complaintUrlTemplate = "{$baseUrl}/api/v1/complaints/add?society_id={$selectedSoc['id']}&phone_number={$mobile}&title={title}&description={description}&category=General";

        $actionOption = $isChairman ? "Create Notice" : "Register Complaint";
        $actionUrl = $isChairman ? $noticeUrlTemplate : $complaintUrlTemplate;

        return $this->jsonResponse([
            'status' => 'success',
            'message' => "Successfully selected society '{$selectedSoc['name']}'",
            'active_society' => [
                'id' => $selectedSoc['id'],
                'name' => $selectedSoc['name'],
                'flat_number' => $selectedSoc['flat_number'] ?? 'N/A',
                'committee_role' => $role,
                'member_id' => $selectedSoc['member_id'] ?? null
            ],
            'whatsapp_action' => [
                'role' => $role,
                'allowed_option' => $actionOption,
                'action_url_template' => $actionUrl,
                'notice_creation_url' => "{$baseUrl}/api/v1/notices/add?society_id={$selectedSoc['id']}&phone_number={$mobile}&title={title}&content={content}",
                'complaint_registration_url' => "{$baseUrl}/api/v1/complaints/add?society_id={$selectedSoc['id']}&phone_number={$mobile}&title={title}&description={description}"
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

    // GET / POST /api/v1/notices/add & /api/v1/notices/create
    public function addNotice() {
        $input = $this->getJsonInput();
        $societyId = intval($input['society_id'] ?? 1);
        $userPhone = trim($input['phone_number'] ?? $input['phone'] ?? $input['user_phone'] ?? $input['mobile_number'] ?? $input['mobile'] ?? '');
        $userId = intval($input['user_id'] ?? 0);
        $isAdmin = !empty($input['is_admin']);
        $isChairmanOverride = (!empty($input['is_chairman']) || (isset($input['role']) && strtolower($input['role']) === 'chairman'));

        // Chairman Validation
        $memberModel = new Member();
        $member = null;
        if (!empty($userPhone)) {
            $member = $memberModel->getMemberByPhoneAndSociety($userPhone, $societyId);
        } else if ($userId > 0) {
            $userModel = new User();
            $u = $userModel->findById($userId);
            if ($u && !empty($u['mobile_number'])) {
                $member = $memberModel->getMemberByPhoneAndSociety($u['mobile_number'], $societyId);
            }
        }

        $userRole = $member['committee_role'] ?? ($isChairmanOverride ? 'Chairman' : 'Resident');
        $isChairman = ($userRole === 'Chairman' || $isChairmanOverride);

        if (!$isAdmin && !$isChairman) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Permission Denied: Only the Chairman of this society can create notices.',
                'your_role' => $userRole,
                'society_id' => $societyId
            ], 403);
        }

        $title = trim($input['title'] ?? $input['subject'] ?? $input['notice_title'] ?? '');
        $content = trim($input['content'] ?? $input['notice_text'] ?? $input['description'] ?? $input['text'] ?? $input['body'] ?? '');
        $category = trim($input['category'] ?? 'General');
        $isUrgent = !empty($input['is_urgent']) ? 1 : 0;
        $noticeDate = trim($input['notice_date'] ?? date('Y-m-d'));

        if (empty($title) || empty($content)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Title and Content are required to create a notice.'
            ], 400);
        }

        $noticeModel = new Notice();
        $noticeId = $noticeModel->create([
            'society_id' => $societyId,
            'created_by_user_id' => $member['user_id'] ?? $userId ?: null,
            'notice_date' => $noticeDate,
            'title' => $title,
            'category' => $category,
            'is_urgent' => $isUrgent,
            'content' => $content
        ]);

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Notice created and published successfully to database!',
            'notice_id' => $noticeId,
            'notice' => [
                'id' => $noticeId,
                'society_id' => $societyId,
                'title' => $title,
                'category' => $category,
                'is_urgent' => $isUrgent,
                'notice_date' => $noticeDate,
                'content' => $content,
                'created_by_role' => $userRole
            ]
        ], 201);
    }

    // GET /api/v1/complaints?society_id=X
    public function getComplaints() {
        $societyId = intval($_GET['society_id'] ?? 1);
        $complaintModel = new Complaint();
        $complaints = $complaintModel->getAll($societyId);
        return $this->jsonResponse(['status' => 'success', 'society_id' => $societyId, 'data' => $complaints]);
    }

    // GET / POST /api/v1/complaints/add & /api/v1/complaints/create
    public function addComplaint() {
        $input = $this->getJsonInput();
        $societyId = intval($input['society_id'] ?? 1);
        $userPhone = trim($input['phone_number'] ?? $input['phone'] ?? $input['user_phone'] ?? $input['mobile_number'] ?? $input['mobile'] ?? '');
        $userId = intval($input['user_id'] ?? 0);

        $memberModel = new Member();
        $member = null;
        if (!empty($userPhone)) {
            $member = $memberModel->getMemberByPhoneAndSociety($userPhone, $societyId);
        } else if ($userId > 0) {
            $userModel = new User();
            $u = $userModel->findById($userId);
            if ($u && !empty($u['mobile_number'])) {
                $member = $memberModel->getMemberByPhoneAndSociety($u['mobile_number'], $societyId);
            }
        }

        $title = trim($input['title'] ?? $input['subject'] ?? $input['issue'] ?? $input['complaint_title'] ?? '');
        $description = trim($input['description'] ?? $input['content'] ?? $input['complaint_text'] ?? $input['details'] ?? $input['text'] ?? '');
        $category = trim($input['category'] ?? 'General');

        if (empty($title) || empty($description)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Title and Description are required to lodge a complaint.'
            ], 400);
        }

        $memberId = $member['id'] ?? 1;
        $flatNumber = $member['flat_number'] ?? $input['flat_number'] ?? 'N/A';

        $complaintModel = new Complaint();
        $complaintId = $complaintModel->create([
            'society_id' => $societyId,
            'member_id' => $memberId,
            'flat_number' => $flatNumber,
            'title' => $title,
            'category' => $category,
            'description' => $description
        ]);

        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Complaint lodged successfully and uploaded to database!',
            'complaint_id' => $complaintId,
            'complaint' => [
                'id' => $complaintId,
                'society_id' => $societyId,
                'member_id' => $memberId,
                'flat_number' => $flatNumber,
                'title' => $title,
                'category' => $category,
                'description' => $description,
                'status' => 'Open'
            ]
        ], 201);
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
