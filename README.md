# Meridian Heights CHS - Society Management System (PHP MVC)

A modern Society Management System built with PHP using the Model-View-Controller (MVC) architecture pattern and PDO MySQL database.

## Architecture Overview

- **`config/`**: Database configuration and PDO connection manager.
- **`core/`**: Custom MVC engine (Router, Front Controller, Base Controller, Base Model, Session Manager).
- **`controllers/`**: Application controllers (`AuthController.php`, `DashboardController.php`).
- **`models/`**: Data models (`User.php`, `Otp.php`, `PasswordReset.php`).
- **`services/`**: Notification service (`NotificationService.php` for SMS & WhatsApp simulations).
- **`views/`**: Modular layout views (`header.php`, `footer.php`, `auth/`, `dashboard/`).
- **`database/`**: SQL database setup script (`schema.sql`).

## Step 1 Features

1. **Member Registration**: Inputs for Name, Society Name, and Mobile Number.
2. **OTP Verification**: 6-digit OTP generated and sent to user's mobile number.
3. **WhatsApp & Text Message Link Dispatch**: After OTP verification, a secure password creation link is sent via WhatsApp and Text Message.
4. **Strict Password Creation Rules**:
   - Minimum 8 characters
   - At least 1 uppercase letter (`[A-Z]`)
   - At least 1 lowercase letter (`[a-z]`)
   - At least 1 special character (`[!@#$%^&*(),.?":{}|<>]`)
   - Confirm password verification
5. **Member Login**: Mobile number and password authentication.
6. **Society Dashboard**: Beautiful dashboard UI for members and admins.

## Installation & Running Locally

1. **Database Setup**:
   - Start MySQL server (via XAMPP, WAMP, Laragon, or standalone MySQL).
   - Import `database/schema.sql` or let the app automatically initialize the tables on first request.

2. **Start PHP Development Server**:
   ```bash
   php -S localhost:8000
   ```

3. **Access Application**:
   Open browser at `http://localhost:8000/register` or `http://localhost:8000/login`.
