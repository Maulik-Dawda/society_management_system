<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Society.php';
require_once __DIR__ . '/../models/MaintenanceBill.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Expense.php';

class FinanceController extends Controller {
    private $societyModel;
    private $billModel;
    private $paymentModel;
    private $expenseModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::setFlash('error', "Please log in to access this page.");
            $this->redirect('/login');
        }
        $this->societyModel = new Society();
        $this->billModel = new MaintenanceBill();
        $this->paymentModel = new Payment();
        $this->expenseModel = new Expense();
    }

    private function enforceRegistration() {
        $userId = Session::get('user_id');
        $society = $this->societyModel->findByUserId($userId);
        if (!$society || empty($society['pan_number']) || empty($society['registered_address'])) {
            Session::setFlash('info', "Please complete your society registration first.");
            $this->redirect('/registration');
        }
    }

    public function maintenance() {
        $this->enforceRegistration();
        $bills = $this->billModel->getAll();
        $this->view('finance/maintenance', ['bills' => $bills]);
    }

    public function generateBills() {
        $this->enforceRegistration();
        $cycle = $_POST['billing_cycle'] ?? date('Y-m');
        $basis = $_POST['charge_basis'] ?? 'Fixed';
        $amount = floatval($_POST['amount'] ?? 10000);
        $dueDate = $_POST['due_date'] ?? date('Y-m-10');
        $lateFeeRule = $_POST['late_fee_rule'] ?? '₹200 flat + 1.5% monthly';

        $count = $this->billModel->createBatch($cycle, $basis, $amount, $dueDate, $lateFeeRule);
        Session::setFlash('success', "Generated maintenance bills for {$count} flats for billing cycle {$cycle}!");
        $this->redirect('/maintenance');
    }

    public function payments() {
        $this->enforceRegistration();
        $payments = $this->paymentModel->getAll();
        $this->view('finance/payments', ['payments' => $payments]);
    }

    public function collectPayment() {
        $this->enforceRegistration();
        $flatNumber = trim($_POST['flat_number'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);

        if (empty($flatNumber) || $amount <= 0) {
            Session::setFlash('error', "Flat Number and a valid payment Amount are required.");
            $this->redirect('/payments');
        }

        $receiptNo = $this->paymentModel->create([
            'flat_number' => $flatNumber,
            'owner_name' => $_POST['owner_name'] ?? 'Resident',
            'amount' => $amount,
            'payment_mode' => $_POST['payment_mode'] ?? 'UPI',
            'payment_date' => $_POST['payment_date'] ?? date('Y-m-d'),
            'reference_no' => $_POST['reference_no'] ?? null
        ]);

        Session::setFlash('success', "Payment of ₹" . number_format($amount, 2) . " recorded for {$flatNumber}. Receipt: {$receiptNo}");
        $this->redirect('/payments');
    }

    public function expenses() {
        $this->enforceRegistration();
        $expenses = $this->expenseModel->getAll();
        $this->view('finance/expenses', ['expenses' => $expenses]);
    }

    public function addExpense() {
        $this->enforceRegistration();
        $category = trim($_POST['category'] ?? '');
        $vendorName = trim($_POST['vendor_name'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);

        if (empty($category) || empty($vendorName) || $amount <= 0) {
            Session::setFlash('error', "Category, Vendor Name, and a valid Amount are required.");
            $this->redirect('/expenses');
        }

        $this->expenseModel->create([
            'expense_date' => $_POST['expense_date'] ?? date('Y-m-d'),
            'category' => $category,
            'vendor_name' => $vendorName,
            'bill_number' => $_POST['bill_number'] ?? null,
            'amount' => $amount,
            'gst_pct' => floatval($_POST['gst_pct'] ?? 18),
            'payment_mode' => $_POST['payment_mode'] ?? 'Bank transfer',
            'notes' => $_POST['notes'] ?? null
        ]);

        Session::setFlash('success', "Expense of ₹" . number_format($amount, 2) . " for {$vendorName} ({$category}) recorded successfully!");
        $this->redirect('/expenses');
    }

    public function reports() {
        $this->enforceRegistration();
        $payments = $this->paymentModel->getAll();
        $expenses = $this->expenseModel->getAll();
        $this->view('finance/reports', [
            'payments' => $payments,
            'expenses' => $expenses
        ]);
    }

    public function tallyExport() {
        $this->enforceRegistration();
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="tally_export_' . date('Y-m-d') . '.xml"');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<ENVELOPE><HEADER><TALLYREQUEST>Import Data</TALLYREQUEST></HEADER><BODY><IMPORTDATA><REQUESTDESC><REPORTNAME>Vouchers</REPORTNAME></REQUESTDESC><REQUESTDATA>';
        $xml .= '<VOUCHER><DATE>' . date('Ymd') . '</DATE><NARRATION>Society Maintenance Vouchers Export</NARRATION></VOUCHER>';
        $xml .= '</REQUESTDATA></IMPORTDATA></BODY></ENVELOPE>';
        
        echo $xml;
        exit();
    }
}
