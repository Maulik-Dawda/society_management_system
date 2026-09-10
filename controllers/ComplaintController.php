<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Complaint.php';
require_once __DIR__ . '/../models/Member.php';

class ComplaintController extends Controller {
    private $complaintModel;
    private $memberModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
        $this->complaintModel = new Complaint();
        $this->memberModel = new Member();
    }

    public function index() {
        $societyId = Session::get('active_society_id') ?? 1;
        $complaints = $this->complaintModel->getAll($societyId);
        
        $this->view('society/complaints', [
            'complaints' => $complaints,
            'userRole' => Session::get('user_role') ?? 'Resident'
        ]);
    }

    public function add() {
        $societyId = Session::get('active_society_id') ?? 1;
        $userId = Session::get('user_id');
        $mobile = Session::get('user_mobile');

        $member = $this->memberModel->getMemberByPhoneAndSociety($mobile, $societyId);
        $memberId = $member ? $member['id'] : (Session::get('user_member_id') ?? 1);
        $flatNumber = $member ? $member['flat_number'] : (Session::get('user_flat') ?? 'A-101');

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($title) || empty($description)) {
            Session::setFlash('error', "Title and Description are required to file a complaint.");
            $this->redirect('/complaints');
        }

        $this->complaintModel->create([
            'society_id' => $societyId,
            'member_id' => $memberId,
            'flat_number' => $flatNumber,
            'title' => $title,
            'category' => $_POST['category'] ?? 'General',
            'description' => $description
        ]);

        Session::setFlash('success', "Complaint '{$title}' lodged successfully!");
        $this->redirect('/complaints');
    }

    public function updateStatus() {
        $id = intval($_POST['complaint_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'Open');

        if ($id <= 0) {
            Session::setFlash('error', "Invalid complaint.");
            $this->redirect('/complaints');
        }

        $this->complaintModel->updateStatus($id, $status);
        Session::setFlash('success', "Complaint status updated to '{$status}'!");
        $this->redirect('/complaints');
    }
}
