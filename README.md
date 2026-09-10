# Meridian Heights CHS - Society Management System (PHP MVC & REST APIs)

A modern, API-First Society Management System built with PHP using Model-View-Controller (MVC) architecture, PDO MySQL database, and REST APIs for WhatsApp Chatbot integration.

---

## 🏗️ Architecture Overview

- **`config/`**: Database configuration (`database.php`) supporting Hostinger & local environment variables.
- **`core/`**: Core MVC Engine (`App.php` router, `Controller.php`, `Model.php`, `Session.php`).
- **`controllers/`**:
  - `ApiController.php`: REST APIs returning JSON endpoints for WhatsApp Chatbot integrations (`/api/v1/...`).
  - `AuthController.php`: Dual login (Admin Email vs User Mobile), multi-society selection flow.
  - `SocietyController.php`: Members, Committee, Notices (Chairman-only), Vehicles, and Registration.
  - `ComplaintController.php`: Resident complaints management & status tracking.
  - `FinanceController.php`: Maintenance billing, payments collection, expenses, and Tally XML exports.
  - `DashboardController.php`: Executive society dashboard.
- **`models/`**: `User`, `Society`, `Member`, `Notice`, `Complaint`, `Vehicle`, `MaintenanceBill`, `Payment`, `Expense`.
- **`views/`**: Clean layout views (`layouts/header.php`, `layouts/sidebar.php`, `layouts/drawers.php`, `society/`, `finance/`, `auth/`).
- **`database/`**: Complete SQL schema (`schema.sql`).

---

## 🌟 Key System Features

1. **API-First WhatsApp Bot Endpoints (`/api/v1/...`)**:
   - `POST /api/v1/auth/login`: Admin Email login & User Mobile login.
   - `GET/POST /api/v1/societies`: Admin society registration & listing.
   - `GET/POST /api/v1/members`: Society member directory management.
   - `GET/POST /api/v1/notices`: Published announcements (**Chairman-only enforcement**).
   - `GET/POST /api/v1/complaints`: Resident complaints filing & status tracking.

2. **Dual Authentication (No OTP Requirement)**:
   - **Admin Login**: Log in via **Email + Password** to register societies.
   - **User Login**: Log in via **Mobile Number + Password** directly.

3. **Multi-Society Support & Selection Page**:
   - Users owning/occupying flats in **multiple societies** choose their active society at `/select-society`.
   - Single-society users automatically skip society selection and land directly on the Dashboard.

4. **Decoupled Multi-Society Roles**:
   - Committee roles (`Chairman`, `Secretary`, `Treasurer`, `Committee Member`, `Resident`) are mapped per `(user_id, society_id)`.
   - Allows a user to be a **Normal Resident in Society A**, while serving as **Chairman in Society B**.

5. **Permission Enforcements & Complaints Module**:
   - **Notices**: Strictly restricted to users holding the **Chairman** role (or Admin).
   - **Complaints (`/complaints`)**: Residents file issues (`Open`, `In Progress`, `Resolved`) for committee tracking.

---

## 🛠️ Local Development & Running

1. **Database Setup**:
   Import `database/schema.sql` into your MySQL database (compatible with phpMyAdmin and Hostinger shared hosting).

2. **Start PHP Development Server**:
   ```bash
   php -S localhost:8000
   ```

3. **Access Application**:
   Open browser at `http://localhost:8000/login`.
